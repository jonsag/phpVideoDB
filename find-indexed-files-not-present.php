<html>
<head>
<title>jsvideos find indexed files not present</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>

  <body>
  <h1><center>jsvideo find indexed files not present</center></h1>

<?php

   include ('config.php');
include ('functions/functions.php');

   if (is_apache()) {
     header( 'Content-type: text/html; charset=utf-8' );
   }

$counter1 = 0;
$present = 0;
$notPresent = 0;

$framePath = ($picDir . "/" . $videoFrameDir);
$thumbPath = ($framePath . "/" . $thumbDir);

if (is_cli() && $argv[1] != '') {
  if ($argv[1] == "yes") {
    $delete = "yes";
  }
}
elseif (is_apache() && isset($_POST['delete'])) {
  $delete = ($_POST['delete']);
}
else {
  $delete = "no";
}
  
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

$sql = "SELECT * FROM videos";

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
  $frames[$counter1] = $row['frames'];
}

echo "Found ". $counter1 . " videos<br>\n";
echo "-----------------------------------------------------<br>\n";

for ($counter2 = 1; $counter2 <=$counter1; $counter2++) {

  if (file_exists($path[$counter2] . $fileName[$counter2])) {
    $present++;
      echo "Present: ";
    }
  else {
    $notPresent++;
    echo "NOT present: ";
    $notId[$notPresent] = $id[$counter2];
    $notPath[$notPresent] = $path[$counter2];
    $notFileName[$notPresent] = $fileName[$counter2];
  }

  echo $path[$counter2] . $fileName[$counter2] . "<br>\n";

  if (is_apache()) {
    flush();
    ob_flush();
  }

}

if ($delete == "yes" ) {
  echo "Deleting records, frames and thumbs, if any<br>\n";

  for ($counter3 = 1; $counter3 <= $notPresent; $counter3++) {

    delete_entry($notId[$counter3],$notPath[$counter3],$notFileName[$counter3],"no");

    lf();
    
    if (is_apache()) {
      flush();
      ob_flush();
    }
    
  }

  echo "Deleted all not present<br>\n";

}

echo "<br>Present: " . $present . "<br>\n";
echo "NOT present: " . $notPresent . "<br>\n";
echo "making a total of " . ($present + $notPresent) . "<br>\n";

mysql_close($db_con);

footer();
?>

</body>
</html>
