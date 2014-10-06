<html>
<head>
<title>jsvideos view all actors</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo view all actors</center></h1>

<?php
   include ('config.php');
include ('functions/functions.php');

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// select database
mysql_select_db($db_name) or die(mysql_error());

$counter = 0;
$counter2 = 0;

$id = array();

$sql = "SELECT * FROM actors";

$query = mysql_query($sql);

// read data into arrays
while($row = mysql_fetch_array($query)) {
  $counter++;

  $id[$counter] = $row['id'];
  $mf[$counter] = $row['mf'];
  $actorsName[$counter] = $row['actorsName'];
  $aka[$counter] = $row['aka'];
  $birthName[$counter] = $row['birthName'];
  $birthDate[$counter] = $row['birthDate'];
  $birthCity[$counter] = $row['birthCity'];
  $birthCountry[$counter] = $row['birthCountry'];
  $ethnicity[$counter] = $row['ethnicity'];
  $height[$counter] = $row['height'];
  $weight[$counter] = $row['weight'];
  $hair[$counter] = $row['hair'];
  $eyes[$counter] = $row['eyes'];
  $tattoos[$counter] = $row['tattoos'];
  $body[$counter] = $row['body'];
  $breasts[$counter] = $row['breasts'];
  $legs[$counter] = $row['legs'];
  $facePic[$counter] = $row['facePic'];
  $fullPic[$counter] = $row['fullPic'];
  $nudePic[$counter] = $row['nudePic'];
  $hcPic[$counter] = $row['hcPic'];

}

for ($counter2 = 1; $counter2 <= $counter; $counter2++) {
  echo $counter2 . "/" . $counter . " Id: " . $id[$counter2] . "<br>\n";

  if ($mf[$counter2]) {
    echo "Female<br>\n";
  }
  else {
    echo "Male<br>\n";
  }

  echo $actorsName[$counter2] . " aka " . $aka[$counter2] . "<br>\n";

  $country = query_properties_name("countries","country_name",$birthCountry[$counter2]);

  echo "Born " . $birthDate[$counter];

  if ($birthName[$counter2] != '') {
    echo " as " . $birthName[$counter2];
  }
  if ($birthCity[$counter2] != '') {
    echo " in " . $birthCity[$counter2] . ", " . $country . "<br>\n";
  }
  else {
  echo " in " . $country . "<br>\n";
  }

  echo "Ethnicity: " . query_properties_name("ethnicity","properties",$ethnicity[$counter2]) . "<br>\n";
  echo "Height: " . $height[$counter2] . " cm<br>\n";
  echo "Weight: " . $weight[$counter2] . " kg<br>\n";
  echo "Hair: " . query_properties_name("hair","properties",$hair[$counter2]) . "<br>\n";
  echo "Eyes: " . query_properties_name("eyes","properties",$eyes[$counter2]) . "<br>\n";
  echo "Tattoos: " . query_properties_name("tattoos","properties",$tattoos[$counter2]) . "<br>\n";
  echo "Body: " . query_properties_name("body","properties",$body[$counter2]) . "<br>\n";
  echo "Breasts: " . query_properties_name("breasts","properties",$breasts[$counter2]) . "<br>\n";
  echo "Legs: " . query_properties_name("legs","properties",$legs[$counter2]) . "<br>\n";

  //lf();

  $pics = array($facePic[$counter2],$fullPic[$counter2],$nudePic[$counter2],$hcPic[$counter2]);
  foreach($pics as $pic) {
    $thumb = basename($pic);
    $thumb = pathinfo($thumb, PATHINFO_FILENAME);
    $thumb = $picDir . '/' . $actorPicDir . '/' . $thumbDir . '/' . $thumbPrefix . $thumb . '.' .  $picFormat;
    echo '<a href="' . $pic . '"><img src="' . $thumb . '"></a>';
  }

  lf();
  echo '<a href="edit-actor.php?id=' . $id[$counter2] . '">Edit ' . $actorsName[$counter2] . '</a>';
  lf();
  echo '<a href="view-actor.php?id=' . $id[$counter2] . '">Videos with ' . $actorsName[$counter2] . '</a>';
  dlf();
}

footer();

?>

</body>
</html>
