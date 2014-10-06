<html>
<head>
<title>jsvideos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
<link rel="stylesheet" type="text/css" href="css/styles.css">
  </head>

  <body>
  <h1><center>jsvideo</center></h1>

   Search directories on this server to scan and index videos (<a href="explore.php">explore.php</a>)
<hr>
<form action="explore.php" method="post" enctype="multipart/form-data"/>
Set root to start browsing: <input type="text" size="50" name="startPath"/><br>
<input type="submit">
<input type="reset" value="Reset!"><br>
</form><br>

   Find files that are not indexed (<a href="view-files-not-indexed.php">view-files-not-indexed.php</a>)
<hr>
<form action="view-files-not-indexed.php" method="post" enctype="multipart/form-data"/>
Set root to start browsing: <input type="text" size="50" name="startPath"/><br>
Also try to index them: <input type="checkbox" name="index" value="yes">WARNING - Make a control run first!<br>
   Also make md5sum: <input type="checkbox" name="makeMd5sum" value="yes"><br>
   Also create frames: <input type="checkbox" name="makeFrames" value="yes"><br>
<input type="submit">
<input type="reset" value="Reset!"><br>
</form><br>

   Search videos by videos database entries (<a href="present-videos.php">present-videos.php</a>)
<hr>
<form action="present-videos.php" method="post" enctype="multipart/form-data"/>
Search term: <input type="text" size="50" name="searchTerm"/><br>
<input type="submit">
<input type="reset" value="Reset!"><br>
</form><br>

   Find indexed videos that are not present (<a href="find-indexed-files-not-present.php">find-indexed-files-not-present.php</a>)
<hr>
<form action="find-indexed-files-not-present.php" method="post" enctype="multipart/form-data"/>
   Also delete frames,thumbs and entries in database: <input type="checkbox" name="delete" value="yes">WARNING - Make a control run first!<br>
<input type="submit">
<input type="reset" value="Reset!"><br>
</form><br>

   Create frames from videos in database (<a href="create-frames.php">create-frames.php</a>)
<hr>
<form action="create-frames.php" method="post" enctype="multipart/form-data"/>
<input type="radio" name="which" value="no" checked="checked">Videos with no frames<br>
<input type="radio" name="which" value="wrong">Videos with wrong number of frames<br>
<input type="submit">
<input type="reset" value="Reset!"><br>
</form><br>

   Check if directory and file names complies to our standard (<a href="check-naming.php">check-naming.php</a>)
<hr>
<form action="check-naming.php" method="post" enctype="multipart/form-data"/>
   Set root to run check in: <input type="text" size="50" name="startPath"/><br>
   Also try to rename them: <input type="checkbox" name="rename" value="yes">WARNING - Make a control run first!<br>
<input type="submit">
<input type="reset" value="Reset!"><br>
</form><br>

<?php

   include ('functions/functions.php');

footer();

?>

</body>
</html>
