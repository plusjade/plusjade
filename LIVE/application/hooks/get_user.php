<?php
// get website configuration from database 	
function get_user()
{
	$session = Sessionk::instance();
	$host 	= '';
    $user 	= 'root';
    $pass 	= 'genius12';
    $db		= 'plusjade';
	$parts	= explode('.', $_SERVER['HTTP_HOST']);	
	
	if(in_array('plusjade', $parts))
	{  # search on subdomain
		$field_name = 'url';
		if(count($parts) == 2)
			$site_name = 'jade';
		else
			$site_name = $parts[0];
	}
	else
	{  # search on domain
		$field_name = 'domain';
		$site_name = $_SERVER['HTTP_HOST'];
	}

	#if(! isset($_SESSION['site_name']) )
	#{
		$connection = mysql_connect($host, $user, $pass) or die ("Unable to connect!");
		mysql_select_db($db) or die ("Unable to select master database!");

		$query = "SELECT * FROM sites WHERE $field_name = '{$site_name}'";
		$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
		if (mysql_num_rows($result) > 0) 
		{
			while($row = mysql_fetch_array($result)) 
			{	
				$_SESSION['site_id']	= $row['site_id'];
				$_SESSION['site_name']	= $row['url'];		
				$_SESSION['theme'] 		= $row['theme'];				
				$_SESSION['banner']		= $row['banner'];		
			}
		}
		else
		{ 
			echo 'did not find name in database';
			//header('Location:' . ROOTDOMAIN);
			die();
		}
	#}
	#else
		#echo $_SESSION['site_name']; die();
}

Event::add('system.ready', 'get_user');

/**
 * if url has /e/ it means we are trying to directly access a controller
 * else everything is treated as a page name and queried in pages db.
 */
$pieces = explode('/', $_SERVER['REQUEST_URI']);

if('showroom' == $pieces['1'] AND 'XMLHttpRequest' == @$_SERVER['HTTP_X_REQUESTED_WITH'])
{
	$category	= @$pieces['2'];
	$item		= @$pieces['3'];
	
	$showroom = new Showroom_Controller();
	
	if(! empty($category) AND empty($item) )
	{
		echo $showroom->_items_category($category);
	}
	elseif(! empty($category) AND !empty($item) )
		echo $showroom->_item($category, $item);
		
	die();
}
elseif('get' != $pieces['1'])
	Event::add('system.ready', 'build_page');

	


/* end */