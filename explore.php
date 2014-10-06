<html>
<head>
<title>jsvideos explore</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo explore</center></h1>

<?php
   
// include configuration file
include ('config.php');
// include functions
include ('functions/functions.php');

$countIndexed = 0;
$countNotIndexed = 0;
$countVideos = 0;

$startPath = $_POST['startPath'] . "/";

echo "Contents of " . $startPath . "<br>\n";
echo "-----------------------------------------<br>\n";

echo '<a href="index-videos.php?startPath=' . $startPath . '">Index videos in this directory</a>';
lf();

?>

<form action="explore.php" method="post" enctype="multipart/form-data"/>
  
<?php

  // include configuration file
  include ('config.php');

  if (is_dir($startPath)) {

    $fileList = scandir($startPath);

    foreach($fileList as $file) {
      $isVideo = "no";
      $ext = substr(strrchr($file,'.'),1);

      // link to directory
      if (is_link($startPath . $file) && is_dir($startPath . $file)) {
	$color = $darkRed;
      }

      // link to file
      elseif (is_link($startPath . $file) && is_file($startPath . $file)) {
	$color = $darkBlue;
      	// link to video
        foreach($videoExtensions as &$extension) {
          if($ext == $extension) {
	    $color = $darkGreen;
	    $isVideo = "yes";
	    $indexed = isIndexed($startPath,$file);
            break;
          }
        }
      }

      // directory
      elseif (is_dir($startPath . $file)) {
	$color = $red;
      }

      // file
      elseif(is_file($startPath . $file)) {
	$color = $blue;
	foreach($videoExtensions as &$extension) {

	  if($ext == $extension) {
	    $color = $green;
	    $isVideo = "yes";
	    $indexed = isIndexed($startPath,$file);
	    break;
	  }
	}
      }

      echo '<p style="color:' . $color . '"><input type="radio" name="startPath" value="' . $startPath . $file . '">' . $file . '</p> ';
      echo "\n";
      
      if ($isVideo == "yes") {
	if ($indexed == "yes") {
	  echo "Already in database";
	  $countIndexed++;
	  $countVideos++;
	}
	else {
	  echo "NOT in database";
	  $countNotIndexed++;
	  $countVideos++;
	}
      }
    }
  }
?>

<br><br>
<input type="submit" name="submit" value="Explore">
<input type="reset" value="Reset!"><br>
</form>

<?php
    echo "Videos indexed: " . $countIndexed . "<br>\n";
echo "Videos NOT indexed: " . $countNotIndexed . "<br>\n";
echo "Total videos: " . $countVideos . "<br><br>\n";

echo '<a href="index-videos.php?startPath=' . $startPath . '">Index videos in this directory</a>';
lf();

echo '<p style="color:' . $red . '">directory</p>';
echo '<p style="color:' . $darkRed . '"/>link to directory</p>';
echo '<p style="color:' . $blue . '">file</p>';
echo '<p style="color:' . $darkBlue . '">link to file</p>';
echo '<p style="color:' . $green . '">video</p>';
echo '<p style="color:' . $darkGreen . '">link to video</p>';

footer();

?>

</body>
</html>