<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Performs CRUD for tools
 * tools exist as objects in the system. Tools can be:
	created
		create a new tool object in the system.
	updated
		updates managed via the tool module logic.
	instanced
		instances are recorded onto pages and tell which tools should display where.
	cloned
		creates a new tool that models an existing tool.
	deleted.
		deletes the tool and all its instances.
 *
 */
 
class Tool_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}


/*	
 * List ALL TOOLS for this site.
 */
	function index()
	{
		$system_tools = ORM::factory('system_tool')
			->orderby(array(
				'protected'	=>'desc',
				'name'		=>'asc'
			))
			->find_all();
			
		# Get all tool references from pages_tools owned by this site.
		$tools = ORM::factory('tool')
			->where('fk_site', $this->site_id)
			->orderby(array('system_tool_id'=>'asc', 'id'=>'asc'))
			->find_all();
	
		$primary = new View('tool/manage');
		$primary->system_tools	= $system_tools;
		$primary->tools			= $tools;
		$primary->page_id		= (isset($_GET['page_id'])) ? $_GET['page_id'] : 0;
		die($primary);
	}

/*
 *	create a new tool and add it to specific page.
 */
	public function create($page_id=NULL)
	{		
		valid::id_key($page_id);		

		if($_POST)
		{
			# is a type sent?
			$type = (empty($_POST['type']))
				? NULL
				: $_POST['type'];
			
			# all tools passed here should be non-protected
			die(self::_create_tool($page_id, $_POST['tool'], $this->site_name, $type, FALSE, FALSE, TRUE));
		}	
		
		$tools = ORM::factory('system_tool')
			->where(array(
				'protected'	=> 'no',
				'enabled'	=> 'yes',
				'visible'	=> 'yes'
			))
			->find_all();
	
		$primary = new View('tool/new_tool');
		$primary->tools_list = $tools;
		$primary->page_id = $page_id;
		die($primary);
		
	}

/*
 * auto create a page_builder.
	i.e. Creates a new page with a new protected tool added to it.
 * used at auth->create_website() to automatically load page_builders to new websites.
 
	This can be a static function since we should not reference "this" site,
	but rather any given site. (this site would reference the plusjade site).
	
 * this should only be used internally so no need for crazy validation.
 */
	public static function _auto_tool($toolname, $site_id, $site_name, $theme)
	{
		$toolname = strtolower($toolname);
	
		$max = ORM::factory('page')
			->select('MAX(position) as highest')
			->where('fk_site', $site_id)
			->find();		
	
		# if a page_builder template exists, assign it.
		$template =
			(file_exists(DATAPATH . "$site_name/themes/$theme/templates/$toolname.html"))
			? $toolname
			: 'master';
	
		$new_page = ORM::factory('page');
		$new_page->fk_site		= $site_id;
		$new_page->page_name	= valid::filter_php_filename($toolname);
		$new_page->label		= ucfirst($toolname);
		$new_page->template		= $template;
		$new_page->position		= ++$max->highest;
		$new_page->menu			= 'yes';
		$new_page->save();
		
		# TODO: this has not been updated sitewide.
		$type = NULL; # the type lets you specify a specific view for the tool.
		
		# attempt to add the tool.
		if(TRUE !== self::_create_tool($new_page->id, $toolname, $site_name, $type, TRUE, TRUE))
			die("Could not add $toolname");
		
		return TRUE;
	}

	
