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

$grabbed = 0;
$noFrame = 0;

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// select database
mysql_select_db($db_name) or die(mysql_error());

$sql = "SELECT * FROM videos WHERE frames NOT LIKE '0'";

$query = mysql_query($sql);

while($row = mysql_fetch_array($query)) {
  $counter1++;

  $id[$counter1] = $row['id'];
  $path[$counter1] = $row['path'];
  $fileName[$counter1] = $row['fileName'];
  $directory[$counter1] = $row['directory'];

  $frames[$counter1] = $row['frames'];

}

for ($counter2 = 1;$counter2 <= $counter1; $counter2++) {

  echo $counter2 . "/" . $counter1;
  lf();
  echo "Id: " . $id[$counter2];
  lf();
  echo $path[$counter2] . $fileName[$counter2];
  lf();

  //echo "Directory: " . $directory[$counter2];
  //lf();

  echo "No of frames: " . $frames[$counter2];
  lf();


  for ($counter3 = 1; $counter3 <= $frames[$counter2]; $counter3++) {
    $thumb = ($picDir . "/" . $videoFrameDir . "/" . $thumbDir . "/" . $thumbPrefix . $directory[$counter2] . "-" . $fileName[$counter2] . "-" . $counter3 . "." . $picFormat);
    $frame = ($picDir . "/" . $videoFrameDir . "/" . $directory[$counter2] . "-" . $fileName[$counter2] . "-" . $counter3 . "." . $picFormat);


    if (file_exists($thumb) && file_exists($frame)) {
      echo $counter3 . " ";
    }
    else {
      $noFrame = 1;
    }

    //echo '<a href="' . $frame . '">';
    //echo '<img src="' . $thumb . '"></a>';
    // nl();

  }

  if ($noFrame == 1) {
    lf();
    $updateSql = "UPDATE videos SET frames='0' WHERE id='$id[$counter2]'";
    echo "Updating database where id=" . $id[$counter2];
    lf();
    $result = mysql_query($updateSql) or die(mysql_error());

    echo "Creating frames...";
    grab_frames($id[$counter2],$path[$counter2],$fileName[$counter2]);
    $grabbed++;
    $noFrame = 0;
  }

  dlf();

  if (is_apache()) {
    flush();
    ob_flush();
  }

}

echo "Grabbed frames for " . $grabbed . " videos.";
lf();

footer();

// close connection to mysql
mysql_close($db_con);

?>

</body>
</html>
