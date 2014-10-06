<?php

function convertToFlv($infile,$height,$width) {
  include ('config.php');

  $outfile = random_string(10) . ".flv";

  $height = round(($height / ($width / $flvWidth)));
  
  echo "Converting to " . $outfile . " " . $flvWidth . "x" . $height . " px"; lf();
  echo 'ffmpeg -i ' . $infile . ' -f flv -b ' . $flvVideoBitrate . ' -ar ' . $flvAudioFrequency . ' -ab ' . $flvAudioBitrate . ' -s ' . $flvWidth . 'x' . $height . ' -r ' . $flvFps . ' -compression_level ' . $flvComp . ' ' . $flvDir . '/' . $outfile;
  lf();
  
  exec('ffmpeg -i ' . $infile . ' -f flv -b ' . $flvVideoBitrate . ' -ar ' . $flvAudioFrequency . ' -ab ' . $flvAudioBitrate . ' -s ' . $flvWidth . 'x' . $height . ' -r ' . $flvFps . ' -compression_level ' . $flvComp . ' ' . $flvDir . '/' . $outfile . ' </dev/null 1>encodeLog.txt 2>&1 &');
  
  echo "Done";
  
  echo '<script src="java/encode-progress.js"></script>';
  
  lf();
  
  if (is_apache()) {
    flush();
    ob_flush();
  }
  
  return($flvDir . "/" . $outfile);
}


function lf() {
  if (is_apache()) {
    echo "<br>\n";
  }
  else {
    echo "\n";
  }
}


function dlf() {
  if (is_apache()) {
  echo "<br>\n<br>\n";
  }
  else {
    echo "\n\n";
  }
}


function nl() {
  echo "\n";
}


function crypto_rand_secure($min, $max) {
  $range = $max - $min;
  if ($range < 0) return $min; // not so random...
  $log = log($range, 2);
  $bytes = (int) ($log / 8) + 1; // length in bytes
  $bits = (int) $log + 1; // length in bits
  $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
  do {
    $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
    $rnd = $rnd & $filter; // discard irrelevant bits
  } while ($rnd >= $range);
  return $min + $rnd;
}


function random_string($length){
  $string = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet.= "0123456789";
  for($i=0;$i<$length;$i++){
    $string .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
  }
  return $string;
}


function check_if_changed($oldValue,$newValue,$table_name,$column_name) {
  $oldValue = query_properties_name($table_name,$column_name,$oldValue);
  if ($newValue == $oldValue) {
    echo '<img src="pictures/icons/greenDot.png" height="15">';
  }
  else {
    echo '<img src="pictures/icons/redDot.png" height="15">';
  }
}


function query_properties($query) {
  $result = mysql_query($query);
  if ($result) {
    return (mysql_result($result, 0));
  }
  else {
    die('Invalid query: ' . mysql_error());
  }
}


function query_properties_id($table_name,$column_name,$value) {
  $sql = "SELECT id FROM $table_name WHERE $column_name = '$value'";
  $result = mysql_query($sql);
  if ($result) {
    return (mysql_result($result, 0));
  }
  else {
    die('Invalid query: ' . mysql_error());
  }
}


function query_properties_name($table_name,$column_name,$id) {
  $sql = "SELECT $column_name FROM $table_name WHERE id='$id'";
  $result = mysql_query($sql);
  if ($result) {
    return (mysql_result($result,0));
  }
  else {
    die('Invalid query: ' . mysql_error());
  }
}


