<?php defined('SYSPATH') OR die('No direct access allowed.');


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
		list($page_name, $category, $item) = $url_array;
		$page_name	= $this->get_page_name($page_name, 'showroom', $showroom->id);		


		$primary = new View("public_showroom/display/wrapper");
		
		# parse the params.
		# format: toggle cat list | # of columns | 
		$params = explode('|',$showroom->params);
		
		# do we show the category list?
		$primary->categories = ('off' == $params[0])
			? ''
			: Tree::display_tree('showroom', $showroom->showroom_cats, $page_name);
		
		# is the category item url specified?
		if('get' == $url_array['0'] OR (empty($category) AND empty($item)))
		{
			$primary->items = (empty($showroom->home_cat))
				? 'Home Category not set'
				: self::items_category($page_name, $showroom, $showroom->home_cat);
		}
		elseif(empty($item))
			$primary->items = self::items_category($page_name, $showroom, $category);
		else
			$primary->item = self::item($page_name, $category, $item);



		# add custom javascript;
		$primary->global_readyJS(self::javascripts($showroom));
		
		# admin js hack.
		if($this->client->can_edit($this->site_id))
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
 * category_id can be either the id or the url.
 */
	private function items_category($page_name, $showroom, $category_id)
	{
		# get the parent to determine the view.
		if(!is_object($showroom))
			$showroom = ORM::factory('showroom', $showroom);
		
		# get the category		
		$category = ORM::factory('showroom_cat')
			->where(array(
				'fk_site'		=> $this->site_id,
				'showroom_id'	=> $showroom->id,
			))
			->find($category_id);
			
		if(!$category->loaded)
			return '<h1>invalid category</h1>';

		# get the items.
		$items = ORM::factory('showroom_cat_item')
			->where(array(
				'fk_site'			=> $this->site_id,
				'showroom_cat_id'	=> $category->id
			))
			->find_all();
		if(0 == $items->count())
			return '<h1>No items. Check back soon!</h1>';
		
		$view = new View("public_showroom/display/$showroom->view");
		
		# do view stuff
		if('gallery' == $showroom->view)
		{
			# request javascript file
			$view->request_js_files('lightbox/lightbox.js');
			# parse the params.
			# format: toggle cat list | # of columns | 
			$params = explode('|',$showroom->params);
			$view->columns = (isset($params[1]) and is_numeric($params[1]))
				? $params[1]
				: 2;
		}
		
		$view->category		= $category;
		$view->page_name	= $page_name;
		$view->items		= $items;
		$view->img_path		= $this->assets->assets_url();
		return $view;
	}

/*
 * show a single item
 */
	private function item($page_name, $category, $item_url)
	{
		$item = ORM::factory('showroom_cat_item')
			->where(array(
				'fk_site' => $this->site_id,
				'url'	  => $item_url,
			))
			->find();
		if(!$item->loaded)
			return '<h1>Invalid item</h1>';

		# prep image Json.

		$view = new View('public_showroom/display/single_item');
		$view->item			= $item;
		$view->images		= $images;	
		$view->category		= $category;
		$view->page_name	= $page_name;
		$view->img_path		= $this->assets->assets_url();
		return $view;
	}

	
	
	
/*
 * output the appropriate javascript based on the type and view.
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

					$("body").click($.delegate({		
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
		return $this->place_javascript($js);
	}
	
	
/*
 * ajax handler
 *
 */
	public function _ajax($url_array, $parent_id)
	{		
		list($page_name, $category, $item) = $url_array;

		if(! empty($category) AND empty($item) )
			echo  self::items_category($page_name, $parent_id, $category);
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
		$new_cat->showroom_id	= $parent_id;
		$new_cat->fk_site		= $site_id;
		$new_cat->name			= 'ROOT';
		$new_cat->local_parent	= 0;
		$new_cat->position		= 0;
		$new_cat->save();
			
		$showroom = ORM::factory('showroom')
			->where('fk_site', $site_id)
			->find($parent_id);
		
		$showroom->root_id = $new_cat->id;
		$showroom->save();

		return 'manage';
	}
	
}  /*end*/



