<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Functions for interaction/manipulation of "pages" in the +jade system.
 *	
 */	
 
class Page_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}
	
/*
 * show file-structure view of all pages
	**KEY: Variable logic used in this class
	------------------------------------------
	sample page_name : about/jade/skills
		full_path	= about/jade/skills
		directory	= about/jade
		filename	= skills
		NOTE: directory contains no trailing slash
 */
 
	public function index()
	{				
		$page_builders = ORM::factory('system_tool')
			->where(array(
				'protected'	=> 'yes',
				'enabled'	=> 'yes',
				'visible'	=> 'yes'
			))
			->find_all();
		
		# get the pages list.
		ob_start();
		$this->list_all();
		
		$primary = new View("page/index");		
		$primary->files_structure	= ob_get_clean();
		$primary->page_builders		= $page_builders;
		die($primary);
	}
	
/*
 * Outputs all site pages into organized folders and sub-folders.
 */ 
	public function list_all()
	{
		$pages_data = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->orderby('page_name')
			->find_all();

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

			if(empty($directory))
				$directory = 'ROOT';
			
			$folders_array[$directory][$filename] = $data;
		}	

		# emulate file browsing interface
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
					? $filename 
					: "$directory/$filename";

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
				if(!empty($folders_array[$full_path]))
					$vars['is_folder'] = TRUE;
				
				# display html
				echo View::factory('page/page_wrapper_html', array('vars' => $vars));
			}
			echo '</div>';
		}		
		echo '<script type="text/javascript">$("div.ROOT").show();</script>';
	}

/*
 * add a new page to site
 */
	public function add()
	{
		if($_POST)
		{
			# Validate page_name & duplicate check
			$directory = (empty($_POST['directory'])) ? 'ROOT' : $_POST['directory']; 		
			$full_path = $filename = self::validate_page_name($_POST['label'], $_POST['page_name'], $directory);

			if('ROOT' != $directory)
				$full_path = "$directory/$filename";

			$max = ORM::factory('page')
				->select('MAX(position) as highest')
				->where('fk_site', $this->site_id)
				->find();	

			$new_page = ORM::factory('page');
			$new_page->fk_site		= $this->site_id;
			$new_page->page_name	= $full_path;
			$new_page->label		= $_POST['label'];
			$new_page->template		= $_POST['template'];
			$new_page->position		= ++$max->highest;
			if(! empty($_POST['menu']) AND 'yes' == $_POST['menu'])
				$new_page->menu	= 'yes';
				
			$new_page->save();

			# setup vars...
			$visibility = (empty($_POST['menu'])) ? 'hidden' : 'enabled';		
			$vars = array(
				'id'			=> $new_page->id,
				'visibility'	=> $visibility,
				'is_folder'		=> FALSE,
				'is_protected'	=> FALSE,
				'full_path'		=> $full_path,
				'filename'		=> $filename,
			);

			# send success html to javascript handler
			die(View::factory('page/page_wrapper_html', array('vars' => $vars)));
		}

		
		
		# directory comes from pages browser via js
		if(! isset($_GET['directory']) )
			die('no directory selected');
			
		$primary		= new View("page/new_page");
		$directory		= $_GET['directory'];
		$path_array		= explode('/', $directory);
		$primary->directory = $directory;
		
		# get available templates from theme.
		if($templates = yaml::parse($this->site_name, NULL, "themes/$this->theme/config"))
			$primary->templates = $templates;
		else
			$primary->templates = array('master' => 'default layout');

		
		# Javascript duplicatate_page name filter Validation
		# convert filter_array to string to use as javascript array
		$filter_array	= self::get_folder_filenames($directory);
		$filter_string	= implode("','",$filter_array);
		
		$primary->filter = "'$filter_string'";
		die($primary);
	}


