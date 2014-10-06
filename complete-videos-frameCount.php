<?php

// include configuration file
include ('config.php');
// include functions
include ('functions/functions.php');
 
$i = 0;
$ii = 0;

$id = array();
$path = array();
$fileName = array ();

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

$sql = "SELECT * FROM videos WHERE frameCount IS NULL OR frameCount='0'";

$query = mysql_query($sql);

if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db($db_name) or die(mysql_error());;

while($row = mysql_fetch_array($query)) {
  $i++;

  $id[$i] = $row['id'];
  $path[$i] = $row['path'];
  $fileName[$i] = $row['fileName'];

  echo $id[$i] . " " . $path[$i] . $fileName[$i] . "\n";

  $movie = new ffmpeg_movie($path[$i] . $fileName[$i]);

  $frameCount[$i] = $movie->getFrameCount();

  echo "Frame Count: " . $frameCount[$i] . "\n";

  $updateSql = "UPDATE videos SET frameCount='$frameCount[$i]' WHERE id='$id[$i]'";

  echo "Updating database where id= " . $id[$i] . "\n\n";

  $result = mysql_query($updateSql) or die(mysql_error());

  if (is_apache()) {
    flush();
    ob_flush();
  }

}

/*
for ($ii = 1; $ii <= $i; $ii++) {

$sql = "UPDATE frameCount SET frameCount='$frameCount' WHERE id='$id[$ii]'";

echo $ii . "/" . $i . "Updating database where id= " . $id[$ii];
$result = mysql_query($sql) or die(mysql_error());

}
*/

mysql_close($db_con);

?>
