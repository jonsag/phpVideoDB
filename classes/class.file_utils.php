<?php
/**
 * file_utils is a class designed to bring you basic commands to work over an fs.
 * <br>Last update: October 24, 2006.
 * <br>Author: Marcelo Entraigas <m_entraigas at yahoo dot com>.
 * <br>Licence: BSD License.
 */
define('_linux', strpos(_path,':')? false : true);
define('_slash', _linux? '/' : chr(92));

class file_utils {
  var $files    = array();
  var $folders  = array();
  var $chroot   = '/var/www/';
  
  /**
   * class constructor.
   * here is defined the default working path.
   *
   * @param string $path
   */
  function file_utils ($path='') {
  	//check for '/../', '/./' and '//'
  	$pattern = (_linux == true) ? '([/+\.{0,2}/?]+)' : '([\\+\.{0,2}\\?]+)';
    if($path===''){
      $path = dirname(__FILE__) . _slash;
    }else{
      $path = ereg_replace($pattern, '/', $path);
    }
    //default chroot
    if ($this->chroot != ''){
     $pattern = sprintf("%s",str_replace('/','\/',$this->chroot));
     if (!ereg ("^{$pattern}",$path))
    		$path = $this->chroot;
    }
    define('_path' , $path);
  }


  /**
   * List a folder content and put it on $this->folders or $this->files.
   *
   * @param string $path
   */
  function ls($path=''){
    clearstatcache();
    $handle = @opendir($path);
    if($handle==false){
      $path = _path;
    }
    $handle = @opendir($path);
    if($handle != false){
    	while(false!==($filename=@readdir($handle))){
    		if ($filename == '..'){
    			$pattern = (_linux == true) ? '(\/+[^/]+\/?)$' : ''; //msdos repace missing!
    			$filepath = ereg_replace($pattern, '', $path);    			
    		}else{
	    		$filepath           = $path . $filename;
    		}
    	   $flag               = 'folders';
    		$tmp['filepath']    = $filepath;
    		$tmp['description'] = htmlentities($filename);
    		$tmp['perms']       = sprintf("%o",@fileperms($filepath));
    		$tmp['time']        = date("H:i m-d-y",@filemtime($filepath));
    		if(@is_file($filepath)){
          $flag  = 'files';
          $tmp['size'] = filesize($filepath);
        }
    		$eval = sprintf("\$this->%s['%s'] = \$tmp;", $flag, $filename);
    		eval($eval);
    	}
    }
    @closedir($handle);
    @ksort($this->files);
    @ksort($this->folders);
  }
  
  /**
   * Get a human redable size
   *
   * @param integer $size
   * @return string
   */
  function get_size($size){
    $size = (int) $size;
    if($size<1000)
      $size = sprintf("%0.0f Bytes",$size);
    elseif ($size<(1024*1000))
      $size = sprintf("%0.2f KB",$size/1024);
    elseif ($size<(1024*1024*1000))
      $size = sprintf("%0.2f MB",$size/(1024*1024));//1048576
    else
      $size = sprintf("%0.2f GB",$size/(1024*1024*1024));//1073741824
    return $size;
  }
  
  /**
   * Dowload a file from server
   *
   * @param string $file
   */
  function download($file){
    if(is_file($file) && @fopen($file,'r')){
    	header("Content-type: application/force-download");
    	header(sprintf("Content-Disposition: attachment; filename=%s",basename($file)));
    	@readfile($file);
    }else{
      header('HTTP/1.0 401 Unauthorized');
    }
  	exit;
  }
  
  /**
   * Make a folder on the server
   *
   * @param string $dir
   * @param string $perm
   */
  function mkdir ($dir, $perm='0777'){
    $tmp  = explode(_slash, $dir);
    $path = '';
    foreach ($tmp as $local) {
    	$path .= $local . _slash; 
      $mkdir = "if(@mkdir('$dir',$perm)==false) return false;";
      eval($mkdir);
    }
  }

  /**
   * Cahnge file perms
   *
   * @param string $file
   * @param string $perm
   */
  function chmod ($file, $perm) {
    $perm  = ereg('[1-7]{1,3}',$perm)? sprintf("0%d",$perm) : "'$perm'";
  	$chmod = "@chmod('$file', $perm);";
  	eval($chmod);
  }
  
  /**
   * Upload a file/s to the server
   *
   * @param string $to
   */
  function upload ($to) {
    foreach ($_FILES as $file) {
      if(is_uploaded_file($file['tmp_name'])){
        @move_uploaded_file($file['tmp_name'], $to . basename($file['name']));
        @chmod($to . basename($file['name']), 0755);
      }
    }
  }
  
  /**
   * Delete a file from the server
   *
   * @param string $filename
   */
  function rm ($filename) {
    @unlink($filename);
  }
  
  /**
   * Generate/overwrite a file with content
   *
   * @param string $content
   * @param string $to
   * @return true|false
   */
  function save ($content, $to){
    if(!empty($content) and $fp = @fopen($to, 'w')) {
      @fwrite($fp, $content);
      return @fclose($fp);
    }
    return false;
  }

  /**
   * Generate/append a file with content
   *
   * @param string $content
   * @param string $to
   * @return true|false
   */
  function append ($string, $to){
    if(!empty($string) and $fp = @fopen($to, 'a')) {
      @fwrite($fp, $string);
      return @fclose($fp);
    }
    return false;
  }
}
?>