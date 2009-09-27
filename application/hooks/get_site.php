<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * This is the Main GateKeeper to Plusjade.
 * It routes all logic to appropriate controllers.
 
 * 1. Fetches appropriate site from URL.
 * 2. Routes URL to appropriate controller based on config for site name.
 * 	Cases:
		a. is ajax request:		Fetch raw data.
		b. is page_name:		grab tools, Build page, render the page.
		c. is get/controller:	Admin, map to appropriate controller.
 */
 
function get_site()
{
	$session = Session::instance();
	$domain_array = explode('.', $_SERVER['HTTP_HOST']);	
	
	# if the url = [subdomain].plusjade.com
	if(in_array(ROOTNAME, $domain_array))
	{
		$field_name	= 'subdomain';
		$site_name	= $domain_array['0'];
		
		# if no subdomain, set the site_name to the admin account 
		if('2' == count($domain_array))
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
	
	$site = ORM::factory('site')
		->where(array($field_name => $site_name))
		->find();
	if (!$site->loaded)
	{
		header("HTTP/1.0 404 Not Found");
		die('site does not exist');
	}
	
	# IMPORTANT: sets the site name & non-sensitive site_data.
	$_SESSION['site_name']	= $site->subdomain;
	$_SESSION['created']	= $site->created;
	
	# Make sure site_config file exists
	# the site_config file is parsed in the root Controller_Core library file.
	$site_config_path = DATAPATH . "$site->subdomain/protected/site_config.yml";

	if(!file_exists($site_config_path))
	{
		$replacements = array(
			$site->id,
			$site->subdomain,
			$site->theme,
			$site->banner,
			$site->homepage
		);
		yaml::new_site_config($site_name, $replacements);
	}

	/*
	 --- Route the URL ---
	 ---------------------
	 * The URL will tell us how to build the page.
	  		a. is ajax request
	 		b. is page_name
				is protected page?
			c. is file request
	  		d. is /get/
	 */

	# Get page_name
	$url_array = Uri::url_array();
	$page_name = (empty($url_array['0'])) 
		? $site->homepage
		: $url_array['0'];
	

	if('files' == $page_name)
	{
		$file = new Files_Controller;
		die($file->_output($url_array));	
	}
	
	if('get' != $page_name)
	{
		# Is page_name protected?
		$page_config_value = yaml::does_key_exist($site->subdomain, 'pages_config', $page_name);
		
		# Is protected ajax request?
		# get info from pages_config.yaml
		if($page_config_value AND request::is_ajax())
		{
			# extract toolname and parent_id from pages_config.yaml
			$page_data = explode('-', $page_config_value);
			list($toolname, $parent_id) = $page_data;
			
			# make sure the page_name is correct.
			$url_array['0'] = $page_name; 
			
			# send to tool _ajax handler. we expect raw data output.
			die(Load_Tool::factory($toolname)->_ajax($url_array, $parent_id));
		}
		# ajax call to a non protected tool.
		elseif(isset($_POST['post_handler']) AND request::is_ajax())
		{
			# currently only enabled for format  form type.
			list($toolname, $parent_id) = explode(':', $_POST['post_handler']);
			die(Load_Tool::factory($toolname)->_ajax($url_array, $parent_id));
		}
		else
		{
			# Non-protected page_names can be a subdirectory name.
			# do this only if an actual url string is being sent.
			# so we need to get the full url string.
			if(! empty($url_array['1']) AND !$page_config_value)
			{
				$page_name = strstr($_SERVER['REQUEST_URI'], '/');
				$page_name = ltrim($page_name, '/');
			}
			
			# Grab the page row
			$page = ORM::factory('page')
				->where(array(
					'fk_site'	=> $site->id,
					'page_name'	=> $page_name
				))
				->find();
				
			# does the page exist?
			if(!$page->loaded)
				Event::run('system.404');

			# Load the page!
			$build_page = new Build_Page_Controller;
			die($build_page->_index($page));
		}
	}
	/*
		* else this is "get", load controller as normal
		* app/config/routes.php routes the url correctly.
	*/
}
Event::add('system.ready', 'get_site');
/* end */