<?php defined('SYSPATH') or die('No direct script access.');
 
class Data_Folder_Core {

	public function dir_copy($srcdir, $dstdir, $offset = '', $verbose = false)
	{
		//http://us2.php.net/manual/en/function.copy.php#86738
		if(!isset($offset)) $offset=0;
		$num = 0;
		$fail = 0;
		$sizetotal = 0;
		$fifail = '';
		if(!is_dir($dstdir)) mkdir($dstdir);
		if($curdir = opendir($srcdir)) {
			while($file = readdir($curdir)) {
				if($file != '.' && $file != '..') {
					$srcfile = $srcdir . '/' . $file;    # added by marajax
					$dstfile = $dstdir . '/' . $file;    # added by marajax
					if(is_file($srcfile)) {
						if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
						if($ow > 0) {
							if($verbose) echo "Copying '$srcfile' to '$dstfile'...<br />";
							if(copy($srcfile, $dstfile)) {
								touch($dstfile, filemtime($srcfile)); $num++;
								chmod($dstfile, 0777);    # added by marajax
								$sizetotal = ($sizetotal + filesize($dstfile));
								if($verbose) echo "OK\n";
							}
							else {
								echo "Error: File '$srcfile' could not be copied!<br />\n";
								$fail++;
								$fifail = $fifail.$srcfile.'|';
							}
						}
					}
					else if(is_dir($srcfile)) {
						$ret = $this->dir_copy($srcfile, $dstfile, $verbose);
					}
				}
			}
			closedir($curdir);
		}
		return TRUE;
	}

	# http://us2.php.net/manual/en/function.rmdir.php#88723
	# remove a filled directory recursively
	function rmdir_recurse($path)
	{
		$path = rtrim($path, '/').'/';
		if(!is_dir($path))
			return TRUE;
			
		$handle = opendir($path);
		while(false !== ($file = readdir($handle)))
		{
			if($file != '.' and $file != '..' )
			{
				$fullpath = $path.$file;
				if(is_dir($fullpath))
					$this->rmdir_recurse($fullpath);
				else
					unlink($fullpath);
			}
		}

		closedir($handle);
		
		if(rmdir($path))
			return TRUE;
		else
			return FALSE;
	}

	 # Original PHP code by Chirp Internet: www.chirp.com.au 
	 # Please acknowledge use of this code by including this header. 
	 # DOCS: http://www.the-art-of-web.com/php/dirlist/
	 /*
	 $dir 		= full directory path to parse
	 $parent	= the parent directory (set to root on first instance) 	
	 $recurse	= true to recurse all subdirectories
	 $omit 		= any directory name you wish to omit
	 */
	 static function get_file_list($dir, $parent = 'root', $recurse=false, $omit = null) 
	 { 
		$retval = array(); 	
		# add trailing slash if missing 	
		if(substr($dir, -1) != "/") $dir .= "/"; 	
		# open pointer to directory and read list of files 
		$d = @dir($dir) or die("get_file_list: Failed opening directory $dir for reading");
		
		while(false !== ($entry = $d->read())) 
		{ 
			# skip hidden files and any omissions
			if($entry[0] == ".") continue;
			if(!empty($omit))
				if($entry == "$omit") continue;
				
			if(is_dir("$dir$entry")) 
			{
				if($recurse && is_readable("$dir$entry/"))
					$retval[$entry] = self::get_file_list("$dir$entry/", $entry, true);	 
			} 
			elseif( is_readable("$dir$entry") && $entry != 'Thumbs.db' ) 
				array_push($retval, $entry);			 
		 } 
		 $d->close(); 
		 return $retval; 
	}

	 function get_files_flat($dir, $recurse=false) 
	 { 
		$retval = array(); 	
		# add trailing slash if missing 	
		if(substr($dir, -1) != "/") $dir .= "/"; 	
		# open pointer to directory and read list of files 
		$d = @dir($dir) or die("get_files_flat: Failed opening directory $dir for reading");
		
		while(false !== ($entry = $d->read())) 
		{ 
			# skip hidden files 
			if($entry[0] == "." OR $entry == 'modules') continue;
			if(is_dir("$dir$entry")) 
			{
				if($recurse && is_readable("$dir$entry/"))
					$retval = array_merge($retval, $this->get_files_flat("$dir$entry/", true));  
			} 
			elseif(is_readable("$dir$entry")) 
				$retval[] = $entry;			 
		 } 
		 $d->close(); 
		 return $retval; 
	}

	 static function get_dir_only($dir, $recurse=false) 
	 { 
		$retval = array(); 	
		# add trailing slash if missing 	
		if(substr($dir, -1) != "/") $dir .= "/"; 	
		# open pointer to directory and read list of files 
		$d = @dir($dir) or die("get_file_list: Failed opening directory $dir for reading");
		
		while(false !== ($entry = $d->read())) 
		{ 
			# skip hidden files 
			if($entry[0] == ".") continue;
			if(is_dir("$dir$entry")) 
				$retval[] = $entry;	
		 } 
		 $d->close(); 
		 return $retval; 
	}

	/*
	$array			array 	main array to be made into a tree
	$css_class			string	classname appended to each filename
	$filter_array	array	array to compare against 
	*/
	function make_tree($array, $filter_array = array(), $class = '')
	{
		if(!is_array($array))
			return FALSE;
			
		foreach($array as $file => $name)
		{
			if(is_array($name))
			{
				echo '<div class="file_stuff">';
				echo '<b>',$file,'</b><br>';
				echo '<div class="file_list">';
				$this->make_tree($name, $filter_array, $class);
				echo '</div></div>';			
			}
			else
			{
				if(count($filter_array)>0)
				{
					if(in_array($name, $filter_array)) $class_name = 'file_not_in_use';
					else $class_name = $class;
				}
				else
					$class_name = $class;
									
				echo '<span class="',$class_name,'">',$name,'</span><br>';
				$class_name = $class;
			}
		}
		
	}
	
}