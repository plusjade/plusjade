<?php defined('SYSPATH') or die('No direct script access.');
 
 
 
/*
 * This should be css functions relative to tools only.
 * The theme css is handled via theme controller.
 */
class Css_Core {

	
/*
 * used @ tool->add to generate a new css file from theme/stock instance
 */
	function generate_tool_css($toolname, $tool_id, $return_contents=FALSE)
	{
		$tool_path		= Assets::theme_dir("$this->theme/tools/$toolname");			
		$custom_file	= "$tool_path/css/$tool_id.css";		
		$theme_file		= "$tool_path/css/stock.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/stock.css";
		$return			= FALSE;
		
		# make sure the folders exist.
		if(! is_dir($tool_path) )
			mkdir($tool_path);

		if(! is_dir("$tool_path/css") )
			mkdir("$tool_path/css");
			
		ob_start();
		if(file_exists($theme_file))
			readfile($theme_file);
		elseif(file_exists($stock_file))
			readfile($stock_file);
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
 * used @ tool->css for intelligently retrieving css file associated with a tool.
 * Cascades from theme specific , then to stock.
 *
 */
	function get_tool_css($toolname, $tool_id, $stock=FALSE)
	{
		$tool_theme		= Assets::themes_dir("$this->theme/tools/$toolname/css");			
		$custom_file	= "$tool_theme/css/$tool_id.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/stock.css";
		
		ob_start();
		# return contents of a template or +jade stock tool css file.
		if(FALSE != $stock)
		{
			switch($stock)
			{
				case 'template':
					if(file_exists("$tool_theme/stock.css"))
						readfile("$tool_theme/stock.css");
					else
						return NULL;
					break;
				case 'stock':
					if(file_exists($stock_file))
						readfile($stock_file);
					else
						return NULL;
					break;
				default:
					return NULL;
			}
				
			return str_replace('++', $tool_id , ob_get_clean());
		}
		
		# this file may not exist if the tool was added before user changes themes.
		# always generate a file if it does not exist.
		if(file_exists("$tool_theme/$tool_id.css"))
		{
			readfile("$tool_theme/$tool_id.css");
			return ob_get_clean();
		}
		
		# if it does not exist, generate a new one.
		return self::generate_tool_css($toolname, $tool_id, TRUE);
	}

/*
 * 
 * save a custom tool css file.
 */
	function save_custom_css($toolname, $tool_id, $contents)
	{	
		$theme_tool_css = Assets::themes_dir("$this->theme/tools/$toolname/css/$tool_id.css");
		$contents = self::replace_tokens($contents);
		
		if( file_put_contents($theme_tool_css, $contents) )
			return 'CSS Changes Saved.';

		return 'The was a problem saving the file.';	
	}
	
/*
 * 
 * save a file as a template.
 */
	function save_template($toolname, $contents)
	{	
		$template_css	= Assets::themes_dir("$this->theme/tools/$toolname/css/stock.css");
		$contents		= preg_replace("/_(\d+)/", '_++', $contents);
		
		if( file_put_contents($template_css, $contents) )
			return 'Template Saved';

		return 'The was a problem saving the file.';	
	}
	
	
/*
 * Replace any tokens with respective real-values.
 */ 
	public function replace_tokens($contents)
	{
		$keys = array(
			'%MY_THEME%',
			'%MY_FILES%'
		);
		$replacements = array(
			Assets::theme_url('tools'),
			Assets::assets_url()
		);
		
		return str_replace($keys, $replacements , $contents);		
	}
	

	
} # end



