<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 	THIS IS NOT A REAL/STANDARD YAML PARSER - 
 *  its a dead simple implementation for our dead-simple requirements.
 *  Create, read, update, delete values in our yaml files.
 *  yaml files exist in site data directory in "protected" folder
	site_config:
		makes it easier to load site data rather than querying the db every time.
 
	pages_config: 
		is needed to determine how to load a page.
		if page is protected, only the first directory is used.
		if not we look at the full url. ex: mysite.com/blog/team/jade
		if the name "blog" is in the pages config, it knows which tool to load and how.
		In this way extensions to the url can act as commands as opposed to being 
		treated as a page_name. ex: mysite.com/blog/entry/a-title-to-a-blog-entry
		if its not, the page_name = "blog/team/jade" & and passed to build_page.php
 */
class yaml_Core {
	
/*
 * parse the yaml file and return the key/value array
 * if the yaml file does not exist, we should build yah!
*/
	public static function parse($site_name, $filename, $full_path=NULL)
	{
		if(NULL == $full_path)
			$config_path = DATAPATH . "$site_name/protected/$filename.yml";
		else
			$config_path = DATAPATH . "$site_name/$full_path.yml";
		
		
		$yaml_array = array();
		if('site_config' == $filename)
			$yaml_array = array(
				'site_id'		=> '',
				'site_name'		=> '',
				'theme'			=> '',
				'banner'		=> '',
				'homepage'		=> '',
				'account_page'	=> '',
				'claimed'		=> ''
			);

		if(file_exists($config_path))
		{
			$lines = file($config_path, FILE_SKIP_EMPTY_LINES);

			foreach($lines as $line)
			{
				$pieces = explode(':', $line);
				$pieces = array_pad($pieces, 2, 0);
				$key	= trim($pieces['0']);
				$value	= trim($pieces['1']);
				if(! empty($value))
					$yaml_array[$key] = $value;
			}	
			return $yaml_array;
		}
		
		# If pages_config.yml does not exist, create it.
		# other files get passed here so we limit to pages_config FOR NOW.
		# This should not need to happen very often.
		if('pages_config' == $filename)
		{
			if(!self::build_pages_config($site_name))
				die('yaml::build_pages_config() Could not create pages_config.yml');
			
			return self::parse($site_name, $filename, $full_path);
		}
		return $yaml_array;
	}

/* ------------------- simple key = value functions for site_config ------------------- */
	
/*
 * Add or edit a key/value pair from a yaml file.
 */
	public static function edit_site_value($site_name, $filename, $key, $new_value)
	{
		$config_path		= DATAPATH . "$site_name/protected/$filename.yml";
		$config_array		= self::parse($site_name, $filename);		
		$config_array[$key]	= $new_value;
		return self::build_yaml_file($config_array, $config_path);
	}

/* regenerate and save edited yaml file
 * from an edited key/value array of values
 * should only be used internally
 */
	private static function build_yaml_file($config_array, $config_path)
	{
		$contents = '';
		foreach($config_array as $key => $value)
			if(! empty($key) )
				$contents .= "$key : $value\n";

		if(file_put_contents($config_path, $contents))
			return TRUE;
		
		return FALSE;
	}
	
/* ------------------- END: simple key = value functions for site_config ------------------- */
	

/*
 * Searches for a key and returns the value if found.
 */	
	public static function does_key_exist($site_name, $filename, $key)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yml";
		$protected_pages = self::parse($site_name, $filename);
		
		if( array_key_exists($key, $protected_pages) )
			return  $protected_pages[$key];
		
		return FALSE;
	}

/*
 * Searches for a value and returns the key if found.
 */
	public static function does_value_exist($site_name, $filename, $value)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yml";
		$protected_pages = self::parse($site_name, $filename);
		
		if( $key = array_search($value, $protected_pages) )
			return  $key;

		return FALSE;
	}

/*
 * add a value to the yaml file.
 */	
	public static function add_value($site_name, $filename, $newline)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yml";	
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

/*
 * edit a key from the yaml file (mostly for pages config where keys are page_names).
 */		
	public static function edit_key($site_name, $filename, $old_key, $new_key )
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yml";
		
		if(self::does_key_exist($site_name, $filename, $old_key))
		{
			$config_contents = file_get_contents($config_path);
			$new_content =  str_replace("$old_key:", "$new_key:" , $config_contents);
			file_put_contents($config_path, $new_content);
		}
		return TRUE;
	}
	
/*
 * delete a value from the yaml file.
 */	
	public static function delete_value($site_name, $filename, $key)
	{
		$config_path = DATAPATH . "$site_name/protected/$filename.yml";	
		if( file_exists($config_path) )
		{
			$config_array = self::parse($site_name, $filename);
			
			if( array_key_exists($key, $config_array) )
			{
				unset($config_array[$key]);				
				self::build_yaml_file($config_array, $config_path);
			}
		}		
		return TRUE;
	}

	
	
/*
 * Creates a proper pages_config.yml file in _data/<site>/protected
 * yaml::parse test to see if the file exist and calls this if it does not.
 */
	private static function build_pages_config($site_name)
	{
		$config_path = DATAPATH . "$site_name/protected/pages_config.yml";

		if(file_exists($config_path))
			return TRUE;
			
		# OPTIMIZE this later. 2 queries = no good.
		$site = ORM::factory('site', $site_name);
		if(!$site->loaded)
			return FALSE;

		$db = new Database;
		$protected_pages = $db->query("
			SELECT pages_tools.*, tools.parent_id, LOWER(system_tools.name) as name, pages.page_name
			FROM pages_tools
			JOIN tools ON tools.id = pages_tools.tool_id
			JOIN system_tools ON system_tools.id = tools.system_tool_id 
			JOIN pages ON pages.id = pages_tools.page_id
			WHERE pages_tools.fk_site = '$site->id'
			AND system_tools.protected = 'yes'
		");
		
		ob_start();
		# page_name:toolname-tool_id
		foreach($protected_pages as $page)
			echo "$page->page_name:$page->name-$page->parent_id\n";
	
		if(file_put_contents($config_path, ob_get_clean()))
			return TRUE;
			
		return FALSE;
	}
	
/* 
 * Create a new site_config file for a specified website
 */	
	public static function new_site_config($site_name, $replacements)
	{
		$site_config_path = DATAPATH . "$site_name/protected/site_config.yml";
		$template = file_get_contents(DOCROOT . '/_assets/data/site_config.template.yml');
		$keys = array(
			'%SITE_ID%',
			'%SITE_NAME%',
			'%THEME%',
			'%BANNER%',
			'%HOMEPAGE%',
			'%CLAIMED%',
		);
		if(!file_put_contents($site_config_path, str_replace($keys, $replacements , $template)))
			die('Could not create site_config.yml file.');	

		return TRUE;
	}
	
	
} // End yaml_core