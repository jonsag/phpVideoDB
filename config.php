<?php

date_default_timezone_set('Europe/Stockholm');

//setlocale(LC_ALL, 'en_US');

// Connections Parameters 
$db_host = "localhost";
$db_name = "jsvideos";
$username = "videos";
$password = "videopass";

$db_con = mysql_connect($db_host,$username,$password);
$connection_string = mysql_select_db($db_name);

// tables used
$video_tbl = "videos";
$actors_tbl = "actors";
$countries_tbl = "countries";
$ethnicity_tbl = "ethnicity";
$hair_tbl = "hair";
$eyes_tbl = "eyes";
$tattoos_tbl = "tattoos";
$body_tbl = "body";
$breasts_tbl= "breasts";
$legs_tbl = "legs";

// extensions
$videoExtensions = array("264","3gp","asf","avchd","avi","divx","f4v","flv","h264","m1v","m2v","m4v","mkv","mov","mp4","mp7","mpeg","mpg","ogm","rm","rmvb","wmv","xvid");
$picExtensions = array("gif", "jpeg", "jpg", "png");

// picture settings
$maxPicSize = 2000000;
$picDir = "pictures";
$actorPicDir = "actors";
$videoFrameDir = "videoFrames";
$thumbDir = "thumbs";
$thumbPrefix ="tn_";
$thumbWidth = 100;
$thumbHeigth = 100;

// size for the contactsheet
$contactsheetWidth = 1024;
$contacysheetHeigth = 768;
// quality of contactsheet
$contactsheetQuality = 100;
// frame thicknessaround shots
$contactsheetFrame  = 5;
// spacing between shots
$contactsheetSpacingH = 3;
$contactsheetSpacingV = 3;
// background colour
$contactsheetBackground = "white";
// height for the info text square
$contactsheetTextHeigth = 100;
// size of the info text
$contactsheetPointSize = 15;
// colour of the text
$contactsheetTextFill = "black";
// Colour of the error text
$contactsheetErrorTextFill = "red";

// number of columns
$frameColumns = 3;
// number of rows
$frameRows = 2;
$noOfFrames = ($frameColumns * $frameRows);
// how many seconds after the start, and before the end will we grab first frame
$startOffset = 10;
$endOffset = 10;
// grab to this format
$picFormat = "png";

// flv settings
$flvDir = "tmp";
$flvFps = "5"; // frames per second
$flvComp = "0"; // compression level, 0 lowest - worst quality
$flvWidth = "320"; 
$flvVideoBitrate = "384"; // kbit/s
$flvAudioFrequency = "11025";
$flvAudioBitrate = "48"; // 

// colors
$red = "#FF0000";
$darkRed = "#900000";
$blue = "#0000FF";
$darkBlue = "#000066";
$green = "#00FF00";
$darkGreen = "#006600";

// mplayer commands

///// mediainfo commands
//  md5sum
//  duration
$getDuration = "mediainfo --Inform='General;%Duration/String3%' ";
//  height
$getHeight = "mediainfo --Inform='Video;%Height%' ";
//  width
$getWidth = "mediainfo --Inform='Video;%Width%' ";
//  fileSize
$getFileSize = "mediainfo --Inform='General;%FileSize/String%' ";
//  aspectRatio
$getAspectRatio = "mediainfo --Inform='Video;%AspectRatio/String%' ";
//  videoCodec
$getVideoCodec = "mediainfo --Inform='Video;%CodecID/Hint%' ";
//  audioCodec
$getAudioCodec = "mediainfo --Inform='Audio;%CodecID/Hint%' ";
//  videoBitrate
$getVideoBitrate = "mediainfo --Inform='Video;%BitRate/String%' ";
//  audioBitrate
$getAudioBitrate = "mediainfo --Inform='Audio;%BitRate/String%' ";
//  overallBitrate
$getOverallBitrate = "mediainfo --Inform='General;%BitRate/String%' ";
//  frameRate
$getFrameRate = "mediainfo --Inform='Video;%FrameRate/String%' ";
?>
