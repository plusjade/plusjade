<?php

class Edit_Showroom_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for Showroom module.
 *	Extends the module template to build page for ajax rendering.
 *	Only Logged in users should have access
 *
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
		tool_ui::validate_id($tool_id);
		$db = new Database;
		$parent = $db->query("SELECT * FROM showrooms 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();			

		#show category list.
		$primary = new View("showroom/edit/manage_showroom");
		$items = $db->query("SELECT * FROM showroom_items 
			WHERE parent_id = '$parent->id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC 
		");		
		$primary->tree = Tree::display_tree('showroom', $items, TRUE);		
		$primary->tool_id = $tool_id;
		echo $primary;
		die();
	}

	
	function items($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		$primary = new View('showroom/edit/manage_items');
		$db = new Database;
		
		# Get list of categories
		$categories = $db->query("SELECT id, name FROM showroom_items
			WHERE parent_id = '$tool_id' AND fk_site = '$this->site_id'
			AND local_parent != '0'
			ORDER BY lft ASC
		");	
		$primary->categories = $categories;
		echo $primary;
		die();
	}

	function list_items($cat_id=NULL)
	{
		tool_ui::validate_id($cat_id);
		$db = new Database;
		$primary = new View('showroom/edit/list');
		
		#display items in this cat
		$items = $db->query("SELECT * FROM showroom_items_meta 
			WHERE cat_id = '$cat_id' AND fk_site = '$this->site_id'
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
/*
 * Save nested positions of the category menus
 * Can also delete any links removed from the list.
 * Gets output positions from this::manage
 */ 
	function category_sort($tool_id)
	{
		if($_POST)
		{
			tool_ui::validate_id($tool_id);
			echo Tree::save_tree('showrooms', 'showroom_items', $tool_id, $_POST['output']);
		}
		die();
	}	
	
/*
 * Add categories
 */ 
	public function add($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		
		if($_POST)
		{
			$db = new Database;
			# Get parent
			$parent	= $db->query("SELECT * FROM showrooms 
				WHERE id = '$tool_id' 
				AND fk_site = '$this->site_id' 
			")->current();
			
			foreach($_POST['category'] as $key => $category)
			{
				# Make URL friendly
				$url = trim($category);
				$url = strtolower($url);
				$url = preg_replace("(\W)", '_', $url);
			
				$data = array(
					'parent_id'		=> $tool_id,
					'fk_site'		=> $this->site_id,
					'url'			=> $url,
					'name'			=> $category,
					'local_parent'	=> $parent->root_id,
					'position'		=> '0'
				);	
				$db->insert('showroom_items', $data); 	
			}
			# Update left and right values
			Tree::rebuild_tree('showroom_items', $parent->root_id, '1');
			echo 'Categories added<br>Updating...'; #status message
			die();
			
		}
		else
		{
			$primary = new View('showroom/edit/new_category');
			$primary->tool_id = $tool_id;				
			echo $primary;
		}
		die();		
	}


/*
 * Add Item(s)
 */ 
	public function add_item($tool_id=NULL)
	{		
		tool_ui::validate_id($tool_id);
		$db = new Database;	
		if($_POST)
		{
			if( empty($_POST['name']) )
			{
				echo 'Name is required'; die(); # error
			}
			
			# Get highest position
			$get_highest = $db->query("SELECT MAX(position) as highest 
				FROM showroom_items_meta 
				WHERE cat_id = '{$_POST['category']}'
			")->current();
			
			# Make URL friendly
			$url = trim($_POST['url']);
			$url = ( empty($url) ) ? $_POST['name'] : $url;
			$url = preg_replace("(\W)", '_', $url);
			
			$data = array(			
				'fk_site'	=> $this->site_id,
				'url'		=> $url,
				'cat_id'	=> $_POST['category'],
				'name'		=> $_POST['name'],
				'intro'		=> $_POST['intro'],
				'body'		=> $_POST['body'],
				'position'	=> ++$get_highest->highest,				
			);	

			# Upload image if sent
			if(! empty($_FILES['image']['name']) )
				if (! $data['img'] = $this->_upload_image($_FILES, $_POST['category']) )
					echo 'Image must be jpg, gif, or png.';


			$db->insert('showroom_items_meta', $data);
			
			echo 'Item added'; #status message
		}
		else
		{
			# Get list of categories
			$categories = $db->query("SELECT id, name FROM showroom_items
				WHERE parent_id = '$tool_id' AND fk_site = '$this->site_id'
				AND local_parent != '0'
				ORDER BY lft ASC
			");
			
			# If categories
			if( count($categories) > 0)
			{
				$primary = new View("showroom/edit/new_item");
				$primary->tool_id = $tool_id;			
				$this->template->primary = $primary;
				$primary->categories = $categories;
				echo $primary;			
			}
			else
			{
				# add categories screen
				$primary = new View('showroom/edit/new_category');
				$primary->tool_id = $tool_id;				
				$primary->message = 'You will need to add some categories first.';
				echo $primary;
			}
		}
		die();		
	}
	
	
/*
 * Edit single Item
 */
	public function edit($id=NULL)
	{
		tool_ui::validate_id($id);
		$db = new Database;
			
		if($_POST)
		{
			if( empty($_POST['name']) )
			{
				echo 'Name is required'; # error
				die();
			}
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
		
			$old_image = DOCROOT."data/$this->site_name/assets/images/showroom/{$_POST['old_category']}/{$_POST['old_image']}";
		
			# Upload image if sent
			if(! empty($_FILES['image']['name']) )
			{
				if (! $data['img'] = $this->_upload_image($_FILES, $_POST['category']) )
					echo 'Image must be jpg, gif, or png.';
				
				if(! empty($_POST['old_image']) )
					if( file_exists($old_image) )
						unlink($old_image);
			}
			#If user has changed the category:
			elseif ($_POST['category'] != $_POST['old_category'])
			{
				$new_path = DOCROOT."data/$this->site_name/assets/images/showroom/{$_POST['category']}";
				if(! is_dir($new_path) )
					mkdir($new_path);
				
				$small_path = DOCROOT."data/$this->site_name/assets/images/showroom/{$_POST['old_category']}/sm_{$_POST['old_image']}";
				
				rename($old_image, "$new_path/{$_POST['old_image']}");
				rename($small_path, "$new_path/sm_{$_POST['old_image']}");
			
			}			

			$db->update('showroom_items_meta', $data, "id = '$id' AND fk_site = '$this->site_id'");
			
			echo 'Item saved!!<br>Updating...';

		}
		else
		{
			$primary = new View("showroom/edit/single_item");

			# Grab single item
			$item = $db->query("SELECT * FROM showroom_items_meta
				WHERE id = '$id' AND fk_site = '$this->site_id'
			")->current();
			
			# If item exists & belongs to this site:
			if(! empty($item) )
			{
				# Get list of categories
				$category = $db->query("SELECT id, name, parent_id FROM showroom_items
					WHERE id = '$item->cat_id' AND fk_site = '$this->site_id'
				")->current();
				
				$categories = $db->query("SELECT id, name FROM showroom_items
					WHERE parent_id = '$category->parent_id' AND fk_site = '$this->site_id'
					AND local_parent != '0'
					ORDER BY lft ASC
				");
				
				$primary->categories = $categories;	
				$primary->item = $item;
				echo $primary;			
			}
			else
				echo 'Bad id';
		}
		die();		
	}

/*
 * DELETE showroom (item) single
 * Success Response via inline JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of showroom item row 
 */
	public function delete($id=NULL)
	{
		tool_ui::validate_id($id);				
		# Get image object
		$image = $this->_grab_tool_child('showroom', $id);

		# Image File delete		
		$image_path = "$this->site_data_dir/assets/images/showroom/$image->img";	
	
		if(! empty($image->image) AND file_exists($image_path) )
			unlink($image_path);
			
		# db delete
		$this->_delete_single_common('showroom', $id);
		
		echo 'Item Deleted!<br>Updating...';
		die();
	}

/*
 * SAVE items sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		$this->_save_sort_common($_GET['showroom'], 'showroom_items');
		die();
	}
	
/*
 * SAVE showroom parent settings
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function settings($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		$db = new Database;
		
		if($_POST)
		{
			$data = array(
				'name'		=> $_POST['name'],
				'view'		=> $_POST['view'],
				'params'	=> $_POST['params'],
			);
			$db->update('showrooms', $data, " id = '$tool_id' AND fk_site = '{$this->site_id}' ");
			echo 'Showroom updated!!';		
		}
		else
		{
			$this->_view_edit_settings('showroom', $tool_id);	
		}
		die();
	}
	
/*
 * Upload an image to showroom
 * @Param array $file = $_FILES array
 */ 	
	private function _upload_image($_FILES, $category_id)
	{		
		$files = new Validation($_FILES);
		$files->add_rules('image', 'upload::valid','upload::type[gif,jpg,png]', 'upload::size[1M]');
		
		if ($files->validate())
		{
		
			# Name = unix timestamp_album_counter.extension 
			# with a counter appended. then finally the extension.
			$key=1;
			# Temp file name
			$filename	= upload::save('image');
			$image		= new Image($filename);			
			$ext		= $image->__get('ext');
			$name		= time()."$category_id".''."$key.$ext";
			$image_store	= DATAPATH ."$this->site_name/assets/images/showroom/$category_id";			
			
			
			if(! is_dir($image_store) )
				mkdir($image_store);	
			
			if( $image->__get('width') > 350 )
				$image->resize(350, 650);
			
			$image->save("$image_store/$name");
		  
			# Create sm thumb (TODO: optimize this later)
			$image_sm = new Image($filename);			
			
			$sm_width = $image_sm->__get('width');
			$sm_height = $image_sm->__get('height');
			
			# Make square thumbnails
			if( $sm_width > $sm_height )
				$image_sm->resize(200,200,Image::HEIGHT)->crop(200,200);
			else
				$image_sm->resize(200,200,Image::WIDTH)->crop(200,200);
			
			$image_sm->save("$image_store/sm_$name");
			
			# Remove temp file
			unlink($filename);
			
			return $name;
		}
		else
			return FALSE;
	}	
}

/* -- end of application/controllers/showroom.php -- */