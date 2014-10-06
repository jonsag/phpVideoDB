<?php

// comment out line 94 for sharp run

// the target directory, no trailing slash
$directory = '/home/jon/usb2';
$directory = '/home/jon/usb5';
$directory = '/home/jon/xxx1';
//$directory = './';

$directories = array("/home/jon/usb2","/home/jon/usb5","home/jon/xxx1");
$count = 0;

foreach ($directories as $directory) {
  
  $characters = array("/","_",",");
  
  try {
    // check if we have a valid directory
    if( !is_dir($directory) )
      {
	throw new Exception('Directory does not exist!'."\n");
      }
    
    // check if we have permission to rename the files
    if( !is_writable( $directory ))
      {
	throw new Exception('You do not have renaming permissions!'."\n");
      }
    
    /*
     * @collapse white space
     * @param string $string
     * @return string
     */
    function collapseWhiteSpace($string) {
      return  preg_replace('/\s+/', ' ', $string);
    }
    
    function collapseUnderScores($string) {
      return  preg_replace('/_+/', ' ', $string);
    }
    
    /*
     * @convert file names to nice names
     * @param string $filename
     * @return string
     */
    function safe_names($filename) {
      $filename = collapseWhiteSpace($filename);
      $filename = collapseUnderScores($filename);
      $filename = str_replace(' ', '_', $filename);
      $filename = str_replace('_-_', '-', $filename);
      $filename = str_replace('_.', '.', $filename);
      $filename = preg_replace('/[^a-z0-9-._,]/i','',$filename);
      // return strtolower($filename);
      return  $filename;
    }
    
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, 0));
    
    // loop directly over the object
    while($it->valid()) {
      // check if value is a directory
      if(!$it->isDot()) {
	if(!is_writable($directory . '/' . $it->getSubPathName())) {
	  echo 'Permission Denied: ' . $directory . '/' . $it->getSubPathName() . "\n";
	}
	else {
	  // the old file name
	  $old_file = $directory . '/' . $it->getSubPathName();
	  // folder file resides in
	  $sub_folder = dirname($it->getSubPathName());
	  // full path to this file
	  $full_path = ($directory . "/" . $sub_folder);
	  // remove unwanted characters from full path
	  $safe_path = str_replace($characters, '', $full_path);
	  
	  // the new file name
	  $new_file = $directory . '/' . $it->getSubPath() . '/' . safe_names($it->current());
	  // remove full path from file name
	  $new_file = str_replace($safe_path, '', $new_file);
	  
	  // check if we need to rename   
	  if ($old_file != $new_file) {
	    
	    echo "Sub folder name is " . $sub_folder . "\n";
	    echo "Full path is " . $full_path . "\n";
	    echo "Safe path is " . $safe_path . "\n";
	    
	    echo "Renaming\n" . $old_file . " to\n" . $new_file . "\n";
	    
	    // rename the file
	    //rename ($old_file, $new_file); // this line should be commented until after you made a test run
	    
	    // a little message to say file is converted
	    echo 'Renamed ' . $directory . '/' . $it->getSubPathName() . "\n\n";
	    $count++;
	  }
	  //else {
	  //  echo "No need to rename " . $old_file . "\n\n";
	  //}
	}
      }
      // move to the next iteration
      $it->next();
    }
    
    // when we are all done let the user know
    echo "Renaming of files complete\n";
    echo "Renamed " . $count .  " files\n\n";
  }
  
  catch(Exception $e) {
    echo $e->getMessage()."\n";
  }
}

?>