function delete_entry($id,$path,$fileName,$deleteVideo) {
  include ('config.php');
  echo "Deleting id: " . $id . " " . $path . $fileName;
  lf();
  if (file_exists($path . $fileName)) {
    if (is_writeable($path . $fileName)) {
      if ($deleteVideo == "yes") {
	lf();
	echo "Deleting videofile...";
	lf();
	unlink($path . $fileName);
      }
      else {
	echo "Will not try to delete video";
	lf();
      }
    }
    else {
      echo "Not writeable";
      lf();
      if ($deleteVideo == "yes") {
	echo "Can not delete " . $path . $fileName;
	lf();
	echo "Please delete it manually, and then reload this page, eg:";
	lf();
	echo "rm '" . $path . $fileName . "'";
	lf();
      }
      else {
	echo "Will not try to delete video. Not writeable";
	lf();
      }
    }  
  }
  else {
    echo $path . $fileName . " is already deleted. Continuing...";
    lf();
  }
  dlf();
  echo "Deleting frames and thumbs...";
  dlf();
  delete_old_frames($path,$fileName);
  if (file_exists($path . $fileName)) {
    echo "Could not delete videofile. Please remove it manually!";
    lf();
  }
  lf();
  echo "Deleting database entries...";
  dlf();
  $sql = "DELETE FROM actorsVideos WHERE video='$id'";
  $result = mysql_query($sql)  or die(mysql_error());
  $sql = "DELETE FROM activitiesVideos WHERE video='$id'";
  $result = mysql_query($sql)  or die(mysql_error());
  $sql = "DELETE FROM videos WHERE id='$id'";
  $result = mysql_query($sql)  or die(mysql_error());
}


function rename_fullpath($fullPath,$rename) {
  $nameDiffered = 0;
  $success = 0;
  $path = dirname($fullPath);
  $fileName = basename($fullPath);
  $newFile = preg_replace('/\s+/', '_', $fileName); // collapse white space, replace with _
  //$newFile = preg_replace('/[^a-zA-Z0-9%\[\]\.\(\)%&-~]/s', '_', $newFile);
  $newFile = preg_replace('/[^a-zA-Z0-9-._,]/i','',$newFile); // restrict to allowed characters
  $newFile = preg_replace('/_+/', '_', $newFile); // collapse underscores
  $newFile = str_replace('_-_', '-', $newFile); // replace _-_ with -
  $newFile = str_replace('_.', '.', $newFile); // replace _. with .
  if (substr($newFile,0,1) == "_") {
    $newFile = preg_replace('/_/', '', $newFile, 1); // delete first occurence of _ 
  }
  $newFullPath = $path . "/" . $newFile;
  if ($fullPath != $newFullPath) {
    $nameDiffered = 1;
    echo "Will rename to " . $newFullPath;
    lf();
    if (is_writeable($fullPath)) {
      if (file_exists($newFullPath)) {
	echo $newFullPath . " already exists. Please take manual steps to solve the problem";
	lf();
      }
      else {
	if ($rename == "yes") {
	  echo "Renaming to " . $newFullPath;
	  lf();
	  rename($fullPath, $newFullPath);
	  $fullPath = $newFullPath;
	}
	else {
	  echo "Only dry run. No name changes made";
	  lf();
	}
      }
    }
    else {
      echo $fullPath . " is not writeable, can not rename";
      lf();
    }
  }
  else {
    $nameDiffered = 0;
    echo "Name complies, no need to rename";
    lf();
  }
  if (file_exists($fullPath)) {
    $success = 1;
  }
  $result = array($fullPath,$nameDiffered,$success);
  return ($result);
}


