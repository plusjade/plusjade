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
	$host 	= '';
    $user 	= 'root';
    $pass 	= 'genius12';
    $db		= 'plusjade';
	$domain_array	= explode('.', $_SERVER['HTTP_HOST']);	
	
	# if the url is a subdomain.plusjade.com
	if(in_array('plusjade', $domain_array))
	{
		$field_name	= 'url';
		$site_name	= $domain_array['0'];
		if( '2' == count($domain_array) )
			$site_name = 'jade';
	}
	else
	{
		# custom domain
		$field_name	= 'domain';
		$site_name	= $_SERVER['HTTP_HOST'];
		if ( isset($_SESSION['site_name']) )
			return TRUE;
	}

	$connection = mysql_connect($host, $user, $pass) or die ("Unable to connect!");
	mysql_select_db($db) or die ("Unable to select master database!");

	$query = "SELECT * FROM sites WHERE $field_name = '$site_name'";
	$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
	
	if (mysql_num_rows($result) == 0) 
	{
		echo 'site does not exist';
		die();
	}
	
	while($row = mysql_fetch_array($result)) 
	{
		# IMPORTANT: sets the site name
		$_SESSION['site_name'] = $row['url'];
		
		$identifer_path = DATAPATH . "{$row['url']}/protected/identifer.yaml";
		
		if(! file_exists($identifer_path) )
		{
			$template = file_get_contents(DATAPATH . 'identifer.yaml.template');
			$keys = array(
				'%SITE_ID%',
				'%SITE_NAME%',
				'%THEME%',
				'%BANNER%',
			);
			$replacements = array(
				$row['site_id'],
				$row['url'],
				$row['theme'],
				$row['banner']
			);
			$content = str_replace($keys, $replacements , $template);
			file_put_contents($identifer_path, $content);
		}
	}

	# route the url
	$url_array = explode('/', $_SERVER['REQUEST_URI']);
	$page_name = $url_array['1'];
	
	# Filter Public Ajax Requests
	if('get' != $page_name AND 'XMLHttpRequest' == @$_SERVER['HTTP_X_REQUESTED_WITH'])
	{
		$ajax = new Ajax;
		echo $ajax->$page_name($url_array);
		die();
	}
	elseif('get' != $page_name)
	{
		$page = new Build_Page_Controller;
		echo $page->_index();
		#die();
	}
	/*
	 * else this is "get", load controller as normal
	*/ 
}
Event::add('system.ready', 'get_site');
/* end */