<?php defined('SYSPATH') or die('No direct script access.');
 
class Css_Core {

	/*
	 * 
	 *
	 *
	 */
	function get_contents($tool_name, $tool_id)
	{
		$dir_path	= DOCROOT."data/$this->site_name/tools_css/$tool_name";
		$file_path	= "$dir_path/$tool_id.css";		
				
		if(! file_exists($file_path) )
		{
			if(! is_dir($dir_path) )
				mkdir("$dir_path");
			
			$source = MODPATH . "$tool_name/views/$tool_name/custom.css";
			if( file_exists($source) )
				$source_contents = file_get_contents($source);
			else
				$source_contents = '/* new file */';
			
			# Change the values
			$source_contents = str_replace('++', $tool_id , $source_contents);
			
			if( file_put_contents($file_path, $source_contents) )
				return $source_contents;
			else
				return 'could not copy the source file';
			
			/*
			# Copy custom file template in the tool folder
			if( copy($source, $file_path) )
				echo 'Page created!!'; #Success message
			else
				echo 'Unable to copy page.'; # Error message
			*/
		}
		else
		{
			return file_get_contents($file_path);
		}
	}

	/*
	 * Execute further commands after a tool is added
	 * if needed
	 */
	function save_contents($tool_name, $tool_id, $contents)
	{
		$dir_path	= DOCROOT."/data/$this->site_name/tools_css/$tool_name";
		$file_path	= "$dir_path/$tool_id.css";	
		
		if( file_put_contents($file_path, $contents) )
			return 'Changes Saved<br>Updating...';
		else
			return 'The was a problem saving the file.';
					
	}
}