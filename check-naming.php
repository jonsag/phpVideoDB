<html>
<head>
<title>jsvideos check directory and file names...</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>files check directory and file names...</center></h1>


<?php

// include configuration file
include ('config.php');
// include functions
include ('functions/functions.php');

if (is_apache()) {
  header( 'Content-type: text/html; charset=utf-8' );
}

$renamed =0;
$notRenamed = 0;
$objects = 0;

if (is_cli()) {
  $startPath = $argv[1];
}
else {
  $startPath = $_POST['startPath'];
}

if (is_cli()) {
  // 2nd argument should be 'yes' if renaming should be done
  $rename = $argv[2];
}
else {
  if (isset($_POST['rename'])) {
    $rename = $_POST['rename'];
  }
  else {
    $rename = "no";
  }
}

foreach (ListFiles($startPath) as $key=>$file){

  if (is_link($file)) {
    echo "Link: " . $file . "<br\n";
    echo "Will do nothing<br>\n";
  }

    elseif (is_dir($file)) {
      $objects++;
      echo "Directory: " . $file . "<br>\n";
      echo "Checking name...<br>\n";
      $result = rename_fullpath($file,$rename);
      if ($result[1]) {
	$renamed++;
      }
      else {
	$notRenamed++;
      }
    }

    elseif (is_file($file)) {
      // find file extension
      $ext = substr(strrchr($file,'.'),1);
            
      // check if extension matches video extension
      foreach($videoExtensions as $extension) {
	
	if($ext == $extension) {
	  $objects++;
	  echo "Videofile: " . $file . "<br>\n";
	  echo "Checking name...<br>\n";
	  $result = rename_fullpath($file,$rename);
	  if ($result[1]) {
	    $renamed++;
	  }
	  else {
	    $notRenamed++;
	  }
	  lf();
	  break;
	}
      }
    }

    if (is_apache()) {
      flush();
      ob_flush();
    }
    
}

echo $objects . " files and folders<br>\n";
if ($rename == "yes") {
  echo $renamed . " was renamed<br>\n";
}
else {
echo $renamed . " that should be renamed<br>\n";
}
echo $notRenamed . " had complying names<br>\n";

footer();

?>
