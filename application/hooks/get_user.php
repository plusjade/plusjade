<?php
// get website configuration from database 	
function get_user()
{
	$session = Session::instance();
	$host 	= '';
    $user 	= 'root';
    $pass 	= '';
    $db		= 'plusjade';
	$parts	= explode('.', $_SERVER['HTTP_HOST']);	
	
	if(in_array('localhost', $parts))
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
if( $pieces[1] != 'get' AND $pieces[1] != 'e' )
	Event::add('system.ready', 'build_page');
/* end */