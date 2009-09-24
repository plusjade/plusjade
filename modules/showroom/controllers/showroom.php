<?php


class Showroom_Controller extends Public_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

/*
 * The index displays various showroom views based on the url and routes as necessary
 * This is for non-ajax requests. _ajax handles ajax routing.
 * expects parent showroom table object
 */ 
	public function _index($showroom)
	{
		
		$url_array	= uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'showroom', $showroom->id);		
		$category	= $url_array['1'];
		$item		= $url_array['2'];

		$primary = new View("public_showroom/display/index");
		
		# show the categories list.
		function render_node_showroom($item, $page_name)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><a href="/'. $page_name .'/'. $item->url .'" class="loader">' . $item->name . '</a></span>'; 
		}
		$primary->categories = Tree::display_tree('showroom', $showroom->showroom_cats, $page_name);
	
	
		# which category do we show on the front page?
		if('get' == $url_array['0'] OR (empty($category) AND empty($item)))
		{
			$primary->items = (empty($showroom->home_cat))
				? 'Home Category not set'
				: self::items_category($page_name, $showroom->id, $showroom->home_cat);
		}
		elseif(empty($item))
			$primary->items = self::items_category($page_name, $showroom->id, $category, $showroom->view);
		else
			$primary->item = self::item($page_name, $category, $item);



		# add custom javascript;
		$primary->global_readyJS(self::javascripts($showroom));
		# admin hack.
		if($this->client->logged_in())
			$primary->global_readyJS('
				$("#click_hook").click(function(){
					$().add_toolkit_items("showroom");
				});
			');
			
		return $this->wrap_tool($primary, 'showroom', $showroom);	
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
			->where(array(
				'fk_site'			=> $this->site_id,
				'showroom_cat_id'	=> $category_ob->id
			))
			->find_all();
		if(0 == $items->count())
			return 'No items. Check back soon!';
			
		$view = new View("public_showroom/display/items_$view");
		$view->category		= $category;
		$view->page_name	= $page_name;
		$view->items		= $items;
		$view->img_path		= $this->assets->assets_url();
		return $view;
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
 * output the appropriate javascript based on the format view.
 */	
	private function javascripts($showroom)
	{
		$js = '';
		# prepare the javascript
		switch($showroom->type)
		{				
			default: # base
				$js = '
					var target_div = "div.showroom_items";
					var loading = "<div class=\"ajax_loading\">Loading...</div>";

					$(".showroom_wrapper").click($.delegate({		
						"a.loader": function(e){
								$(target_div).html(loading);
								$(target_div).load(e.target.href, function(){
									$("#click_hook").click();
								});
								return false;
						},
						
						"a img.loader": function(e){
								$(target_div).html(loading);
								var url = $(e.target).parent("a").attr("href");
								$(target_div).load(url, function(){
									$("#click_hook").click();
								});
								return false;
						}
					}));				
				';
				break;
		}
		# place the javascript.
		return $this->place_javascript($js, TRUE);
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
	public static function _tool_adder($parent_id, $site_id, $sample=FALSE)
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



