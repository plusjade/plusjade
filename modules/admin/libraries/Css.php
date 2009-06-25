<?php defined('SYSPATH') or die('No direct script access.');
 
class Css_Core {

	
/*
 * used @ tool->add to generate a new css file from theme/stock instance
 */
	function generate_tool_css($toolname, $tool_id, $return_contents=FALSE)
	{
		$dir_path		= DATAPATH . "$this->site_name/tools_css/$toolname";
		$custom_file	= "$dir_path/$tool_id.css";		
		$theme_file		= DATAPATH . "$this->site_name/themes/$this->theme/tools/$toolname/stock.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/stock.css";
		$return			= FALSE;
		
		if(! is_dir($dir_path) )
			mkdir($dir_path);
		
		ob_start();
		if(file_exists($theme_file))
		{
			echo "/* rendered via theme: '$this->theme' tool css. */\n";
			readfile($theme_file);
		}
		elseif(file_exists($stock_file))
		{
			echo "/* stock +Jade tool css. */\n";
			readfile($stock_file);
		}
		else
			echo '/* No css available for this tool. */';
			
		$source_contents = str_replace('++', $tool_id , ob_get_clean());
		
		# TODO: add this to the one above for efficiency
		$source_contents = self::replace_tokens($source_contents);
		
		if( file_put_contents($custom_file, $source_contents) )
			$return = TRUE;
		
		if($return_contents)
			return $source_contents;
		
		return $return;
	}
	
	
	
/*
 * used @ css->edit for intelligently retrieving css file associated with a tool.
 * Cascades from theme specific , then to stock.
 *
 */
	function get_tool_css($toolname, $tool_id, $stock=FALSE)
	{
		$dir_path		= DATAPATH . "$this->site_name/tools_css/$toolname";
		$custom_file	= "$dir_path/$tool_id.css";
		$theme_file		= DATAPATH . "$this->site_name/themes/$this->theme/tools/$toolname/stock.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/stock.css";
		
		ob_start();
		if(TRUE == $stock)
		{
			if(file_exists($theme_file))
				readfile($theme_file);
			else if(file_exists($stock_file))
				readfile($stock_file);
			else
				return '/* No theme or stock css file for this tool.*/';
				
			return str_replace('++', $tool_id , ob_get_clean());
		}
		
		# a custom file should always exist since its added via tool->add.
		if(file_exists($custom_file))
		{
			readfile($custom_file);
			return ob_get_clean();
		}
		
		# if it does not exist, this is a problem, so we fix it.
		return self::generate_tool_css($toolname, $tool_id, TRUE);
	}

/*
 * 
 * save a custom tool css file.
 */
	function save_custom_css($tool_name, $tool_id, $contents)
	{
		$dir_path	= DATAPATH . "$this->site_name/tools_css/$tool_name";
		$file_path	= "$dir_path/$tool_id.css";	

		$contents = self::replace_tokens($contents);
		
		if( file_put_contents($file_path, $contents) )
			return 'CSS Changes Saved.';

		return 'The was a problem saving the file.';	
	}
/*
 * Replace any tokens with respective real-values.
 */ 
	private function replace_tokens($contents)
	{
		$theme_path = Assets::url_path_theme('tools');
		$files_path = Assets::url_path_direct();
		$keys = array(
			'%MY_THEME%',
			'%MY_FILES%'
		);
		$replacements = array(
			$theme_path,
			$files_path
		);
		
		return str_replace($keys, $replacements , $contents);		
	}
	
	public function replace_custom()
	{
	
	
	}
	
} # end



