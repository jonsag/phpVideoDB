<html>
<head>
<title>jsvideos find duplicates</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo find duplicates</center></h1>

<?php
   
   // include configuration file
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

$duplicate = 0;

$counter2 = 0;

// connect ro mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// set database
mysql_select_db($db_name) or die(mysql_error());

// column to check for duplicates
$column = "md5sum";

$sql = "SELECT $column, COUNT(*) c FROM videos WHERE $column IS NOT NULL AND $column<>'0' GROUP BY $column HAVING c > 1";

$result = mysql_query($sql);

if ($result) {
  while($row = mysql_fetch_array($result)) {
    $duplicate++;
    $dupe[$duplicate] = $row[$column];
    $c = $row['c'];
    echo $column . "= " .  $dupe[$duplicate] . " is present " . $c . " times<br><br>\n";
  }
  lf();
}
else {
  die('Invalid query: ' . mysql_error());
}

lf();

if ($duplicate == 0) {
  echo "No duplicates found<br>\n";
}
else {

  for ($counter1 = 1; $counter1 <= $duplicate; $counter1++) {
    
    $sql ="SELECT * FROM videos WHERE $column='$dupe[$counter1]'";
    
    $result = mysql_query($sql);
    
    if ($result) {
      
      echo '<form action="delete-entry" method="post" enctype="multipart/form-data"/>';
      lf();
      
      while($row = mysql_fetch_array($result)) {
	$counter2++;
	
	$id[$counter2] = $row['id'];
	$path[$counter2] = $row['path'];
	$fileName[$counter2] = $row['fileName'];
	$directory[$counter2] = $row['directory'];
	$md5sum[$counter2] = $row['md5sum'];
	$duration[$counter2] = $row['duration'];
	$fileSize[$counter2] = $row['fileSize'];
	$frameCount[$counter2] = $row['frameCount'];
	$frames[$counter2] = $row['frames'];
	
	echo '<input type="radio" name="id" value="' . $id[$counter2] . '">';
	echo "Video id: " . $id[$counter2] . "<br>\n";
	echo $path[$counter2] . $fileName[$counter2];
	lf();
	echo "md5sum= " . $md5sum[$counter2] . "<br>\n";
	echo " Duration: " . $duration[$counter2];
	echo " Filesize: " . $fileSize[$counter2];
	echo " Framecount: " . $frameCount[$counter2] . "<br>\n";
	
	display_frames($directory[$counter2],$fileName[$counter2],$frames[$counter2]);
	lf();
      }
      
      echo '<input type="submit">';
      echo '<input type="reset" value="Reset!">';
      dlf();
      echo "</form>";
      dlf();
      
      
    }
    else {
      die('Invalid query: ' . mysql_error());
    }
  }
}

footer();

?>

</body>
</html>