function grab_frames($id,$path,$fileName) {
  include ('config.php');
  // include php5-ffmpeg
  $extension = "ffmpeg";
  $extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
  $extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;
  // load extension
  if(!extension_loaded($extension)) {
    dl($extension_soname) or die("Can't load extension $extension_fullname");
    lf();
  }
  $successFrames = 0;
  $directory = basename($path);
  $movie = new ffmpeg_movie($path . $fileName);
  $duration = $movie->getDuration();
  echo "Grabbing frames for " . $path . $fileName . " with id " . $id;
  lf();
  echo "Video is " . $duration . " seconds long";
  lf();
  $movie = new ffmpeg_movie($path . $fileName);
  $frames = $movie->getFrameCount();
  $increments = round(($duration - $startOffset - $endOffset) / ($noOfFrames - 1));
  $successFrames = 0;
  $position = $startOffset;
  delete_old_frames($path,$fileName);
  // start loop for grabbing frames
  for ($mplayerFrame = 1; $mplayerFrame <= $noOfFrames; $mplayerFrame++) {
    // catching frame
    echo "Creating frame# " . $mplayerFrame . " at " . $position . " seconds";
    lf();
    // grab frame
    exec('mplayer -nosound -ss ' . $position  . ' -frames 1 -vo ' . $picFormat . " " .  $path . $fileName . ' > /dev/null 2>&1');
    $newFramePath = ($picDir . "/" . $videoFrameDir . "/" . $directory . "-" . $fileName . "-" . $mplayerFrame . "." . $picFormat);
    if (file_exists('00000001.' .  $picFormat)) {
      rename('00000001.' .  $picFormat,$newFramePath);
    }
    // does the new frame exist
    if (file_exists($newFramePath)) {
      echo "Frame created";
      lf();
      $successFrames++;
      $newThumbPath = ($picDir . "/" . $videoFrameDir . "/" . $thumbDir . "/" . $thumbPrefix . $directory . "-" . $fileName . "-" . $mplayerFrame . "." . $picFormat);
      if (make_thumb($newFramePath,$newThumbPath)) {
	echo "Thumb created";
	lf();
      }
      else {
	echo "Could not create thumb";
	lf();
      }
    }
    else {
      echo "Could not create frame";
      lf();
    }
    $position = ($position + $increments);
  }
  if($successFrames == $noOfFrames) {
    echo "Successfully wrote " . $noOfFrames . " frames";
    lf();
  }
  else {
    echo "Only managed to grab " . $successFrames . " of " . $noOfFrames . " frames";
    lf();
  }
  $updateSql = "UPDATE videos SET frames='$successFrames' WHERE id='$id'";
  echo "Updating database where id=" . $id;
  dlf();
  $result = mysql_query($updateSql) or die(mysql_error());
  return ($successFrames);
}


function make_thumb($fullPathToImage,$fullPathToThumb) {
  include ('config.php');
  exec('convert ' . $fullPathToImage . ' -resize ' . $thumbWidth . 'x' . $thumbHeigth . ' ' . $fullPathToThumb);
  if (file_exists($fullPathToThumb)) {
    return (1);
  }
}


function is_apache() {
  if (PHP_SAPI == "apache2handler") {
    return (1);
  }
}


function is_cli() {
  if (PHP_SAPI == "cli") {
    return (1);
  }
}


function delete_old_frames($path,$fileName){
  include ('config.php');
  $directory = basename($path);
  if (file_exists($picDir . "/" . $videoFrameDir . "/" . $directory . "-" . $fileName . "-1." . $picFormat)) {
    $oldFrames = glob($picDir . "/" . $videoFrameDir . "/" . $directory . "-" . $fileName . "-*." . $picFormat);
    array_walk($oldFrames, function ($oldFrame) {
	echo "Deleting old frame " . $oldFrame;
	lf();
	unlink($oldFrame);
      });
  }
  else {
    echo "No frames to delete";
    lf();
  }
  if (file_exists($picDir . "/" . $videoFrameDir . "/" . $thumbDir . "/" . $thumbPrefix . $directory . "-" . $fileName . "-1." . $picFormat)){
		  $oldThumbs = glob($picDir . "/" . $videoFrameDir . "/" . $thumbDir . "/" . $thumbPrefix . $directory . "-" . $fileName . "-*." . $picFormat);
    array_walk($oldThumbs, function ($oldThumb) {
	echo "Deleting old thumb " . $oldThumb;
	lf();
	unlink($oldThumb);
      });
  }
  else {
    echo "No thumbs to delete";
    lf();
  }
}


