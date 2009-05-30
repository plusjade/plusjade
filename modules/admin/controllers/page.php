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
		**KEY: Variable logic used in this class
		------------------------------------------
		sample page_name : about/jade/skills
			full_path	= about/jade/skills
			directory	= about/jade
			filename	= skills
			
			! note directory contains no trailing slash
	 */
	function index()
	{
		$db			= new Database;				
		$primary	= new View("page/all_pages");		
		$pages_data = $db->query("
			SELECT id,page_name, menu, enable
			FROM pages
			WHERE fk_site = '$this->site_id'
			ORDER BY page_name
		");
		$page_name_array = array();
		$folders_array = array();
		
		# build the page array, insert all pertinent data.
		foreach($pages_data as $page)
			$page_name_array[$page->page_name] = "$page->id:$page->menu:$page->enable";

		# create array of all sub_directories	
		foreach($page_name_array as $full_path => $data)
		{
			$node_array	= explode('/',$full_path);
			$filename	= array_pop($node_array);
			$directory	= implode('/', $node_array);

			if( empty($directory) )
				$directory = 'ROOT';
			
			$folders_array[$directory][$filename] = $data;
		}	
		# troubleshooting...
			#echo'<pre>';print_r($page_name_array);echo'</pre>';
			#echo'<pre>';print_r($files_array);echo'</pre>';die();
		
			#---- 
		
		# emulate file browsing interface
		ob_start();
		foreach($folders_array as $directory => $file_array)
		{
			$path_for_css = str_replace('/','_',$directory, $count);
			
			echo '<div class="' .$path_for_css. ' sub_folders">';
			
			foreach($file_array as $filename => $data)
			{
				$file_data	= explode(':', $data);
				list($id, $menu, $enable) = $file_data;
				$visibility = 
					('no' == $enable) ? 'disabled' :
						( ('no' == $menu) ? 'hidden' : 'enabled' );					

				$full_path = ('ROOT' == $directory)
					? $filename : "$directory/$filename";
					
				$protected_page	= '';
				$folderize = '<img src="'.url::image_path('admin/folder_add.png').'" alt="" class="folderize" id="'.$id.'" rel="'.$full_path.'">';
				
				if('ROOT' == $directory AND $builder = yaml::does_key_exist($this->site_name, 'pages_config', $filename) )
				{
					$builder = explode(':',$builder);
					$protected_page = '<img src="'.url::image_path('admin/shield.png').'" title="'.$builder['0'].'" alt="">';
					$folderize = '';
				}	
				?>
				<div id='page_wrapper_<?php echo $id?>' class="<?php echo $visibility?> asset">
					<?php
					if(! empty($folders_array[$full_path]) )
					{
						$folderize='';
						?>
						<div class="folder_bar">
							<a href="/<?php echo $full_path?>" class="open_folder" rel="<?php echo $full_path?>">
								<img src="<?php echo url::image_path('admin/folder.png')?>" class="open_folder" rel="<?php echo $full_path?>" alt="" >
							</a>
						</div>
						<?php 
					}
					?>
					<div class="page_bar">
						<div><?php echo $protected_page?></div>
						<div><a href="<?php echo url::site($full_path)?>" class="" title="Go to Page: <?php echo url::site($full_path)?>"><img src="<?php echo url::image_path('admin/magnifier.png')?>" alt=""></a></div>
						<div><a href="/get/page/settings/<?php echo $id?>" title="Page Settings"><img src="<?php echo url::image_path('admin/cog_edit.png')?>" alt="" class="img_facebox"></a></div>
						<div><?php echo $folderize?></div>
						<div><a href="/get/page/delete/<?php echo $id?>" id="<?php echo $id?>" title="Delete Page"><img src="<?php echo url::image_path('admin/delete.png')?>" class="delete_page" alt=""></a></div>
					</div>

					<div class="page_icon">
						<img src="<?php echo url::image_path('admin/page.png')?>" alt="">
						<?php echo $filename?>
					</div>
					
				</div>
				<?php
			}
			echo '</div>';
		}
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
			$directory = ( empty($_POST['directory']) ) ? 'ROOT' : $_POST['directory']; 
			
			# Validate page_name & duplicate check
			$full_path = $filename = self::_validate_page_name($_POST['label'], $_POST['page_name'], $directory);

			if(! empty($directory) )
				$full_path = "$directory/$filename";
			
			$db = new Database;			
			$max = $db->query("
				SELECT MAX(position) as highest 
				FROM pages WHERE fk_site = '$this->site_id'
			")->current();			
		
			# Add to pages table
			$data = array(
				'fk_site'	=> $this->site_id,
				'page_name'	=> $full_path,
				'label'		=> $_POST['label'],
				'position'	=> ++$max->highest,
			);
			if(! empty($_POST['menu']) )
				$data['menu'] = 'yes';
			
			$page_id = $db->insert('pages', $data)->insert_id();

			# outputing html here is easier to maintain as of now ...
			$page_access = ( empty($_POST['menu']) ) ? 'hidden' : 'enabled';
			?>
			<div id='page_icon_<?php echo $page_id?>' class="<?php echo $page_access?> asset">
				<div class="page_bar">
					<a href="<?php echo url::site($full_path)?>" class="" title="Go to Page: <?php echo url::site($full_path)?>"><img src="<?php echo url::image_path('admin/magnifier.png')?>" alt=""></a>
					<a href="/get/page/settings/<?php echo $page_id?>" title="Page Settings"><img src="<?php echo url::image_path('admin/cog_edit.png')?>" alt="" class="img_facebox"></a>
					<img src="<?php echo url::image_path('admin/folder_add.png')?>" alt="" class="folderize" rel="<?php echo $full_path?>">
		
					<a href="/get/page/delete/<?php echo $page_id?>" id="<?php echo $page_id?>" title="Delete Page"><img src="<?php echo url::image_path('admin/delete.png')?>" class="delete_page" alt=""></a>
				</div>

				<div class="page_icon">
					<img src="<?php echo url::image_path('admin/page.png')?>" alt="">
					<?php echo $filename?>
				</div>
				
			</div>			
			<?php
			die();
		}
		else
		{
			# directory comes from all_pages javascript loader
			if(! isset($_GET['directory']) )
				die('no directory selected');
				
			$primary		= new View("page/new_page");
			$db				= new Database;
			$directory	= $_GET['directory'];
			$path_array		= explode('/', $directory);
			$primary->directory = $directory;
			
			
			# if the path is root...
			if( empty($directory) )
			{
				$page_builders = $db->query("
					SELECT * FROM tools_list WHERE protected = 'yes'
				");
				$primary->page_builders = $page_builders;
				
				$directory = 'ROOT';
			}
			
			# Javascript duplicatate_page name filter Validation
			# -------------------------------
			# get page_name filter for this path
			$filter_array = self::_get_filename_filter($directory);

			# convert filter_array to string to use as javascript array
			$filter_string = implode("','",$filter_array);
			$filter_string = "'$filter_string'";

			#echo'<pre>';print_r($filter_array);echo'</pre>';die();
			#echo $filter_string;die();
			$primary->filter = $filter_string;
			die($primary);
		}
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
			# Validate page_name & duplicate check
			$directory = ( empty($_POST['directory']) ) ? NULL : $_POST['directory']; 
			$full_path = $filename = self::_validate_page_name($_POST['label'], $_POST['page_name'], $directory, $_POST['page_name']);

			if(! empty($directory) )
				$full_path = "$directory/$filename";
			
			# Update pages table
			$data = array(
				'page_name'	=> $full_path,
				'title'		=> $_POST['title'],
				'meta'		=> $_POST['meta'],
				'label'		=> $_POST['label'],
				'menu'		=> $_POST['menu'],
				'enable'	=> $_POST['enable'],
			);
			$db->update('pages', $data, "id = '$page_id' AND fk_site = '$this->site_id' "); 			

			# if new page name & page is protected update the page_config file.
			if($filename != $_POST['old_page_name'])
			{
				yaml::edit_value($this->site_name, 'pages_config', $_POST['old_page_name'], $filename );
			}
			
			die('Changes Saved!<br>Updating...'); # success				
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
			$filename	= $page->page_name;
			$directory	= '';
			$directory_array = explode('/',$filename);
			
			if( 1 < count($directory_array) )
			{
				$filename	=  array_pop($directory_array);
				$directory	= implode('/', $directory_array);
			}
			$primary->filename	= $filename;	
			$primary->directory	= $directory;				
			
			# Javascript dup page_name filter Validation
			$filter_array = self::_get_filename_filter($directory, $filename);

			# convert filter_array to string to use as javascript array
			$filter_string = implode("','",$filter_array);			
			$primary->page_filter_js = "'$filter_string'";
			
			
			# is page protected?
			$primary->is_protected = FALSE;
			if(yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
				$primary->is_protected = TRUE;
			
			die($primary);
		}
	}


/*
 * Validate a page_name string when adding or updating a page_name
 * Checks:
	$post values exist and not empty
	sanitizes characters
	name is unique
 */	
	function _validate_page_name($label, $page_name, $directory='ROOT', $omit=NULL)
	{
		$label = trim($label);
		if( empty($label) )
			die('Name is required'); #error	
		
		$page_name = trim($page_name);
		if( empty($page_name) )
			$page_name = strtolower($label);

		# Sanitize page_name
		$page_name = valid::filter_php_url($page_name);

		# Validate Unique Page_name relative to page directory		
		$filter_array = self::_get_filename_filter($directory, $omit);	
		if( in_array($page_name, $filter_array) )
			die('Page name already exists');

		return $page_name;
	}
		
/*
 * build filename filter array to protect current named pages
 * relative to the directory they belong to.
		concat "%%" to mark the start of the page_name
		avoides deeper nested duplicate file_structure matches
		ex: Match "country/city"
				1. country/city/state
				2. users/location/country/city/state		
		without "%%" #2 will match.
*/
	function _get_filename_filter($directory='ROOT', $omit=NULL)
	{
		$db = new Database;
		$directory = ( empty($directory) ) ? 'ROOT' : $directory;
		$filter_array = array();
		$pages = $db->query("
			SELECT CONCAT('%%', page_name) as page_name
			FROM pages
			WHERE fk_site = '$this->site_id'
		");	
	
		if('ROOT' == $directory)
		{
			foreach($pages as $page)
			{
				str_replace('/','_', $page->page_name, $count);
				if('0' == $count)
					$filter_array[] = ltrim($page->page_name, '%');
			}
		}
		else
		{
			foreach($pages as $page)
			{
				if( preg_match("[%%$directory]", $page->page_name) )
				{
					$value = str_replace("%%$directory", '', $page->page_name);
					str_replace('/','_', $value, $count);
					if('1' == $count)
						$filter_array[] = ltrim($value, '/');
				}
			}
		}
		
		if($omit)
			foreach($filter_array as $key => $page_name)
				if($page_name == $omit)
					unset($filter_array[$key]);

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


	
}
/* End of file page.php */