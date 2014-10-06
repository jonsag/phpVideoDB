<html>
<head>
<title>jsvideos check frames</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>check frames</center></h1>

<?php
///// include configuration file
include ('config.php');
// include functions
include ('functions/functions.php');

if (is_apache()) {
  header( 'Content-type: text/html; charset=utf-8' );
}

function delete_file($fileName,$fileSize) {
  $fileSize = ($fileSize + filesize($fileName));
  unlink($fileName);
  return $fileSize;
}

$pictures = 0;
$thumbs = 0;
$exists = 0;
$notExists = 0;
$sizeSaved = 0;
$fileSize = 0;

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}
// select database
mysql_select_db($db_name) or die(mysql_error());

// find files in pic dir
$files = array_diff(scandir($picDir . "/" . $videoFrameDir), array('..', '.'));
$numbers = count($files);
echo "No of frames: " . $numbers;
lf();
// test each file
foreach($files as $fileName) {
  // if file is a picture
  if (substr(strrchr($fileName,'.'),1) == $picFormat) {
    $pictures++;    
    $directory = strtok($fileName, "-");
    $dirLen = strlen($directory);
    $videoName = substr($fileName,0, strrpos($fileName,'-',2));
    $videoName = substr($videoName, ($dirLen+1));
    $sql = "SELECT COUNT(*) FROM videos WHERE directory='$directory' AND fileName='$videoName'";
    $query = mysql_query($sql);
    $result = mysql_fetch_row($query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    if ($result[0] > 0) {
      $exists++;
    }
    else {
      $notExists++;
      echo $fileName . " does not exit as video";
      $fileName = $picDir . "/" . $videoFrameDir . "/" . $fileName;
      $fileSize = delete_file($fileName,$fileSize);
      lf();
    }
  }
  elseif (is_file($picDir . "/" . $videoFrameDir . "/" . $fileName)) {
      $notexists++;
      echo $fileName . " is not an image";
      lf();
  }
  if (is_apache()) {
    flush();
    ob_flush();
  }
}

// find files in thumbs dir
$files = array_diff(scandir($picDir . "/" . $videoFrameDir . "/" . $thumbDir), array('..', '.'));
$numbers = count($files);
echo "No of thumbs: " . $numbers;
lf();
// test each file
foreach($files as $fileName) {
  // if file is a picture
  if (substr(strrchr($fileName,'.'),1) == $picFormat) {
    $pictures++;
    $prefixLen = strlen($thumbPrefix);
    $directory = substr(strtok($fileName, "-"), $prefixLen);

    $dirLen = strlen($directory);
    $videoName = substr($fileName,0, strrpos($fileName,'-',2));
    $videoName = substr($videoName, ($prefixLen + $dirLen + 1));
    $sql = "SELECT COUNT(*) FROM videos WHERE directory='$directory' AND fileName='$videoName'";
    $query = mysql_query($sql);
    $result = mysql_fetch_row($query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    if ($result[0] > 0) {
      $exists++;
    }
    else {
      $notExists++;
      echo $fileName . " does not exit as video";
      $fileName = $picDir . "/" . $videoFrameDir . "/" . $thumbDir . "/" . $fileName;
      $fileSize = delete_file($fileName,$fileSize);
      lf();
    }
  }
  elseif (is_file($picDir . "/" . $videoFrameDir . "/" . $thumbDir . "/" . $fileName)) {
    $notexists++;
    echo $fileName . " is not an image";
    lf();
  }
  if (is_apache()) {
    flush();
    ob_flush();
  }
}

lf();
echo $pictures . " images";
lf();
echo $exists . " of those exist as videos";
lf();

if ($notExists > 0) {
  echo $notExists . " did not exist as a video, and was deleted";
  lf();
  echo formatBytes($fileSize) . " space saved";
}
else {
  echo "Nothing deleted";
}

lf();

footer();

// close connection to mysql

mysql_close($db_con);

?>

</body>
</html>
