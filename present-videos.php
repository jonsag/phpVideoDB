<html>
<head>
<title>jsvideos present videos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
<script src="flowplayer/flowplayer.min.js"></script>
<script language="JavaScript">
   flowplayer("player", "flowplayer/flowplayer.swf");
</script>

  </head>

  <body>
  <h1><center>present videos</center></h1>


<?php

// include config   
include ('config.php');
// include functions
include ('functions/functions.php');

if (is_apache()) {
  header( 'Content-type: text/html; charset=utf-8' );
}

$counter1 = 0;

  $searchTerm = $_POST['searchTerm'];
  echo "Searching for path LIKE '%" . $searchTerm . "%' OR fileName LIKE '%" . $searchTerm . "%' OR directory LIKE '%" . $searchTerm . "%'<br><br>\n";
  $sql = "SELECT * FROM videos WHERE path LIKE '%$searchTerm' OR fileName LIKE '%$searchTerm%' OR directory LIKE '%$searchTerm%'";

if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

$query = mysql_query($sql);

mysql_select_db($db_name) or die(mysql_error());;

while($row = mysql_fetch_array($query)) {
  
  $counter1++;

  $id[$counter1] = $row['id'];
  $path[$counter1] = $row['path'];
  $fileName[$counter1] = $row['fileName'];
  $directory[$counter1] = $row['directory'];
  $duration[$counter1] = $row['duration'];
  $fileSize[$counter1] = $row['fileSize'];
  $videoCodec[$counter1] = $row['videoCodec'];
  $frames[$counter1] = $row['frames'];
}

echo "Found ". $counter1 . " videos<br>\n";
echo "-----------------------------------------------------<br>\n";

for ($counter2 = 1; $counter2 <=$counter1; $counter2++) {

  echo "<br>" . $path[$counter2] . $fileName[$counter2] . "<br>\n";
  echo "Duration: " . $duration[$counter2] . " seconds<br>\n";
  echo "Filesize: " . formatBytes($fileSize[$counter2]) . "<br>\n";
  echo "VideoCodec: " . $videoCodec[$counter2] . "<br>\n";

  display_frames($directory[$counter2],$fileName[$counter2],$frames[$counter2]);

  lf();
  echo '<a href="view-and-tag-video.php?id=' . $id[$counter2] . '">View and tag video</a>';
  lf ();

  //  echo '<a href="' .  $path[$counter2] . $fileName[$counter2] . '" style="display:block;width:425px;height:300px;" id="player"></a>';

  if (is_apache()) {
    flush();
    ob_flush();
  }
  
  echo "<br>\n-----------------------------------------------------<br>\n";
}

mysql_close($db_con);

footer();
?>

</body>
</html>
