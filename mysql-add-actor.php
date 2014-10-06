<?php

///// include configuration file
include ('config.php');

///// functions
function query_properties($query) {

  ///// include configuration file
  include ('config.php');
  
  if (!$db_con) {
    die('Could not connect: ' . mysql_error());
  }
  
  mysql_select_db($db_name);
  $result = mysql_query($query);
  $id = mysql_result($result, 0);
  return $id;
}

function storePicture($picType,$actorsName) {

  ///// include configuration file
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
	  echo "Return Code: " . $_FILES[$picType]["error"] . "<br>";
	}
  else
    {
      //echo "Upload: " . $_FILES[$picType]["name"] . "<br>";
      //echo "Type: " . $_FILES[$picType]["type"] . "<br>";
      //echo "Size: " . ($_FILES[$picType]["size"] / 1024) . " kB<br>";
      //echo "Temp file: " . $_FILES[$picType]["tmp_name"] . "<br>";

      $newPic = ($actorsName . "-" . $picType . "-" . $_FILES[$picType]["name"]);
      $newPic = preg_replace('/[^a-zA-Z0-9%\[\]\.\(\)%&-]/s', '_', $newPic);
      $newPic = ($picDir . "/" . $actorsPicDir . "/" . $newPic);

      if (file_exists($newPic = ($newPic))) {
          echo $_FILES[$picType]["name"] . " already exists. ";
        } else {
        move_uploaded_file($_FILES[$picType]["tmp_name"],$newPic);
        //echo "Stored in: " . $picDir . "/" . $actorsPicDir . "/" . $_FILES[$picType]["name"] . "<br>\n";
        //echo '<img src="' . $newPic . '"  width="200">';
	return ($newPic);
      }
    }
    }
else
  {
    echo "Invalid file";
  }
}

function makeThumb($newPic, $dest, $thumbWidth) {

  // read the source image
  $source_image = imagecreatefromjpeg($newPic);
  $width = imagesx($source_image);
  $height = imagesy($source_image);
  
  // find the "desired height" of this thumbnail, relative to the desired width
  $desired_height = floor($height * ($thumbWidth / $width));
  
  // create a new, "virtual" image
  $virtual_image = imagecreatetruecolor($thumbWidth, $desired_height);
  
  // copy source image at a resized size */
  imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $thumbWidth, $desired_height, $width, $height);
  
  // create the physical thumbnail image to its destination
  $thumbName = "tn_" . (basename($newPic));
  $thumbName = $dest . $thumbName;
  imagejpeg($virtual_image, $thumbName);
  return $thumbName;
}

?>

<html>
<body>

<?php
// actorsName
$actorsName = $_POST['actorsName'];
echo "Name: " . $actorsName . "<br>\n";

// sex                                                                                                                                                                             
$mf = $_POST['mf'];
echo "Sex: ";
if ($mf == "1") {
  echo "Female";
} else {
  echo "Male";
}
echo "<br>\n";

// aka
$aka = $_POST['aka'];
echo "aka's: " . $aka . "<br>\n";;

// birthDate
$birthDate = $_POST['birthDate'];
echo "Birth Date: " . $birthDate . "<br>\n";

// birthCity
$birthCity = $_POST['birthCity'];
echo "Birth City: " . $birthCity . "<br>\n";

// birthCountry
$birthCountry = $_POST['birthCountry'];
echo "Birth Country: " . $birthCountry;
$query = "SELECT id FROM countries WHERE `country_name` = '$birthCountry';";
$birthCountry = query_properties($query);
echo " id=" . $birthCountry . "<br>\n";

// ethnicity
$ethnicity = $_POST['ethnicity'];
echo "Ethnicity: " . $ethnicity;
$query = "SELECT id FROM ethnicity WHERE `properties` = '$ethnicity';";
$ethnicity = query_properties($query);
echo " id=" . $ethnicity . "<br>\n";

// height
$height = $_POST['height'];

// weight
$weight = $_POST['weight'];

// hair
$hair = $_POST['hair'];
echo "Hair: " . $hair;
$query = "SELECT id FROM hair WHERE `properties` = '$hair';";
$hair = query_properties($query);
echo " id=" . $hair . "<br>\n";

// eyes
$eyes = $_POST['eyes'];
$eyes = $_POST['eyes'];
echo "Eyes: " . $eyes;
$query = "SELECT id FROM eyes WHERE `properties` = '$eyes';";
$eyes = query_properties($query);
echo " id=" . $eyes . "<br>\n";

// tattoos
tattoos = $_POST['tattoos'];
tattoos = $_POST['tattoos'];
echo "Tattoos: " . tattoos;
$query = "SELECT id FROM tattoos_tbl WHERE `properties` = 'tattoos';";
tattoos = query_properties($query);
echo " id=" . tattoos . "<br>\n";

// body
$body = $_POST['body'];
$body = $_POST['body'];
echo "Body: " . $body;
$query = "SELECT id FROM body WHERE `properties` = '$body';";
$body = query_properties($query);
echo " id=" . $body . "<br>\n";

// breasts
$breasts = $_POST['breasts'];
$breasts = $_POST['breasts'];
echo "Breasts: " . $breasts;
$query = "SELECT id FROM breasts WHERE `properties` = '$breasts';";
$breasts = query_properties($query);
echo " id=" . $breasts . "<br>\n";

// legs
$legs = $_POST['legs'];
$legs = $_POST['legs'];
echo "Legs: " . $legs;
$query = "SELECT id FROM legs WHERE `properties` = '$legs';";
$legs = query_properties($query);
echo " id=" . $legs . "<br><br>\n";

// pictures
$picture = array("facePic","fullPic","nudePic","hcPic");
foreach ($picture as &$picType) {
  $picCount++;
  $newPic = storePicture($picType,$actorsName);
  $dest = ($picDir . "/" . $actorsPicDir . "/" . $thumbDir . "/");
  $thumbName = makeThumb($newPic,$dest,$thumbWidth);
  //echo "<br>Stored in<br>" . $newPic . "<br><br>\n";
  $pic[$picCount] = $newPic;
  echo '<img src="' . $thumbName . '"  width="200"><br><br>';
}

///// writing to mysql                                                                                                  
echo "<br><br>\nConnecting to mysql...<br>\n";
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

echo "Adding actor to database...<br>\n";
mysql_select_db($db_name);
$query = "INSERT INTO actors (mf,actorsName,aka,birthDate,birthCity,birthCountry,ethnicity,height,weight,hair,eyes,tattoos,body,breasts,legs,facePic,fullPic,nudePic,hcPic)
VALUES ('$mf','$actorsName','$aka','$birthDate','$birthCity','$birthCountry','$ethnicity','$height','$weight','$hair','$eyes','tattoos','$body','$breasts','$legs','$pic[1]','$pic[2]','$pic[3]','$pic[4]')";
$result = mysql_query($query);
if ($result) {
  echo "Actor added<br>\n";
}
else {
  die('Invalid query: ' . mysql_error());
}

mysql_close($db_con);

?>

</body>
</html> 