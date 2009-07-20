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
		$db = new Database;
		$parent = $db->query("
			SELECT * FROM showrooms 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();			

		# show category list.
		$primary = new View("edit_showroom/manage");
		$items = $db->query("
			SELECT cat.*, COUNT(items.id) AS item_count
			FROM showroom_items AS cat
			LEFT JOIN showroom_items_meta AS items ON cat.id = items.cat_id
			WHERE parent_id = '$parent->id' 
			AND cat.fk_site = '$this->site_id'
			GROUP BY cat.id
			ORDER BY cat.lft ASC 
		");

		function render_node_showroom($item)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><b rel="' . $item->url . '">' . $item->name . '</b> <small>('. $item->item_count .')</small></span>'; 
		}
		
		$primary->tree = Tree::display_tree('showroom', $items, null, TRUE);		
		$primary->tool_id = $tool_id;
		die($primary);
	}

/*
 * manage items view for a particular category
 */ 
	function items($tool_id=NULL, $cat_id=NULL)
	{
		valid::id_key($tool_id);
		valid::id_key($cat_id);
		$db = new Database;
		$primary = new View('edit_showroom/manage_items');
		
		#display items in this cat
		$items = $db->query("
			SELECT * FROM showroom_items_meta 
			WHERE cat_id = '$cat_id' 
			AND fk_site = '$this->site_id'
			ORDER by position;
		");			
		
		if( '0' == count($items) )
			die('<span class="on_close two">close-2</span> No items');	

		$primary->items = $items;
		$primary->tool_id = $tool_id;
		die($primary);
	}
	
	
/*
 * Save nested positions of the category menus
 * Can also delete any links removed from the list.
 * Gets output positions from this::manage
 */ 
	function save_tree($tool_id)
	{
		if($_POST)
		{
			valid::id_key($tool_id);
			echo Tree::save_tree('showrooms', 'showroom_items', $tool_id, $this->site_id, $_POST['output']);
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
				
			# sanitize url
			$url	= trim($_POST['url']);
			$url	= (empty($url)) ? trim($_POST['category']) : $url; 
			$url	= valid::filter_php_url($url);
			
			$db = new Database;
			# Get parent
			$parent	= $db->query("
				SELECT * FROM showrooms 
				WHERE id = '$tool_id' 
				AND fk_site = '$this->site_id' 
			")->current();

			$_POST['local_parent'] = 
				((empty($_POST['local_parent']) OR !is_numeric($_POST['local_parent'])))
				? $parent->root_id : $_POST['local_parent'];

			$data = array(
				'parent_id'		=> $tool_id,
				'fk_site'		=> $this->site_id,
				'url'			=> $url,
				'name'			=> trim($_POST['category']),
				'local_parent'	=> $_POST['local_parent'],
				'position'		=> '0'
			);	
			$insert_id = $db->insert('showroom_items', $data)->insert_id(); 	

			# Update left and right values
			Tree::rebuild_tree('showroom_items', $parent->root_id, $this->site_id, '1');
			
			die("$insert_id");  # need for javascript
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
		$db = new Database;
		
		if($_POST)
		{
			if(empty($_POST['category']))
				die('category name is required');
				
			# sanitize url
			$url	= trim($_POST['url']);
			$url	= (empty($url)) ? trim($_POST['category']) : $url; 
			$url	= valid::filter_php_url($url);
		
			$data = array(
				'url'			=> $url,
				'name'			=> trim($_POST['category']),
			);	
			$db->update(
				'showroom_items',
				$data,
				"id='$cat_id' and fk_site = '$this->site_id'"
			); 
			die('Showroom category updated.');
		}
	
		$cat = $db->query("
			SELECT * FROM showroom_items
			WHERE id = '$cat_id' 
			AND fk_site = '$this->site_id' 
		")->current();
		$primary = new View('edit_showroom/edit_category');
		$primary->cat = $cat;				
		die($primary);	
	}
	
/*
 * Add Item(s)
 */ 
	public function add_item($tool_id=NULL)
	{	
		valid::id_key($tool_id);
		$db = new Database;	
		
		if($_POST)
		{
			if( empty($_POST['name']) )
				die('Name is required'); # error
			
			# Get highest position
			$get_highest = $db->query("
				SELECT MAX(position) as highest 
				FROM showroom_items_meta 
				WHERE cat_id = '{$_POST['category_id']}'
			")->current();
			
			# sanitize url
			$url = trim($_POST['url']);
			$url = (empty($url)) ? $_POST['name'] : $url;
			$url = valid::filter_php_url($url);
			
			$data = array(			
				'fk_site'	=> $this->site_id,
				'url'		=> $url,
				'cat_id'	=> $_POST['category_id'],
				'name'		=> $_POST['name'],
				'intro'		=> $_POST['intro'],
				'body'		=> $_POST['body'],
				'images'	=> trim($_POST['images'], '|'),
				'position'	=> ++$get_highest->highest,				
			);	
			
			$db->insert('showroom_items_meta', $data);	
			die('Item added'); #status message
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
		$db = new Database;
			
		if($_POST)
		{
			if(empty($_POST['name']))
				die('Name is required'); # error
				
			# sanitze url
			$url = trim($_POST['url']);
			$url = (empty($url)) ? $_POST['name'] : $url;
			$url = valid::filter_php_url($url);
			
			$data = array(
				'url'		=> $url,
				'cat_id'	=> $_POST['category'],
				'name'		=> $_POST['name'],
				'intro'		=> $_POST['intro'],
				'body'		=> $_POST['body'],
				'images'		=> trim($_POST['images'], '|'),					
			);
			$db->update(
				'showroom_items_meta',
				$data,
				"id = '$id' AND fk_site = '$this->site_id'"
			);	
			die('Item saved');
		}
		// TODO: this seems apsurdly slow...  1.5 seconds.
		
		# Grab single item
		$item = $db->query("
			SELECT * FROM showroom_items_meta
			WHERE id = '$id' 
			AND fk_site = '$this->site_id'
		")->current();
		if(empty($item) )
			die('item does not exist');
			
		# Get list of categories 
		# TODO: could probably join these no?
		$category = $db->query("
			SELECT id, name, parent_id 
			FROM showroom_items
			WHERE id = '$item->cat_id' 
			AND fk_site = '$this->site_id'
		")->current();
		
		$categories = $db->query("
			SELECT id, name FROM showroom_items
			WHERE parent_id = '$category->parent_id' 
			AND fk_site = '$this->site_id'
			AND local_parent != '0'
			ORDER BY lft ASC
		");
		$primary = new View("edit_showroom/edit_item");
		$primary->categories = $categories;	
		$primary->item = $item;

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
		$primary->images = $images;	
		die($primary);	
	}

/*
 * delete a single showroom item
 */
	public function delete($tool_id=NULL, $id=NULL)
	{
		valid::id_key($tool_id);
		valid::id_key($id);				
		
		$db = new Database;
		$db->delete('showroom_items_meta', "id = '$id' AND fk_site ='$this->site_id'");
		die('Showroom item Deleted');
	}

/*
 * SAVE items sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		die( $this->_save_sort_common($_GET['showroom'], 'showroom_items') );
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
		$db = new Database;
		
		if($_POST)
		{
			$data = array(
				'name'		=> $_POST['name'],
				'view'		=> $_POST['view'],
				'params'	=> $_POST['params'],
			);
			$db->update(
				'showrooms',
				$data,
				"id = '$tool_id' AND fk_site = '$this->site_id'"
			);
			die('Showroom updated');		
		}
		die( $this->_view_edit_settings('showroom', $tool_id) );
	}
	

/*
 * Need this to enable nested showroom categories
 * Need to add a root child to items list for every other
 * child to belong to
 * Add root child id to parent for easier access.
 */	
	function _tool_adder($tool_id)
	{
		$db = new Database;
		$data = array(
			'parent_id'		=> $tool_id,
			'fk_site'		=> $this->site_id,
			'name'			=> 'ROOT',
			'local_parent'	=> '0',
			'position'		=> '0'
		);	
		$root_insert_id = $db->insert('showroom_items', $data)->insert_id(); 	
		
		$db->update('showrooms', 
			array( 'root_id' => $root_insert_id ), 
			array( 'id' => $tool_id, 'fk_site' => $this->site_id ) 
		);	
	
		return 'manage';
	}
	
/*
 * logic executed after this blog tool is deleted from site.
 * TOD0: finish this.
 */	
	function _tool_deleter($tool_id, $site_id)
	{
		$db = new Database;
		
		# delete items_meta (items)
		$db->query("
			DELETE items.*
			FROM showroom_items as cats, showroom_items_meta as items
			WHERE cats.fk_site = '$this->site_id'
			AND cats.parent_id = '$tool_id'
			AND cats.id = items.cat_id
		");
		return TRUE;
		/*
		# delete data assets
		$showroom_dir = Assets::assets_dir("tools/showrooms/$tool_id");
		if(is_dir($showroom_dir))
		{
			$d = dir($showroom_dir); 
			while($file = $d->read())
			{
				 if('.' != $file && '..' != $file)
					unlink("$showroom_dir/$file"); 
			} 
			$d->close(); 
			rmdir($showroom_dir);
		}
		*/		
	}

} /* -- end -- */