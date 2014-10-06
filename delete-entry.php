<html>
<head>
<title>jsvideos deleting video</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo deleting video...</center></h1>

<?php

   include ('config.php');
include ('functions/functions.php');

$db_con = mysql_connect($db_host,$username,$password);
$connection_string = mysql_select_db($db_name);

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

// select database
mysql_select_db($db_name) or die(mysql_error());;

$id = $_POST['id'];

$sql = "SELECT * FROM videos WHERE id='$id' LIMIT 1"; 

echo "Searching for record with id= " . $id . " ...<br>\n<br>\n" ;

$result = mysql_query($sql)  or die(mysql_error());

$row = mysql_fetch_assoc($result);

$path = $row['path'];
$fileName = $row['fileName'];
$directory = $row['directory'];
$frames = $row['frames'];

echo "id: " . $row['id'] . "<br>\n";
echo $path . $fileName . "<br>\n<br>\n";
echo $frames . " frames<br>\n";

if ($frames > 0) {
  display_frames($directory,$fileName,$frames);
}

lf();

delete_entry($id,$path,$fileName,"yes");

mysql_close($db_con);

echo '<a href="find-duplicates.php">Find more duplicates</a>';

footer();

?>

  </body>
  </html>
  