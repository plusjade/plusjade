<?php
class Page_Controller extends Controller {

/**
 *	Provides CRUD for pages 
 *	
 */
	
	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}
/*
 * show file-structure view of all pages
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
			SELECT id, page_name, menu, enable
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
			#echo'<pre>';print_r($folders_array);echo'</pre>';die();
		
		# emulate file browsing interface
		ob_start();
		foreach($folders_array as $directory => $file_array)
		{
			$path_for_css = str_replace('/', '_', $directory, $count);
			
			echo "<div class=\"$path_for_css sub_folders\">";
			
			foreach($file_array as $filename => $data)
			{
				# parse filedata for page
				$file_data	= explode(':', $data);
				list($id, $menu, $enable) = $file_data;
				
				# is page on menu, hidden, or disabled?
				$visibility = 
					('no' == $enable) ? 'disabled' :
						( ('no' == $menu) ? 'hidden' : 'enabled' );					
				
				# full path to page
				$full_path = ('ROOT' == $directory)
					? $filename : "$directory/$filename";

				$vars = array(
					'id'			=> $id,
					'visibility'	=> $visibility,
					'is_folder'		=> FALSE,
					'is_protected'	=> FALSE,
					'full_path'		=> $full_path,
					'filename'		=> $filename,
				);
				
				# is page protected?
				if('ROOT' == $directory AND $builder = yaml::does_key_exist($this->site_name, 'pages_config', $filename) )
				{
					$builder = explode(':', $builder);
					$vars['is_protected'] = TRUE;
					$vars['page_builder'] = $builder['0'];
				}	
				
				# is page_name a folder? (has children)
				if(! empty($folders_array[$full_path]) )
					$vars['is_folder'] = TRUE;
				
				# display html
				echo View::factory('page/page_wrapper_html', array('vars' => $vars));
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
			$full_path = $filename = self::validate_page_name($_POST['label'], $_POST['page_name'], $directory);

			if('ROOT' != $directory)
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
				'template'	=> $_POST['template'],
				'position'	=> ++$max->highest,
			);
			if(! empty($_POST['menu']) AND 'yes' == $_POST['menu'])
				$data['menu'] = 'yes';
			
			# setup vars...
			$page_id = $db->insert('pages', $data)->insert_id();
			$visibility = ( empty($_POST['menu']) ) ? 'hidden' : 'enabled';		
			$vars = array(
				'id'			=> $page_id,
				'visibility'	=> $visibility,
				'is_folder'		=> FALSE,
				'is_protected'	=> FALSE,
				'full_path'		=> $full_path,
				'filename'		=> $filename,
			);

			/*
			# i only need this if enabling auto-add page builders to new pages..
			# is page protected?
			if('ROOT' == $directory AND $builder = yaml::does_key_exist($this->site_name, 'pages_config', $filename) )
			{
				$builder = explode(':', $builder);
				$vars['is_protected'] = TRUE;
				$vars['page_builder'] = $builder['0'];
			}
			*/
			
			# send html to javascript handler
			die( View::factory('page/page_wrapper_html', array('vars' => $vars)) );
		}

		# directory comes from all_pages javascript loader
		if(! isset($_GET['directory']) )
			die('no directory selected');
			
		$primary		= new View("page/new_page");
		$db				= new Database;
		$directory		= $_GET['directory'];
		$path_array		= explode('/', $directory);
		$primary->directory = $directory;
		
		# get available templates from theme.
		if($templates = yaml::parse($this->site_name, NULL, "themes/$this->theme/config"))
			$primary->templates = $templates;
		else
			$primary->templates = array('master' => 'default layout');

		
		# Javascript duplicatate_page name filter Validation
		# get page_name filter for this path
		$filter_array = self::get_filename_filter($directory);

		# convert filter_array to string to use as javascript array
		$filter_string = implode("','",$filter_array);
		$filter_string = "'$filter_string'";

		#echo'<pre>';print_r($filter_array);echo'</pre>';die();
		#echo $filter_string;die();
		$primary->filter = $filter_string;
		die($primary);

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
			$db->update('pages', array('position' => "$position"), "id = '$id' AND fk_site = '$this->site_id'"); 	
			
		die('Page Sort Order Saved'); # success
	}
	
/*
 * Save the Main menu order to db
 */		
	public function load_menu()
	{
		die( View::factory('_global/menu') );
	}

	
