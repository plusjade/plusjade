<?php
class Page_Controller extends Admin_Controller {

	/**
	 *	Provides CRUD for pages 
	 *	
	 */
	
	function __construct()
	{
		parent::__construct();
		$this->client->can_edit($this->site_id);
	}
	

	/*
	 * show file structure view of all pages
	 *
	 */
	function index()
	{
		$db			= new Database;				
		$primary	= new View("page/all_pages");		
		$pages = $db->query("
			SELECT id,page_name, menu, enable
			FROM pages
			WHERE fk_site = '$this->site_id'
		");
		$files_array = self::_create_file_structure($pages);
	
		# emulate file browsing interface
		function show_files($files_array, $breadcrumb=null)
		{
			$counter = 3; # number of file columns to display
			foreach($files_array as $directory => $data)
			{
				if( is_array($data) )
				{
					$path_for_css = str_replace('/','_',$directory, $count);
					if(0 < $count )
					{
						$filename = strrchr($directory, '/');
						$filename = trim($filename, '/');
					}
					else
						$filename = $directory;
				
					$file_data = explode(':', $files_array[":$filename"]);
					list($id, $menu, $enable) = $file_data;
					
					?>
					<div class="enabled asset">
						<div class="top_icons folder_bar">
							<img src="<?php echo url::image_path('admin/folder.png')?>" alt="" class="floatleft">
							<a href="/<?php echo $directory?>" rel="<?php echo $directory?>" class="open_folder">open</a>
						</div>
				
						<div class="top_icons page_bar">
							<img src="<?php echo url::image_path('admin/page.png')?>" class="asset_type_icon" alt="">
							<a href="<?php echo url::site("$breadcrumb/$filename")?>" class="" title="Go to Page"><img src="<?php echo url::image_path('admin/magnifier.png')?>" alt=""></a>
							<a href="/get/page/settings/<?php echo $id?>" title="Page Settings"><img src="<?php echo url::image_path('admin/cog_edit.png')?>" alt="" class="img_facebox"></a>
							<a href="/get/page/delete/<?php echo $id?>" id="<?php echo $id?>" title="Delete Page"><img src="<?php echo  url::image_path('admin/delete.png')?>" class="delete_page" alt=""></a>
						</div>						
	
						<div class="filename_wrapper">
							<?php echo $filename?>
						</div>
						
					</div>
					
					<div class="<?php echo $path_for_css?> sub_folders">
						<?php echo show_files($data, $directory)?>
					</div>
					<?php 
				}
				else
				{
					if( FALSE === strpos($directory, ':') )
					{
						$data = explode(':', $data);
						list($id, $menu, $enable) = $data;
						$visibility = 
							('no' == $enable) ? 'disabled' :
								( ('no' == $menu) ? 'hidden' : 'enabled' );
							
						$folder_path = $directory;	
						if(NULL != $breadcrumb)
							$folder_path = "$breadcrumb/$directory";
						
						$folder_path = str_replace('/','_', $folder_path);
						?>
						<div id='page_icon_<?php echo $id?>' class="<?php echo $visibility?> asset">
							<div class="top_icons page_bar">
								<img src="<?php echo url::image_path('admin/page.png')?>" class="asset_type_icon" alt="">
								<a href="<?php echo url::site("$breadcrumb/$directory")?>" class="" title="Go to Page"><img src="<?php echo url::image_path('admin/magnifier.png')?>" alt=""></a>
								<a href="/get/page/settings/<?php echo $id?>" title="Page Settings"><img src="<?php echo url::image_path('admin/cog_edit.png')?>" alt="" class="img_facebox"></a>
								<a href="/get/page/folderize/<?php echo $id?>" title="Make Folder"><img src="<?php echo url::image_path('admin/folder_add.png')?>" alt="" class="folderize" rel="<?php echo $folder_path?>"></a>
								<a href="/get/page/delete/<?php echo $id?>" id="<?php echo $id?>" title="Delete Page"><img src="<?php echo url::image_path('admin/delete.png')?>" class="delete_page" alt=""></a>
							</div>
							<div class="filename_wrapper"><?php echo $directory?></div>
						</div>
						<?php
					}
				}
				++$counter;
				if( 0 == $counter%3 )
					echo '<div class="clearboth"></div>';
			}
		}
		ob_start();
		show_files($files_array);
		$primary->files_structure = ob_get_clean();
		
		die($primary);
	}

	
/*
 * add a new page to site
 */
	function add()
	{
		if($_POST)
		{
			die('00:name:enabled');
			$label = trim($_POST['label']);
			if( empty($label) )
				die('Name is required'); #error	

			$db = new Database;
			
			# Sanitize page_name
			$page_name = trim($_POST['page_name']);
			if( empty($page_name) )
				$page_name = strtolower($label);

			$page_name = valid::filter_php_url($page_name);

			/* 
			 * Server Validate Unique Page_name
			 * relative to page directory
			 */
			
			# Is this a root or sub_page?
			if( empty($_POST['sub_page']) )
			{
				$filter_array = self::_get_page_name_filter('ROOT');
				$page_name_node = $page_name;
			}
			else
			{
				$filter_array = self::_get_page_name_filter($_POST['sub_page']);				
				$page_name_node = $page_name;
				$page_name = $_POST['sub_page']."/$page_name";
			}
			
			if( in_array($page_name_node, $filter_array) )
				die('Page name already exists');			


			# not edited below this point
			# ---------------------------------
			$max = $db->query("
				SELECT MAX(position) as highest 
				FROM pages WHERE fk_site = '$this->site_id'
			")->current();			
		
			# Add to pages table
			$data = array(
				'fk_site'	=> $this->site_id,
				'page_name'	=> $page_name,
				'label'		=> $_POST['label'],
				'position'	=> ++$max->highest,
			);
			if(! empty($_POST['menu']) )
				$data['menu'] = 'yes';
			
			$page_id = $db->insert('pages', $data)->insert_id();
			
			
			# is a page_builder submitted?
			# page builders cannot be on sub_pages
			if(empty($_POST['sub_page']) AND !empty($_POST['page_builder']) AND '0' != $_POST['page_builder'])
			{
				$tools_id = $_POST['page_builder'];
				# GET tool name
				$tool = $db->query("
					SELECT name FROM tools_list WHERE id='$tools_id'
				")->current();
				$tool_name = strtolower($tool->name);

				# INSERT row in tool parent table
				$data = array(
					'fk_site'	=> $this->site_id
				);			
				$tool_id = $db->insert("{$tool_name}s", $data)->insert_id();
				
				# INSERT pages_tools row inserting tool parent id
				$data = array(
					'page_id'	=> $page_id,
					'fk_site'	=> $this->site_id,
					'tool'		=> $tools_id,
					'tool_id'	=> $tool_id,
					'position'	=> 1
				);
				$db->insert('pages_tools', $data);
				
				Load_Tool::after_add($tool->name, $tool_id );
				
				# this tool is protected so add page to pages_config.yaml
				# and update pages row
				$newline = "\n$page_name:$tool_name:$tool_id,\n";
				yaml::add_value($this->site_name, 'pages_config', $newline);
				$db->update('pages', array('protected' => "$tool_name:$tool_id"), array('id' => $page_id));
			}
			
			# pass new page data...
			$page_access = ( empty($_POST['menu']) ) ? 'hidden' : 'enabled';
			echo "$page_id:$page_name_node:$page_access";
		}
		else
		{
			# path_string comes from all_pages javascript loader
			if(! isset($_GET['path_string']) )
				die('no directory selected');
				
			$primary		= new View("page/new_page");
			$db				= new Database;
			$path_string	= $_GET['path_string'];
			$path_array		= explode('/', $path_string);
			$primary->path_string = $path_string;
			
			
			# if the path is root...
			if( empty($path_string) )
			{
				$page_builders = $db->query("
					SELECT * FROM tools_list WHERE protected = 'yes'
				");
				$primary->page_builders = $page_builders;
				
				$path_string = 'ROOT';
			}
			
			# Javascript duplicatate_page name filter Validation
			# -------------------------------
			# get page_name filter for this path
			$filter_array = self::_get_page_name_filter($path_string);

			# convert filter_array to string to use as javascript array
			$filter_string = implode("','",$filter_array);
			$filter_string = "'$filter_string'";

			#echo'<pre>';print_r($filter_array);echo'</pre>';die();
			#echo $filter_string;die();
			$primary->filter = $filter_string;
			die($primary);
		}
		die();

	}

	
	
/*
 * build a page_name filter to protect current named pages
 * relative to the directory they belong to.
 */
		/* 
		concat "%%" to mark the start of the page_name
		avoides deeper nested duplicate file_structure matches
		ex: Match "country/city"
				1. country/city/state
				2. users/location/country/city/state
				
				we don't want 2 to match but it will...
		*/
	function _get_page_name_filter($path_string)
	{
		$db = new Database;
		$filter_array = array();
		$pages = $db->query("
			SELECT CONCAT('%%',page_name) as page_name
			FROM pages
			WHERE fk_site = '$this->site_id'
		");	

		if('ROOT' == $path_string)
		{
			foreach($pages as $page)
			{
				str_replace('/','_', $page->page_name, $count);
				if('0' == $count)
					$filter_array[] = ltrim($page->page_name, '%');
			}
			return $filter_array;
		}
		
		foreach($pages as $page)
		{
			if( preg_match("[%%$path_string]", $page->page_name) )
			{
				$value = str_replace("%%$path_string",'',$page->page_name);
				str_replace('/','_', $value, $count);
				if('1' == $count)
					$filter_array[] = ltrim($value, '/');
			}
		}
		return $filter_array;
		
		#echo'<pre>';print_r($filter_array);echo'</pre>';die();
	}


	
	
/*
	NOT USING
	but still some good logic to refer to maybe?
*/
	function get_sub_filter($main_array, $full_path, $pointer=0)
	{
		$path_array		= explode('/', $full_path);
		$count			= (count($path_array)-1);
		$node			= $path_array[$pointer];
		$current_path	= substr("$full_path",0,strpos($full_path,$node)+strlen($node));
		
		if( $count == $pointer )
		{
			$filter = '';
			foreach($main_array[$full_path] as $name)
				if(! is_array($name) )
					$filter .= "'$name',";
	
			$filter = trim($filter, ',');
			return $filter;
			
		}
		else
			get_sub_filter($main_array[$current_path], $full_path, ++$pointer);
	}

	
	/*
	 * param $page_date (object) as of now
	 */
	static function _create_file_structure($page_data)
	{
		# build the page array, insert all pertinent data.
		$page_name_array = array();

		foreach($page_data as $page)
			$page_name_array[$page->page_name] = "$page->id:$page->menu:$page->enable";

		#sort the page_name array by most sub_directories to least.
		function cmp($a, $b)
		{
			str_replace('/','_', $a, $count_a);
			str_replace('/','_', $b, $count_b);
			if ($count_a == $count_b)
				return 0;

			return ($count_a < $count_b) ? 1 : -1;
		}
		uksort($page_name_array, 'cmp');
		
		# create an associative array to model the nested directories
		$files_array = array();	
		foreach($page_name_array as $name => $data)
		{
			$node_array	= explode('/',$name);
			$count		= count($node_array);
			$last_node	= array_pop($node_array);
			@list($one, $two, $three, $four) = $node_array;	
			
			# this is rudimentary, but it works...
			switch($count)
			{
				case '5':
					if( empty($files_array[$one]["$one/$two"]["$one/$two/$three"]["$one/$two/$three/$four"]["$one/$two/$three/$four/$last_node"]) )
						$files_array[$one]["$one/$two"]["$one/$two/$three"]["$one/$two/$three/$four"][$last_node] = $data;
					else
						$files_array[$one]["$one/$two"]["$one/$two/$three"]["$one/$two/$three/$four"]["page_$last_node"] = $data;
					break;
				case '4':
					if( empty($files_array[$one]["$one/$two"]["$one/$two/$three"]["$one/$two/$three/$last_node"]) )
						$files_array[$one]["$one/$two"]["$one/$two/$three"][$last_node] = $data;
					else
						$files_array[$one]["$one/$two"]["$one/$two/$three"][":$last_node"] = $data;
					break;	
				case '3':
					if( empty($files_array[$one]["$one/$two"]["$one/$two/$last_node"]) )
						$files_array[$one]["$one/$two"][$last_node] = $data;
					else
						$files_array[$one]["$one/$two"][":$last_node"] = $data;
					break;		
				case '2':
					if( empty($files_array[$one]["$one/$last_node"]) )
						$files_array[$one][$last_node] = $data;
					else
						$files_array[$one][":$last_node"] = $data;
					break;		
				case '1':
					if( empty($files_array[$last_node]) )
						$files_array[$last_node] = $data;
					else
						$files_array[":$last_node"] = $data;
					break;
			}			
		}	

		# TODO: 
			# tag protected pages ...
			# $primary->protected_pages = yaml::parse($this->site_name, 'pages_config');
			
		# troubleshooting...
		#echo'<pre>';print_r($page_name_array);echo'</pre>';
		#echo'<pre>';print_r($files_array);echo'</pre>';die();
		return $files_array;
	}
	
	
/*
 * Sort the Main Menu links
 */
	function navigation()
	{		
		$db			= new Database;				
		$primary	= new View("page/navigation");
		
		$pages = $db->query("
			SELECT * FROM pages 
			WHERE fk_site = '$this->site_id'
			AND menu = 'yes'
			ORDER BY position
		");		
		$primary->pages = $pages;
		$primary->protected_pages = yaml::parse($this->site_name, 'pages_config');
		die($primary);
	}

/*
 * Save the Main menu order to db
 */		
	public function save_sort()
	{
		$db = new Database;
		foreach($_GET['page'] as $position => $id)
			$db->update('pages', array('position' => "$position"), "id = '$id'"); 	
			
		die('Sort Order Saved!'); # status response	
	}
	
	
/*
 * DELETE single page from pages table
 * Note: does not delete any tools owned by this page.
 */
	function delete($page_id=NULL)
	{
		die('test delete');
		valid::id_key($page_id);
		$db = new Database;		
		$page = $db->query("
			SELECT page_name FROM pages WHERE id='$page_id'
		")->current();

		# if deleting a protected page
		yaml::delete_value($this->site_name, 'pages_config', $page->page_name);
		
		$data = array(
			'id'		=> $page_id,
			'fk_site'	=> $this->site_id,		
		);
		$db->delete('pages', $data);
		
		die('Page deleted!!'); # success			
	}

/*
 * Save the tool positions/containers, and local/global scope on the page
 * the posts happens via ajax in the public/assets/js/admin/init.js file
 * invoked via id="get_tool_sort" link (now as callback for tool sortable js)
 */
	function tools($page_id=NULL)
	{
		valid::id_key($page_id);		
		
		if($_POST)
		{
			#echo '<PRE>';print_r($_POST);echo '</PRE>'; die();
			$db = new Database;
			$output = rtrim($_POST['output'], '#');	
			$output = explode('#', $output);
			
			if( empty($output['0']) )
				die('There are no tools to sort');
	
			# hash format "scope.guid_id.container.position"
			foreach($output as $hash)
			{
				$pieces	= explode('.', $hash);
				
				# Update the rows
				$guid 				= strstr($pieces['1'], '_');
				$guid 				= ltrim($guid, '_');
				$data['position']	= $pieces['3'];			
				$data['page_id']	= $page_id;
				$data['container']	= $pieces['2'];	
				if( 'global' == $pieces['0'] )
				{
					$data['page_id']	= $pieces['2'];
					$data['container']	= $pieces['2'];
				}
				$db->update('pages_tools', $data, "guid = '$guid' AND fk_site = '$this->site_id'");								
			}	
			echo 'Order Updated!';
		}
		die();
	}
	
/*
 * Configure page settings	
 */ 
	function settings($page_id=NULL)
	{
		valid::id_key($page_id);
		$db = new Database;

		if($_POST)
		{
			/*
			
				TODO: 
				validate page name for cases of root and subpages
			
			*/
			$label = trim($_POST['label']);
			if(empty($label) )
				die('Label is required'); #error	
			
			$page_name = trim($_POST['page_name']);
			if( empty($page_name) )
				$page_name = $label;
			
			$page_name = valid::filter_php_url($page_name);

			/* Make sure page name is unique
			 * TODO: consider adding a javascript signifer that validates
			 * the javascript validation so we can bypass server validation?? 0.o
			 */
			$page_names = $db->query("
				SELECT GROUP_CONCAT( page_name separator ',') as name_string
				FROM pages
				WHERE fk_site = '$this->site_id'
				AND id != '$page_id'
			")->current();		
			$name_array = explode(',', $page_names->name_string);		
			if( in_array($page_name, $name_array) )
				die('Page name already exists');
				
			# if new page name & page is protected update the page_config file.
			if($page_name != $_POST['old_page_name'])
			{
				yaml::edit_value($this->site_name, 'pages_config', $_POST['old_page_name'], $page_name );
			}
			
			# Update pages table
			$data = array(
				'page_name'	=> $page_name,
				'title'		=> $_POST['title'],
				'meta'		=> $_POST['meta'],
				'label'		=> $_POST['label'],
				'menu'		=> $_POST['menu'],
				'enable'	=> $_POST['enable'],
			);
			$db->update('pages', $data, "id = '$page_id' AND fk_site = '$this->site_id' "); 			

			echo 'Changes Saved!<br>Updating...'; # success				
		}
		else
		{
			$page = $db->query("
				SELECT * FROM pages
				WHERE id = '$page_id' 
				AND fk_site = '$this->site_id'
			")->current();

			if(! is_object($page) )
				die('Page not found'); # error

			$primary = new View("page/page_settings");	
			$primary->page = $page;	
			
			# Is this a subpage?
			$page_name	= $page->page_name;
			$sub_page	= '';
			$page_directories = explode('/',$page_name);
			
			if( 1 < count($page_directories) )
			{
				$page_name	=  array_pop($page_directories);
				$sub_page	= implode('/', $page_directories).'/';
			}
			$primary->page_name	= $page_name;	
			$primary->sub_page	= $sub_page;				
			
			
			/*
			 * Send all site page_names except this name, in javascript formatted array, 
			 * so the validator can check for duplicates.
			 */
			$page_names = $db->query("
				SELECT GROUP_CONCAT( CONCAT('\'',page_name,'\'') separator ',') as name_string
				FROM pages
				WHERE fk_site = '$this->site_id'
				AND id != '$page->id'
			")->current();
			$primary->page_names = $page_names->name_string;
			

			# is page protected?
			$primary->is_protected = FALSE;
			if(yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
				$primary->is_protected = TRUE;
			
			echo $primary;
		}
		die();
	}
	
}
/* End of file page.php */