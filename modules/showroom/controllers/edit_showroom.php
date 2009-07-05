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

		#show category list.
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
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span>' . $item->name . ' <small>('. $item->item_count .')</small></span>'; 
		}
		
		$primary->tree = Tree::display_tree('showroom', $items, null, TRUE);		
		$primary->tool_id = $tool_id;
		die($primary);
	}

	
	function items($cat_id=NULL)
	{
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
			die('No items. Check back soon!');	

		$primary->items = $items;
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
			echo Tree::save_tree('showrooms', 'showroom_items', $tool_id, $_POST['output']);
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
			$db = new Database;
			# Get parent
			$parent	= $db->query("
				SELECT * FROM showrooms 
				WHERE id = '$tool_id' 
				AND fk_site = '$this->site_id' 
			")->current();
			
			# Make URL friendly
			$category	= trim($_POST['category']);
			$url		= valid::filter_php_url($category);
		
			$data = array(
				'parent_id'		=> $tool_id,
				'fk_site'		=> $this->site_id,
				'url'			=> $url,
				'name'			=> $category,
				'local_parent'	=> $parent->root_id,
				'position'		=> '0'
			);	
			$db->insert('showroom_items', $data); 	

			# Update left and right values
			Tree::rebuild_tree('showroom_items', $parent->root_id, '1');
			die('Categories added'); #status message			
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
			# Make URL friendly
			$category	= trim($_POST['category']);
			$url		= valid::filter_php_url($category);
		
			$data = array(
				'url'			=> $url,
				'name'			=> $category,
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
			
			# Make URL friendly
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
				'position'	=> ++$get_highest->highest,				
			);	
			
			# Upload image if sent
			if(!empty($_FILES['image']['tmp_name']) AND is_uploaded_file($_FILES['image']['tmp_name']))
				if (! $data['img'] = self::upload_image($_FILES, $_POST['category_id']) )
					echo 'Image not saved! Must be jpg, gif, or png.';
			
			$db->insert('showroom_items_meta', $data);	
			die('Item added'); #status message
		}
		elseif($_GET)
		{
			$category = valid::id_key(@$_GET['category']);
			$primary = new View("edit_showroom/add_item");
			$primary->tool_id = $tool_id;
			$primary->category = $category;
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
			if( empty($_POST['name']) )
				die('Name is required'); # error
				
			# Make URL friendly
			$url = trim($_POST['url']);
			$url = ( empty($url) ) ? $_POST['name'] : $url;
			$url = preg_replace("(\W)", '_', $url);
			
			$data = array(
				'url'		=> $url,
				'cat_id'	=> $_POST['category'],
				'name'		=> $_POST['name'],
				'intro'		=> $_POST['intro'],
				'body'		=> $_POST['body'],		
			);
			$old_image = Assets::dir_path("showroom/{$_POST['old_category']}/{$_POST['old_image']}");
		
			# Upload image if sent
			if(is_uploaded_file($_FILES['image']['name']))
			{
				if (! $data['img'] = self::upload_image($_FILES, $_POST['category']) )
					echo 'Image must be jpg, gif, or png.';
				
				if(! empty($_POST['old_image']) )
					if( file_exists($old_image) )
						unlink($old_image);
			}
			# If user has changed the category:
			elseif ($_POST['category'] != $_POST['old_category'])
			{
				$new_path = DOCROOT."data/$this->site_name/assets/images/showroom/{$_POST['category']}";
				if(! is_dir($new_path) )
					mkdir($new_path);
				
				$small_path = DOCROOT."data/$this->site_name/assets/images/showroom/{$_POST['old_category']}/sm_{$_POST['old_image']}";
				
				rename($old_image, "$new_path/{$_POST['old_image']}");
				rename($small_path, "$new_path/sm_{$_POST['old_image']}");
			
			}			

			$db->update(
				'showroom_items_meta',
				$data,
				"id = '$id' AND fk_site = '$this->site_id'"
			);	
			die('Item saved');
		}

		# Grab single item
		$item = $db->query("
			SELECT * FROM showroom_items_meta
			WHERE id = '$id' 
			AND fk_site = '$this->site_id'
		")->current();
		
		# If item exists & belongs to this site:
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
		die($primary);	
	}

/*
 * DELETE showroom (item) single
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of showroom item row 
 */
	public function delete($id=NULL)
	{
		valid::id_key($id);				
		$image = $this->_grab_tool_child('showroom', $id);

		# Image File delete		
		$image_path = Assets::dir_path("tools/showroom/$image->img");	
	
		if(! empty($image->image) AND file_exists($image_path) )
			unlink($image_path);
			
		# db delete
		$this->_delete_single_common('showroom', $id);
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
 * Upload an image to showroom
 * @Param array $file = $_FILES array
 */ 	
	private function upload_image($_FILES, $category_id)
	{		
		$files = new Validation($_FILES);
		$files->add_rules('image', 'upload::valid','upload::type[gif,jpg,png]', 'upload::size[1M]');
		
		if(!$files->validate())
			return FALSE;
			
		# Temp file name
		$filename	= upload::save('image');
		$image		= new Image($filename);			
		$ext		= $image->__get('ext');		
		$name		= text::random('alnum', 18).'.'.$ext;
		$image_store = Assets::dir_path("tools/showroom");
		
		if(! is_dir($image_store) )
			mkdir($image_store);
			
		if(! is_dir("$image_store/$category_id") )
			mkdir("$image_store/$category_id");	

		if(! is_dir("$image_store/$category_id/_sm") )
			mkdir("$image_store/$category_id/_sm");	
			
		if( $image->__get('width') > 350 )
			$image->resize(350, 650);
		
		$image->save("$image_store/$category_id/$name");
	  
		# Create sm thumb (TODO: optimize this later)
		$image_sm = new Image($filename);			
		
		$sm_width = $image_sm->__get('width');
		$sm_height = $image_sm->__get('height');
		
		# Make square thumbnails
		if( $sm_width > $sm_height )
			$image_sm->resize(100,100,Image::HEIGHT)->crop(100,100);
		else
			$image_sm->resize(100,100,Image::WIDTH)->crop(100,100);
		
		$image_sm->save("$image_store/$category_id/_sm/$name");
		
		# Remove temp file
		unlink($filename);
		
		return $name;
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
		
		# delete data assets
		$showroom_dir = Assets::dir_path("tools/showrooms/$tool_id");
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
		
		return TRUE;
	}
	
	
	
 
} /* -- end of application/controllers/showroom.php -- */