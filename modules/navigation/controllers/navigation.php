<?php

class Navigation_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	/*
	 * Displays a nestable navigation menu
	 *
	 */	 
	function _index($tool_id)
	{	
		tool_ui::validate_id($tool_id);	
		
		$primary	= new View('navigation/index');	
		$db			= new Database;
		
		$parent	= $db->query("SELECT * FROM navigations 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id' ")->current();
			
		$items	= $db->query("SELECT * FROM navigation_items 
			WHERE parent_id = '$parent->id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC ");		
		
		
		$attributes = explode(',', $parent->attributes);
		$attr_string ='';
		if(! empty($attributes['0']) )
		{
			foreach($attributes as $attr)
			{
				$pieces	= explode(':', $attr);
				$name	= $pieces['0'];
				$value	= $pieces['1'];
				
				if ( empty($attr_array[$name]) ) 
					$attr_array[$name]='';
				
				$attr_array[$name] .= $value . ' ';
			}
			$attr_array['class'] .= 'navigation_wrapper ';
			
			
			foreach ($attr_array as $name => $value)
			{
				$attr_string .= $name .' = "' . trim($value) .'" ';
			}
			#echo $attr_string; die();
			
		}
		
		$primary->parent = $parent;
		$primary->attributes = $attr_string;
		$primary->tree = Navigation::display_tree($items);
		
		
		#$primary->attributes = $attributes;
		
		
		# Javascript
		$primary->add_root_js_files('simple_tree/jquery.simple.tree.js');
		# $primary->global_readyJS('');
		
		return $primary;
	}
  
}

/* -- end of application/controllers/showroom.php -- */