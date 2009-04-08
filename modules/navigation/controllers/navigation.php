<?php

class Navigation_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{	
		tool_ui::validate_id($tool_id);	
		$primary = new View('navigation/index');	
		
		$db = new Database;
		$parent = $db->query("SELECT * FROM navigations WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();
		$items = $db->query("SELECT * FROM navigation_items WHERE parent_id = '$parent->id' AND fk_site = '$this->site_id' ORDER BY lft ASC ");		
		
		$primary->items = $items;
		
		$primary->global_readyJS('
			$(".sortable").sortable({axis:"y"});
		
		
		');
		
		return  Navigation::display_tree($items);
		
		# return $primary;
	}
  
}

/* -- end of application/controllers/showroom.php -- */