function display_frames($directory,$fileName,$frames) {
  include ('config.php');
  $counter = 0;
  for ($counter = 1; $counter <= $frames; $counter++) {
    $thumb = ($picDir . "/" . $videoFrameDir . "/" . $thumbDir . "/" . $thumbPrefix . $directory . "-" . $fileName . "-" . $counter . "." . $picFormat);
    $frame = ($picDir . "/" . $videoFrameDir . "/" . $directory . "-" . $fileName . "-" . $counter . "." . $picFormat);
    echo '<a href="' . $frame . '">';
    echo '<img src="' . $thumb . '"></a>';
    nl();
  }
}


function formatBytes($size, $precision = 2)
{
  $base = log($size) / log(1024);
  $suffixes = array('', ' kiB', ' MiB', ' GiB', ' TiB');
  return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}


function index_file($path,$fileName,$makeMd5sum) {
  include ('config.php');
  // give here your own video/movie file
  $mi = new mediaInfo($path . $fileName);
  $movie = new ffmpeg_movie($path . $fileName);
  ///// check if file already exist, based on path/fileName
  $sql = "SELECT COUNT(*) FROM videos WHERE path='$path' AND fileName='$fileName'";
  $query = mysql_query($sql);
  $result = mysql_fetch_row($query);
  if ($result) {
    if ($result[0] == 0) {
      echo "Based on path/filename, this is a new file. Proceeding...";
      lf();
      // directory                                                                                                                                                    
      $directory = basename($path);
      // md5sum
      if ($makeMd5sum == "yes") {
	lf();
	echo "Calculating hash...";                                                                    
        $md5sum = md5_file($path . $fileName);
	echo ": " . $md5sum;
	lf();
	/*
     ///// check if file with this hash already exist
     $sql = "SELECT COUNT(*) FROM videos WHERE md5sum='$md5sum'";                                                                                         
     $query = mysql_query($sql);
     if (!$db_con) {    die('Could not connect: ' . mysql_error());
     }
     mysql_select_db($db_name);
     $result = mysql_fetch_row($query);
     if ($result) {
     if ($result[0] == 0) {
     echo "Based on hash, this is a new file. Proceeding...;
     lf();
     mysql_close($db_con);
	*/
      }
      // duration
      $duration = $movie->getDuration();
      echo "Duration: " . $duration . " seconds";
      lf();
      // height
      $height = $movie->getFrameHeight();
      echo "Height: " . $height . " pixels";
      lf();
      // width
      $width = $movie->getFrameWidth();
      echo "Width: " . $width . " pixels";
      lf();
      // fileSize
      $fileSize = filesize($path . $fileName);
      echo "Filesize: " . $fileSize . " bytes";
      lf();
      // aspectRatio
      $aspectRatio = $mi->get_video_property('Display aspect ratio');
      echo "Aspect Ratio: " . $aspectRatio;
      lf();
      // videoCodec
      $videoCodec = $movie->getVideoCodec();
      echo "Video Codec: " . $videoCodec;
      lf();
      // audioCodec
      $audioCodec = $movie->getAudioCodec();
      echo "Audio Codec: " . $audioCodec;
      lf();
      // videoBitrate
      $videoBitrate = $movie->getVideoBitRate();
      echo "Video Bitrate:" . $videoBitrate . " bps";
      lf();
      // audioBitrate
      $audioBitrate = $movie->getAudioBitRate();
      echo "Audio Bitrate: " . $audioBitrate . " bps";
      lf();
      // overallBitrate
      $overallBitrate = $movie->getBitRate();
      echo "Overall Bitrate: " . $overallBitrate . " bps";
      lf();
      // frameRate
      $frameRate = $movie->getFrameRate();
      echo "FrameRate: " . $frameRate . " fps";
      lf();
      // frameCount
      $frameCount = $movie->getFrameCount();
      echo "Frame Count: " . $frameCount;
      lf();
      // writing to database
      echo "Writing to MySQL...";
      lf();
      if ($makeMd5sum == "yes") {
	$query = "INSERT INTO $video_tbl (path,fileName,directory,md5sum,duration,height,width,fileSize,aspectRatio,videoCodec,audioCodec,videoBitrate,audioBitrate,overallBitrate,frameRate) VALUES ('$path','$fileName','$directory','$md5sum','$duration','$height','$width','$fileSize','$aspectRatio','$videoCodec','$audioCodec','$videoBitrate','$audioBitrate','$overallBitrate','$frameRate')";
      }
      else {
	$query = "INSERT INTO $video_tbl (path,fileName,directory,duration,height,width,fileSize,aspectRatio,videoCodec,audioCodec,videoBitrate,audioBitrate,overallBitrate,frameRate,frameCount) VALUES ('$path','$fileName','$directory','$duration','$height','$width','$fileSize','$aspectRatio','$videoCodec','$audioCodec','$videoBitrate','$audioBitrate','$overallBitrate','$frameRate','$frameCount')";
      }
      $result = mysql_query($query);
      if ($result) {
	$id = mysql_insert_id();
        echo "OK";
	lf();
      }
      else {
        die('Invalid query: ' . mysql_error());
      }
    } else {
      echo "Based on path/filename, file already exist in database... Skipping";
      lf();
    }
  }
  else {
    die('Invalid query: ' . mysql_error());
  }
  return ($id);
}


