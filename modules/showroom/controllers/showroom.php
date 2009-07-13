<?php

class Showroom_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

/*
 * The index displays various showroom views based on the url and routes as necessary
 * This is for non-ajax requests. _ajax handles ajax routing.
 */ 
	function _index($tool_id)
	{
		$db			= new Database;
		$url_array	= uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'showroom', $tool_id);		
		$category	= $url_array['1'];
		$item		= $url_array['2'];
		$primary	= new View("public_showroom/index");

		$parent = $db->query("
			SELECT * FROM showrooms 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();			
		
		# Show products immediately
		if(	'simple' == $parent->params )
		{
			## THIS IS OFFLINE FOR NOW ##
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

			$item_view->category = 'BLAH';
			$primary->items = $item_view;
		}
		else
		{
			# show the categories list.
			$primary->categories = self::categories($parent->id, $page_name);
			
			# default full showroom view
			if('get' == $url_array['0'] OR (empty($category) AND empty($item)) )
			{
				$primary->items = (empty($parent->home_cat)) ?
					'Home Category not set' : self::items_category($tool_id, $parent->home_cat, $page_name);
			}
			elseif(empty($item))
				$primary->items = self::items_category($tool_id, $category, $page_name, $parent->view);
			else
				$primary->item = self::item($category, $item, $page_name);
		}

		# Javascript
		if($this->client->logged_in())
			$primary->global_readyJS('
				$("#click_hook").click(function(){
					$().add_toolkit_items("showroom");
				});
			');
			
		return $this->public_template($primary, 'showroom', $tool_id);	
	}

	
/*
 * get category list from this showroom
 */
	private function categories($parent_id, $page_name)
	{
		$db = new Database;
		$items = $db->query("
			SELECT * FROM showroom_items 
			WHERE parent_id = '$parent_id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC 
		");		
		if(0 == $items->count())
			return 'no categories';
			
		function render_node_showroom($item, $page_name)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><a href="/'. $page_name .'/'. $item->url .'" class="loader">' . $item->name . '</a></span>'; 
		}
		return Tree::display_tree('showroom', $items, $page_name);
	}
	
/*
 * show items from a given category
 * TODO: FIX THIS
 */
	private function items_category($tool_id, $category, $page_name, $view='list')
	{
		$db = new Database;
		$item_view = new View("public_showroom/items_$view");
		
		# parent category 		
		$parent = $db->query("
			SELECT * FROM showroom_items
			WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
			AND url = '$category'
		")->current();			
		
		if(!is_object($parent))
			return 'invalid category';

		# display items in this cat
		$items = $db->query("
			SELECT *, SUBSTRING_INDEX(images, '|', 1) AS images
			FROM showroom_items_meta
			WHERE cat_id = '$parent->id'	
			AND fk_site = '$this->site_id'	
			ORDER by position;
		");			
		if(0 == count($items))
			return 'No items. Check back soon!';

		$item_view->category	= $category;
		$item_view->page_name	= $page_name;
		$item_view->items		= $items;
		return $item_view;
	}

/*
 * show a single item
 */
	private function item($category, $item, $page_name)
	{
		$db = new Database;	
		$primary = new View('public_showroom/single_item');		

		$item_object = $db->query("
			SELECT * FROM showroom_items_meta 
			WHERE fk_site = '$this->site_id'
			AND url = '$item' 
		")->current();			
		
		if(!is_object($item_object))
			return 'Invalid item';

		$primary->item = $item_object;
		
		# images  with thumbnails
		$image_array = explode('|', $item_object->images);
		$images = array();
		foreach($image_array as $image)
		{
			if(0 < substr_count($image, '/'))
			{
				$filename = strrchr($image, '/');
				$small = str_replace($filename, "/_sm$filename", $image);
			}
			else
				$small = "/_sm/$image";
			
			$images[] = "$small|$image";
		}

		$primary->images	= $images;	
		$primary->category	= $category;
		$primary->page_name	= $page_name;
		return $primary;
	}

/*
 * ajax handler
 *
 */
	function _ajax($url_array, $tool_id)
	{		
		list($page_name, $category, $item) = $url_array;

		if(! empty($category) AND empty($item) )
			echo  self::items_category($tool_id, $category, $page_name);
		elseif(! empty($category) AND !empty($item) )
			echo self::item($category, $item, $page_name);

		die();
	}
	
}/*end*/



