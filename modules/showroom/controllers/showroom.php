<?php

class Showroom_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{
		$db = new Database;
		$item_id = uri::easy_segment(2);
				
		# Single item view
		if(! empty($item_id) )
		{
			$primary = new View('showroom/single_item');
			$item = $db->query("SELECT * FROM showroom_items WHERE id = '$item_id' ")->current();
			
			$primary->img_path = 'http://' . ROOTDOMAIN . "/data/{$this->site_name}" . '/assets/images/showroom';  
			$primary->item = $item;
		}
		else
		{ # Main view 
			
			$parent = $db->query("SELECT * FROM showrooms WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();			
			$primary = new View("showroom/{$parent->view}_showroom");
			
			$result = $db->query("SELECT * FROM showroom_items WHERE parent_id = '$parent->id' AND fk_site = '{$this->site_id}' ORDER BY position");		
			$primary->items = $result;
		}
		
		# render view
		$primary->page_name = uri::easy_segment();
		
		return $primary;
	}
	
}

/* -- end of application/controllers/showroom.php -- */