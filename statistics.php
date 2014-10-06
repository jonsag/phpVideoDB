<?php
///// include configuration file
include ('config.php');
// include functions
include ('functions/functions.php');
?>

<html>
<head>
<title>jsvideos statistics</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  </head>
  
  <body>
  <h1><center>jsvideo statistics</center></h1>
  
<table border="1">
<tr>
  <td>file type</td>
  <td>count</td>
  <td>disk space</td>
  <td>duration</td>
</tr>  
<tr>
  <?php
  if (!$db_con) {
    die('Could not connect: ' . mysql_error());
  }

mysql_select_db($db_name);

///// file types
foreach ($videoExtensions as $extension) {

  // number of files by file type
  $query = ("SELECT COUNT(1) FROM videos WHERE fileName LIKE '%.$extension'");
  $result = mysql_query($query);
  
  if ($result) {
    $row = mysql_fetch_array($result);
    $total = $row[0];
    // column file types
    echo "<td>" . $extension . "</td>\n";
    // column file countcount
    echo "<td>" . $total . "</td>\n";
  }
  else {
    die('Invalid query: ' . mysql_error());
  }

  // disk space by file type
  $query = ("SELECT SUM(fileSize) FROM videos WHERE fileName LIKE '%.$extension'");
  $result = mysql_query($query);

  if ($result) {
    $row = mysql_fetch_array($result);
    $total = $row[0];
    if ($total < 1) {
      $total = 0;
    }
    // column disk space
    echo "<td>" . $total . " bytes<br>\n";
    if ($total >= 1) {
    echo formatBytes($total);
    }
      echo "</td>\n";
  }
  else {
    die('Invalid query: ' . mysql_error());
  }

  // duration by file type
  $query = ("SELECT SUM(duration) FROM videos WHERE fileName LIKE '%.$extension'");
  $result = mysql_query($query);

  if ($result) {
    $row = mysql_fetch_array($result);
    $total = $row[0];
    if ($total < 1) {
      $total = 0;
    }
    $durationArray = secondsToTime($total);
    // column duration
    echo "<td>" . $total . " seconds <br>\n";
    if ($total >= 1) {
    echo $durationArray['d'] . " days ";
    echo $durationArray['h'] . " hours "; 
    echo $durationArray['m'] . " minutes "; 
    echo $durationArray['s'] . " seconds";
    }
    echo "</td>\n";
  }
  else {
    die('Invalid query: ' . mysql_error());
  }

  echo "</tr>\n<tr>";  

}

// total rows                                                                                                                                                                      
$query = ("SELECT COUNT(1) FROM videos");
$result = mysql_query($query);

if ($result) {
  $row = mysql_fetch_array($result);
  $total = $row[0];
  echo "<td>Total</td>";
  echo "<td>" . $total . "</td>\n";
}
else {
  die('Invalid query: ' . mysql_error());
}

///// total disk space 
$query = ("SELECT SUM(fileSize) FROM videos");
$result = mysql_query($query);

if ($result) {
  $row = mysql_fetch_array($result);
  $total = $row[0];

  echo "<td>" . $total . " bytes<br>\n";
  echo formatBytes($total) . "</td>\n";
}
else {
  die('Invalid query: ' . mysql_error());
}


///// total duration
$query = ("SELECT SUM(duration) FROM videos");
$result = mysql_query($query);

if ($result) {
  $row = mysql_fetch_array($result);
  $total = $row[0];
  $durationArray = secondsToTime($total);

  echo "<td>" . $total . " seconds <br>\n";
  echo $durationArray['d'] . " days ";
  echo $durationArray['h'] . " hours ";
  echo $durationArray['m'] . " minutes ";
  echo $durationArray['s'] . " seconds )</td>\n";
}
else {
  die('Invalid query: ' . mysql_error());
}

mysql_close($db_con);

footer();
?>
</tr>
</table>
</body>
</html>
