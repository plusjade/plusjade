<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 	Functions to work with parsing, creating, updating, and deleting values in  
 *  site yaml files
 *  yaml files exist in site data directory under "protected" folder
 *
 */
class yaml_Core {

	/*
		parse the yaml file and return the key/value array
	*/
	public static function parse($site_name, $filename)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yaml";
		$yaml_array = array();
		if( file_exists($config_path) )
		{
			$pages_config = file_get_contents($config_path);
			$pages_config = explode(',',$pages_config);
			
			foreach($pages_config as $entry)
			{
				$pieces = explode(':', $entry);
				$key	= trim(@$pieces['0']);
				$value	= trim(@$pieces['1']);
				$id		= trim(@$pieces['2']);				
				$yaml_array[$key] = "$value:$id";
			}
		}			
		
		return $yaml_array;
	}


	public static function parse_name($site_name, $filename)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yaml";
		$yaml_array = array();
		if( file_exists($config_path) )
		{
			$pages_config = file_get_contents($config_path);
			$pages_config = explode(',',$pages_config);
			
			foreach($pages_config as $entry)
			{
				$pieces = explode(':', $entry);
				$yaml_array[] = trim(@$pieces['0']);
			}
		}			
		
		return $yaml_array;
	}
	
	public static function does_key_exist($site_name, $filename, $key)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yaml";
		$protected_pages = self::parse($site_name, $filename);
		
		if( array_key_exists($key, $protected_pages) )
			return  $protected_pages[$key];
		else
			return FALSE;
	}
	
	
	public static function add_value($site_name, $filename, $newline)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yaml";	
		if( file_exists($config_path) )
		{
			$fh = fopen($config_path, 'a') or die('cannot open file');
			fwrite($fh, $newline);
			fclose($fh);			
		}
		else
			file_put_contents($config_path, $newline);
		
		return true;
	}
	
	public static function edit_value($site_name, $filename, $old_value, $new_value )
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yaml";
		
		if(self::does_key_exist($site_name, $filename, $old_value))
		{
			$config_contents = file_get_contents($config_path);
			$new_content =  str_replace("$old_value:", "$new_value:" , $config_contents);
			file_put_contents($config_path, $new_content);
		}
		return true;
	}
	
	

	
	public static function delete_value($site_name, $filename, $key)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yaml";	
		if( file_exists($config_path) )
		{
			$config_array = self::parse($site_name, $filename);
			
			if( array_key_exists($key, $config_array) )
			{
				unset($config_array[$key]);
				$contents = '';
				
				foreach($config_array as $key => $value)
				{
					if(! empty($key) )
						$contents .= "$key:$value,\n";
				}
				file_put_contents($config_path, $contents);
			}
		}		
		return true;
	}
	
} // End yaml_core