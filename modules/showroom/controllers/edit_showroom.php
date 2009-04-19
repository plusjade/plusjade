<?php

class Edit_Showroom_Controller extends Edit_Module_Controller {

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

		$embed_js ='
			// start simple tree mode
			$simpleTreeCollection = $(".facebox .simpleTree").simpleTree({
				autoclose: true,
				animate:true
			});
			
			// add delete icons
			$(".facebox li:not(.root)>span").after(" <img src=\"/images/navigation/cross.png\" class=\"li_delete\" alt=\"\">");
			
			// activate delete icons
			$(".facebox .li_delete").click(function(){
				$(this).parent().remove();	
			});
			
			
			// Gather and send nest data.
			$(".facebox #link_save_sort").click(function() {
				var output = "";
				var tool_id = $(this).attr("rel");
				
				$(".facebox #admin_category_wrapper ul").each(function(){
					var parentId = $(this).parent().attr("rel");
					if(!parentId) parentId = 0;
					var $kids = $(this).children("li:not(.root, .line, .line-last)");
					
					// Data set format: "id:local_parent_id:position#"
					$kids.each(function(i){
						output += $(this).attr("rel") + ":" + parentId + ":" + i + "#";
					});
				});
				
				//alert (output); return false;
				
				
				$.facebox(function() {
						$.post("/get/edit_showroom/category_sort/"+tool_id, {output: output}, function(data){
							$.facebox(data, "status_reload", "facebox_response");
							location.reload();
						})
					}, 
					"status_reload", 
					"facebox_response"
				);

				
			});		
		';
		$this->template->rootJS($embed_js);
		$this->template->primary = $primary;
		$primary->tool_id = $tool_id;
		echo $this->template;
		die();
	
		
		# Javascript Save sort
		$save_sort_js = tool_ui::js_save_sort_init('showroom');
		$this->template->rootJS($save_sort_js);
		
		# Javascript delete
		$delete_js = tool_ui::js_delete_init('showroom');
		$this->template->rootJS($delete_js);
		# Show the manage panel
		$this->_show_manage_module_items('showroom', $tool_id);
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
		
		$this->template->rootJS = '
			$("#admin_cat_dropdown").change(function(){
				val = $("option:selected", this).val();
				//alert(val);
				$("#load_box").load("get/edit_showroom/list_items/"+val);
			})
		
		
		';

		$this->template->primary = $primary;
		
		
		echo $this->template;
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
				$data = array(
					'parent_id'		=> $tool_id,
					'fk_site'		=> $this->site_id,
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
			# Get highest position
			$get_highest = $db->query("SELECT MAX(position) as highest 
				FROM showroom_items_meta 
				WHERE cat_id = '{$_POST['category']}'
			")->current();

			$data = array(			
				'fk_site'	=> $this->site_id,
				'cat_id'	=> $_POST['category'],
				'name'		=> $_POST['name'],
				'intro'		=> $_POST['intro'],
				'body'		=> $_POST['body'],
				'position'	=> ++$get_highest->highest,				
			);	

			# Upload image if sent
			if(! empty($_FILES['image']['name']) )
				if (! $data['img'] = $this->_upload_image($_FILES) )
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
				
				#Javascript
				$this->template->rootJS = '$("#tab_container").tabs()';
				
				echo $this->template;			
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

			$data = array(
				'name'	=> $_POST['name'],
				'intro'	=> $_POST['intro'],
				'body'	=> $_POST['body'],		
			);
			
			# Upload image if sent
			if(!empty($_FILES['image']['name']))
			{
				$image = DOCROOT."data/$this->site_name/assets/images/showroom/{$_POST['old_image']}";
		
				if (! $data['img'] = $this->_upload_image($_FILES) )
					echo 'Image must be jpg, gif, or png.';
				
				if(! empty($_POST['old_image']))
					unlink($image);
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
				# Javascript
				$this->template->rootJS = '$("#container-1").tabs()';			
		
				$primary->item = $item;
				$this->template->primary = $primary;
				echo $this->template;			
			}
			else
			{
				echo 'Bad id';
			}	
		

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
		$image = $this->_grab_module_child('showroom', $id);

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
			$this->_show_edit_settings('showroom', $tool_id);	
		}
		die();
	}
	
/*
 * Upload an image to showroom
 * @Param array $file = $_FILES array
 */ 	
	private function _upload_image($_FILES)
	{		
		$files = new Validation($_FILES);
		$files->add_rules('image', 'upload::valid','upload::type[gif,jpg,png]', 'upload::size[1M]');
		
		if ($files->validate())
		{
			# Temp file name
			$filename	= upload::save('image');
			$image		= new Image($filename);			
			$ext		= $image->__get('ext');
			$file_name	= basename($filename).'.'.$ext;
			$directory	= DOCROOT."data/{$this->site_name}/assets/images/showroom";			
			
			if(! is_dir($directory) )
				mkdir($directory);	
			
			if( $image->__get('width') > 350 )
				$image->resize(350, 650);
			
			$image->save("$directory/$file_name");
		 
			# Remove temp file
			unlink($filename);
			
			return $file_name;
		}
		else
			return FALSE;

		
	}
	
}

/* -- end of application/controllers/showroom.php -- */