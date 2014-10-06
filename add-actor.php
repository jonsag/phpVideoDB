<?php

///// include configuration file
include ('config.php');
include ('functions/functions.php');

$country_name = "";

?>

<html>
<head>
<title>jsvideos add actor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo add actor</center></h1>

   <form action="add-actor-to-database.php" method="post" enctype="multipart/form-data"/>
   Name: <input type="text" name="actorsName"/>
   Female: <input type="checkbox" name="mf" value="1" checked="checked"/>
   aka: <input type="text" name="aka" size="50" maxlength="255"/>( separate aliases with , )<br>
  Birth Name: <input type="text" name="birthName"/>
   Birth Date: <input type="text" name="birthDate" size="10" maxlenght="10"/>
   Birth City: <input type="text" name="birthCity"/>

Country: <select id="birthCountry" name="birthCountry"><br>
<?php
if (!$db_con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_name, $db_con) or die ("Error selecting specified database on mysql server: ".mysql_error());
$query="SELECT country_name FROM countries";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['country_name'];
  echo "<option>".  $property . "</option>";
}
?>
</select>
<br>
Height: <input type="text" name="height" size="3" maxlength="3"/>cm
Weight: <input type="text" name="weight" size="3" maxlength="3"/>kg
<br>

Ethnicity: <select id="ethnicity" name="ethnicity">
<?php
  $query="SELECT properties FROM ethnicity";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo "<option>".  $property . "</option>";
}
?>
</select>
<br>

Hair: <select id="hair" name="hair">
<?php
  $query="SELECT properties FROM hair";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo "<option>".  $property . "</option>";
}
?>
</select>

Eyes: <select id="eyes" name="eyes">
<?php
  $query="SELECT properties FROM eyes";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo "<option>".  $property . "</option>";
}
?>
</select>
<br>

Tattoos: <select id="tattoos" name="tattoos">
<?php
  $query="SELECT properties FROM tattoos";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo "<option>".  $property . "</option>";
}
?>
</select>
<br>

Body: <select id="body" name="body">
<?php
  $query="SELECT properties FROM body";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo "<option>".  $property . "</option>";
}
?>
</select>

Breasts: <select id="breasts" name="breasts">
<?php
  $query="SELECT properties FROM breasts";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo "<option>".  $property . "</option>";
}
?>
</select>

Legs: <select id="legs" name="legs">
<?php
  $query="SELECT properties FROM legs";
$result=mysql_query($query) or die ("Query to get data failed: ".mysql_error());
while ($propertyList=mysql_fetch_array($result)) {
  $property=$propertyList['properties'];
  echo "<option>".  $property . "</option>";
}
?>
</select>

<br><br>
Pictures<br>
<label for="facePic">Face:</label><input type="file" name="facePic" id="facePic"><br>
<label for="fullPic">Full:</label><input type="file" name="fullPic" id="fullPic"><br>
<label for="nudePic">Nude:</label><input type="file" name="nudePic" id="nudePic"><br>
<label for="hcPic">HC:</label><input type="file" name="hcPic" id="hcPic"><br>

<br><br>
<input type="submit">
<input type="reset" value="Reset!"><br>
</form>

<?php
  footer();

  mysql_close($db_con);
?>

</body>
</html>