/*
 * DELETE single page from pages table
 * Note: does not delete any tools owned by this page.
 */
	function delete($page_id=NULL)
	{
		valid::id_key($page_id);
		$db = new Database;		
		# the page to be deleted.
		$page = $db->query("
			SELECT page_name
			FROM pages
			WHERE id='$page_id'
		")->current();
		
		if(! is_object($page))
			die('invalid page');
		
		$id_set = $page_id;
		

		# Get all pages to look for children.
		$pages_data = $db->query("
			SELECT id, page_name
			FROM pages
			WHERE fk_site = '$this->site_id'
			ORDER BY page_name
		");
		$page_name_array = array();
		$folders_array = array();
		
		# build page array
		foreach($pages_data as $each_page)
			$page_name_array[$each_page->page_name] = $each_page->id;
	
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

		# if this page contains children, we can delete them all.
		# OR just not allow pages with children to be deleted.
		if(array_key_exists($page->page_name, $folders_array))
			die('A page must have no sub-pages before it can be deleted.');
			#$id_set .= ','. implode(',', $folders_array[$page->page_name]);

		
		# if deleting a protected page
		yaml::delete_value($this->site_name, 'pages_config', $page->page_name);

		$db->query("
			DELETE FROM pages
			WHERE id IN ($id_set)
			AND fk_site = '$this->site_id'
		");
		die('Page deleted.'); # success			
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
			$full_path = $filename = self::validate_page_name($_POST['label'], $_POST['page_name'], $directory, $_POST['page_name']);

			if(! empty($directory) )
				$full_path = "$directory/$filename";
			
			# Update pages table
			$data = array(
				'page_name'	=> $full_path,
				'title'		=> $_POST['title'],
				'meta'		=> $_POST['meta'],
				'label'		=> $_POST['label'],
				'template'	=> $_POST['template'],
				'menu'		=> $_POST['menu'],
				'enable'	=> $_POST['enable'],
			);
			$db->update(
				'pages',
				$data,
				"id = '$page_id' AND fk_site = '$this->site_id'
			"); 			

			# if new page name & page is protected update the page_config file.
			if($filename != $_POST['old_page_name'])
				yaml::edit_key($this->site_name, 'pages_config', $_POST['old_page_name'], $filename );

			# if this page was the homepage, update homepage value
			if($this->homepage == $_POST['old_page_name'])
			{
				$db->update('sites', array('homepage' => $filename), "site_id = '$this->site_id'");
				yaml::edit_site_value($this->site_name, 'site_config', 'homepage', $filename );
			}

			
			die('Page Settings Saved'); # success				
		}

		$page = $db->query("
			SELECT *
			FROM pages
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


		# get available templates from theme.
		if($templates = yaml::parse($this->site_name, NULL, "themes/$this->theme/config"))
			$primary->templates = $templates;
		else
			$primary->templates = array('master' => 'default layout');
			
		# Javascript duplicate page_name filter Validation
		$filter_array = self::get_filename_filter($directory, $filename);

		# convert filter_array to string to use as javascript array
		$filter_string = implode("','",$filter_array);			
		$primary->page_filter_js = "'$filter_string'";
		
		
		# is page protected?
		$primary->is_protected = FALSE;
		if(yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
			$primary->is_protected = TRUE;
		
		die($primary);
	}


/*
 * Validate a page_name string when adding or updating a page_name
 * Checks:
	$post values exist and not empty
	sanitizes characters
	name is unique
 */	
	private function validate_page_name($label, $page_name, $directory='ROOT', $omit=NULL)
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
		$filter_array = self::get_filename_filter($directory, $omit);	
		if( in_array($page_name, $filter_array) )
			die('Page name already exists');

		return $page_name;
	}
	
/*
 * build filename filter array to protect current named pages-
 * -relative to the directory they belong to.
		concat "%%" to mark the start of the page_name
		avoides deeper nested duplicate file_structure matches
		ex: Match "country/city"
				1. country/city/state
				2. users/location/country/city/state		
		without "%%" #2 will match.
*/
	private function get_filename_filter($directory='ROOT', $omit=NULL)
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
			# Reserved page_names: add _assets, _data, index.php
			$filter_array[] = '_assets';
			$filter_array[] = '_data';
			$filter_array[] = 'index.php';
		}
		else
			foreach($pages as $page)
				if( preg_match("[%%$directory]", $page->page_name) )
				{
					$value = str_replace("%%$directory", '', $page->page_name);
					str_replace('/','_', $value, $count);
					if('1' == $count)
						$filter_array[] = ltrim($value, '/');
				}
		
		if($omit)
			foreach($filter_array as $key => $page_name)
				if($page_name == $omit)
					unset($filter_array[$key]);

		return $filter_array;			
		#echo'<pre>';print_r($filter_array);echo'</pre>';die();
	}
	
}
/* End of file page.php */