/*
 * View and handler for adding a new page with a page_builder pre-installed.
 */
	public function add_builder()
	{
		# which protected tool?
		if(empty($_GET['system_tool']))
			die('invalid system tool');	
		$system_tool_id = valid::id_key($_GET['system_tool']);
		
		# get the system tool.
		$system_tool = ORM::factory('system_tool')
			->select('*, LOWER(name) AS name')
			->where(array(
				'enabled' => 'yes',
				'visible' => 'yes', # this protects account from being added twice.
			))
			->find($system_tool_id);
		if(!$system_tool->loaded)
			die('invalid system tool.');

		$toolname = valid::filter_php_filename($system_tool->name);
		
		if($_POST)
		{
			# Validate page_name & duplicate check
			$filename = self::validate_page_name($_POST['label'], $_POST['page_name'], 'ROOT');
			
			# create a new page.
			$max = ORM::factory('page')
				->select('MAX(position) as highest')
				->where('fk_site', $this->site_id)
				->find();		
		
			# does a template exist for this protected tool?
			$template =
				(file_exists($this->assets->themes_dir("$this->theme/templates/".strtolower($toolname).'.html')))
				? strtolower($toolname)
				: 'master';
		
			$new_page = ORM::factory('page');
			$new_page->fk_site		= $this->site_id;
			$new_page->page_name	= $filename;
			$new_page->label		= $_POST['label'];
			$new_page->template		= $template;
			$new_page->position		= ++$max->highest;
			if(! empty($_POST['menu']) AND 'yes' == $_POST['menu'])
				$new_page->menu	= 'yes';
			$new_page->save();

			# init tool controller
			$tool_controller = new Tool_Controller();
			# create the tool.
			$tool = $tool_controller->_create_tool($system_tool_id, NULL, NULL, TRUE);	
			# add it to this page.
			$tool_controller->_add_to_page($tool, $new_page);
	
	
			# send html to javascript handler
			$visibility	= (empty($_POST['menu'])) ? 'hidden' : 'enabled';		
			$vars		= array(
				'id'			=> $new_page->id,
				'visibility'	=> $visibility,
				'is_folder'		=> FALSE,
				'is_protected'	=> TRUE,
				'full_path'		=> $filename,
				'filename'		=> $filename,
				'page_builder'	=> "$toolname-$system_tool_id"
			);
			# output to the javascript UI.
			die(View::factory('page/page_wrapper_html', array('vars' => $vars)));
		}

		# Javascript duplicatate_page name filter Validation
		# convert filter_array to string for js
		$filter_array		= self::get_folder_filenames('ROOT');	
		$filter_string		= "'" . implode("','", $filter_array) . "'";
		
		$primary = new View("page/new_builder");
		$primary->filter			= $filter_string;
		$primary->system_tool_id	= $system_tool_id;
		$primary->toolname			= $toolname;
		die($primary);
	}

	
/*
 * DELETE single page from pages table
 * Note: does not delete any tools owned by this page.
 * Deleting pages with children is currently set to False;
 */
	public function delete($page_id=NULL)
	{
		valid::id_key($page_id);

		$page = ORM::factory('page', $page_id);
		if(!$page->loaded)
			die('invalid page');
		
		# is this page set as homepage?
		if($page->page_name == $this->homepage)
			die('Cannot delete the current home page. Specify a new home page first.');
		
		# is this page a folder with children?
		# we can delete them all, OR just not allow parents to be deleted.
		$children = self::get_folder_filenames($page->page_name, 'all_children');
		if(0 < count($children))
			die('A page must have no sub-pages before it can be deleted.');
			#$id_set .= ','. implode(',', $folders_array[$page->page_name]);

			
		# if deleting a protected page
		yaml::delete_value($this->site_name, 'pages_config', $page->page_name);

		ORM::factory('page')
			->where('fk_site', $this->site_id)
			->delete($page_id);
		die('Page deleted.');		
	}

	
