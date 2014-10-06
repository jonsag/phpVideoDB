<html>
<head>
<title>jsvideos files not indexed...</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>files not indexed...</center></h1>


<?php

$files = 0;

///// include configuration file
include ('config.php');
// include functions
include ('functions/functions.php');

// include classes
require_once('classes/class.mediaInfo.php');

// include php5-ffmpeg
$extension = "ffmpeg";
$extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
$extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;

// load extension
if(!extension_loaded($extension)) {
  dl($extension_soname) or die("Can't load extension $extension_fullname<br>\n");
}

if (is_apache()) {
  header( 'Content-type: text/html; charset=utf-8' );
}


if (is_cli() && $argv[1] != '') {
  if (is_dir($argv [1])) {
    // 1st argument should be a path
    $startPath = $argv[1];
  }
  else {
    echo "Not a valid path\n";
    exit;
  }
  if ($argv[2] != '') {
    // 2nd argument must be 'yes' if you want to index
    $index = $argv[2];
  }
  if ($argv[3] != '') {
    // 3rd argument must be 'yes' if you want to make hash
    $makeMd5sum = $argv[3];
  }
  if ($argv[4] != '') {
    // 4th argument must be 'yes' if you want to make frames
    $makeFrames = $argv[4];
  }
}
elseif (is_apache()) {
  $startPath = $_POST['startPath'];
  
  if (isset($_POST['index'])) {
    $index = $_POST['index'];
  }
  else {
    $index = "no";
  }
  
  if (isset($_POST['makeMd5sum'])) {
    $makeMd5sum = $_POST['makeMd5sum'];
  }
  else {
    $makeMd5sum = "no";
  }
  
  if (isset($_POST['makeFrames'])) {
    $makeFrames = $_POST['makeFrames'];
  }
  else {
    $makeFrames = "no";
  }
}
else {
  echo "No arguments supplied\n";
  exit;
}

$filesPresent = 0;
$filesNotPresent = 0;

echo "Searching " . $startPath . "<br>\n";
echo "--------------------------------------------------<br>\n";

foreach (ListFiles($startPath) as $key=>$file){
  if (is_file($file)) {

    // find file extension
    $ext = substr(strrchr($file,'.'),1);

    // connect to mysql
    if (!$db_con) {
      die('Could not connect: ' . mysql_error());
    }

    // select database
    mysql_select_db($db_name);

    // check if extension matches video extension                                                                                                                                 
    foreach($videoExtensions as $extension) {
      if($ext == $extension) {

	$rename = array();

	$rename = rename_fullpath($file,"yes");
	$nameDiffed = $rename[1];
	$renameSuccess = $rename[2];
	if ($nameDiffed && $renameSuccess) {
	  echo "File was successfully renamed<br>\n";
	  $file = $rename[0];
	}
	elseif ($nameDiffed) {
	  echo "Filename did not apply to standard, but could not rename<br>\n";
	} elseif (!$nameDiffed) {
	  echo "No need to rename<br>\n";
	}

	//echo "<br>File before str_replace: " . $file . "<br>\n";

	$file = str_replace('//','/',$file);
	//echo "File after str_replace: " . $file . "<br>\n";

	$path = (dirname($file) . "/");
	//echo "Path after dirname: " . $path . "<br>\n";

	$fileName = basename($file);	
	//echo "Filename after basename: " . $fileName . "<br>\n";

	$sql = "SELECT COUNT(*) FROM videos WHERE path='$path' AND fileName='$fileName'";
	//echo "sql: SELECT COUNT(*) FROM videos WHERE path='" . $path . "' AND fileName='" . $fileName . "'";

	$query = mysql_query($sql);
	$result = mysql_fetch_row($query);

	if ($result) {

	  if ($result[0] == 0) {
	    $filesNotPresent++;
	    echo "<br>\nNOT present: " . $path . $fileName ."<br>\n";
	    echo "Based on path/filename, this file file is not indexed<br>\n";

	    if ($index == "yes") {
	      $fullPath = ($path . $fileName);
	      
	      /*
	      $rename = array();
	      $rename = rename_fullpath($fullPath,"yes");
	      $nameDiffed = $rename[1];
	      $renameSuccess = $rename[2];

	      if ($nameDiffed && $renameSuccess) {
		echo "File was successfully renamed<br>\n";
		$fullPath = $rename[0];
	      }
	      elseif ($nameDiffed) {
		echo "Filename did not apply to standard, but could not rename<br>\n";
	      } elseif (!$nameDiffed) {
		echo "No need to rename<br>\n";
	      }
	      */

	      $path = (dirname($fullPath) . "/");
	      $fileName = basename($fullPath);

	      $id = index_file($path,$fileName,$makeMd5sum);

	      if ($makeFrames == "yes" && $id != "") {
		$successFrames = grab_frames($id,$path,$fileName);
		$directory = basename($path);
		if (is_apache()) {
		  display_frames($directory,$fileName,$successFrames);
		}
		lf();
	      }

	    }
	    break;
	  }
	  else {
	    $filesPresent++;
	    echo "<br>\nPresent in database: " . $path . $fileName ."<br>\n";
	  }
	}
	else {
	  die('Invalid query: ' . mysql_error());
	}
      }
    }
  }    
  if (is_apache()) {
    flush();
    ob_flush();
  }
}  

mysql_close($db_con);

echo "<br>\nVideos not indexed in " . $startPath . ": " . $filesNotPresent . "<br>\n";
echo "Videos indexed in " . $startPath . ": " . $filesPresent . "<br>\n";

footer();

?>

</body>
</html>
