<?php
	$db = new Database;
	// fix ugly session variable reference
	$query = $db->query("SELECT page_name, display_name FROM menus WHERE fk_site = '{$_SESSION['site_id']}' AND enable != 'no' ORDER BY position");

	$pieces = explode('/', $_SERVER['REQUEST_URI']);
	if(!empty($pieces['1'])) $selected = $pieces['1'];
	else $selected = 'home';
	
	foreach($query as $row){
		if($row->display_name != '')
			$name = $row->display_name;
		else
			$name = $row->page_name;
			
		if($selected == $row->page_name)
			echo '<a href="'.url::site("$row->page_name").'" id="tab_selected">'.ucwords($name)."</a>\n";
		else
			echo '<a href="'.url::site("$row->page_name").'">'.ucwords($name)."</a>\n";
	}