function ListFiles($dir) {
  if($dh = opendir($dir)) {
    $files = Array();
    $inner_files = Array();
    while($file = readdir($dh)) {
      if($file != "." && $file != ".." && $file[0] != '.') {
        if(is_dir($dir . "/" . $file)) {
          $inner_files = ListFiles($dir . "/" . $file);
          if(is_array($inner_files)) $files = array_merge($files, $inner_files);
        } else {
          array_push($files, $dir . "/" . $file);
        }
      }
    }
    closedir($dh);
    return $files;
  }
}


function secondsToTime($inputSeconds) {
  $secondsInAMinute = 60;
  $secondsInAnHour  = 60 * $secondsInAMinute;
  $secondsInADay    = 24 * $secondsInAnHour;
  // extract days
  $days = floor($inputSeconds / $secondsInADay);
  // extract hours
  $hourSeconds = $inputSeconds % $secondsInADay;
  $hours = floor($hourSeconds / $secondsInAnHour);
  // extract minutes
  $minuteSeconds = $hourSeconds % $secondsInAnHour;
  $minutes = floor($minuteSeconds / $secondsInAMinute);
  // extract the remaining seconds
  $remainingSeconds = $minuteSeconds % $secondsInAMinute;
  $seconds = ceil($remainingSeconds);
  // return the final array
  $obj = array(
               'd' => (int) $days,
               'h' => (int) $hours,
               'm' => (int) $minutes,
               's' => (int) $seconds,
               );
  return $obj;
}


function isIndexed($startPath,$file) {
  include ('config.php');
  $sql = "SELECT COUNT(*) FROM videos WHERE path='$startPath' AND fileName='$file'";
  $query = mysql_query($sql);
  if (!$db_con) {
    die('Could not connect: ' . mysql_error());
  }
  mysql_select_db($db_name);
  $result = mysql_fetch_row($query);
  if ($result) {
    if ($result[0] == 0) {
      $indexed = "no";
    }
    else {
      $indexed = "yes";
    }
  }  
  else {
    die('Invalid query: ' . mysql_error());
  }
  return($indexed);
}


function mysql_write_video($query) {
  include ('config.php');
  if (!$db_con) {
    die('Could not connect: ' . mysql_error());
  }
  mysql_select_db($db_name);
  $result = mysql_query($query);
  if ($result) {
    echo "OK";
    lf();
  }
  else {
    die('Invalid query: ' . mysql_error());
  }
  mysql_close($db_con);
}

