<!DOCTYPE html>
<html>
<head>

<title>jsvideos view and tag video</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />

<!-- jquery library -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 
<!-- skin -->
<link rel="stylesheet" href="//releases.flowplayer.org/5.4.3/skin/minimalist.css">

<!-- flowplayer javascript component -->
<script type="text/javascript" src="flowplayer-flash/flowplayer-3.2.12.min.js"></script>

</head>

<body>

<h1><center>view and tag video</center></h1>

<?php

include ('config.php');
include ('functions/functions.php');

if (is_apache()) {
  header( 'Content-type: text/html; charset=utf-8' );
}

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// select database
mysql_select_db($db_name) or die(mysql_error());

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  echo "Video id: " . $id;
  lf();
}
else {
  echo "No video selected";
  lf();
}

$sql = "SELECT * FROM videos WHERE id='$id'";

$result = mysql_query($sql);

if (!$result) {
  echo 'Could not run query: ' . mysql_error();
  exit;
}

$row = mysql_fetch_array($result);


$path = $row['path'];
$fileName = $row['fileName'];
$duration = $row['duration'];
$height = $row['height'];
$width = $row['width'];
$fileSize = $row['fileSize'];
$videoCodec = $row['videoCodec'];
$audioCodec = $row['audioCodec'];
$frameCount = $row['frameCount'];

$fullPath = $path . $fileName;
echo "Full path: " . $fullPath; lf();
echo "Duration: " . $duration . " s"; lf();
echo "Size (WxH): " . $width . "x" . $height . " px"; lf();
echo "Size: " . formatBytes($fileSize); lf();
echo "Video Codec: " . $videoCodec . " Audio Codec: " . $audioCodec; lf();
echo "Frame Count: " . $frameCount; lf();

if (is_apache()) {
  flush();
  ob_flush();
}

$flv = convertToFlv($fullPath,$height,$width);

//$flv = $flvDir . "/" . "1045809.flv";

echo "Now playing: " . $flv; lf();

echo '<a ';
//echo 'href="http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv" ';
echo 'href="' . $flv . '"';
nl();
echo 'style="display:block;width:320px;height:240px" ';
nl();
echo 'id="player"> ';
nl();
echo '</a> ';
nl();

echo '<script> ';
nl();
echo 'flowplayer("player", "flowplayer-flash/flowplayer-3.2.16.swf"); ';
nl();
echo '</script> ';
nl();


footer();

?>

</body>
</html>
