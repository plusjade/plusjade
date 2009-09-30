<?php defined('SYSPATH') OR die('No direct access allowed.');


class Edit_Showroom_Controller extends Edit_Tool_Controller {


	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * Display the categories drilldown
 * UPDATE category positions
 */
	public function manage($parent_id=NULL)
	{
		valid::id_key($parent_id);
		
		$showroom = ORM::factory('showroom')
			->where('fk_site' , $this->site_id)
			->find($parent_id);
		if(!$showroom->loaded)
			die('invalid showroom');
			
		# show category list.
		$db = new Database;
		$items = $db->query("
			SELECT cat.*, COUNT(items.id) AS item_count
			FROM showroom_cats AS cat
			LEFT JOIN showroom_cat_items AS items ON cat.id = items.showroom_cat_id
			WHERE showroom_id = '$showroom->id' 
			AND cat.fk_site = '$this->site_id'
			GROUP BY cat.id
			ORDER BY cat.lft ASC 
		");

		$view = new View("edit_showroom/manage");
		$view->tree = Tree::display_tree('showroom', $items, NULL, NULL, 'showroom_admin', TRUE);		
		$view->parent_id = $parent_id;
		die($view);
	}


/*
 * Save nested positions of the category menus
 * Can also delete any links removed from the list.
 
 TODO: deleting a category should also delete the items. 
	or at least put them in a purgatory!
 
 * Gets output positions from this::manage
 */ 
	public function save_tree($parent_id)
	{
		if($_POST)
		{
			valid::id_key($parent_id);
			echo Tree::save_tree('showroom', 'showroom_cat', $parent_id, $this->site_id, $_POST['output']);
		}
		die();
	}	
	
/*
 * Add categories
 */ 
	public public function add($parent_id=NULL)
	{
		valid::id_key($parent_id);
		if($_POST)
		{
			if(empty($_POST['category']))
				die('category name is required');

			$showroom = ORM::factory('showroom')
				->where('fk_site', $this->site_id)
				->find($parent_id);	
			if(!$showroom->loaded)			
				die('invalid showroom.');

			$url = trim($_POST['url']);
			$url = (empty($url)) ? trim($_POST['category']) : $url; 

			$_POST['local_parent'] = 
				((empty($_POST['local_parent']) OR !is_numeric($_POST['local_parent'])))
				? $showroom->root_id : $_POST['local_parent'];

			$new_cat = ORM::factory('showroom_cat');
			$new_cat->showroom_id	= $parent_id;
			$new_cat->fk_site		= $this->site_id;
			$new_cat->url			= valid::filter_php_url($url);
			$new_cat->name			= trim($_POST['category']);
			$new_cat->local_parent	= $_POST['local_parent'];
			$new_cat->position		= 0;
			$new_cat->save();
			
			# Update left and right values
			Tree::rebuild_tree('showroom_cat', $showroom->root_id, $this->site_id, 1);
			die("$new_cat->id");  # need for javascript
		}

		$primary = new View('edit_showroom/add_category');
		$primary->tool_id = $parent_id;				
		die($primary);	
	}

/*
 * edit a category
 */ 
	public function edit_category($cat_id=NULL)
	{
		valid::id_key($cat_id);
		
		$category = ORM::factory('showroom_cat')
			->where('fk_site', $this->site_id)
			->find($cat_id);	
		if(!$category->loaded)
			die('invalid category item id');
		
		if($_POST)
		{
			if(empty($_POST['category']))
				die('category name is required');
	
			$url = trim($_POST['url']);
			$url = (empty($url)) ? trim($_POST['category']) : $url; 
		
			$category->url = valid::filter_php_url($url);
			$category->name = trim($_POST['category']);
			$category->save();
			die('Showroom category updated.');
		}

		$primary = new View('edit_showroom/edit_category');
		$primary->cat = $category;				
		die($primary);	
	}

/*
 * manage items view for a particular category
 */ 
	public function items($parent_id=NULL, $cat_id=NULL)
	{
		valid::id_key($parent_id);
		valid::id_key($cat_id);
	
		$items = ORM::factory('showroom_cat_item')
			->where(array(
				'fk_site'			=> $this->site_id,
				'showroom_cat_id'	=> $cat_id,
			))
			->find_all();
		if(0 === $items->count())
			die('<span class="on_close two">close-2</span> No items');	

		$primary = new View('edit_showroom/manage_items');
		$primary->items = $items;
		$primary->tool_id = $parent_id;
		die($primary);
	}
	
/*
 * Add Item(s)
 */ 
	public function add_item($parent_id=NULL)
	{	
		valid::id_key($parent_id);

		if($_POST)
		{
			if(empty($_POST['name']))
				die('Name is required'); # error
			
			$max = ORM::factory('showroom_cat_item')
				->select('MAX(position) as highest')
				->where('showroom_cat_id', $_POST['category_id'])
				->find();	
				
			# sanitize url
			$url = trim($_POST['url']);
			$url = (empty($url)) ? $_POST['name'] : $url;

			# verify image JSON
			if(NULL === json_decode($_POST['images']))
				$_POST['images'] = '';
				
			$new_item = ORM::factory('showroom_cat_item');
			$new_item->fk_site			= $this->site_id;
			$new_item->url				= valid::filter_php_url($url);
			$new_item->showroom_cat_id	= $_POST['category_id'];
			$new_item->name				= $_POST['name'];
			$new_item->intro			= $_POST['body'];
			$new_item->images			= $_POST['images'];
			$new_item->position			= ++$max->highest;
			$new_item->save();
			die('Showroom item added');
		}

		# Get list of categories
		$showroom = ORM::factory('showroom', $parent_id);

		$view = new View("edit_showroom/add_item");
		$view->parent_id	= $parent_id;
		$view->categories	= Tree::display_tree('showroom', $showroom->showroom_cats, NULL, NULL, 'render_edit_showroom');	
		die($view);
	}
	
	
/*
 * Edit single Item
 */
	public function edit($id=NULL)
	{
		valid::id_key($id);

		$item = ORM::factory('showroom_cat_item')
			->where('fk_site', $this->site_id)
			->find($id);	
		if(FALSE === $item->loaded)
			die('invalid showroom item id');
			
		if($_POST)
		{
			if(empty($_POST['name']))
				die('Name is required'); # error
				
			# sanitze url
			$url = trim($_POST['url']);
			$url = (empty($url)) ? $_POST['name'] : $url;

			# verify image JSON
			if(NULL === json_decode($_POST['images']))
				$_POST['images'] = '';

				
			$item->url				= valid::filter_php_url($url);
			$item->showroom_cat_id	= $_POST['category_id'];
			$item->name				= $_POST['name'];
			$item->intro			= $_POST['body'];
			$item->images			= $_POST['images'];
			$item->save();
			die('Showroom item saved');
		}

		// TODO: this seems apsurdly slow...  1.5 seconds.

		# which category does this item belong to?
		$category = ORM::factory('showroom_cat')
			->where('fk_site', $this->site_id)
			->find($item->showroom_cat_id);
		
		# Get list of categories
		$showroom = ORM::factory('showroom', $category->showroom_id);
			
		# parse images	
		$images = json_decode($item->images);
		if(NULL === $images)
			$images = array();
		foreach($images as $image)
			$image->thumb = image::thumb($image->path);
				
		$primary = new View("edit_showroom/edit_item");
		$primary->categories	= Tree::display_tree('showroom', $showroom->showroom_cats, NULL, NULL, 'render_edit_showroom');
		$primary->category_id	= $category->id;
		$primary->item			= $item;
		$primary->images		= $images;
		$primary->img_path		= $this->assets->assets_url();	
		die($primary);	
	}

/*
 * delete a single showroom item
 */
	public function delete($id=NULL)
	{
		valid::id_key($id);				

		ORM::factory('showroom_cat_item')
			->where('fk_site', $this->site_id)
			->delete($id);
		die('Showroom item Deleted');
	}

/*
 * SAVE items sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		if(empty($_GET['item']))
			die('No items to sort');

		$db = new Database;	
		foreach($_GET['item'] as $position => $id)
			$db->update('showroom_cat_items', array('position' => $position), "id = '$id' AND fk_site = '$this->site_id'"); 	
		
		die('Showroom item order saved.');
	}
	
/*
 * SAVE showroom parent settings
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function settings($parent_id=NULL)
	{
		die('Showroom settings are currently disabled while we update our code. Thanks!');
		valid::id_key($parent_id);
		
		if($_POST)
		{
		
		}
	}
	


	
/*
 * TODO: This works but we need to make this ORM.
 */	
	public static function _tool_deleter($parent_id, $site_id)
	{
		$db = new Database;
		$db->query("
			DELETE cats.*, items.*
			FROM showroom_cats as cats, showroom_cat_items as items
			WHERE cats.fk_site = '$site_id'
			AND cats.showroom_id = '$parent_id'
			AND cats.id = items.showroom_cat_id
		");
		
		# hack to remove the root node which has no items on it.
		ORM::factory('showroom_cat')
			->where(array(
				'fk_site'		=> $site_id,
				'showroom_id'	=> $parent_id,
			))
			->delete_all();
		
		return TRUE;
	}

} /* -- end -- */