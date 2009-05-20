<?php

/*
	Consider putting this in the build_page controller
	and/or
	somehow separating Menu View logic entirely
	Include maybe?
	So custom menus can be implemented safely.	
*/

$db		= new Database;	
$menus	= $db->query("
	SELECT page_name, label FROM pages 
	WHERE fk_site = '$this->site_id' 
	AND menu = 'yes' 
	AND enable = 'yes'
	ORDER BY position
");

$pieces		= explode('/', $_SERVER['REQUEST_URI']);
$selected	= 'home';

if(! empty($pieces['1']) ) 
	$selected = $pieces['1'];
		
echo '<ul id="primary_menu">';
	foreach( $menus as $menu )
	{
		$name = ( $menu->label == '' ) ? $menu->page_name : $menu->label;
		
		if( $menu->page_name == $selected )
			echo '<li><a href="' , url::site("$menu->page_name") , '" class="selected">' , $name , "</a></li>\n";
		else
			echo '<li><a href="' , url::site("$menu->page_name") , '">' , $name , "</a></li>\n";
	}
echo '</ul>';

/* end of menu.php */