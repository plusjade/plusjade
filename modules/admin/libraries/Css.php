<?php defined('SYSPATH') or die('No direct script access.');
 
class Css_Core {

	/*
	 * 
	 *
	 *
	 */
	function get_css_file($tool_name, $tool_id)
	{
		$dir_path	= DOCROOT."data/$this->site_name/tools_css/$tool_name";
		$file_path	= "$dir_path/$tool_id.css";		
		
		ob_start();
		
		if(! file_exists($file_path) )
		{
			if(! is_dir($dir_path) )
				mkdir("$dir_path");
			
			$source = MODPATH . "$tool_name/views/$tool_name/custom.css";
					
			if( file_exists($source) )
				readfile($source);
			else
			{
				return '/* new file */';
			}
						
			# Change the values
			$source_contents = str_replace('++', $tool_id , ob_get_clean() );
			
			if( file_put_contents($file_path, $source_contents) )
				return $source_contents;
			else
				return 'could not copy the source file';
		
		}
		else
		{
			readfile($file_path);
			return ob_get_clean();
		}

	}

	/*
	 * Execute further commands after a tool is added
	 * if needed
	 */
	function save_custom_css($tool_name, $tool_id, $contents)
	{
		$dir_path	= DOCROOT."/data/$this->site_name/tools_css/$tool_name";
		$file_path	= "$dir_path/$tool_id.css";	
		
		if( file_put_contents($file_path, $contents) )
			return 'Changes Saved<br>Updating...';
		else
			return 'The was a problem saving the file.';
					
	}
}