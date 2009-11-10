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
	cloned (not yet added)
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
	public function index()
	{
		$system_tools = ORM::factory('system_tool')
			->orderby(array(
				'protected'	=>'desc',
				'name'		=>'asc'
			))
			->find_all();
			
		# Get all tool from the tools table.
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
 * View and Handler for creating a tool in the system and placing onto page. 
 * attempts to create a new tool and add it to a specific page.
 */
	public function create_to_page()
	{	
		if(empty($_GET['page_id']))
			die('invalid input');		
		$page_id = valid::id_key($_GET['page_id']);

		if($_POST)
		{
			if(empty($_POST['type']) OR empty($_POST['tool']))
				die('nothing posted.');
			
			# get the page
			$page = ORM::factory('page')
				->where('fk_site', $this->site_id)
				->find($page_id);
			if(!$page->loaded)
				die('invalid page');
			
			
			# if page is protected, disallow protected tools to be created.		
			$allow_protected = (yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
				? FALSE
				: TRUE;

			# try to create the tool. (returns tool data object)
			$tool = $this->_create_tool($_POST['tool'], $_POST['type'], NULL, $allow_protected);
		
			# add the tool to this page. (returns the instance_id)
			$instance_id = $this->_add_to_page($tool, $page);

			# send the data to the UI
			$tool_data = new stdClass;
			$tool_data->toolname	= strtolower($tool->system_tool->name);
			$tool_data->method		= $tool->system_tool->helper_method;
			$tool_data->parent_id	= $tool->parent_id;
			$tool_data->tool_id		= $tool->id;
			$tool_data->instance	= $instance_id;
			die(json_encode($tool_data));
		}	
		
		# get all available non-protected tools.
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
 * strictly creates a tool within the plusjade system.
 * RETURNS: (object) the generated global tool record on success.
 * ERRORS: are hard-died as of now.
 * 	this is separate so we can use this modularly in other areas.
 * 	Currently ONLY used here and page_controller->add_builder()
 * 
 */
	public function _create_tool($system_tool_id, $type=NULL, $view=NULL, $allow_protected=FALSE, $sample=FALSE)
	{
		# get the system tool
		$system_tool = ORM::factory('system_tool')
			->select('*, LOWER(name) AS name')
			->where('enabled','yes')
			->find($system_tool_id);
		if(!$system_tool->loaded)
			die("invalid system_tool: $system_tool_id ");
		
		# is this tool protected and are we allowed to create it?
		if('yes' == $system_tool->protected AND !$allow_protected)
			die('You cannot create a protected tool.');

		# set the type (if none specified, hit the default)
		$type = (empty($type)) ? $system_tool->type : $type ;
		
		# is the view set?
		if(empty($view))
		{
			# set the default view for the type.
			$system_tool_type = ORM::factory('system_tool_type')
				->where(array(
					'system_tool_id' => $system_tool_id,
					'type'			 => $type
				))
				->find();
			$view = $system_tool_type->view;
		}
		
		# add row to the tools parent table.
		$parent = ORM::factory($system_tool->name);
		$parent->fk_site = $this->site_id;
		$parent->type = $type;
		$parent->view = $view;
		$parent->save();
		
		# add new global tool record
		$tool = ORM::factory('tool');
		$tool->fk_site = $this->site_id;
		$tool->system_tool_id = $system_tool->id;
		$tool->parent_id = $parent->id;
		$tool->save();
		
		# generate tool_css file
		self::_generate_tool_css($system_tool->name, $parent->id, $type, $view, $this->site_name, $this->theme);
		
		# run _tool_adder
		Load_Tool::factory($system_tool->name)->_tool_adder($parent->id, $this->site_id, $sample);
		
		return $tool;
	}


	
/*
 * strictly adds an existing tool to an existing page. 
 * RETURNS: instance_id of the newly created instance.
   
   this validates against protected tools and pages.
   
	expects a tool and a page as objects, 
	if no objects, they must be ids.
 *
 */
	public function _add_to_page($tool, $page)
	{
		# make sure the tool is an object
		if(!is_object($tool))
		{
			$tool = ORM::factory('tool')
				->where('fk_site', $this->site_id)
				->find($tool);
			if(!$tool->loaded)
				return 'invalid tool';
		}
		
		# make sure the page is an object
		if(!is_object($page))
		{
			$page = ORM::factory('page')
				->where('fk_site', $this->site_id)
				->find($page);
			if(!$page->loaded)
				return 'invalid page';
		}

		
		# is the tool we are trying to add protected?	
		if('yes' == $tool->system_tool->protected)
		{
			# is this page a root page?
			str_replace('/', '', $page->page_name, $matches);
			if(0 < $matches)
				return 'protected tools must be on root pages';
				
			# is this page already protected?		
			if($page_config_value = yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
				return 'this page already contains a protected tool';
		}

		
		# get min position of tools on page
		$db = new Database;	
		$lowest = $db->query("
			SELECT MIN(position) as lowest
			FROM pages_tools 
			WHERE page_id ='$page->id'
			AND fk_site = '$this->site_id'
		")->current()->lowest;
		
		# add a new record to pages_tools
		# effectively "adding this tool to this page"
		$data = array(
			'tool_id'	=> $tool->id,
			'page_id'	=> $page->id,
			'fk_site'	=> $this->site_id,
			'position'	=> ($lowest-1)
		);
		$instance_id = $db->insert('pages_tools', $data)->insert_id();

		# only on successs...
		if('yes' == $tool->system_tool->protected)
		{
			$toolname = strtolower($tool->system_tool->name);
			# protect the page relative to this new tool.
			$newline = "\n$page->page_name:$toolname-$tool->parent_id";
			yaml::add_value($this->site_name, 'pages_config', $newline);		
		}

		return "$instance_id";
	}
	
/*
 * update stuff from an existing tool. mainly just name right now
 */
	public function update($tool_id=NULL)
	{
		valid::id_key($tool_id);
		if(!isset($_GET['name']))
			die('no name specified');
			
		# get the tool.
		$tool = ORM::factory('tool')
			->where('fk_site', $this->site_id)
			->find($tool_id);
		if(!$tool->loaded)
			die('Tool does not exist');
			
		$tool->name = $_GET['name'];
		$tool->save();
		die('Name Saved!!');
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
			# note: the page may not be set if the page was deleted but the tool still exists.
		if('yes' == $tool->system_tool->protected)
			if(isset($tool->pages->current()->page_name))
				yaml::delete_value($this->site_name, 'pages_config', $tool->pages->current()->page_name);
		

		# DELETE the custom folder for this tool. (houses custom css files)
		$custom_folder = $this->assets->themes_dir("$this->theme/tools/" . strtolower($tool->system_tool->name) . "/_created/$tool->parent_id");
		if(is_dir($custom_folder))
			Jdirectory::remove($custom_folder);
			
		# run tool_deleter
		Load_Tool::edit_factory($tool->system_tool->name)->_tool_deleter($tool->parent_id, $this->site_id);
		
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
 * handler only for adding a tool instance to a specific page.
 */
	public function add()
	{
		if(empty($_GET['tool_id']) OR empty($_GET['page_id']))
			die('invalid paramaters');
		$tool_id = valid::id_key($_GET['tool_id']);	
		$page_id = valid::id_key($_GET['page_id']);
		
		$instance_id = $this->_add_to_page($tool_id, $page_id);
		
		#javascript UI needs this to return the instance_id
		die($instance_id);
	}

	
/*
 * Remove a tool instance from the page.
 * Does not delete the tool.
 */
	public function remove()
	{
		if(empty($_GET['instance_id']))
			die('invalid instance_id');
			
		$instance_id = valid::id_key($_GET['instance_id']);
		$db = new Database;	
		
		# get the instance
		$instance = $db->query("
			SELECT * FROM pages_tools
			WHERE id = '$instance_id'
			AND fk_site = '$this->site_id'
		")->current();
		if(!is_object($instance))
			die('invalid instance');
			
		# get the tool.
		$tool = ORM::factory('tool')
			->where('fk_site', $this->site_id)
			->find($instance->tool_id);
			
		# is this tool protected?
		if('yes' == $tool->system_tool->protected)
		{
			# get the page.
			$page = ORM::factory('page')
				->where('fk_site', $this->site_id)
				->find($instance->page_id);
			
			# remove the protected signifier for this page.
			yaml::delete_value($this->site_name, 'pages_config', $page->page_name);
		}
		
		# delete the instance.
		$db->delete('pages_tools', 
			array(
				'id'	  => $instance_id,
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
	public function html($toolname=NULL, $parent_id=NULL)
	{
		#die('asdfa');
		#valid::id_key($parent_id);
		# TODO: probably should query this in the db...
		
		$parent = ORM::factory($toolname)
			->where('fk_site', $this->site_id)
			->find($parent_id);
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
		
		# type and view should be required to continue.
		
		# Overwrite old file with new file contents;
		if($_POST)
		{
			#$tool->attributes = $_POST['attributes'];
			#$tool->save();

			if(isset($_POST['save_template']))
			{
				#NOT WORKING.
				$contents		= preg_replace("/_(\d+)/", '_++', $_POST['contents']);
				$theme_template	= $this->assets->themes_dir("$this->theme/tools/$system_tool->name/$tool->type/{$tool->view}_template.css");
				if(file_put_contents($theme_template, $contents))
					die('Template Saved');

				die('The was a problem saving the file.');
			}

			# save the css
			$custom_file = $this->assets->themes_dir("$this->theme/tools/$system_tool->name/_created/$tool_id/{$tool->type}_$tool->view.css");
			if(file_put_contents($custom_file, $_POST['output']))
				die('CSS Changes Saved.');

			die('The was a problem saving the file.');	
		}

		# get stock css?
		#$stock = self::get_css($system_tool->name, $tool_id, $tool->type, $tool->view, 'stock');
		#echo kohana::debug($stock); die();
		
		
		# testing !
		$css = self::get_css($system_tool->name, $tool_id, $tool->type, $tool->view);
		#$css = self::objectify_css($css);	
		
		echo "<textarea style='width:500px;height:900px;'>$css</textarea>"; die();
		#echo kohana::debug($css); die();
		
		$view = new View('tool/edit_css');	
		$view->css = $css;
		$view->tool = $tool;		
		$view->name_id = $system_tool_id;
		$view->toolname = $system_tool->name;
		$view->js_rel_command = "update-$system_tool->name-$tool_id";
		die($view);
	}
	
	
	
/*
 * intelligently retrievs css specific to a tool.
 * Cascades in priority order:
		Custom file
		Theme file template (as defined by user)
		Theme file stock (as defined by them creator)
		+Jade file stock (base styling)
 *
 */
	private function get_css($toolname, $parent_id, $type, $view, $stock=FALSE)
	{
		$tools_folder	= $this->assets->themes_dir("$this->theme/tools");			
		$custom_file	= "$tools_folder/$toolname/_created/$parent_id/{$type}_$view.css";
		$theme_stock	= "$tools_folder/$toolname/{$type}_$view.css";
		$theme_template	= "$tools_folder/$toolname/$type/{$view}_template.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/$type/$view.sass";
		
		if(file_exists($stock_file))
		{
			#ob_start();
			#readfile($stock_file);
			#return ob_get_clean();
			
			# replace the main wrapper with the specific tool_wrapper!
			# IMPORTANT! all plusjade tool sass files must start at 2nd line =_=
			$file = file($stock_file);
			$file[1] = "#{$toolname}_wrapper_$parent_id\n";
			return Kosass::factory('compact')->structure($file);
			
			
			#return Kosass::factory('compact')->compile($file);
		}
		
		# return a theme template or +jade stock tool css file.
		if($stock)
		{
			switch($stock)
			{
				case 'template':
					if(!file_exists($theme_template))
						return NULL;					
					return file_get_contents($theme_template);
					break;
					
				case 'stock':
					if(!file_exists($stock_file))
						return NULL;						
					# replace the main wrapper with the specific tool_wrapper!
					# IMPORTANT! all plusjade tool sass files must start at 2nd line =_=
					$file = file($stock_file);
					$file[1] = "#{$toolname}_wrapper_$parent_id";
					return Kosass::factory('compact')->compile($file);
					break;
			}
			return NULL;
		}
		
		# this file may not exist if the tool was added before user changes themes.
		# always generate a file if it does not exist.
		if(file_exists($custom_file))
		{
			ob_start();
			readfile($custom_file);
			return ob_get_clean();
		}
		# if it does not exist, generate a new one.
		return self::_generate_tool_css($toolname, $parent_id, $type, $view, $this->site_name, $this->theme, TRUE);
	}


/*
 * Takes a string of valid css and turns it into an object so we can better handle
 * and update elements/properties on the front-end interface.
 */
	private static function objectify_css($css)
	{
		$css = explode('}', $css);
		
		$parsed = new StdClass;
		foreach($css as $line)
		{
			if(empty($line[0]))
				continue;			
			$line = explode('{', $line);
			$element = trim($line[0]);
			$parsed->$element = trim($line[1]);
		}
		return $parsed;
	}
	
	
/*
 * Generates a new css file for a tool based on tool type and view.
 * Generate meaning we create a parsed file within the sites _data folder.
 * The method cascades down the available css tree as follows:
	Theme template (TODO: template has not been implimented yet)
	Theme stock
	+Jade stock
	static so public controllers can overt the login check.
	** used at tool_css_controller->live()
	
	# all parameters should be required.
 */
	public static function _generate_tool_css($toolname, $parent_id, $type, $view, $site_name, $theme, $return_contents=FALSE)
	{
		$tools_folder	= DATAPATH . "$site_name/themes/$theme/tools";
		$theme_file		= "$tools_folder/$toolname/$type/$view.sass";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/$type/$view.sass";
		$place_file		= "$tools_folder/$toolname/_created/$parent_id/{$type}_$view.css";
		$return			= FALSE;

		# --- folder checks ---
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
		if(!is_dir("$tools_folder/$toolname/_created/$parent_id"))
			mkdir("$tools_folder/$toolname/_created/$parent_id");

			
			
		ob_start();
		if(file_exists($theme_file))
		{
			$file = file($theme_file);
			$file[1] = "#{$toolname}_wrapper_$parent_id";
			echo Kosass::factory('compact')->compile($file);
		}
		elseif(file_exists($stock_file))
		{
			$file = file($stock_file);
			$file[1] = "#{$toolname}_wrapper_$parent_id";
			echo Kosass::factory('compact')->compile($file);
		}
		else
			echo '/* No css available for this tool. */';

		$source_contents = self::replace_tokens(ob_get_clean(), $site_name, $theme);
		
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

 
