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
 
	define the params position format:
	0: category navigation main view
		0 = off
		full
		flat
	
	
	url formats: 
		categories: mysite.com/page_name/cat_id/category/n-sub-cat/n2-sub-cat
		items: mysite.com/page_name/item_id/item-url-name
 */ 
	public function _index($showroom)
	{
		$url_array	= uri::url_array();
		list($page_name, $first_node, $item) = $url_array;
		$page_name	= $this->get_page_name($page_name, 'showroom', $showroom->id);		

		# parse the params.
		$params = explode('|',$showroom->params);
		$primary = new View("public_showroom/display/wrapper");
		
		
		# what is the url asking for?
		if('get' == $url_array['0'] OR (empty($first_node)))
		{
			# if empty, display default category
			$primary->items = (empty($showroom->home_cat))
				? '(Home Category not set)'
				: self::items_category($page_name, $showroom, (int) $showroom->home_cat);
		}
		elseif(is_numeric($first_node))
			$primary->items = self::items_category($page_name, $showroom, $first_node);
		else
			$primary->item = self::item($page_name, $first_node, $item);


		# determine the category to highlight.
		$first_node = (empty($first_node))
			? $showroom->home_cat
			: $first_node; 			
			
		
		# how do we show the category list on every showroom page?
		$category_list = '';
		if(!empty($params[0]))
		{
			if('flat' == $params[0])
			{
				# showing only root categories.
				$root_cats = ORM::factory('showroom_cat')
					->where(array(
						'fk_site'		=> $this->site_id,
						'showroom_id'	=> $showroom->id,
						'local_parent'	=> $showroom->root_id,
					))
					->orderby(array('lft' => 'asc'))
					->find_all();	
				$category_list = Tree::display_flat_tree('showroom', $root_cats, $page_name, $first_node);	
			}
			else
				$category_list = Tree::display_tree('showroom', $showroom->showroom_cats, $page_name, $first_node);
	
		}
		$primary->categories = $category_list;
		
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
			return '<div class="not_found">Invalid category</div>';
		
		# get any sub categories ...
		$sub_cats = ORM::factory('showroom_cat')
			->where(array(
				'fk_site'		=> $this->site_id,
				'showroom_id'	=> $showroom->id,
				'lft >='		=> "$category->lft",
				'lft <='		=> "$category->rgt",
			))
			->find_all();
			
		# create array from the cat and sub_cats
		$cat_ids = array();
		foreach($sub_cats as $cat)
			$cat_ids[] = $cat->id;
	
		# get all the items.
		$items = ORM::factory('showroom_cat_item')
			->where(array(
				'fk_site' => $this->site_id,
			))
			->in('showroom_cat_id', $cat_ids)
			->find_all();
		if(0 == $items->count())
			return '<div class="not_found">No items. Check back soon!</div>';
		
		$view = new View("public_showroom/display/$showroom->view");
		
		# do view stuff
		if('gallery' == $showroom->view)
		{
			# request javascript file
			$view->request_js_files('lightbox/lightbox.js');
			# parse the params.
			$params = explode('|',$showroom->params);
			$view->columns = (isset($params[1]) AND is_numeric($params[1]))
				? $params[1]
				: 2;
			$view->thumb_size = (isset($params[2]) AND is_numeric($params[2]))
				? $params[2]
				: 75;
		}

		# get the path to this category
		$path = ORM::factory('showroom_cat')
			->where(array(
				'fk_site'		=> $this->site_id,
				'showroom_id'	=> $showroom->id,
				'lft <'			=> $category->lft,
				'rgt >'			=> $category->rgt,
				'local_parent !='=> 0
			))
			->orderby(array('lft' => 'asc'))
			->find_all();
		
		$view->path			= $path;
		$view->category		= $category;
		$view->sub_categories	= Tree::display_tree('showroom', $sub_cats, $page_name);
		$view->page_name	= $page_name;
		$view->img_path		= $this->assets->assets_url();
		$view->items		= $items;
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
			return '<div class="not_found">Invalid item</div>';

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
								$("a.loader").removeClass("active");
								$(e.target).addClass("active");
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
		list($page_name, $first_node) = $url_array;

		if(empty($first_node))
			die('invalid showroom request');
			
		if(is_numeric($first_node))
			echo self::items_category($page_name, $parent_id, $first_node);
		else
			echo self::item($page_name, $first_node, $item);

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