/*
 * Configure page settings
 */ 
	public function settings($page_id=NULL)
	{
		valid::id_key($page_id);
		
		$page = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->find($page_id);
		if(!$page->loaded)
			die('invalid page id');

		if($_POST)
		{
			# Validate page_name & duplicate check
			$directory = (empty($_POST['directory'])) ? NULL : $_POST['directory']; 
			$new_page_name = $filename = self::validate_page_name($_POST['label'], $_POST['page_name'], $directory, $_POST['page_name']);

			if(!empty($directory))
				$new_page_name = "$directory/$filename";

			# if this page was the homepage, update homepage value
			if($this->homepage == $_POST['old_page_name'])
			{
				$site = ORM::factory('site', $this->site_id);
				$site->homepage = $filename;
				$site->save();
				yaml::edit_site_value($this->site_name, 'site_config', 'homepage', $filename);
				$_POST['enable'] = 'yes'; # force homepage to be enabled.
			}
			
			$page->page_name	= $new_page_name;
			$page->title		= $_POST['title'];
			$page->meta			= $_POST['meta'];
			$page->label		= $_POST['label'];
			$page->template		= $_POST['template'];
			$page->menu			= $_POST['menu'];
			$page->enable		= (isset($_POST['enable']))? $_POST['enable'] : 'yes';
			$page->save();
		
			# did the page name change?
			# update all children within this "folder"
			if($_POST['old_page_name'] != $filename)
			{
				# if this page is protected update the pages_config file.
				yaml::edit_key($this->site_name, 'pages_config', $_POST['old_page_name'], $filename );
			
				$old_full_page = (empty($_POST['directory']))
					? $_POST['old_page_name']
					: $_POST['directory'] . '/' . $_POST['old_page_name'];
				$dir_pages = self::get_folder_filenames($old_full_page, 'change');
		
				# if this page has children, update them!
				foreach($dir_pages as $page_id => $page_name)
				{
					$page = ORM::factory('page')
						->where('fk_site', $this->site_id)
						->find($page_id);
					if(!$page->loaded)
						continue;
						
					$page->page_name = "$new_page_name/$page_name";
					$page->save();
				}
			}
	
			# if the page was the account page, update site_config
			if($this->account_page == $_POST['old_page_name'])
				yaml::edit_site_value($this->site_name, 'site_config', 'account_page', $filename);

				
			# should we publish this page?
			if(isset($_POST['publish']) AND 'yes' == $_POST['publish'])
			{
				$cache = DATAPATH ."$this->site_name/cache/$page_id.html";
				if(file_exists($cache))
					unlink($cache);
			}
			
			die('Page Settings Saved'); # success				
		}
	
		# Is this a subpage? (pop the end filename node from the directory.)
		$filename	= $page->page_name;
		$directory	= '';
		$directory_array = explode('/',$filename);
		if(1 < count($directory_array))
		{
			$filename	=  array_pop($directory_array);
			$directory	= implode('/', $directory_array);
		}

		$primary = new View("page/page_settings");	
		$primary->page		= $page;	
		$primary->filename	= $filename;	
		$primary->dir		= $directory;				


		# get available templates from theme.
		if($templates = yaml::parse($this->site_name, NULL, "themes/$this->theme/config"))
			$primary->templates = $templates;
		else
			$primary->templates = array('master' => 'default layout');
			
		# Javascript duplicate page_name filter Validation
		# convert filter_array to string to use as javascript array
		$filter_array = self::get_folder_filenames($directory, NULL, $filename);
		$filter_string = implode("','",$filter_array);			
		$primary->page_filter_js = "'$filter_string'";
		
		# is page protected?		
		$primary->is_protected = 
			(yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
			? TRUE
			: FALSE;
		
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
		if(empty($label))
			die('Name is required');
		
		$page_name = trim($page_name);
		if(empty($page_name))
			$page_name = strtolower($label);

		# Sanitize page_name
		$page_name = valid::filter_php_url($page_name);

		# Validate Unique Page_name relative to page directory		
		$filter_array = self::get_folder_filenames($directory, NULL, $omit);	
		if(in_array($page_name, $filter_array))
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
	private function get_folder_filenames($directory='ROOT', $mode='validate', $omit=NULL)
	{
		$directory = (empty($directory)) ? 'ROOT' : $directory;
		$filter_array = array();
		
		$pages = ORM::factory('page')
			->select(array("CONCAT('%%', page_name) as page_name, id"))
			->where('fk_site', $this->site_id)
			->find_all();
	
		if('ROOT' == $directory)
		{
			foreach($pages as $page)
			{
				# root page names do not contain forward slashes
				str_replace('/','_', $page->page_name, $count);
				if('0' == $count)
					$filter_array[] = ltrim($page->page_name, '%');
			}
			# Reserved root page_names.
			$filter_array[] = '_assets';
			$filter_array[] = '_data';
			$filter_array[] = 'get';
			$filter_array[] = 'file';
			$filter_array[] = 'index.php';
		}
		else
			foreach($pages as $page)
				if(preg_match("[%%$directory]", $page->page_name))
				{
					$value = str_replace("%%$directory", '', $page->page_name);
					
					if('validate' == $mode)
					{
						# to validate, we only want the names of
						# files/folders within this immediate directory.
						str_replace('/','_', $value, $count);
						if('1' == $count)
							$filter_array[$page->id] = ltrim($value, '/');
					}
					else
					{
						## this mode will list all true "page names" as children of the folder.
						# as a flat represenation in order to change them.
						# empty values mean this IS the folder, which we don't want.
						if(!empty($value))
							$filter_array[$page->id] = ltrim($value, '/');
					}
				}
		
		if($omit)
			foreach($filter_array as $key => $page_name)
				if($page_name == $omit)
					unset($filter_array[$key]);

		return $filter_array;
	}

/* cool code that builds an array to parse for children of pages
this job is handle by the get_folder_filenames but we'll keep it here for
coolio reference
			
		# Get all pages to look for children.
		$pages_data = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->orderby('page_name')
			->find_all();

			
		$page_name_array = array();
		$folders_array = array();
		
	
		# create the page array
		foreach($pages_data as $each_page)
			$page_name_array[$each_page->page_name] = $each_page->id;
	
		# create array of all sub_directories	
		foreach($page_name_array as $full_path => $data)
		{
			$node_array	= explode('/',$full_path);
			$filename	= array_pop($node_array);
			$directory	= implode('/', $node_array);

			if(empty($directory))
				$directory = 'ROOT';
			
			$folders_array[$directory][$filename] = $data;
		}	
		
		echo kohana::debug($page_name_array);
		echo kohana::debug($folders_array);		
*/	
	
	/* ----- functions relative to MAIN-MENU handling ----- */
	
/*
 * Sort the Main Menu links
 */
	public function navigation()
	{			
		$pages = ORM::factory('page')
			->where(array(
				'fk_site' => $this->site_id,
				'menu' => 'yes'
			))
			->orderby('position')
			->find_all();

		$all_pages = ORM::factory('page')
			->where(array('fk_site' => $this->site_id))
			->orderby('page_name')
			->find_all();
			
		$primary = new View('page/navigation');
		$primary->pages = $pages;
		$primary->all_pages = $all_pages;
		die($primary);
	}

/*
 * Save the Main menu order to db
 */		
	public function save_sort()
	{
		$db = new Database;
		foreach($_GET['item'] as $position => $id)
			$db->update('pages', array('position' => "$position"), "id = '$id' AND fk_site = '$this->site_id'"); 	
			
		die('Page Sort Order Saved'); # success
	}


/*
 * set a pages menu display to hidden.
 */		
	public function hide($page_id=NULL)
	{
		valid::id_key($page_id);
		
		$page = ORM::factory('page')->where('fk_site', $this->site_id)->find($page_id);
		$page->menu = 'no';
		$page->save();
		die('Page is hidden'); # success
	}
	
	
/*
 * this may not belong here. 
 * the view of the primary menu. Used with ajax to reload on-demand
 */		
	public function load_menu()
	{
		die( View::factory('_global/menu') );
	}

	
	
}  /* End of page.php */
