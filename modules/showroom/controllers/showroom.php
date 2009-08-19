<?php

class Showroom_Controller extends Public_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

/*
 * The index displays various showroom views based on the url and routes as necessary
 * This is for non-ajax requests. _ajax handles ajax routing.
 */ 
	public function _index($tool_id)
	{
		$url_array	= uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'showroom', $tool_id);		
		$category	= $url_array['1'];
		$item		= $url_array['2'];
		
		
		$showroom = ORM::factory('showroom')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $showroom->loaded)
			return $this->public_template('this showroom id not found.', 'showroom', $showroom);
	
		$primary = new View("public_showroom/display/index");
		
		# show the categories list.
		function render_node_showroom($item, $page_name)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><a href="/'. $page_name .'/'. $item->url .'" class="loader">' . $item->name . '</a></span>'; 
		}
		$primary->categories = Tree::display_tree('showroom', $showroom->showroom_cats, $page_name);
	

		if('get' == $url_array['0'] OR (empty($category) AND empty($item)))
		{
			$primary->items = (empty($showroom->home_cat)) ?
				'Home Category not set' : self::items_category($page_name, $tool_id, $showroom->home_cat);
		}
		elseif(empty($item))
			$primary->items = self::items_category($page_name, $tool_id, $category, $showroom->view);
		else
			$primary->item = self::item($page_name, $category, $item);

		# Javascript
		if($this->client->logged_in())
			$primary->global_readyJS('
				$("#click_hook").click(function(){
					$().add_toolkit_items("showroom");
				});
			');
			
		return $this->public_template($primary, 'showroom', $showroom);	
	}

	
/*
 * show items from a given category
 * TODO: FIX THIS
 */
	private function items_category($page_name, $tool_id, $category, $view='list')
	{
		$category_ob = ORM::factory('showroom_cat')
			->where(array(
				'fk_site'		=> $this->site_id,
				'showroom_id'	=> $tool_id,
				'url'			=> $category
			))
			->find();
		if(FALSE === $category_ob->loaded)
			return 'invalid category';

		$items = ORM::factory('showroom_cat_item')
			->select(array('*', "SUBSTRING_INDEX(images, '|', 1) AS images"))
			->where(array(
				'fk_site'			=> $this->site_id,
				'showroom_cat_id'	=> $category_ob->id
			))
			->find_all();		
		if(0 == $items->count())
			return 'No items. Check back soon!';

		$item_view = new View("public_showroom/display/items_$view");
		$item_view->category	= $category;
		$item_view->page_name	= $page_name;
		$item_view->items		= $items;
		$item_view->img_path	= $this->assets->assets_url();
		return $item_view;
	}

/*
 * show a single item
 */
	private function item($page_name, $category, $item)
	{
		$item_object = ORM::factory('showroom_cat_item')
			->where(array(
				'fk_site' => $this->site_id,
				'url'	  => $item,
			))
			->find();
		if(FALSE === $item_object->loaded)
			return 'Invalid item';

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

		$primary = new View('public_showroom/display/single_item');
		$primary->item		= $item_object;
		$primary->images	= $images;	
		$primary->category	= $category;
		$primary->page_name	= $page_name;
		$primary->img_path	= $this->assets->assets_url();
		return $primary;
	}

/*
 * ajax handler
 *
 */
	public function _ajax($url_array, $tool_id)
	{		
		list($page_name, $category, $item) = $url_array;

		if(! empty($category) AND empty($item) )
			echo  self::items_category($page_name, $tool_id, $category);
		elseif(! empty($category) AND !empty($item) )
			echo self::item($page_name, $category, $item);

		die();
	}

	
/*
 * Need this to enable nested showroom categories
 * Need to add a root child to items list for every other
 * child to belong to
 * Add root child id to parent for easier access.
 */	
	public static function _tool_adder($tool_id, $site_id)
	{
	
		# this can all be done in the overloaded save function for
		# navigations model - look into it.	
		$new_cat = ORM::factory('showroom_cat');
		$new_cat->showroom_id	= $tool_id;
		$new_cat->fk_site		= $site_id;
		$new_cat->name			= 'ROOT';
		$new_cat->local_parent	= 0;
		$new_cat->position		= 0;
		$new_cat->save();
			
		$showroom = ORM::factory('showroom')
			->where('fk_site', $this->site_id)
			->find($tool_id);
		
		$showroom->root_id = $new_cat->id;
		$showroom->save();

		return 'manage';
	}
	
	
}  /*end*/



