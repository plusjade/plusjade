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
		$this->client->can_edit($this->site_id);
	}
	
# List ALL TOOLS for this site.
	function index()
	{
		$db = new Database;
		$primary = new View('tool/manage');

		# Get all tool references in pages_tools owned by this site.
		$tools = $db->query("
			SELECT pages_tools.*, pages.page_name, tools_list.* 
			FROM pages_tools 
			LEFT JOIN tools_list ON pages_tools.tool = tools_list.id
			LEFT JOIN pages ON pages_tools.page_id = pages.id
			WHERE pages_tools.fk_site = '$this->site_id' 
			ORDER BY tools_list.id, pages_tools.page_id
		");
	
		# Get all pages belonging to this site.
		$pages = $db->query("
			SELECT id, page_name 
			FROM pages 
			WHERE fk_site = '$this->site_id' 
			ORDER BY page_name
		");
		
		$primary->tools = $tools;
		$primary->pages = $pages;
		die($primary);
	}

/*
 *	ADD single tool to specific page.
 *  No tool can start out as an orphan.
 *
 */
	function add($page_id=NULL)
	{		
		valid::id_key($page_id);		
		$db = new Database;		

		if($_POST)
		{
			(int) $id = $_POST['tool'];
			
			# GET tool name
			$tool = $db->query("
				SELECT LOWER(name) AS name, protected
				FROM tools_list
				WHERE id='$id'
				AND enabled = 'yes'
			")->current();
			if(! is_object($tool) )
				die('invalid tool');
				
			$table = $tool->name.'s';

			# INSERT row in tool parent table
			$data = array(
				'fk_site'	=> $this->site_id
			);			
			$tool_insert_id = $db->insert($table, $data)->insert_id();

			# GET MIN position of tools on page			
			$lowest = $db->query("
				SELECT MIN(position) as lowest
				FROM pages_tools 
				WHERE page_id ='$page_id'
			")->current()->lowest;
			
			# INSERT pages_tools row inserting tool parent id
			$data = array(
				'page_id'	=> $page_id,
				'fk_site'	=> $this->site_id,
				'tool'		=> $id,
				'tool_id'	=> $tool_insert_id,
				'position'	=> ($lowest-1)
			);
			$tool_guid = $db->insert('pages_tools', $data)->insert_id();
			
			# if tool is protected, add page to pages_config file.
			if('yes' == $tool->protected)
			{
				$page = $db->query("
					SELECT page_name
					FROM pages
					WHERE id = '$page_id'
				")->current();		
			
				$newline = "\n$page->page_name:$tool->name:$tool_insert_id,\n";
				yaml::add_value($this->site_name, 'pages_config', $newline);
			}
			
			# generate tool_css file
			//$css = new Css;
			Css::generate_tool_css($tool->name, $tool_insert_id);
			
			# run _tool_adder
			$step_2 = 'add';
			$edit_tool = Load_Tool::edit_factory($tool->name);
			if( is_callable(array($edit_tool, '_tool_adder')) )
				$step2 = $edit_tool->_tool_adder($tool_insert_id, $this->site_id);

			# Pass output to javascript @tool view "add" 
			# so it can load the next step page
			# data Format-> toolname:next_step:tool_id:tool_guid
			die(strtolower($tool->name).":$step2:$tool_insert_id:$tool_guid");
		}	
			
		$primary = new View('tool/new_tool');
		$tools = $db->query("
			SELECT * FROM tools_list
			WHERE protected = 'no'
			AND enabled = 'yes'
		");
		
		# is page protected? if not show page builders.
		$page = $db->query("
			SELECT page_name FROM pages WHERE id = '$page_id'
		")->current();			

		# If not a sub page and does not have builder installed already..
		
		if( FALSE !== strpos($page->page_name, '/') )
			$protected_tools = 'Page builders cannot be placed on sub pages';
		elseif( yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name) )	
			$protected_tools = 'A page builder already exists on this page.';
		else
		{
			$protected_tools = $db->query("
				SELECT * FROM tools_list WHERE protected = 'yes'
			");		
		}
		$primary->protected_tools = $protected_tools;
			
		$primary->tools_list = $tools;
		$primary->page_id = $page_id;
		die($primary);
		
	}
	

/*
 *	Delete single tool, both parent and children.
 *  Deletes the page reference in pages_tools as well.
 *  Comes from the tools js red toolbar
 *  Calls edit_<toolname>::_tool_deleter which is used to delete
 *  assets, run logic specific to said tool.
 */	
	function delete($tool_guid=NULL)
	{
		valid::id_key($tool_guid);		
		$db = new Database;	
	
		$tool_data = $db->query("
			SELECT pages_tools.*, LOWER(tools_list.name) as name, tools_list.protected, pages.page_name
			FROM pages_tools
			JOIN tools_list ON pages_tools.tool = tools_list.id
			LEFT JOIN pages ON pages_tools.page_id = pages.id
			WHERE guid = '$tool_guid' 
			AND pages_tools.fk_site = '$this->site_id'
		")->current();	
		
		if(! is_object($tool_data) )
			die('Tool does not exist');
		
		$table_parent	= $tool_data->name.'s';
		$table_child	= $tool_data->name.'_items';

		# DELETE pages_tools row
		$db->delete('pages_tools', array('guid' => $tool_guid ) );		
			
		# DELETE tool parent table row ('tool's table) 
		$db->delete($table_parent, array('id' => $tool_data->tool_id, 'fk_site' => $this->site_id) );	
		
		# DELETE all tool child items
		if('text' != $tool_data->name)
			$db->delete($table_child, array('parent_id' => $tool_data->tool_id, 'fk_site' => $this->site_id) );	

		# is tool protected?
		if('yes' == $tool_data->protected)
			yaml::delete_value($this->site_name, 'pages_config', $tool_data->page_name);
		
		# DELETE custom css file
		$custom_css = DATAPATH . "$this->site_name/tools_css/$tool_data->name/$tool_data->tool_id.css";
		if(file_exists($custom_css))
			unlink($custom_css);
		
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

		$primary	= new View('tool/move');
		$pages = $db->query("
			SELECT id, page_name 
			FROM pages 
			WHERE fk_site = '$this->site_id' 
			ORDER BY page_name
		");
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
		
		if(! empty($_POST['page_id']) )
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
			
		$protected_tools = $db->query("
			SELECT * FROM tools_list
			WHERE protected = 'yes'
		");
		
		foreach($protected_tools as $tool)
			if($tool_data->tool == $tool->id)
				die('Page builder tools are limited to one page');

		
		$primary = new View('tool/scope');
		$primary->tool_data = $tool_data;
		$primary->page_id = $page_id;
		$primary->js_rel_command = "scope-all-$tool_guid";
		die($primary);
		
	}
	
/*
 * get the rendered html of a single tool 
 * used to insert updated tool data into the DOM via ajax
 */	
	function html($toolname=NULL, $tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		# probably should query this in the db...
		$toolname= ucwords($toolname);
		$tool_object = Load_Tool::factory($toolname);	
		die( $tool_object->_index($tool_id) );
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
			SELECT pages_tools.*, LOWER(tools_list.name) as name
			FROM pages_tools
			JOIN tools_list ON tools_list.id = pages_tools.tool
			WHERE pages_tools.guid = '$tool_guid'
			AND pages_tools.fk_site = '$this->site_id'
		")->current();
		
		$scope = ('5' >= $tool_data->page_id) ? 'global' : 'local';
		
		# determine if tool is protected so we can omit scope link
		$protected = FALSE;
		$protected_tools = $db->query("
			SELECT * FROM tools_list
			WHERE protected = 'yes'
		");
		foreach($protected_tools as $tool)
			if($tool->id == $tool_data->tool)
				$protected = TRUE;		
		
		$data_array = array(
			'guid'		=> $tool_data->guid,
			'name'		=> $tool_data->name,
			'name_id'	=> $tool_data->tool,
			'tool_id'	=> $tool_data->tool_id,
			'scope'		=> $scope,
			'page_id'	=> $tool_data->page_id,
			'protected'	=> $protected,	
		);	
		$primary->data_array = $data_array;
		die($primary);
	}
}