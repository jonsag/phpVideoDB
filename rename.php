<?php

$characters = array(",","_");

$directories = glob(getcwd() . "/*");

foreach ($directories as $directory) {
  
  if (is_dir($directory)) {

    echo "Running through " . $directory . "\n";
    
    $filename = basename(getcwd());
    $filename = ("homejonusb2" . $filename);
    
    $directory = basename($directory);
    
    $files = glob(getcwd() . "/" . $directory . "/*");
    
    $filename = ($filename . $directory);
    
    $filename = str_replace($characters, '', $filename);
    
    foreach ($files as $oldFile) {
      
      //$oldFile = basename($oldFile);
      
      $newFile = str_replace($filename, '', $oldFile);
      
      if ($oldFile != $newFile) {
	echo "Renaming\n" . $oldFile . "\nto\n" . $newFile . "\n\n";
      }
      
      rename ($oldFile, $newFile);
    }
  }
  echo "Deleted all occurencies of " . $filename ." from filename\n\n";
}

?>
