<?php defined('SYSPATH') or die('No direct script access.');
 
class Css_Core {

	
	# used @ tool->add to generate a new css file from theme/stock instance
	function generate_tool_css($toolname, $tool_id, $return_contents=FALSE)
	{
		$dir_path		= DATAPATH . "$this->site_name/tools_css/$toolname";
		$custom_file	= "$dir_path/$tool_id.css";		
		$theme_file		= DATAPATH . "$this->site_name/themes/$this->theme/tools/$toolname/stock.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/custom.css";
		$return			= FALSE;
		
		if(! is_dir($dir_path) )
			mkdir($dir_path);
		
		ob_start();
		if(file_exists($theme_file))
			readfile($theme_file);
		elseif(file_exists($stock_file))
			readfile($stock_file);
		else
			echo '/* new file */';
			
		$source_contents = str_replace('++', $tool_id , ob_get_clean());
		
		if( file_put_contents($custom_file, $source_contents) )
			$return = TRUE;
		
		if($return_contents)
			return $source_contents;
		else
			return $return;
	}
	
	
	
	/*
	 * used @ css->edit for intelligently retrieving the css file associated with a tool.
	 * if the file does not exist
	 *
	 */
	function get_tool_css($toolname, $tool_id, $stock=FALSE)
	{
		$dir_path		= DATAPATH . "$this->site_name/tools_css/$toolname";
		$custom_file	= "$dir_path/$tool_id.css";		
		$source			= MODPATH . "$toolname/views/public_$toolname/custom.css";
		
		ob_start();
		if(TRUE == $stock AND file_exists($source))
		{
			readfile($source);
			$contents = str_replace('++', $tool_id , ob_get_clean());
			return $contents;
		}		

		# a custom file should always exist since its added via tool->add.
		# if it does not exist, this is a problem, so we fix it.
		if(file_exists($custom_file))
		{
			readfile($custom_file);
			return ob_get_clean();
		}
		else
			return self::generate_tool_css($toolname, $tool_id, TRUE);
	}

	/*
	 * 
	 * 
	 */
	function save_custom_css($tool_name, $tool_id, $contents)
	{
		$dir_path	= DOCROOT."data/$this->site_name/tools_css/$tool_name";
		$file_path	= "$dir_path/$tool_id.css";	
		
		if( file_put_contents($file_path, $contents) )
			return 'CSS Changes Saved.';

		return 'The was a problem saving the file.';	
	}
}