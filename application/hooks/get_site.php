<?php
/*
 * 1. Fetches appropriate site from URL.
 * 2. Routes URL to appropriate controller.
 * 	Cases:
		a. is ajax request:		Fetch raw data.
		b. is page_name:		Build page, grab tools, display shell.
		c. is get/controller:	Admin, go directly to controller.
		
	NOTES: reserved names so far:
		get, showroom, blog, calendar
 */
function get_site()
{
	$session = Session::instance();
	$db = new Database;
	$domain_array = explode('.', $_SERVER['HTTP_HOST']);	
	
	# if the url = [subdomain].plusjade.com
	if(in_array(ROOTNAME, $domain_array))
	{
		$field_name	= 'subdomain';
		$site_name	= $domain_array['0'];
		
		# if no subdomain, set the site_name to the admin account 
		if( '2' == count($domain_array) )
			$site_name = ROOTACCOUNT;
	}
	else
	{
		# custom domain
		#if ( isset($_SESSION['site_name']) )
			#return TRUE;
			
		$field_name	= 'custom_domain';
		$site_name	= $_SERVER['HTTP_HOST'];
	}
	
	$site_row = $db->query("
		SELECT * 
		FROM sites 
		WHERE $field_name = '$site_name'
	")->current();
	
	if (! is_object($site_row) ) 
		die('site does not exist');
	
	# IMPORTANT: sets the site name & non-sensitive site_data.
	$_SESSION['site_name']	= $site_row->subdomain;
	
	# Make sure site_config file exists
	$site_config_path = DATAPATH . "$site_row->subdomain/protected/site_config.yml";
	
	if(! file_exists($site_config_path) )
	{
		$template = file_get_contents(DOCROOT . '/_assets/data/site_config.template.yml');
		$keys = array(
			'%SITE_ID%',
			'%SITE_NAME%',
			'%THEME%',
			'%BANNER%',
			'%HOMEPAGE%',
		);
		$replacements = array(
			$site_row->id,
			$site_row->subdomain,
			$site_row->theme,
			$site_row->banner,
			$site_row->homepage
		);
		$content = str_replace($keys, $replacements , $template);
		if(!file_put_contents($site_config_path, $content))
			die('Could not create site_config.yml file.');
	}
	# the site_config file is parsed in the root Controller_Core library file.
	

	## --- Route the URL --- ##
	/*
	 * The URL will tell us how to build the page.
	 * 		a. is ajax request
	 *		b. is page_name
				is protected page?
			c. is file request
	 * 		d. is get/
	 */

	# Get page_name
	$url_array = Uri::url_array();
	$page_name = ( empty($url_array['0']) ) ? $site_row->homepage : $url_array['0'];
	

	if('files' == $page_name)
	{
		$file = new Files_Controller;
		die( $file->_output($url_array) );	
	}
	if('get' != $page_name)
	{
		# Is page_name protected? (contains a builder)
		$page_config_value = yaml::does_key_exist($site_row->subdomain, 'pages_config', $page_name);
		
		# Is public ajax request?
		# Only builder tools should use ajax so we get info from pages_config.yaml
		if($page_config_value AND 'XMLHttpRequest' == @$_SERVER['HTTP_X_REQUESTED_WITH'])
		{
			# extract toolname and tool_id from pages_config.yaml
			$page_data = explode('-', $page_config_value);
			list($toolname, $tool_id) = $page_data;
			
			$tool = Load_Tool::factory($toolname);
			
			$url_array['0'] = $page_name; # make sure the page_name is correct.
			die( $tool->_ajax($url_array, $tool_id) );
		}
		else
		{
			# Non-protected page_names can be a subdirectory names.
			# do this only if an actual url string is being sent.
			# so we need to get the full url string.
			if(! empty($url_array['1']) AND !$page_config_value)
			{
				$page_name = strstr($_SERVER['REQUEST_URI'], '/');
				$page_name = ltrim($page_name, '/');
			}
			
			# Grab the page row
			$page_object = $db->query("
				SELECT * FROM pages 
				WHERE fk_site = '$site_row->id' 
				AND page_name = '$page_name'
			")->current();
			
			if( is_object($page_object) )
			{
				$page = new Build_Page_Controller;
				die( $page->_index($page_object) );
			}
			Event::run('system.404');
			die('Page Not Found');
		}
	}
	/*
		* else this is "get", load controller as normal
		* app/config/routes.php routes the url correctly.
	*/
}
Event::add('system.ready', 'get_site');
/* end */