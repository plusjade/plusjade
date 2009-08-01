<?php
class Tool_Controller extends Controller {

/**
 *	SCOPE: Performs CRUD for tools
 *	Tools belong to pages, but manipulating tools themselves,
 *  Should be separate from page manipulation
 *
 */
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
		$db = new Database;
		$primary = new View('tool/manage');

		# Get all tool references in pages_tools owned by this site.
		$tools = $db->query("
			SELECT pages_tools.guid, pages_tools.page_id, pages.page_name, system_tools.name 
			FROM pages_tools 
			LEFT JOIN system_tools ON pages_tools.system_tool_id = system_tools.id
			LEFT JOIN pages ON pages_tools.page_id = pages.id
			WHERE pages_tools.fk_site = '$this->site_id' 
			ORDER BY system_tools.name ASC, pages.page_name
		");
		$primary->tools = $tools;
		die($primary);
	}

/*
 *	ADD single tool to specific page.
 *  No tool can start out as an orphan.
 */
	function add($page_id=NULL)
	{		
		valid::id_key($page_id);		

		if($_POST)
		{
			#stupid ie sends button contents rather than value.
			$field = (is_numeric($_POST['tool'])) ? 'id' : 'name';
			# all tools passed her should be non-protected
			die(self::_add_tool($page_id, $_POST['tool'], $this->site_name, FALSE, FALSE, TRUE));
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
 * auto add a page_builder to a predefined page.
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
			? $toolname : 'master';
	
		$new_page = ORM::factory('page');
		$new_page->fk_site		= $site_id;
		$new_page->page_name	= valid::filter_php_filename($toolname);
		$new_page->label		= ucfirst($toolname);
		$new_page->template		= $template;
		$new_page->position		= ++$max->highest;
		$new_page->menu			= 'yes';
		$new_page->save();
		
		# attempt to add the tool.
		if(TRUE !== self::_add_tool($new_page->id, $toolname, $site_name, TRUE, TRUE))
			die("Could not add $toolname");
		
		return TRUE;
}

	
/*
 * actually adds the tool to the database and generates assets.
 * this is separate so we can use this modularly in other areas.
 * used here and @ page.php
 
 * this method is static so we can overt the construct logged in check.
 * 
 */
	public static function _add_tool($page_id, $toolname, $site_name, $allow_protected=FALSE, $sample=FALSE, $javascript=FALSE)
	{
		$system_tool = ORM::factory('system_tool')
			->select('*, LOWER(name) AS name')
			->where('enabled','yes')
			->find($toolname);
			
		if(!$system_tool->loaded)
			die('invalid system_tool');
		
		# do we allow protected tools on this page?
		if('yes' == $system_tool->protected AND !$allow_protected)
			die('Cannot add page builders to this page.');
		
		$site_config = yaml::parse($site_name, 'site_config');

		# INSERT row in system_tool parent table
		$tool_table = ORM::factory($system_tool->name);
		$tool_table->fk_site = $site_config['site_id'];
		$tool_table->save();
		
		$db = new Database;
		# GET MIN position of tools on page			
		$lowest = $db->query("
			SELECT MIN(position) as lowest
			FROM pages_tools 
			WHERE page_id ='$page_id'
		")->current()->lowest;
		
		# INSERT pages_tools row inserting tool parent id
		$data = array(
			'page_id'		 => $page_id,
			'fk_site'		 => $site_config['site_id'],
			'system_tool_id' => $system_tool->id,
			'tool_id'		 => $tool_table->id,
			'position'		 => ($lowest-1)
		);
		$tool_guid = $db->insert('pages_tools', $data)->insert_id();
		
		# if tool is protected, add page to pages_config file.
		if('yes' == $system_tool->protected)
		{
			$page = ORM::factory('page', $page_id);
		
			$newline = "\n$page->page_name:$system_tool->name-$tool_table->id";
			yaml::add_value($site_name, 'pages_config', $newline);
		}
		
		# generate tool_css file
		self::_generate_tool_css($system_tool->name, $tool_table->id, $site_name, $site_config['theme']);
		
		# run _tool_adder
		$step_2 = 'add';
		$public_tool = Load_Tool::factory($system_tool->name);
		if(is_callable(array($public_tool, '_tool_adder')))
			$step2 = $public_tool->_tool_adder($tool_table->id, $site_config['site_id'], $sample);

		# Pass output to javascript @tool view "add" 
		# so it can load the next step page
		# data Format-> toolname:next_step:tool_id:tool_guid
		if($javascript)
			return "$system_tool->name:$step2:$tool_table->id:$tool_guid";
			
		return TRUE;
	}



/*
 *	Delete single tool references : parent table & in pages_tools as well.
 *  Comes from the tools js red toolbar
 *  Calls edit_<toolname>::_tool_deleter which is used to delete
 *  assets, run logic specific to said tool.
 */	
	function delete($tool_guid=NULL)
	{
		valid::id_key($tool_guid);		
		$db = new Database;	
	
		$tool_data = $db->query("
			SELECT pages_tools.*, LOWER(system_tools.name) as name, system_tools.protected, pages.page_name
			FROM pages_tools
			JOIN system_tools ON pages_tools.system_tool_id = system_tools.id
			LEFT JOIN pages ON pages_tools.page_id = pages.id
			WHERE guid = '$tool_guid' 
			AND pages_tools.fk_site = '$this->site_id'
		")->current();	
		
		if(! is_object($tool_data) )
			die('Tool does not exist');
		

		# Protect the account tool.
		if('account' == $tool_data->name)
			die('Account tool cannot be deleted, since other tools depend on it!');
			
			
		$table_parent	= $tool_data->name.'s';

		# DELETE pages_tools reference.
		$db->delete('pages_tools', array('guid' => $tool_guid));		

		# DELETE tool parent table row ('tool's table) 
		$db->delete($table_parent, array('id' => $tool_data->tool_id, 'fk_site' => $this->site_id));	
		
		# is tool protected?
		if('yes' == $tool_data->protected)
			yaml::delete_value($this->site_name, 'pages_config', $tool_data->page_name);
		
		# DELETE custom css file
		$theme_tool_css = $this->assets->themes_dir("$this->theme/tools/$tool_data->name/css/$tool_data->tool_id.css");
		if(file_exists($theme_tool_css))
			unlink($theme_tool_css);
		
		# run tool_deleter
		$edit_tool	= Load_Tool::edit_factory($tool_data->name);
		if( is_callable(array($edit_tool,'_tool_deleter')) )
			$edit_tool->_tool_deleter($tool_data->tool_id, $this->site_id);
			
		die('Tool Deleted');
	}

/*
 * Moves a tool from one page to another
 * Moves orphaned tools to a page.
 *
 */ 
	function move($tool_guid=NULL)
	{
		valid::id_key($tool_guid);
		$db = new Database;
		
		if($_POST)
		{
			$data = array(
				'page_id'	=> $_POST['new_page']
			);
			$db->update('pages_tools', $data, array( 'guid' => "$tool_guid", 'fk_site' => "$this->site_id" ) );			
			die('Tool moved!!');
		}

		$pages = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->orderby('page_name')
			->find_all();
			
		$primary = new View('tool/move');
		$primary->pages = $pages;
		$primary->tool_guid = $tool_guid;
		die($primary);	

	}

	
/*
 * Save the tool positions/containers for a given page
 * the posts happens via ajax in the public/assets/js/admin/init.js file
 * invoked via id="get_tool_sort" link (now as callback for tool sortable js)
 */
	function save_positions($page_id=NULL)
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
	
			# hash format "guid_<guid>|container|position#"
			foreach($output as $hash)
			{
				$data = explode('|', $hash);
				list($guid, $container, $position) = $data;
				
				$guid = strstr($guid, '_');
				$guid = ltrim($guid, '_');
				
				# Update the rows
				$data = array(
					'container'	=> $container,
					'position'	=> $position+2,
				);
				$db->update('pages_tools', $data, "guid = '$guid' AND fk_site = '$this->site_id'");								
			}	
			die('Order Updated!');
		}
		die();
	}	
	
/*
 * change the scope of a tool from local-to-page or global-site
 *			
 */	
	function scope($tool_guid=NULL, $page_id=NULL)
	{
		valid::id_key($tool_guid);
		valid::id_key($page_id);
		$db = new Database;
		
		if(!empty($_POST['page_id']))
		{
			$scope = ('5' >= $_POST['page_id']) ? 'global' : 'local';
			$db->update(
				'pages_tools',
				array('page_id' => $_POST['page_id']),
				"guid = '$tool_guid' AND fk_site = '$this->site_id'
			");
			die("$scope"); # for javascript to add appropriate class
		}
		
		$tool_data = $db->query("
			SELECT * FROM pages_tools
			WHERE guid = '$tool_guid'
			AND fk_site = '$this->site_id'
		")->current();
		if(! is_object($tool_data) )
			die('tool does not exist');
		
			
		$protected_tools = ORM::factory('system_tool')
			->where('protected', 'yes')
			->find_all();
		
		foreach($protected_tools as $tool)
			if($tool_data->system_tool_id == $tool->id)
				die('Page builder tools are limited to one page');

		
		$primary = new View('tool/scope');
		$primary->tool_data = $tool_data;
		$primary->page_id = $page_id;
		$primary->js_rel_command = "scope-all-$tool_guid";
		die($primary);
		
	}


/*
 * Edit a custom css file associated with a tool.
 * Custom files are auto created if none exists.
 * Stored in /data/tools_css
 */
	function css($system_tool_id=NULL, $tool_id=NULL)
	{
		valid::id_key($system_tool_id);	
		valid::id_key($tool_id);		
		
		$system_tool = ORM::factory('system_tool')
			->select('*, LOWER(name) AS name')
			->find($system_tool_id);

		$tool = ORM::factory($system_tool->name)
			->where('fk_site', $this->site_id)
			->find($tool_id);
		
		# Overwrite old file with new file contents;
		if($_POST)
		{
			$tool->attributes = $_POST['attributes'];
			$tool->save();

			if(isset($_POST['save_template']))
				die(self::save_template($system_tool->name, $_POST['contents']));
				
			die(self::save_custom_css($system_tool->name, $tool_id, $_POST['contents']));
		}

		$primary = new View('tool/edit_css');	
		$primary->attributes = $tool->attributes;
		$primary->contents	= self::get_tool_css($system_tool->name, $tool_id);
		$primary->stock		= self::get_tool_css($system_tool->name, $tool_id, 'stock');
		$primary->template	= self::get_tool_css($system_tool->name, $tool_id, 'template');
		$data = array(
			'tool_id'			=> $tool_id,
			'name_id'			=> $system_tool_id,
			'toolname'			=> $system_tool->name,
			'js_rel_command'	=> "update-$system_tool->name-$tool_id",
		);
		$primary->data = $data;
		die($primary);
	}

	
/*
 * get the rendered html of a single tool 
 * used to insert updated tool data into the DOM via ajax
 * $().jade_update_tool_html js function @admin/init.js
 */	
	function html($toolname=NULL, $tool_id=NULL)
	{
		valid::id_key($tool_id);
		# TODO: probably should query this in the db...
		$tool_object = Load_Tool::factory($toolname);			
		die($tool_object->_index($tool_id));
	}


/*
 * output red tool toolkit html
 * used in view(admin/admin_panel)
 * also when when adding a <new> tool html into the DOM
 */		
	function toolkit($tool_guid=NULL)
	{
		valid::id_key($tool_guid);
		
		$primary = new View('tool/toolkit_html');
		$db = new Database;
		
		$tool_data = $db->query("
			SELECT pages_tools.*, LOWER(system_tools.name) as name
			FROM pages_tools
			JOIN system_tools ON system_tools.id = pages_tools.system_tool_id
			WHERE pages_tools.guid = '$tool_guid'
			AND pages_tools.fk_site = '$this->site_id'
		")->current();
		
		$scope = ('5' >= $tool_data->page_id) ? 'global' : 'local';
		
		# determine if tool is protected so we can omit scope link
		$protected = FALSE;
		$protected_tools = ORM::factory('system_tool')
			->where('protected', 'yes')
			->find_all();
			
		foreach($protected_tools as $tool)
			if($tool->id == $tool_data->system_tool_id)
				$protected = TRUE;		
		
		$data_array = array(
			'guid'		=> $tool_data->guid,
			'name'		=> $tool_data->name,
			'name_id'	=> $tool_data->system_tool_id,
			'tool_id'	=> $tool_data->tool_id,
			'scope'		=> $scope,
			'page_id'	=> $tool_data->page_id,
			'protected'	=> $protected,	
		);	
		$primary->data_array = $data_array;
		die($primary);
	}
	

	
/*
 * used @ tool->add to generate a new css file from theme/stock instance
 */
	public static function _generate_tool_css($toolname, $tool_id, $site_name, $theme, $return_contents=FALSE)
	{
		$tool_path = DATAPATH . "$site_name/themes/$theme/tools/$toolname";
		
		$custom_file	= "$tool_path/css/$tool_id.css";		
		$theme_file		= "$tool_path/css/stock.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/stock.css";
		$return			= FALSE;
		
		# make sure the folders exist.
		if(! is_dir($tool_path) )
			mkdir($tool_path);

		if(! is_dir("$tool_path/css") )
			mkdir("$tool_path/css");
			
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
		
		if(file_put_contents($custom_file, $source_contents))
			$return = TRUE;
		
		if($return_contents)
			return $source_contents;
		
		return $return;
	}
	
	
	
/*
 * used @ tool->css for intelligently retrieving css file associated with a tool.
 * Cascades from theme specific , then to stock.
 *
 */
	private function get_tool_css($toolname, $tool_id, $stock=FALSE)
	{
		$tool_theme		= $this->assets->themes_dir("$this->theme/tools/$toolname/css");			
		$custom_file	= "$tool_theme/css/$tool_id.css";
		$stock_file		= MODPATH . "$toolname/views/public_$toolname/stock.css";
		
		ob_start();
		# return contents of a template or +jade stock tool css file.
		if(FALSE != $stock)
		{
			switch($stock)
			{
				case 'template':
					if(file_exists("$tool_theme/stock.css"))
						readfile("$tool_theme/stock.css");
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
		if(file_exists("$tool_theme/$tool_id.css"))
		{
			readfile("$tool_theme/$tool_id.css");
			return ob_get_clean();
		}
		# if it does not exist, generate a new one.
		return self::_generate_tool_css($toolname, $tool_id, $this->site_name, $this->theme, TRUE);
	}

/*
 * 
 * save a custom tool css file.
 */
	private function save_custom_css($toolname, $tool_id, $contents)
	{	
		$theme_tool_css = $this->assets->themes_dir("$this->theme/tools/$toolname/css/$tool_id.css");
		$contents = self::replace_tokens($contents, $this->site_name, $this->theme);
		if( file_put_contents($theme_tool_css, $contents) )
			return 'CSS Changes Saved.';

		return 'The was a problem saving the file.';	
	}
	
/*
 * 
 * save a file as a template.
 */
	private function save_template($toolname, $contents)
	{	
		$template_css	= $this->assets->themes_dir("$this->theme/tools/$toolname/css/stock.css");
		$contents		= preg_replace("/_(\d+)/", '_++', $contents);
		
		if(file_put_contents($template_css, $contents))
			return 'Template Saved';

		return 'The was a problem saving the file.';	
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

 



