<html>
<body>
<title>jsvideos add actor to database</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo add actor to database</center></h1>

<?php
   
// include configuration file
include ('config.php');

// include functions
include ('functions/functions.php');

$picCount = 0;

if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_name);

// actorsName
$actorsName = $_POST['actorsName'];
echo "Name:" . $actorsName . "<br>\n";

// sex                                                                                                                                                                             
$mf = $_POST['mf'];
echo "Sex: ";
if ($mf == "1") {
  echo "Female";
} else {
  echo "Male";
}
lf();

// aka
$aka = $_POST['aka'];
echo "aka's: " . $aka . "<br>\n";

// birth
$birthName = $_POST['birthName'];
echo "Birth Name: " . $birthName . "<br>\n";

// birthDate
$birthDate = $_POST['birthDate'];
echo "Birth Date: " . $birthDate . "<br>\n";

// birthCity
$birthCity = $_POST['birthCity'];
echo "Birth City: " . $birthCity . "<br>\n";

// birthCountry
$birthCountry = $_POST['birthCountry'];
echo "Birth Country: " . $birthCountry;
$birthCountry = query_properties_id("countries","country_name",$birthCountry);
echo " id=" . $birthCountry . "<br>\n";

// ethnicity
$ethnicity = $_POST['ethnicity'];
echo "Ethnicity: " . $ethnicity;
$query = "SELECT id FROM ethnicity WHERE `properties` = '$ethnicity';";
$ethnicity = query_properties($query);
echo " id=" . $ethnicity . "<br>\n";

// height
$height = $_POST['height'];
echo "Height: " .  $height . " cm<br>\n";

// weight
$weight = $_POST['weight'];
echo "Weight: " .  $weight . " kg<br>\n";

// hair
$hair = $_POST['hair'];
echo "Hair: " . $hair;
$query = "SELECT id FROM hair WHERE `properties` = '$hair';";
$hair = query_properties($query);
echo " id=" . $hair . "<br>\n";

// eyes
$eyes = $_POST['eyes'];
echo "Eyes: " . $eyes;
$query = "SELECT id FROM eyes WHERE `properties` = '$eyes';";
$eyes = query_properties($query);
echo " id=" . $eyes . "<br>\n";

// tattoos
$tattoos = $_POST['tattoos'];
echo "Tattoos: " . $tattoos;
$query = "SELECT id FROM tattoos WHERE `properties` = '$tattoos';";
$tattoos = query_properties($query);
echo " id=" . $tattoos . "<br>\n";


// body
$body = $_POST['body'];
echo "Body: " . $body;
$query = "SELECT id FROM body WHERE `properties` = '$body';";
$body = query_properties($query);
echo " id=" . $body . "<br>\n";

// breasts
$breasts = $_POST['breasts'];
echo "Breasts: " . $breasts;
$query = "SELECT id FROM breasts WHERE `properties` = '$breasts';";
$breasts = query_properties($query);
echo " id=" . $breasts . "<br>\n";

// legs
$legs = $_POST['legs'];
echo "Legs: " . $legs;
$query = "SELECT id FROM legs WHERE `properties` = '$legs';";
$legs = query_properties($query);
echo " id=" . $legs . "<br><br>\n";

// pictures
echo '<table border="1">';
echo '<tr>';
$picture = array("facePic","fullPic","nudePic","hcPic");
foreach ($picture as &$picType) {
  $picCount++;
  $newPic = storePicture($picType,$actorsName);
  $thumbDest = pathinfo($newPic, PATHINFO_FILENAME);
  $thumbDest = ($picDir . "/" . $actorPicDir . "/" . $thumbDir . "/" . $thumbPrefix . $thumbDest . "." . $picFormat);
  $thumbName = make_thumb($newPic,$thumbDest);
  $pic[$picCount] = $newPic;
  echo '<td><a href="' . $newPic . '"><img src="' . $thumbDest . '"></td>';
}
echo '</tr>';
echo '</table>';

///// writing to mysql                                                                                                  
echo "<br><br>\nConnecting to mysql...<br><br>\n";

if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

$sql = "SELECT COUNT(*) FROM actors WHERE actorsName='$actorsName'";

$query = mysql_query($sql);

$result = mysql_fetch_row($query);

if (!$result) {
  die('Invalid query: ' . mysql_error());
}

if ($result[0] == 0) {

  echo "Adding actor to database...<br>\n";
  
  mysql_select_db($db_name);
  
  $query = "INSERT INTO actors (mf,actorsName,aka,birthName,birthDate,birthCity,birthCountry,ethnicity,height,weight,hair,eyes,tattoos,body,breasts,legs,facePic,fullPic,nudePic,hcPic) VALUES ('$mf','$actorsName','$aka','$birthName','$birthDate','$birthCity','$birthCountry','$ethnicity','$height','$weight','$hair','$eyes','$tattoos','$body','$breasts','$legs','$pic[1]','$pic[2]','$pic[3]','$pic[4]')";

  $result = mysql_query($query);
  
  if ($result) {
    echo "Actor added<br>\n";
    $id = mysql_insert_id();
    echo '<a href="edit-actor.php?id=' . $id . '">Edit ' . $actorsName . '</a>';
  }
  else {
    die('Invalid query: ' . mysql_error());
  }

}
else {
  echo "Actor already in database<br><br>\n";

  $sql = "SELECT id FROM actors WHERE actorsName='$actorsName'";

  $result = mysql_query($sql);

  if ($result) {
    $id = (mysql_result($result,0));
  }
  else {
    die('Invalid query: ' . mysql_error());
  }

  echo '<a href="edit-actor.php?id=' . $id . '">Edit ' . $actorsName . ' instead</a>';

}

mysql_close($db_con);

footer();

?>

</body>
</html>
