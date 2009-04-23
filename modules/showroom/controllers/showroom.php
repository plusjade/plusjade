<?php

class Showroom_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
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

		$primary = new View("showroom/index");
		
		# Show products immediately
		if(	'simple' == $parent->params )
		{
			$item_view = new View("showroom/items_$parent->view");
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
			
			$item_view->items = $items;
			#Fix this
			$item_view->img_path = "/data/$this->site_name/assets/images/showroom/39";
			
			$item_view->category = 'BLAH';
			$primary->items = $item_view;
		}
		else
		{
			$primary = new View("showroom/index");
			
			if( empty($category) AND empty($item)  )
			{
				$primary->categories = $this->_categories($parent->id);
				
				#TODO: Make this configurable frontpage
				$primary->items = $this->_items_category('Shirts');
			}
			elseif( empty($item) )
			{
				$primary->categories = $this->_categories($parent->id);
				$primary->items = $this->_items_category($category, $parent->view);
			}
			else
			{ 
				$primary->categories = $this->_categories($parent->id);
				$primary->item = $this->_item($category, $item);
			}
		}

		$primary->img_path = "/data/$this->site_name/assets/images/showroom/$parent->id";
		$primary->parent = $parent;
		
		
				# Javascript
		$edit_item_toolbar = '';
		if($this->client->logged_in())
			$edit_item_toolbar = '$().add_toolkit_items("showroom");';
			
		$primary->global_readyJS('			
			target_div = "#showroom_wrapper_'.$parent->id.' div.showroom_items";
			loading = "<div class=\"loading\"></div>";
			
			$("#showroom_wrapper_'.$parent->id.'").click($.delegate({		
				"a.loader": function(e){
						$(target_div).html(loading);
						$(target_div).load(e.target.href, function(){
							'. $edit_item_toolbar .'
						});
						return false;
				},
				
				"a img.loader": function(e){
						$(target_div).html(loading);
						url = $(e.target).parent("a").attr("href");
						$(target_div).load(url, function(){
							'. $edit_item_toolbar .'
						});
						return false;
				}
			}));
		');
		
		return $primary;		
	}

	function _categories($parent_id)
	{
		$db = new Database;
		$items = $db->query("SELECT * FROM showroom_items 
			WHERE parent_id = '$parent_id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC 
		");		
		
		return Tree::display_tree('showroom', $items);
	}
	
	# Get items from a category
	function _items_category($category, $view='list')
	{
		$db = new Database;
		$item_view = new View("showroom/items_$view");
		
		# parent category 		
		$parent = $db->query("SELECT * FROM showroom_items 
			WHERE fk_site = '$this->site_id'
			AND name = '$category'
		")->current();			
		
		
		if(is_object($parent))
		{
			$item_view->img_path = "/data/$this->site_name/assets/images/showroom/$parent->id";
		
		
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
			$item_view->category = $category;
			$item_view->items = $items;
			return $item_view;
		}
		else
			return 'No items. Check back soon!';
			
	}

	# Get a single item
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
			$primary->img_path = "/data/$this->site_name/assets/images/showroom/$item_object->cat_id";

			return $primary;
		}
		else
			return 'item does not exist';
			
	}
	
}

/* -- end of application/controllers/showroom.php -- */