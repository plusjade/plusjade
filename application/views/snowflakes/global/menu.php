<?php
if(!empty($load_custom))
	include_once("{$custom_include}global/menu.php");
else
{
	$db = new Database;
	// fix ugly session variable reference
	$query = $db->query("SELECT page_name, display_name FROM menus WHERE fk_site = '{$_SESSION['site_id']}' AND enable != 'no' ORDER BY position");

	$pieces = explode('/', $_SERVER['REQUEST_URI']);
	if(!empty($pieces[1])) $selected = $pieces[1];
	else $selected = 'home';
	echo '<ul>';
	foreach($query as $row){
		if($row->display_name != '')
			$name = $row->display_name;
		else
			$name = $row->page_name;
			
		if($selected == $row->page_name)
			echo '<li class="active"><a href="'.url::site("$row->page_name").'">'.ucwords($name)."</a></li>\n";
		else
			echo '<li><a href="'.url::site("$row->page_name").'">'.ucwords($name)."</a></li>\n";
	}
	echo '</ul>';
}
/* end of menu.php */

