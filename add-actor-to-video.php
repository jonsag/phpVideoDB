<html>
<head>
<title>jsvideos add actor to video</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo add actor to video</center></h1>

<?php
include ('config.php');
include ('functions/functions.php');

$counter = 0;
$counter1 = 0;
$counter2 = 0;
$counter3 = 0;
$counter4 = 0;
$counter5 = 0;
$actorVideoCount= array();
$actorNewVideoCount= array();
$totalVideo = 0;
$totalNewVideo = 0;

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// select database
mysql_select_db($db_name) or die(mysql_error());

// find all actors
$sql = "SELECT * FROM actors ORDER BY actorsName ASC";
$query = mysql_query($sql);
while($row = mysql_fetch_array($query)) {
  $counter++;
  $actorsId[$counter] = $row['id'];
  $actorsName[$counter] = $row['actorsName'];
}

// do all actors
for ($counter1 = 1; $counter1 <= $counter; $counter1++) {
  // replace spaces with _ 
  $actorsName[$counter1] = str_replace(' ', '_', $actorsName[$counter1]);

  // find videos with directory as actors name
  $sql = "SELECT * FROM videos WHERE directory='$actorsName[$counter1]'";
  $query = mysql_query($sql);
  $counter3 = 0;
  while($row = mysql_fetch_array($query)) {
    $counter3++;
    $videosId[$counter3] = $row['id'];
  }

  $actorVideoCount[$counter1] = $counter3;
  $totalVideo = ($totalVideo + $counter3);
  $counter5 = 0;

  // do all videos that has this actor as directory
  for ($counter2 = 1; $counter2 <= $counter3; $counter2++) {
    echo $counter2 . "/" . $counter3 . ": " . $actorsName[$counter1] . " probably appear in video with id=" . $videosId[$counter2] . "<br>\n";
    $sql = "SELECT COUNT(*) FROM actorsVideos WHERE video='$videosId[$counter2]' AND actor='$actorsId[$counter1]'";
    $query = mysql_query($sql);
    $result = mysql_fetch_row($query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    if (!$result[0]) {
      echo "No record found in database<br>\nAdding record...<br>\n";
      $sql = "INSERT INTO actorsVideos (video, actor) VALUES ('$videosId[$counter2]', '$actorsId[$counter1]')";
      $counter5++;

      $result = mysql_query($sql);
      
      if ($result) {
	echo "Added <br>\n";
      }
      else {
	die('Invalid query: ' . mysql_error());
      }

    }
    else {
      echo "Already in database<br>\n";
    }
    $totalNewVideo = ($totalNewVideo + $counter5);
    $actorNewVideoCount[$counter1] = $counter5;
    lf();
  }
}

for ($counter4 = 1; $counter4 <= $counter; $counter4++) {
  $actorsName[$counter4] = str_replace('_',' ',$actorsName[$counter4]);
  echo '<a href="view-actor.php?id=' . $actorsId[$counter4] . '">' . $actorsName[$counter4] . '</a> has ' . $actorVideoCount[$counter4] . ' videos, of which ' . $actorNewVideoCount[$counter4] . ' was new';
  lf();
}

echo "Found a total of " . $totalVideo . " of which " . $totalNewVideo . " was added to database<br>\n";

mysql_close($db_con);

footer();

?>

</body>
</html>