function makeThumb($newPic, $dest, $thumbWidth) {
  include ('config.php');
  // read the source image
  $source_image = imagecreatefromjpeg($newPic);
  // find dimensions of image
  $width = imagesx($source_image);
  $height = imagesy($source_image);
  // find the "desired height" of this thumbnail, relative to the desired width
  $desired_height = floor($height * ($thumbWidth / $width));
  // create a new, "virtual" image
  $virtual_image = imagecreatetruecolor($thumbWidth, $desired_height);
  // copy source image at a resized size
  imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $thumbWidth, $desired_height, $width, $height);
  // create the physical thumbnail image to its destination
  $thumbName = $thumbPrefix . (basename($newPic));
  $thumbName = $dest . $thumbName;
  imagejpeg($virtual_image, $thumbName);
  return $thumbName;
}


function storePicture($picType,$actorsName) {
include ('config.php');
$temp = explode(".", $_FILES[$picType]["name"]);
$extension = end($temp);
if ((($_FILES[$picType]["type"] == "image/gif")
     || ($_FILES[$picType]["type"] == "image/jpeg")
     || ($_FILES[$picType]["type"] == "image/jpg")
     || ($_FILES[$picType]["type"] == "image/pjpeg")
     || ($_FILES[$picType]["type"] == "image/x-png")
     || ($_FILES[$picType]["type"] == "image/png"))
    && ($_FILES[$picType]["size"] < $maxPicSize)
    && in_array($extension, $picExtensions))
  {
    if ($_FILES[$picType]["error"] > 0) {
      echo "Return Code: " . $_FILES[$picType]["error"];
      lf();
    }
  else
    {
      /*
      echo "Upload: " . $_FILES[$picType]["name"];
      lf();
      echo "Type: " . $_FILES[$picType]["type"];
      lf();
      echo "Size: " . ($_FILES[$picType]["size"] / 1024) . " kB";
      lf();
      echo "Temp file: " . $_FILES[$picType]["tmp_name"];
      lf();
      */
      $randomString = random_string(10);
      $newPic = ($actorsName . "-" . $picType . "-" . $randomString . "-"
 . $_FILES[$picType]["name"]);
      $newPic = preg_replace('/[^a-zA-Z0-9%\[\]\.\(\)%&-]/s', '_', $newPic);
      $newPic = ($picDir . "/" . $actorPicDir . "/" . $newPic);
      if (file_exists($newPic = ($newPic))) {
	echo $_FILES[$picType]["name"] . " already exists. ";
      } else {
        move_uploaded_file($_FILES[$picType]["tmp_name"],$newPic);
	/*
        echo "Stored in: " . $picDir . "/" . $actorPicDir . "/" . $_FILES[$picType]["name"];
        lf();
        echo '<img src="' . $newPic . '"  width="200">';
	*/
        return ($newPic);
      }
    }
  }
else
  {
    echo "Invalid file";
  }
}


function footer() {
  if (is_apache()) {
    dlf();
    echo '<table border=1 width=100%>';
    // first row
    echo '<tr>';
    nl();
    echo '<td>';
    echo '<a href="index.php">Home</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="add-actor.php">Add actor</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="statistics.php">View statistics</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="find-duplicates.php">Find duplicates</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="complete-videos-md5sum.php">Create md5sum</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="view-all-actors.php">View all actors</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="add-actor-to-video.php">Index videos per actor</a>';
    echo '</td>';
    nl();
    echo '</tr>';
    nl();
    // second row
    echo '<tr>';
    nl();
    echo '<td>';
    echo '';
    echo '</td>';
    nl();
    echo '<td>';
    echo '';
    echo '</td>';
    nl();
    echo '<td>';
    echo '';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="orphaned-frames.php">Orphaned frames</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="check-frames.php">Check frames</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '<a href="view-actor.php">View actor</a>';
    echo '</td>';
    nl();
    echo '<td>';
    echo '';
    echo '</td>';
    nl();
    echo '</tr>';
    nl(); 
    echo '</table>';
    nl();
    lf();
  }
}
?>
