<?php
$theme_data = APPPATH."views/$theme_name/global/menu.php";

# Cascade the File
# 1. user data -- 2. theme data -- 3. stock

if( file_exists("{$custom_include}global/menu.php") )
	include_once("{$custom_include}global/menu.php");
elseif( file_exists("$theme_data") )
	include_once("$theme_data");
else
{
	/*
		Consider putting this in the build_page controller
		and/or
		somehow separating Menu View logic entirely
		Include maybe?
		So custom menus can be implemented safely.	
	*/
	
	$db			= new Database;	
	$menus		= $db->query("SELECT page_name, display_name FROM menus WHERE fk_site = '$this->site_id' AND enable != 'no' ORDER BY position");
	$pieces		= explode('/', $_SERVER['REQUEST_URI']);
	$selected	= 'home';
	
	if(! empty($pieces['1']) ) 
		$selected = $pieces['1'];
			
	echo '<ul>';
		foreach( $menus as $menu )
		{
			$name = $menu->page_name;		
			if( $menu->display_name != '' )
				$name = $menu->display_name;
								
			if( $menu->page_name == $selected )
				echo '<li><a href="'.url::site("$menu->page_name").'" class="selected">'.ucwords($name)."</a></li>\n";
			else
				echo '<li><a href="'.url::site("$menu->page_name").'">'.ucwords($name)."</a></li>\n";
		}
	echo '</ul>';
}
/* end of menu.php */