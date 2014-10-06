<html>
<head>
<title>jsvideos indexing videos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>indexing videos...</center></h1>

<?php

   // include configuration file
   include ('config.php');
// include functions
include ('functions/functions.php');

   if (is_apache()) {
     header( 'Content-type: text/html; charset=utf-8' );
   }

// declaring some variables
$processed = 0;

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

// directory to parse

$path = $_GET['startPath'];

echo "Parsing ". $path . "<br>\n-------------------------------------------------------------------- <br>\n";

// find video files in current dir
$files = array_diff(scandir($path), array('..', '.'));

$numbers = count($files);

// test each file
foreach($files as $fileName) {

  // counter
  $processed++;
  $fullPath = ($path . $fileName);
  echo "<br>\n" . $processed . "/" . $numbers . ": " . $fullPath . "<br>\n";

  $rename = rename_fullpath($fullPath,"yes");
  $fullPath = $rename[0];
    
  // check if link
  if (is_link($fullPath)) {
    echo "Link <br>\n";

    // else, check if file
  } else {
    if (is_file($fullPath)) {
      echo "Regular file";

      // find file extension
      $ext = substr(strrchr($fullPath,'.'),1);
      echo ", with " . $ext . " extension";
      
      // check if extension matches video extension
      foreach($videoExtensions as $extension) {
	if($ext == $extension) {

	  echo " => probably a video file<br>\n";

	  $fileName = basename($fullPath);

	  index_file($path,$fileName,"no");

	  break;
	}
      }
      lf();
      
      // else, check if directory
    } else {
      if (is_dir($fullPath)) {
	echo "Directory <br>\n";
      } else {
	echo "Not a file, not a directory and not a link...<br>\n";
      }
    }
  }
  if (is_apache()) {
    flush();
    ob_flush();
  }
}
?>

</body>
</html>
