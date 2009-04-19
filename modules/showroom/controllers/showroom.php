<?php

class Showroom_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{
		$db = new Database;
		#$item_id = uri::easy_segment(2);

		# Main view 		
		$parent = $db->query("SELECT * FROM showrooms 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id' ")->current();			

		# show products immediately
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
			#show category list.
			$primary = new View("showroom/categories");
			$items = $db->query("SELECT * FROM showroom_items 
				WHERE parent_id = '$parent->id' 
				AND fk_site = '$this->site_id' 
				ORDER BY lft ASC 
			");		
			$primary->tree = Tree::display_tree('showroom', $items);
		}

		$primary->img_path = "/data/$this->site_name/assets/images/showroom";
		$primary->parent = $parent;
		
		$primary->global_readyJS('
			$("#showroom_wrapper_'.$parent->id.' a").click(function(){	
				$("div.category_view").load(this.href);
				return false;
			});
		');
		
		
		# render view
		$primary->page_name = uri::easy_segment();
		
		return $primary;		
	}
	
	function items($category_id)
	{
		## Rough outline ##
		$db = new Database;
		#$item_id = uri::easy_segment(2);
		$primary = new View("showroom/items_list");
		$primary->img_path = "/data/$this->site_name/assets/images/showroom";
		
		/*
			# Get category to extract lft/rgt values
			$category = $db->query("SELECT * FROM showroom_items WHERE id = '$category_id' AND fk_site = '$this->site_id' ")->current();			

			# use lft/rgt to get child categories 
			$cats = $db->query("SELECT * FROM showroom_items 
				WHERE lft BETWEEN $category->lft AND  $category->rgt	
				AND fk_site = '$this->site_id'
				ORDER BY lft ASC;
			");		
			#display child cats
			echo Tree::display_categories($cats);
		*/
		
		#display items in this cat
		$items = $db->query("SELECT * FROM showroom_items_meta 
			WHERE cat_id = '$category_id' AND fk_site = '$this->site_id'
			ORDER by position;
		");			
		
		if( count($items) > 0 )
		{
			$primary->items = $items;
			echo $primary;
		}
		else
			echo 'No items. Check back soon!';
			
		die();
	}
}

/* -- end of application/controllers/showroom.php -- */