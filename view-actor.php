<html>
<head>
<title>jsvideos view actor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo view actor</center></h1>

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

$counter = 0;
$counter2 = 0;

echo '<form action="view-actor.php" method="post" enctype="multipart/form-data"/>';

echo 'Actor: <select id="actorsName" name="actorsName"><br>';
echo "\n";
$sql="SELECT actorsName FROM actors ORDER BY actorsName ASC";
$result=mysql_query($sql) or die ("Query to get data failed: ".mysql_error());
while ($actorsNameList=mysql_fetch_array($result)) {
  $actorsName=$actorsNameList['actorsName'];
  echo "<option>".  $actorsName. "</option>";
}
echo '</select>';
lf();
echo '<input type="submit">';
echo '<input type="reset" value="Reset!"><br>';

echo '</form>';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM actors WHERE id='$id'";
}
elseif ($_POST['actorsName'] != '') {
  $actorsName = $_POST['actorsName'];
  $sql = "SELECT * FROM actors WHERE actorsName='$actorsName'";
}
else {
  echo "No actor selected<br><br>\n";
}

if (!isset($_GET['id']) && $_POST['actorsName'] == '') {
  footer();
  exit();
}

$query = mysql_query($sql);

// read data into array
$result = mysql_fetch_array($query);

$id = $result['id'];
$mf = $result['mf'];
$actorsName = $result['actorsName'];
$aka = $result['aka'];
$birthName = $result['birthName'];
$birthDate = $result['birthDate'];
$birthCity = $result['birthCity'];
$birthCountry = $result['birthCountry'];
$ethnicity = $result['ethnicity'];
$height = $result['height'];
$weight = $result['weight'];
$hair = $result['hair'];
$eyes = $result['eyes'];
$tattoos = $result['tattoos'];
$body = $result['body'];
$breasts = $result['breasts'];
$legs = $result['legs'];
$facePic = $result['facePic'];
$fullPic = $result['fullPic'];
$nudePic = $result['nudePic'];
$hcPic = $result['hcPic'];

echo "Id: " . $id . "<br>\n";

if ($mf) {
  echo "Female<br>\n";
}
else {
  echo "Male<br>\n";
}

echo $actorsName . " aka " . $aka . "<br>\n";

$country = query_properties_name("countries","country_name",$birthCountry);

echo "Born " . $birthDate[$counter];

if ($birthName != '') {
  echo " as " . $birthName;
}
if ($birthCity != '') {
  echo " in " . $birthCity . ", " . $country . "<br>\n";
}
else {
  echo " in " . $country . "<br>\n";
}

echo "Ethnicity: " . query_properties_name("ethnicity","properties",$ethnicity) . "<br>\n";
echo "Height: " . $height . " cm<br>\n";
echo "Weight: " . $weight . " kg<br>\n";
echo "Hair: " . query_properties_name("hair","properties",$hair) . "<br>\n";
echo "Eyes: " . query_properties_name("eyes","properties",$eyes) . "<br>\n";
echo "Tattoos: " . query_properties_name("tattoos","properties",$tattoos) . "<br>\n";
echo "Body: " . query_properties_name("body","properties",$body) . "<br>\n";
echo "Breasts: " . query_properties_name("breasts","properties",$breasts) . "<br>\n";
echo "Legs: " . query_properties_name("legs","properties",$legs) . "<br>\n";

//lf();

$pics = array($facePic,$fullPic,$nudePic,$hcPic);
foreach($pics as $pic) {
  $thumb = basename($pic);
  $thumb = pathinfo($thumb, PATHINFO_FILENAME);
  $thumb = $picDir . '/' . $actorPicDir . '/' . $thumbDir . '/' . $thumbPrefix . $thumb . '.' .  $picFormat;
  echo '<a href="' . $pic . '"><img src="' . $thumb . '"></a>';
}

lf();
echo '<a href="edit-actor.php?id=' . $id . '">Edit ' . $actorsName . '</a>';
dlf();

$counter = 0;

$sql = "SELECT * from actorsVideos WHERE actor='$id'";

$query = mysql_query($sql);

while($row = mysql_fetch_array($query)) {
  $counter++;

  $videoId[$counter] = $row['video'];

}

// print out the result
for ($counter2 = 1;$counter2 <= $counter; $counter2++) {

  echo $counter2 . "/" . $counter . " Video Id=" . $videoId[$counter2] . "<br>\n";

  $sql = "SELECT * FROM videos WHERE id='$videoId[$counter2]'";

  $query = mysql_query($sql);

  $row = mysql_fetch_array($query);
  
  $path = $row['path'];
  $fileName = $row['fileName'];
  $duration = $row['duration'];
  $fileSize = $row['fileSize'];
  $directory = $row['directory'];  
  $frames = $row['frames'];

  echo $path . $fileName . "<br>\n";
  echo "Duration: " . $duration . " seconds<br>\n";
  echo "Filesize: " . formatBytes($fileSize) . "<br>\n";
  
  display_frames($directory,$fileName,$frames);
  
  dlf();

}

footer();

?>

</body>
</html>
