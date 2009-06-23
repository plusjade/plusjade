<?php

class Showroom_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

#TODO: query appropriate page_name when in ajax and homepage mode.
	
	function _index($tool_id)
	{
		$db			= new Database;
		$category	= uri::easy_segment('2');
		$item		= uri::easy_segment('3');
		$primary	= new View("public_showroom/index");
		
		$parent = $db->query("
			SELECT * FROM showrooms 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();			
	
		# Show products immediately
		if(	'simple' == $parent->params )
		{
			$item_view = new View("public_showroom/items_$parent->view");
			$categories = $db->query("
				SELECT id FROM showroom_items 
				WHERE parent_id = '$parent->id' 
				AND fk_site = '$this->site_id'
			");		
			$ids = '';
			foreach ($categories as $cats)
			{
				$ids .= "$cats->id,";
			}
			$ids = rtrim($ids, ',');
			
			$items = $db->query("
				SELECT * FROM showroom_items_meta 
				WHERE cat_id IN ($ids)
				AND fk_site = '$this->site_id'
				ORDER BY position
			");		
			
			$item_view->items = $items;
			#TODO: Fix this
			
			 
			$item_view->img_path = Assets::url_path("tools/showroom/39");
			$item_view->category = 'BLAH';
			$primary->items = $item_view;
		}
		else
		{
			if( empty($category) AND empty($item)  )
			{
				$primary->categories = $this->_categories($parent->id);
				
				#TODO: Make this configurable frontpage
				$primary->items = $this->_items_category($tool_id, 'Shirts');
			}
			elseif( empty($item) )
			{
				$primary->categories = $this->_categories($parent->id);
				$primary->items = $this->_items_category($tool_id, $category, $parent->view);
			}
			else
			{ 
				$primary->categories = $this->_categories($parent->id);
				$primary->item = $this->_item($category, $item);
			}
		}
		
		
		$primary->img_path = Assets::url_path("tools/showroom/$parent->id");
		$primary->parent = $parent;
		
		
		# Javascript
		if($this->client->logged_in())
			$primary->global_readyJS('
				$("#click_hook").click(function(){
					$().add_toolkit_items("showroom");
				});
			');
			
		return $this->public_template($primary, 'showroom', $tool_id);	
	}

	function _categories($parent_id)
	{
		$db = new Database;
		$items = $db->query("
			SELECT * FROM showroom_items 
			WHERE parent_id = '$parent_id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC 
		");		
		
		function render_node_showroom($item)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><a href="/showroom/'. $item->url .'" class="loader">' . $item->name . '</a></span>'; 
		}
		return Tree::display_tree('showroom', $items);
	}
	
	# Get items from a category
	function _items_category($tool_id, $category, $view='list')
	{
		$db = new Database;
		$item_view = new View("public_showroom/items_$view");
		
		# parent category 		
		$parent = $db->query("
			SELECT * FROM showroom_items
			WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
			AND name = '$category'
		")->current();			
		
		if(is_object($parent))
		{
			$item_view->img_path = Assets::url_path_direct("tools/showroom/$parent->id");
		
			#display items in this cat
			$items = $db->query("
				SELECT * FROM showroom_items_meta
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
		$primary = new View('public_showroom/single_item');		
		#display items in this cat
		$item_object = $db->query("
			SELECT * FROM showroom_items_meta 
			WHERE fk_site = '$this->site_id'
			AND url = '$item' 
		")->current();			
		
		if( count($item_object) > 0 )
		{
			$primary->item = $item_object;
			$primary->category = $category;
			$primary->img_path = Assets::url_path_direct("tools/showroom/$item_object->cat_id");
			
			return $primary;
		}
		else
			return 'item does not exist';
			
	}


	function _ajax($url_array, $tool_id)
	{
		$category	= @$url_array['2'];
		$item		= @$url_array['3'];	
		
		if(! empty($category) AND empty($item) )
			echo $this->_items_category($tool_id, $category);
		elseif(! empty($category) AND !empty($item) )
			echo $this->_item($category, $item);

		die();
	}
	
}

/* -- end of application/controllers/showroom.php -- */