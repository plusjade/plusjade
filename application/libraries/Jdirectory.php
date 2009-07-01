<?php defined('SYSPATH') or die('No direct script access.');

# this is called Jdirectory because "directory" does not work. 
class Jdirectory_Core {


/*
 * Copies all files and folders in a directory to another directory
 * //http://us2.php.net/manual/en/function.copy.php#86738
 */
	public function copy($srcdir, $dstdir, $offset = '', $verbose = false)
	{
		if(!isset($offset))
			$offset=0;
		$num		= 0;
		$fail		= 0;
		$sizetotal	= 0;
		$fifail		= '';
		if(!is_dir($dstdir))
			mkdir($dstdir);
		if($curdir = opendir($srcdir))
		{
			while($file = readdir($curdir))
			{
				if($file != '.' && $file != '..')
				{
					$srcfile = "$srcdir/$file";    # added by marajax
					$dstfile = "$dstdir/$file";    # added by marajax
					
					if(is_file($srcfile))
					{
						if(is_file($dstfile))
							$ow = filemtime($srcfile) - filemtime($dstfile);
						else $ow = 1;
						if($ow > 0)
						{
							if($verbose)
								echo "Copying '$srcfile' to '$dstfile'...<br />";
							if(copy($srcfile, $dstfile))
							{
								touch($dstfile, filemtime($srcfile));
								$num++;
								chmod($dstfile, 0777);    # added by marajax
								$sizetotal = ($sizetotal + filesize($dstfile));
								if($verbose)
									echo "OK\n";
							}
							else
							{
								echo "Error: File '$srcfile' could not be copied!<br />\n";
								$fail++;
								$fifail = "$fifail$srcfile|";
							}
						}
					}
					else if(is_dir($srcfile))
						$ret = self::copy($srcfile, $dstfile, $verbose);
				}
			}
			closedir($curdir);
		}
		return TRUE;
	}

	
/*
 * Remove a filled directory recursively
 * DOCS: http://us2.php.net/manual/en/function.rmdir.php#88723
 */
	public function remove($path)
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
					self::remove($fullpath);
				else
					unlink($fullpath);
			}
		}
		closedir($handle);
		
		if(rmdir($path))
			return TRUE;
		
		return FALSE;
	}
	
/*
 * Original PHP code by Chirp Internet: www.chirp.com.au 
 * Please acknowledge use of this code by including this header. 
 * DOCS: http://www.the-art-of-web.com/php/dirlist/

 $dir 		= full directory path to parse
 $parent	= the parent directory (set to root on first instance)
 $recurse	= true to recurse all subdirectories or list_dir to list directory names but no recurse.
 $omit 		= any directory name you wish to omit
 */
	public static function contents($dir, $parent = 'root', $recurse=false, $omit = null) 
	{ 
		$retval = array(); 	
		if(substr($dir, -1) != "/")
			$dir .= "/"; # add trailing slash if missing 	 	
		# open pointer to directory and read list of files 
		$d = @dir($dir) or die("Jdirectory::contents: Failed opening directory $dir for reading");
		
		while(false !== ($entry = $d->read())) 
		{ 
			# skip hidden files and any omissions
			if( ($entry[0] == "." ) OR (! empty($omit) AND $entry == "$omit" ) )
				continue;
				
			if(is_dir("$dir$entry")) 
			{
				if('list_dir' == $recurse)
					array_push($retval, $entry);
				elseif(TRUE == $recurse && is_readable("$dir$entry/"))
					$retval[$entry] = self::contents("$dir$entry/", $entry, true);

			} 
			elseif( is_readable("$dir$entry") && $entry != 'Thumbs.db' ) 
				array_push($retval, $entry);			 
		} 
		 $d->close(); 
		 return $retval; 
	}

	
} # end