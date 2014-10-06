<html>
<head>
<title>jsvideos edit actor in database</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo write actor in database</center></h1>

<?php
   
// include configuration file
include ('config.php');

// include functions
include ('functions/functions.php');

$picCount = 0;
$oldpicCount = 0;

if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_name);

echo '<img src="pictures/icons/greenDot.png" height="15">Unchanged';
lf();
echo '<img src="pictures/icons/redDot.png" height="15">Changed';
dlf();

$id = $_GET['id'];
echo "Id: " . $id . "<br>\n";

$sql = "SELECT * FROM actors WHERE id='$id'";

$query = mysql_query($sql);

// read data into array                                                                                                                                                             
$result = mysql_fetch_array($query);

$oldmf = $result['mf'];
$oldactorsName = $result['actorsName'];
$oldaka = $result['aka'];
$oldbirthName = $result['birthName'];
$oldbirthDate = $result['birthDate'];
$oldbirthCity = $result['birthCity'];
$oldbirthCountry = $result['birthCountry'];
$oldethnicity = $result['ethnicity'];
$oldheight = $result['height'];
$oldweight = $result['weight'];
$oldhair = $result['hair'];
$oldeyes = $result['eyes'];
$oldtattoos = $result['tattoos'];
$oldbody = $result['body'];
$oldbreasts = $result['breasts'];
$oldlegs = $result['legs'];
$oldfacePic = $result['facePic'];
$oldfullPic = $result['fullPic'];
$oldnudePic = $result['nudePic'];
$oldhcPic = $result['hcPic'];

// actorsName
$actorsName = $_POST['actorsName'];
if ($actorsName == $oldactorsName) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "Name: " . $actorsName . "<br>\n";

// sex
$mf = $_POST['mf'];
if ($mf == $oldmf) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "Sex: ";
if ($mf) {
  echo "Female";
} else {
  echo "Male";
}
lf();

// aka
$aka = $_POST['aka'];
if ($aka == $oldaka) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "aka's: " . $aka . "<br>\n";

// birthName
$birthName = $_POST['birthName'];
if ($birthName == $oldbirthName) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "Birth Name: " . $birthName . "<br>\n";

// birthDate
$birthDate = $_POST['birthDate'];
if ($birthDate == $oldbirthDate) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "Birth Date: " . $birthDate . "<br>\n";

// birthCity
$birthCity = $_POST['birthCity'];
if ($birthCity == $oldbirthCity) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "Birth City: " . $birthCity . "<br>\n";

// birthCountry
$birthCountry = $_POST['birthCountry'];
check_if_changed($oldbirthCountry,$birthCountry,"countries","country_name");
echo "Birth Country: " . $birthCountry;
$birthCountry = query_properties_id("countries","country_name",$birthCountry);
echo " id=" . $birthCountry . "<br>\n";

// ethnicity
$ethnicity = $_POST['ethnicity'];
check_if_changed($oldethnicity,$ethnicity,"ethnicity","properties");
echo "Ethnicity: " . $ethnicity;
$ethnicity = query_properties_id("ethnicity","properties",$ethnicity);
echo " id=" . $ethnicity . "<br>\n";

// height
$height = $_POST['height'];
if ($height == $oldheight) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "Height: " .  $height . " cm<br>\n";

// weight
$weight = $_POST['weight'];
if ($weight == $oldweight) {
  echo '<img src="pictures/icons/greenDot.png" height="15">';
}
else {
  echo '<img src="pictures/icons/redDot.png" height="15">';
}
echo "Weight: " .  $weight . " kg<br>\n";

// hair
$hair = $_POST['hair'];
check_if_changed($oldhair,$hair,"hair","properties");
echo "Hair: " . $hair;
$hair = query_properties_id("hair","properties",$hair);
echo " id=" . $hair . "<br>\n";

// eyes
$eyes = $_POST['eyes'];
check_if_changed($oldeyes,$eyes,"eyes","properties");
echo "Eyes: " . $eyes;
$eyes = query_properties_id("eyes","properties",$eyes);
echo " id=" . $eyes . "<br>\n";

// tattoos
$tattoos = $_POST['tattoos'];
check_if_changed($oldtattoos,$tattoos,"tattoos","properties");
echo "Tattoos: " . $tattoos;
$tattoos = query_properties_id("tattoos","properties",$tattoos);
echo " id=" . $tattoos . "<br>\n";

// body
$body = $_POST['body'];
check_if_changed($oldbody,$body,"body","properties");
echo "Body: " . $body;
$body = query_properties_id("body","properties",$body);
echo " id=" . $body . "<br>\n";

// breasts
$breasts = $_POST['breasts'];
check_if_changed($oldbreasts,$breasts,"breasts","properties");
echo "Breasts: " . $breasts;
$breasts = query_properties_id("breasts","properties",$breasts);
echo " id=" . $breasts . "<br>\n";