/*
 * actually creates the tool and generates assets.
 * this is separate so we can use this modularly in other areas.
 * used here and @ page.php
 
 * this method is static so we can overt the construct logged in check.
 * 
 */
	public static function _create_tool($page_id, $system_tool_id, $site_name, $type=NULL, $allow_protected=FALSE, $sample=FALSE, $javascript=FALSE)
	{
		# get the system tool
		$system_tool = ORM::factory('system_tool')
			->select('*, LOWER(name) AS name')
			->where('enabled','yes')
			->find($system_tool_id);
		if(!$system_tool->loaded)
			die("invalid system_tool: $system_tool_id ");

		# are protected tools allowed on this page?
		if('yes' == $system_tool->protected AND !$allow_protected)
			die('Cannot add page builders to this page.');
			
		# set the type (if none specified, hit the default)
		$type = (empty($type)) ? $system_tool->type : $type ;
		
		# set the default view for the type.
		$system_tool_type = ORM::factory('system_tool_type')
			->where(array(
				'system_tool_id' => $system_tool_id,
				'type'			 => $type
			))
			->find();
		$view = $system_tool_type->view;
		
		
		# load the site_config to get the site_id, this benchmarked much faster.
		$site_config = yaml::parse($site_name, 'site_config');

		# add row to the tools parent table.
		$parent = ORM::factory($system_tool->name);
		$parent->fk_site = $site_config['site_id'];
		$parent->type = $type;
		$parent->save();
		
		# add new global tool record
		$tool = ORM::factory('tool');
		$tool->fk_site = $site_config['site_id'];
		$tool->system_tool_id = $system_tool->id;
		$tool->parent_id = $parent->id;
		$tool->save();
		
		// ----
		# add tool to specified page.
		
		# get min position of tools on page
		$db = new Database;	
		$lowest = $db->query("
			SELECT MIN(position) as lowest
			FROM pages_tools 
			WHERE page_id ='$page_id'
			AND fk_site = '{$site_config['site_id']}'
		")->current()->lowest;
		
		$data = array(
			'tool_id'	=> $tool->id,
			'page_id'	=> $page_id,
			'fk_site'	=> $site_config['site_id'],
			'position'	=> ($lowest-1)
		);
		$instance_id = $db->insert('pages_tools', $data)->insert_id();
		// ----
		
		
		# if tool is protected, add page to pages_config file.
		if('yes' == $system_tool->protected)
		{
			$page = ORM::factory('page', $page_id);
		
			$newline = "\n$page->page_name:$system_tool->name-$parent->id";
			yaml::add_value($site_name, 'pages_config', $newline);
		}
		
		# generate tool_css file
		self::_generate_tool_css($system_tool->name, $parent->id, $type, $view, $site_name, $site_config['theme']);
		
		# run _tool_adder
		$step_2 = 'add';
		$public_tool = Load_Tool::factory($system_tool->name);
		if(is_callable(array($public_tool, '_tool_adder')))
			$step2 = $public_tool->_tool_adder($parent->id, $site_config['site_id'], $sample);

		# is the tool being added via the UI?
		if($javascript)
		{
			# pass tool data to tool view "new_tool"
			$obj = (object) array(
				'toolname'	=> $system_tool->name,
				'method'	=> $step2,
				'instance'	=> $instance_id,
				'parent_id'	=> $parent->id,
				'tool_id'	=> $tool->id,
			);		
			return json_encode($obj);
		}	
		return TRUE;
	}

	
/*
 *	Delete a tool and all its instances : parent table & in pages_tools as well.
 *  Comes from the tools js red toolbar
 *  Calls edit_<toolname>::_tool_deleter which is used to delete
 *  assets, run logic specific to said tool.
 */	
	public function delete($tool_id=NULL)
	{
		valid::id_key($tool_id);		
		
		# delete all page instances of this tool.
		$db = new Database;	
		$db->delete('pages_tools', array('tool_id' => $tool_id, 'fk_site' => $this->site_id));		
		
		# get the tool.
		$tool = ORM::factory('tool')
			->where('fk_site', $this->site_id)
			->find($tool_id);
		if(!$tool->loaded)
			die('Tool does not exist');

		# Protect account tool.
		if('account' == $tool->system_tool->name)
			die('Account tool cannot be deleted, since other tools depend on it!');
		
		# delete the parent table row.
		$parent = ORM::factory($tool->system_tool->name)
			->where('fk_site', $this->site_id)
			->delete($tool->parent_id);

		# is tool protected?
		if('yes' == $tool->system_tool->protected)
			yaml::delete_value($this->site_name, 'pages_config', $tool->pages->current()->page_name);
			
		
		# DELETE the custom folder for this tool. (houses custom css files)
		$custom_folder = $this->assets->themes_dir("$this->theme/tools/$tool->system_tool->name/_created/$tool->parent_id");
		if(is_dir($custom_folder))
			Jdirectory::remove($custom_folder);
		
		# run tool_deleter
		$edit_tool	= Load_Tool::edit_factory($tool->system_tool->name);
		if( is_callable(array($edit_tool,'_tool_deleter')) )
			$edit_tool->_tool_deleter($tool->parent_id, $this->site_id);
		
		# finally, delete the tool 
		$tool->delete();
		
		die('Tool deleted from system');
	}
	
	
	
/*
 * ---------------------------------------------------------------------
 * ----------------- manage tool instances on pages --------------------
 * ---------------------------------------------------------------------
 */
 
/*
 * Adds a tool instance to the specified page.
 */
	public function add($tool_guid=NULL, $page_id=NULL)
	{
		valid::id_key($tool_guid);	
		valid::id_key($page_id);		

		# add tool to specified page.
		$db = new Database;
		# get min position of tools on page
		$lowest = $db->query("
			SELECT MIN(position) as lowest
			FROM pages_tools 
			WHERE page_id ='$page_id'
			AND fk_site = '$this->site_id'
		")->current()->lowest;
		
		$data = array(
			'tool_id'	=> $tool_guid,
			'page_id'	=> $page_id,
			'fk_site'	=> $this->site_id,
			'position'	=> ($lowest-1)
		);
		
		echo $db->insert('pages_tools', $data)->insert_id();
		die();
	}

/*
 * Remove a tool instance from the specified page.
 * Does not delete the tool.
 */
	public function remove($page_id=NULL, $instance=NULL)
	{
		valid::id_key($page_id);
		valid::id_key($instance);

		$db = new Database;	
		$db->delete('pages_tools', 
			array(
				'id'	  => $instance,
				'fk_site' => $this->site_id
			)
		);		
		die('Tool instance removed from page.');
	}
 
	
/*
 * Save the tool instance positions/containers for a given page
 * the posts happens via ajax in the public/assets/js/admin/init.js file
 * invoked as callback for tool sortable js
 */
	public function save_positions($page_id=NULL)
	{
		valid::id_key($page_id);				
		if(isset($_POST['output']))
		{
			$data = json_decode($_POST['output']);
			
			if(0 == count($data))
				die('There are no tools to sort');
	
			$db = new Database;
			foreach($data as $instance)
			{
				$db->update(
					'pages_tools',
					array(
						'container'	=> $instance->container,
						'position'	=> $instance->position+2,
					),
					array(
						'id'	  => $instance->id,
						'page_id' => $page_id,
						'fk_site' => $this->site_id
					)
				);								
			}	
			die('Order Updated!');
		}
		die();
	}	

	
/*
 * change  a tool instance's scope from local-to-page or global-site
 *			
 */	
	public function scope($page_id=NULL, $instance_id=NULL)
	{
		valid::id_key($page_id);
		valid::id_key($instance_id);
		$db = new Database;
		
		if($_POST)
		{
			$scope = ('5' >= $_POST['page_id']) ? 'global' : 'local';
			$db->update(
				'pages_tools',
				array('page_id' => $_POST['page_id']),
				"id = '$instance_id' AND fk_site = '$this->site_id'
			");
			die("$scope"); # for javascript to add appropriate class
		}
		
		$instance = $db->query("
			SELECT *, LOWER(system_tools.id) AS system_tool_id, pages_tools.id AS instance_id
			FROM pages_tools 
			JOIN tools ON pages_tools.tool_id = tools.id
			JOIN system_tools ON tools.system_tool_id = system_tools.id
			WHERE pages_tools.id = '$instance_id'
			AND pages_tools.fk_site = '$this->site_id'
		")->current();
		if(! is_object($instance) )
			die('tool instance does not exist');
		
			
		$protected_tools = ORM::factory('system_tool')
			->where('protected', 'yes')
			->find_all();
		
		foreach($protected_tools as $protected)
			if($instance->system_tool_id == $protected->id)
				die('Page builder tools are limited to one page');

		$primary = new View('tool/scope');
		$primary->instance = $instance;
		$primary->page_id = $page_id;
		$primary->js_rel_command = "scope-all-$instance->tool_id";
		die($primary);
		
	}


/*
 * ---------------------------------------------------------------------
 * ------------------------ HTML output for tools ------------------------
 * ---------------------------------------------------------------------
 */

	
/*
 * get the rendered html of a single tool 
 * used to insert updated tool data into the DOM via ajax
 * $().jade_update_tool_html js function @admin/init.js
 */	
	public function html($toolname=NULL, $tool_id=NULL)
	{
		valid::id_key($tool_id);
		# TODO: probably should query this in the db...
		
		$parent = ORM::factory($toolname)
			->where('fk_site', $this->site_id)
			->find($tool_id);
		if(!$parent->loaded)
			die('Tool does not exist');
			
		die(Load_Tool::factory($toolname)->_index($parent));
	}


/*
 * output red tool toolkit html
 * used in view(admin/admin_panel)
 * also when when adding a <new> tool html into the DOM
 */		
	public function toolkit($instance_id=NULL, $tool_id=NULL, $page_id=NULL)
	{
		valid::id_key($instance_id);
		valid::id_key($tool_id);
		valid::id_key($page_id);
		
		# get the tool.
		$tool = ORM::factory('tool')
			->where('fk_site', $this->site_id)
			->find($tool_id);

		# echo kohana::debug($tool->pages);die();		
		
		$scope = ('5' >= $page_id) ? 'global' : 'local';
		
		# determine if tool is protected so we can omit scope link
		$protected = ('yes' == $tool->system_tool->protected)
			? TRUE
			: FALSE;
		
		$data_array = array(
			'instance'	=> $instance_id,
			'tool_id'	=> $tool->id,
			'name'		=> strtolower($tool->system_tool->name),
			'name_id'	=> $tool->system_tool->id,
			'parent_id'	=> $tool->parent_id,
			'scope'		=> $scope,
			'page_id'	=> $page_id,
			'protected'	=> $protected,	
		);
		
		$view = new View('tool/toolkit_html');
		$view->data_array = $data_array;
		die($view);
	}
	


/*
 * ---------------------------------------------------------------------
 * ------------------------ CSS stuff for tools ------------------------
 * ---------------------------------------------------------------------
 */

 
 /*
 * Output the custom css file associated with this tool for editing.
 * Custom files are auto created if none exists.
 * files are stored in /_data/ on a per theme basis, organized by tool/type/view
 */
	public function css($system_tool_id=NULL, $tool_id=NULL)
	{
		valid::id_key($system_tool_id);	
		valid::id_key($tool_id);		
		
		$system_tool = ORM::factory('system_tool')
			->select('*, LOWER(name) AS name')
			->find($system_tool_id);

		$tool = ORM::factory($system_tool->name)
			->where('fk_site', $this->site_id)
			->find($tool_id);
		
		
		
		
		// ------------------- start legacy support -------------------
		# legacy support for no type:
		if(empty($tool->type))
			$tool->type = $system_tool->type;
			
		# legacy support for no view
		if(empty($tool->view))
		{
			$system_tool_type = ORM::factory('system_tool_type')
				->where(array(
					'system_tool_id' => $system_tool_id,
					'type'			 => $tool->type
				))
				->find();
			$tool->view = $system_tool_type->view;
		}
		if(empty($tool->type) OR empty($tool->view))
			$tool->save();
		// ------------------- end legacy support -------------------

		
		
		
		# Overwrite old file with new file contents;
		if($_POST)
		{
			$tool->attributes = $_POST['attributes'];
			$tool->save();

			if(isset($_POST['save_template']))
			{
				$contents		= preg_replace("/_(\d+)/", '_++', $_POST['contents']);
				$theme_template	= $this->assets->themes_dir("$this->theme/tools/$system_tool->name/$tool->type/{$tool->view}_template.css");
				if(file_put_contents($theme_template, $contents))
					die('Template Saved');

				die('The was a problem saving the file.');
			}
				
			$contents	 = self::replace_tokens($_POST['contents'], $this->site_name, $this->theme);
			$custom_file = $this->assets->themes_dir("$this->theme/tools/$system_tool->name/_created/$tool_id/{$tool->type}_$tool->view.css");
			if(file_put_contents($custom_file, $contents))
				die('CSS Changes Saved.');

			die('The was a problem saving the file.');	
		}

		$primary = new View('tool/edit_css');	
		$primary->tool = $tool;
		$primary->contents	= self::get_tool_css($system_tool->name, $tool_id, $tool->type, $tool->view);
		$primary->stock		= self::get_tool_css($system_tool->name, $tool_id, $tool->type, $tool->view, 'stock');
		$primary->template	= self::get_tool_css($system_tool->name, $tool_id, $tool->type, $tool->view, 'template');
		$data = array(
			'name_id'			=> $system_tool_id,
			'toolname'			=> $system_tool->name,
			'js_rel_command'	=> "update-$system_tool->name-$tool_id",
		);
		$primary->data = $data;
		die($primary);
	}
	
	
	
/*
 * used @ tool->css for intelligently retrieving css file associated with a tool.
 * Cascades from theme specific , then to stock.
	Types of css files in priority order
		Custom file
		Theme file template (as defined by user)
		Theme file stock (as defined by them creator)
		+Jade file stock (base styling)
	
 *
 */
	private function get_tool_css($toolname, $tool_id, $type, $view, $stock=FALSE)
	{
		$tools_folder	= $this->assets->themes_dir("$this->theme/tools");			
		$custom_file	= "$tools_folder/$toolname/_created/$tool_id/{$type}_$view.css";
		$theme_stock	= "$tools_folder/$toolname/$type/$view.css";
		$theme_template	= "$tools_folder/$toolname/$type/{$view}_template.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/$type/$view.css";
		
		ob_start();
		# return contents of a template or +jade stock tool css file.
		if(FALSE != $stock)
		{
			switch($stock)
			{
				case 'template':
					if(file_exists($theme_template))
						readfile($theme_template);
					else
						return NULL;
					break;
					
				case 'stock':
					if(file_exists($stock_file))
						readfile($stock_file);
					else
						return NULL;
					break;
					
				default:
					return NULL;
			}
			return str_replace('++', $tool_id, ob_get_clean());
		}
		
		# this file may not exist if the tool was added before user changes themes.
		# always generate a file if it does not exist.
		if(file_exists($custom_file))
		{
			readfile($custom_file);
			return ob_get_clean();
		}
		# if it does not exist, generate a new one.
		return self::_generate_tool_css($toolname, $tool_id, $type, $view, $this->site_name, $this->theme, TRUE);
	}



/*
 * Generates a new css file for a tool based on tool type and view.
 * The method cascaded down the available css tree as follows:
	Theme template (TODO: template has not been implimented yet)
	Theme stock
	+Jade stock
 */
	public static function _generate_tool_css($toolname, $tool_id, $type, $view, $site_name, $theme, $return_contents=FALSE)
	{
		// ------------------- start legacy support -------------------
		if(empty($type) OR empty($view))
		{
			$system_tool = ORM::factory('system_tool')
				->select('*, LOWER(name) AS name')
				->find($toolname);
				
			$tool = ORM::factory($toolname, $tool_id);	
			
			# legacy support for no type:
			if(empty($type))
			{
				$type = $tool->type = $system_tool->type;
			}
			
			# legacy support for no view
			if(empty($view))
			{
				$system_tool_type = ORM::factory('system_tool_type')
					->where(array(
						'system_tool_id' => $system_tool->id,
						'type'			 => $type
					))
					->find();
				$view = $tool->view = $system_tool_type->view;
			}
			
			$tool->save();
		}	
		// ------------------- end legacy support -------------------
		
		
		$tools_folder	= DATAPATH . "$site_name/themes/$theme/tools";
		$theme_file		= "$tools_folder/$toolname/$type/$view.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/$type/$view.css";
		$place_file		= "$tools_folder/$toolname/_created/$tool_id/{$type}_$view.css";
		$return			= FALSE;

		# is this theme tools folder created?
		if(!is_dir($tools_folder))
			mkdir($tools_folder);
			
		# is this specific toolname folder created?
		if(!is_dir("$tools_folder/$toolname"))
			mkdir("$tools_folder/$toolname");

		# is the "_created" folder created within the toolname folder?
		if(!is_dir("$tools_folder/$toolname/_created"))
			mkdir("$tools_folder/$toolname/_created");
			
		# is this specific tool_id folder created?
		if(!is_dir("$tools_folder/$toolname/_created/$tool_id"))
			mkdir("$tools_folder/$toolname/_created/$tool_id");

		ob_start();
		if(file_exists($theme_file))
			readfile($theme_file);
		elseif(file_exists($stock_file))
			readfile($stock_file);
		else
			echo '/* No css available for this tool. */';
			
		$source_contents = str_replace('++', $tool_id , ob_get_clean());
		# TODO: add this to the one above for efficiency
		$source_contents = self::replace_tokens($source_contents, $site_name, $theme);
		
		if(file_put_contents($place_file, $source_contents))
			$return = TRUE;
		
		if($return_contents)
			return $source_contents;
		
		return $return;
	}
	
	
/*
 * Replace any tokens with respective real-values.
 */ 
	private static function replace_tokens($contents, $site_name, $theme)
	{
		$keys = array(
			'%MY_THEME%',
			'%MY_FILES%'
		);
		$replacements = array(
			"/_data/$site_name/themes/$theme/tools",
			"/_data/$site_name/assets"
		);
		return str_replace($keys, $replacements , $contents);		
	}
	
	
} // end 

 



