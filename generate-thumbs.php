<?php

///// functions
function getThumbImage($videoPath) {
  $movie = new ffmpeg_movie($videoPath,false);
  $this->videoDuration = $movie->getDuration();
  $this->frameCount = $movie->getFrameCount();
  $this->frameRate = $movie->getFrameRate();
  $this->videoTitle = $movie->getTitle();
  $this->author = $movie->getAuthor() ;
  $this->copyright = $movie->getCopyright();
  $this->frameHeight = $movie->getFrameHeight();
  $this->frameWidth = $movie->getFrameWidth();
  
  $capPos = ceil($this->frameCount/4);
  
  if($this->frameWidth>120)
    {
      $cropWidth = ceil(($this->frameWidth-120)/2);
    }
  else
    {
      $cropWidth =0;
    }
  if($this->frameHeight>90)
    {
      $cropHeight = ceil(($this->frameHeight-90)/2);
    }
  else
    {
      $cropHeight = 0;
    }
  if($cropWidth%2!=0)
    {
      $cropWidth = $cropWidth-1;
    }
  if($cropHeight%2!=0)
    {
      $cropHeight = $cropHeight-1;
    }
  
  $frameObject = $movie->getFrame($capPos);
  
  
  if($frameObject)
    {
      $imageName = "tmb_vid_1212.jpg";
      $tmbPath = "/home/home_Dir/public_html/uploads/thumb/".$imageName;
      $frameObject->resize(120,90,0,0,0,0);
      imagejpeg($frameObject->toGDImage(),$tmbPath);
    }
  else
    {
      $imageName="";
    }
  
  return $imageName;
 
}
?>