// legs
$legs = $_POST['legs'];
check_if_changed($oldlegs,$legs,"legs","properties");
echo "Legs: " . $legs;
$legs = query_properties_id("legs","properties",$legs);
echo " id=" . $legs . "<br><br>\n";

// pictures
dlf();

echo "Pictures<br>\n";

echo '<table border="1">';

echo '<tr>';
echo '<td>Face</td>';
echo '<td>Full</td>';
echo '<td>Nude</td>';
echo '<td>HC</td>';
echo '</tr>';

echo "\n";

echo '<tr>';
echo "\n";

$pics = array($oldfacePic,$oldfullPic,$oldnudePic,$oldhcPic);

foreach ($pics as $forPic) {
  $oldpicCount++;

  $oldpic[$oldpicCount] = $forPic;

  $oldthumb[$oldpicCount] = pathinfo($oldpic[$oldpicCount], PATHINFO_FILENAME);
  $oldthumb[$oldpicCount] = ($picDir . "/" . $actorPicDir . "/" . $thumbDir . "/" . $thumbPrefix . $oldthumb[$oldpicCount] . "." . $picFormat);

  echo '<td>';
  if (file_exists($oldthumb[$oldpicCount])) {
    echo '<a href="' . $forPic . '"><img src="' . $oldthumb[$oldpicCount] . '">';
  }
  elseif (file_exists($forPic)) {
    echo '<a href="' . $forPic . '">No thumb';
  }
  else {
    echo 'No pic';
  }
  echo '</td>';
  echo "\n";
}
echo '</tr>';

echo "\n";

echo '<tr>';

$picture = array("facePic","fullPic","nudePic","hcPic");

foreach ($picture as $picType) {
  $picCount++;
  echo "\n";
  echo '<td>';
  //echo "PicType=" . $picType . "<br>Files=" . $_FILES[$picType]["name"] . "<br>";
  if (trim($_FILES[$picType]["name"]) != '') {
    $newPic = storePicture($picType,$actorsName);
    
    if ($newPic != $oldpic[$picCount]) {
      $thumbDest = pathinfo($newPic, PATHINFO_FILENAME);
      $thumbDest = ($picDir . "/" . $actorPicDir . "/" . $thumbDir . "/" . $thumbPrefix . $thumbDest . "." . $picFormat);

      $thumbName = make_thumb($newPic,$thumbDest);

      $pic[$picCount] = $newPic;

      echo '<a href="' . $pic[$picCount] . '"><img src="' . $thumbDest . '">';
    }
    
  }
  else {
    $pic[$picCount] = $oldpic[$picCount];
    echo '<a href="' . $oldpic[$picCount] . '"><img src="' . $oldthumb[$picCount] . '">';
  }

  echo '</td>';
  echo "\n";
}

echo '</tr>';

echo "\n";

echo '<tr>';
for ($counter = 1; $counter <= 4; $counter++) {
  echo "\n";
  echo '<td>';
  if ($pic[$counter] == $oldpic[$counter]) {
    echo '<img src="pictures/icons/greenDot.png" height="15">';
    echo '<br>' . $oldpic[$counter];
  }
  else {
    echo '<img src="pictures/icons/redDot.png" height="15">';
    echo '<br>' . $pic[$counter];
  }
  echo '</td>';
  echo "\n";
}

echo '</tr>';

echo '</table>';

///// writing to mysql                                                                                                  
echo "<br><br>\nConnecting to mysql...<br>\n";

if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

echo "Editing actor in database...<br>\n";

mysql_select_db($db_name);

$query = "UPDATE actors SET mf='$mf', actorsName='$actorsName', aka='$aka', birthName='$birthName', birthDate='$birthDate', birthCity='$birthCity', birthCountry='$birthCountry', ethnicity='$ethnicity', height='$height', weight='$weight', hair='$hair', eyes='$eyes', tattoos='$tattoos', body='$body', breasts='$breasts', legs='$legs', facePic='$pic[1]', fullPic='$pic[2]', nudePic='$pic[3]', hcPic='$pic[4]' WHERE id='$id'";

//$query = "INSERT INTO actors (mf,actorsName,aka,birthName,birthDate,birthCity,birthCountry,ethnicity,height,weight,hair,eyes,tattoos,body,breasts,legs,facePic,fullPic,nudePic,hcPic) VALUES ('$mf','$actorsName','$aka','$birthName','$birthDate','$birthCity','$birthCountry','$ethnicity','$height','$weight','$hair','$eyes','$tattoos','$body','$breasts','$legs','$pic[1]','$pic[2]','$pic[3]','$pic[4]')";

$result = mysql_query($query);

if ($result) {
  echo "Actor edited<br>\n";
}
else {
  die('Invalid query: ' . mysql_error());
}

echo '<a href="view-actor.php?id=' . $id . '">View actor</a>';

mysql_close($db_con);

footer();

?>

</body>
</html>
