<?php
# query user pages and build accordingly
function build_page()
{	
	$session = Sessionk::instance();		
	$host 	= '';
    $user 	= 'root';
    $pass 	= '';
    $db		= 'plusjade';
	
	# get the page name to find in pages db
	$pieces = explode('/', $_SERVER['REQUEST_URI']);
	if(!empty($pieces[1]))
		$page_name = $pieces[1];
	else
		$page_name = 'home';
	
	if( $page_name != 'admin' AND $page_name != 'auth' )
	{
		$site_id = $_SESSION['site_id'];
		$connection = mysql_connect($host, $user, $pass) or die ("Unable to connect!");
		mysql_select_db($db) or die ("Unable to select master database!");

		# For logged in users, disable the "enabled" page filter.
		$check_enabled = " AND enable = 'yes' ";
		if( isset($_SESSION['pAndA']) )
			$check_enabled = '';
			
			
		# Grab the page row
		$query = "SELECT * FROM pages WHERE fk_site = '{$site_id}' AND page_name = '{$page_name}' $check_enabled ";
		$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());

		if (mysql_num_rows($result) > 0)
		{
			$data = mysql_fetch_array($result, MYSQL_ASSOC);
		
			$page = new Build_Page_Controller();
			echo $page->_index($data);		
		}		
		else
		{
			echo 'page does not exist in db (hooks/build_page.php)<br>';
			Event::run('system.404');
		}
		
	}
}
/* end */