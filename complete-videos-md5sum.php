<html>
<head>
<title>jsvideos create mdsum</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>creating md5sum...</center></h1>

<?php

// include configuration file
include ('config.php');
// include functions
include ('functions/functions.php');

if (is_apache()) {
  header( 'Content-type: text/html; charset=utf-8' );
}

$i = 0;
$ii = 0;
$processed = 0;

/*
$id = array();
$path = array();
$fileName = array ();
*/

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

// connect ro mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// set database
mysql_select_db($db_name) or die(mysql_error());

// count how many videos lack md5sum
$sql = "SELECT COUNT(*) FROM videos WHERE md5sum IS NULL OR md5sum=''";
//$sql = "SELECT COUNT(*) FROM videos WHERE md5sum IS NULL";

$query = mysql_query($sql);

$result = mysql_fetch_row($query);

if ($result) {
  $nomd5sum = $result[0];
}
else {
  die('Invalid query: ' . mysql_error() . "<br>\n");
}

// find videos with no md5sum
$sql = "SELECT * FROM videos WHERE md5sum IS NULL OR md5sum=''";
//$sql = "SELECT * FROM videos WHERE md5sum IS NULL";

$query = mysql_query($sql);

while($row = mysql_fetch_array($query)) {

  $processed++;

  $id = $row['id'];
  $path = $row['path'];
  $fileName = $row['fileName'];
  $fileSize = formatBytes($row['fileSize']);

  echo $processed . "/" . $nomd5sum;
  lf();
  echo "Id: " . $id;
  lf();
  echo "File: " . $path . $fileName;
  lf();
  echo "Size: " . $fileSize;
  lf();

  $movie = new ffmpeg_movie($path . $fileName);

  $md5sum = md5_file($path . $fileName);

  echo "md5sum: " . $md5sum;

  lf();

  $updateSql = "UPDATE videos SET md5sum='$md5sum' WHERE id='$id'";

  echo "Updating database where id=" . $id;
  dlf();

  $result = mysql_query($updateSql) or die(mysql_error());

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
