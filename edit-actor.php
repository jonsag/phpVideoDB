
<html>
<head>
<title>jsvideos edit actor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>
  
  <body>
  <h1><center>jsvideo edit actor</center></h1>

<?php
   include ('config.php');
include ('functions/functions.php');
  
$id = $_GET['id'];

echo "Id: " . $id . "<br>\n";

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// select database
mysql_select_db($db_name) or die(mysql_error());

$sql = "SELECT * FROM actors WHERE id='$id'";

$query = mysql_query($sql);

// read data into array
$result = mysql_fetch_array($query);

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


echo '<form action="edit-actor-in-database.php?id=' . $id . '" method="post" enctype="multipart/form-data"/>';
echo 'Name: <input type="text" name="actorsName" value="' . $actorsName . '"/>';
echo 'Female: <input type="checkbox" value="1" name="mf"';
if ($mf == "1") {
  echo ' checked="checked"';
}
echo '/>';
echo 'aka: <input type="text" name="aka" size="50" maxlength="255" value="' . $aka . '"/>( separate aliases with , )';
lf();
echo 'Birth Name: <input type="text" name="birthName" value="' . $birthName . '"/>';
echo 'Birth Date: <input type="text" name="birthDate" value="' . $birthDate . '"size="10" maxlenght="10"/>';
echo 'Birth City: <input type="text" name="birthCity" value="' . $birthCity . '"/>';

echo 'Country: <select id="birthCountry" name="birthCountry">';
$prop = query_properties_name("countries","country_name",$birthCountry);
lf();
$query="SELECT country_name FROM countries";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['country_name'];
  echo '<option';

  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

lf();
echo 'Height: <input type="text" name="height" value="' . $height . '"size="3" maxlength="3"/>cm';
echo 'Weight: <input type="text" name="weight" value="' . $weight . '"size="3" maxlength="3"/>kg';
lf();

echo 'Ethnicity: <select id="ethnicity" name="ethnicity">';
$prop = query_properties_name("ethnicity","properties",$ethnicity);
$query="SELECT properties FROM ethnicity";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo '<option';
  
  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

lf();

echo 'Hair: <select id="hair" name="hair">';
$prop = query_properties_name("hair","properties",$hair);
$query="SELECT properties FROM hair";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo '<option';
  
  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

echo 'Eyes: <select id="eyes" name="eyes">';
$prop = query_properties_name("eyes","properties",$eyes);
$query="SELECT properties FROM eyes";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo '<option';
  
  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

lf();

echo 'Tattoos: <select id="tattoos" name="tattoos">';
$prop = query_properties_name("tattoos","properties",$tattoos);
$query="SELECT properties FROM tattoos";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo '<option';
  
  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

lf();

echo 'Body: <select id="body" name="body">';
$prop = query_properties_name("body","properties",$body);
$query="SELECT properties FROM body";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo '<option';
  
  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

echo 'Breasts: <select id="breasts" name="breasts">';
$prop = query_properties_name("breasts","properties",$breasts);
  $query="SELECT properties FROM breasts";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo '<option';
  
  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

echo 'Legs: <select id="legs" name="legs">';
$prop = query_properties_name("legs","properties",$legs);
$query="SELECT properties FROM legs";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
echo "\n";
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo '<option';
  
  if ($property == $prop) {
    echo ' selected="selected" ';
  }

  echo '>'.  $property . '</option>';
  echo "\n";
}
echo '</select>';
echo "\n";

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
$pics = array($facePic,$fullPic,$nudePic,$hcPic);
foreach ($pics as $pic) {
  $thumb = pathinfo($pic, PATHINFO_FILENAME);
  $thumb = ($picDir . "/" . $actorPicDir . "/" . $thumbDir . "/" . $thumbPrefix . $thumb . "." . $picFormat);
  if (file_exists($thumb)) {
    echo '<td><a href="' . $pic . '"><img src="' . $thumb . '"></td>';
  }
  elseif (file_exists($pic)) {
    echo '<td><a href="' . $pic . '">No thumb</td>';
  }
  else {
    echo '<td>No pic</td>';
  }
  echo "\n";
}
echo '</tr>';

echo "\n";

echo '<tr>';
echo "\n";
echo '<td><label for="facePic"></label><input type="file" name="facePic" id="facePic"></td>';
echo "\n";
echo '<td><label for="fullPic"></label><input type="file" name="fullPic" id="fullPic"></td>';
echo "\n";
echo '<td><label for="nudePic"></label><input type="file" name="nudePic" id="nudePic"></td>';
echo "\n";
echo '<td><label for="hcPic"></label><input type="file" name="hcPic" id="hcPic"></td>';
echo "\n";
echo '</tr>';

echo '</table>';

dlf();
echo '<input type="submit">';
echo '<input type="reset" value="Reset!">';
lf();
echo '</form>';

mysql_close($db_con);

footer();

?>
