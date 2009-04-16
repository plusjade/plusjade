<?php defined('SYSPATH') or die('No direct script access.');
 
class Site_Data_Core {

	function __construct($file)
	{
	
	}
/*
	# NOTES: 
	# -------------------------------------------
	# the modules approach to this is pretty ugly
	# consider placing all css files in modules/ dir and sort by name.
	# make this not so trashy
*/

# create a site data file
	function create_file($file, $dir=NULL)
	{
		# Create the proper directory path.
		if('m' == $dir)
		{
			$folder = "modules/$file/";	
			$current_file = MODPATH."$file/views/$file/css.php";
			$mod_dir = DOCROOT."data/$this->site_name/themes/$this->theme/$folder";
			if(!is_dir($mod_dir))
				mkdir($mod_dir);	
			$file = 'css';	
		}
		else
		{
			$folder = 'global/';
			$current_file = APPPATH."views/$this->theme/$folder$file.php";
		}

		# Look for file in parent Theme/Module folder
		if(file_exists($current_file))
			$source = $current_file;
		else
		{	# Look in _global (common) folder.
			$source = APPPATH."views/_global/clone_$file.php";
			if(! file_exists($source) )
			{
				# Error
				return 'File does not exist';
			}
		}
		
		$dest = DOCROOT."data/$this->site_name/themes/$this->theme/$folder$file.php";

		# Copy the file to site data folder
		if( copy($source, $dest) )
			return 'Page created!!'; #Success message
		else
			return 'Unable to copy page.'; # Error message

	}

# edit a site data file
	function edit_file($filename)
	{		
		$current_file = DOCROOT."data/$this->site_name/themes/$this->theme/$filename";
		
		if( file_exists($current_file) )
		{
			$contents = file_get_contents($current_file);
			return $contents;
		}
		else
			return 'You cannot edit this file'; #status message		
	}

# save edits to a file
	function save_file($filename, $new_contents)
	{
		$current_file = DOCROOT."data/$this->site_name/themes/$this->theme/$filename";
		
		if( file_exists($current_file) )
		{
			# Overwrite old file with new file contents;
			if( file_put_contents($current_file, $new_contents) )
				return 'Page updated!'; # Success message	
			else
				return 'Unable to save changes'; # Error message
		}
		else
			return 'file does not exist';
	}
	
# delete a file
	function delete_file($file, $dir=NULL)
	{

		$current_file = DOCROOT."data/$this->site_name/themes/$this->theme/$folder$file.php";
		
		if( file_exists($current_file) )
		{
			if( unlink($current_file) )
				return 'Page deleted!!'; #Success message
			else
				return 'Unable to delete page.'; #Error message
		
			if( 'm' == $dir AND is_dir($mod_dir) )
				rmdir($mod_dir);
		}
		else
			return 'Page does not exist.'; #Error message
	}

}