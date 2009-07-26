<?php

class Edit_Showroom_Controller extends Edit_Tool_Controller {

/*
 *	An basic product listing generator with nestable categories.
 */
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * Display the categories drilldown
 * UPDATE category positions
 */
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		$showroom = ORM::factory('showroom')
			->where('fk_site' , $this->site_id)
			->find($tool_id);

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


		function render_node_showroom($item)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><b rel="' . $item->url . '">' . $item->name . '</b> <small>('. $item->item_count .')</small></span>'; 
		}
		$primary = new View("edit_showroom/manage");
		$primary->tree = Tree::display_tree('showroom', $items, null, TRUE);		
		$primary->tool_id = $tool_id;
		die($primary);
	}


/*
 * Save nested positions of the category menus
 * Can also delete any links removed from the list.
 
 TODO: deleting a category should also delete the items. 
	or at least put them in a purgatory!
 
 * Gets output positions from this::manage
 */ 
	function save_tree($tool_id)
	{
		if($_POST)
		{
			valid::id_key($tool_id);
			echo Tree::save_tree('showroom', 'showroom_cat', $tool_id, $this->site_id, $_POST['output']);
		}
		die();
	}	
	
/*
 * Add categories
 */ 
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		if($_POST)
		{
			if(empty($_POST['category']))
				die('category name is required');

			$showroom = ORM::factory('showroom')
				->where('fk_site', $this->site_id)
				->find($tool_id);	
			if(FALSE === $showroom->loaded)			
				die('adding categories to invalid showroom.');

			$url	= trim($_POST['url']);
			$url	= (empty($url)) ? trim($_POST['category']) : $url; 

			$_POST['local_parent'] = 
				((empty($_POST['local_parent']) OR !is_numeric($_POST['local_parent'])))
				? $showroom->root_id : $_POST['local_parent'];

			$new_cat = ORM::factory('showroom_cat');
			$new_cat->showroom_id	= $tool_id;
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
		$primary->tool_id = $tool_id;				
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
		if(FALSE === $category->loaded)
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
	function items($tool_id=NULL, $cat_id=NULL)
	{
		valid::id_key($tool_id);
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
		$primary->tool_id = $tool_id;
		die($primary);
	}
	
/*
 * Add Item(s)
 */ 
	public function add_item($tool_id=NULL)
	{	
		valid::id_key($tool_id);

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
			
			$new_item = ORM::factory('showroom_cat_item');
			$new_item->fk_site			= $this->site_id;
			$new_item->url				= valid::filter_php_url($url);
			$new_item->showroom_cat_id	= $_POST['category_id'];
			$new_item->name				= $_POST['name'];
			$new_item->intro			= $_POST['body'];
			$new_item->images			= trim($_POST['images'], '|');
			$new_item->position			= ++$max->highest;
			$new_item->save();
			die('Showroom item added');
		}
		elseif(! empty($_GET['category']))
		{
			$_GET['category'] = valid::id_key($_GET['category']);
			$primary = new View("edit_showroom/add_item");
			$primary->tool_id = $tool_id;
			$primary->category = $_GET['category'];
			die($primary);			
		}
		die();
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
			
			$item->url				= valid::filter_php_url($url);
			$item->showroom_cat_id	= $_POST['category'];
			$item->name				= $_POST['name'];
			$item->intro			= $_POST['body'];
			$item->images			= trim($_POST['images'], '|');
			$item->save();
			die('Showroom item saved');
		}

		// TODO: this seems apsurdly slow...  1.5 seconds.

		# Get list of categories 
		# TODO: could probably join these no?
		$category = ORM::factory('showroom_cat')
			->where('fk_site', $this->site_id)
			->find($item->showroom_cat_id);
		
		$categories = ORM::factory('showroom_cat')
			->where(array(
				'fk_site'			=> $this->site_id,
				'showroom_id'		=> $category->showroom_id,
				'local_parent !='	=> 0,
			))
			->find_all();

		# images 
		$image_array = explode('|', $item->images);
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
		
		$primary = new View("edit_showroom/edit_item");
		$primary->categories	= $categories;	
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
	public function settings($tool_id=NULL)
	{
		die('Showroom settings are currently disabled while we update our code. Thanks!');
		valid::id_key($tool_id);

	}
	

/*
 * Need this to enable nested showroom categories
 * Need to add a root child to items list for every other
 * child to belong to
 * Add root child id to parent for easier access.
 */	
	function _tool_adder($tool_id)
	{
	
		# this can all be done in the overloaded save function for
		# navigations model - look into it.	
		$new_cat = ORM::factory('showroom_cat');
		$new_cat->showroom_id	= $tool_id;
		$new_cat->fk_site		= $this->site_id;
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
	
/*
 * TODO: This works but we need to make this ORM.
 */	
	function _tool_deleter($tool_id, $site_id)
	{
		$db = new Database;
		$db->query("
			DELETE cats.*, items.*
			FROM showroom_cats as cats, showroom_cat_items as items
			WHERE cats.fk_site = '$site_id'
			AND cats.showroom_id = '$tool_id'
			AND cats.id = items.showroom_cat_id
		");
		
		# hack to remove the root node which has no items on it.
		ORM::factory('showroom_cat')
			->where(array(
				'fk_site'		=> $site_id,
				'showroom_id'	=> $tool_id,
			))
			->delete_all();
		
		return TRUE;
	}

} /* -- end -- */