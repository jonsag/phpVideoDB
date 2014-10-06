<html>
<head>
<title>jsvideos grabbing frames</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>grabbing frames...</center></h1>

<?php

// include configuration file                                                                                                                                                    
include ('config.php');
include ('functions/functions.php');

if (is_apache()) {
  header( 'Content-type: text/html; charset=utf-8' );
}

// include php5-ffmpeg
$extension = "ffmpeg";
$extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
$extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;

// load extension
if(!extension_loaded($extension)) {
  dl($extension_soname) or die("Can't load extension $extension_fullname<br>\n");
}

// starting up some variables
$makeFrames = 0;
$noThumbsFiles = 0;

$path = array();
$fileName = array();
$directory = array();
$duration = array();

$framePath = ($picDir . "/" . $videoFrameDir);
$thumbPath = ($picDir . "/" . $videoFrameDir . "/" . $thumbDir);

if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db($db_name) or die(mysql_error());


if (is_cli() && $argv[1] == "wrong") {
  $sql = "SELECT * FROM videos WHERE frames!='$noOfFrames'";
  echo "Creating frames from videos with wrong number of frames\n";
}
elseif (is_cli()) {
  $sql = "SELECT * FROM videos WHERE frames='0'";
  echo "Creating frames from videos with no frames\n";
}
else {
  if ($_POST['which'] == "wrong") {
    $sql = "SELECT * FROM videos WHERE frames!='$noOfFrames'";
    echo "Creating frames from videos with wrong number of frames<br>\n";
  }
  else {
    $sql = "SELECT * FROM videos WHERE frames='0'";
    echo "Creating frames from videos with no frames<br>\n";
  }
}

lf();

$query = mysql_query($sql);

while($row = mysql_fetch_array($query)) {

  $noThumbsFiles++;

  $id[$noThumbsFiles] = $row['id'];
  $path[$noThumbsFiles] = $row['path'];
  $fileName[$noThumbsFiles] = $row['fileName'];
  $directory[$noThumbsFiles] = $row['directory'];
  $duration[$noThumbsFiles] = $row['duration'];
  $frames[$noThumbsFiles] = $row['frames'];

}

// grab frames
for ($counter = 1; $counter <= $noThumbsFiles; $counter++) {

  echo $counter . "/" . $noThumbsFiles . " Processing id " . $id[$counter] . ": " . $path[$counter] . $fileName[$counter] . "<br>\n";
  echo "-------------------------------------------------------------------------------<br>\n";

  $successFrames = grab_frames($id[$counter],$path[$counter],$fileName[$counter]);

  if (is_apache()) {
    display_frames($directory[$counter],$fileName[$counter],$successFrames);
  }

  lf();

  if (is_apache()) {
    flush();
    ob_flush();
  }
}

mysql_close($db_con);

footer();
?>

</body>
</html>
