<?php

class Showroom_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		echo 'blah';
	}
	
	function _index($tool_id)
	{
		$db = new Database;
		$category = uri::easy_segment('2');
		$item = uri::easy_segment('3');
		
		$parent = $db->query("SELECT * FROM showrooms 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();			

		# Show products immediately
		if(	'simple' == $parent->params )
		{
			$primary = new View("showroom/items_$parent->view");
			$categories = $db->query("SELECT id FROM showroom_items 
				WHERE parent_id = '$parent->id' 
				AND fk_site = '$this->site_id'
			");		
			$ids = '';
			foreach ($categories as $cats)
			{
				$ids .= "$cats->id,";
			}
			$ids = rtrim($ids, ',');
			
			$items = $db->query("SELECT * FROM showroom_items_meta 
				WHERE cat_id IN ($ids)
				AND fk_site = '$this->site_id'
				ORDER BY position
			");		
			
			$primary->items = $items;
			
		}
		else
		{
			$primary = new View("showroom/index");
			
			if( empty($category) AND empty($item)  ) #display navigation
			{
				$primary->navigation = $this->_navigation($parent->id);
			}
			elseif( empty($item) ) #display category
			{
				if(@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
				{
					echo $this->_category($category);
					die();
				}

				$primary->navigation = $this->_navigation($parent->id);
				$primary->items = $this->_category($category);

			}
			else #display item
			{ 
				if(@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
				{
					echo $this->_item($category, $item);
					die();
				}
				
				$primary->navigation = $this->_navigation($parent->id);
				$primary->item = $this->_item($category, $item);
			
			}
		}

		$primary->img_path = "/data/$this->site_name/assets/images/showroom";
		$primary->parent = $parent;
		
		$primary->global_readyJS('			
			target_div = "div#category_view_'. $parent->id .'";
			loading = "<div class=\"loading\"></div>";
			
			$("#showroom_wrapper_'.$parent->id.'").click($.delegate({		
				"ul a": function(e){
						$(target_div).html(loading);
						$(target_div).load(e.target.href, function(){

						});
						return false;
				},				
				
				"div#category_view_'. $parent->id .' a": function(e){
						$(target_div).html(loading);
						$(target_div).load(e.target.href, function(){

						});
						return false;
				}
			}));
		');
		
		return $primary;		
	}

	function _navigation($parent_id)
	{
		$db = new Database;
		$items = $db->query("SELECT * FROM showroom_items 
			WHERE parent_id = '$parent_id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC 
		");		
		
		return Tree::display_tree('showroom', $items);
	}
	
	
	function _category($category)
	{
		$db = new Database;
		$primary = new View("showroom/items_list");
		$primary->img_path = "/data/$this->site_name/assets/images/showroom";
		
		# parent 		
		$parent = $db->query("SELECT * FROM showroom_items 
			WHERE fk_site = '$this->site_id'
			AND name = '$category'
		")->current();			

		if(is_object($parent))
		{
			#display items in this cat
			$items = $db->query("SELECT * FROM showroom_items_meta
				WHERE cat_id = '$parent->id'	
				AND fk_site = '$this->site_id'	
				ORDER by position;
			");			
		}
		else
			return 'Not a category';
			
		if( count($items) > 0 )
		{
			$primary->category = $category;
			$primary->items = $items;
			return $primary;
		}
		else
			return 'No items. Check back soon!';
			
	}

	
	function _item($category, $item)
	{
		$db = new Database;	
		$primary = new View('showroom/single_item');		
		#display items in this cat
		$item_object = $db->query("SELECT * FROM showroom_items_meta 
			WHERE fk_site = '$this->site_id'
			AND url = '$item' 
		")->current();			
		
		if( count($item_object) > 0 )
		{
			$primary->item = $item_object;
			$primary->category = $category;
			$primary->img_path = "/data/$this->site_name/assets/images/showroom";

			return $primary;
		}
		else
			return 'item does not exist';
			
	}
	
}

/* -- end of application/controllers/showroom.php -- */