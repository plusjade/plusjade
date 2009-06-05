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
				SELECT name, protected FROM tools_list WHERE id='$id'
			")->current();
			$table = strtolower($tool->name).'s';

			
			# INSERT row in tool parent table
			$data = array(
				'fk_site'	=> $this->site_id
			);			
			$tool_insert_id = $db->insert($table, $data)->insert_id();

			# GET max position of tools on page			
			$tools = $db->query("
				SELECT MAX(position) as highest FROM pages_tools 
				WHERE page_id ='$page_id'
			")->current();			
			
			$highest = 1;
			if(! empty($tools->highest) ) 
				$highest = $tools->highest; 
			
			# INSERT pages_tools row inserting tool parent id
			$data = array(
				'page_id'	=> $page_id,
				'fk_site'	=> $this->site_id,
				'tool'		=> $id,
				'tool_id'	=> $tool_insert_id,
				'position'	=> ++$highest
			);
			$tool_guid = $db->insert('pages_tools', $data)->insert_id();
			
			# if tool is protected, add page to pages_config file.
			if('yes' == $tool->protected)
			{
				$page = $db->query("
					SELECT page_name FROM pages WHERE id = '$page_id'
				")->current();		
			
				$newline = "\n$page->page_name:$tool->name:$tool_insert_id,\n";
				yaml::add_value($this->site_name, 'pages_config', $newline);
			}
			
			$edit_tool	= Load_Tool::edit_factory($tool->name);
			$goto		= $edit_tool->_tool_adder($tool_insert_id, $this->site_id);

			# Pass output to javascript @tool view "add" 
			# so it can load the next step page
			# toolname:next_step:tool_id:tool_guid
			
			die(strtolower($tool->name).":$goto:$tool_insert_id:$tool_guid");
		}	
		else
		{			
			$primary = new View('tool/new_tool');
			$tools = $db->query("
				SELECT * FROM tools_list WHERE protected = 'no'
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
		else
		{
			$primary	= new View('tool/move');
			$pages		= $db->query("SELECT id, page_name FROM pages WHERE fk_site = '$this->site_id' ORDER BY page_name");
			$primary->pages = $pages;
			$primary->tool_guid = $tool_guid;
			die($primary);	
		}
	}
	

/*
 *	Delete single tool, both parent and children.
 *  Deletes the page reference in pages_tools as well.
 *  Comes from the tools js red toolbar
 *
 */	
/*
 * TODO delete all image and non-database assets associated with 
 * the tools.
 */
	function delete($tool_guid=NULL)
	{
		valid::id_key($tool_guid);		
		$db = new Database;	
	
		$tool_object = $db->query("
			SELECT pages_tools.*, tools_list.name, tools_list.protected, pages.page_name
			FROM pages_tools
			JOIN tools_list ON pages_tools.tool = tools_list.id
			JOIN pages ON pages_tools.page_id = pages.id
			WHERE guid = '$tool_guid' 
			AND pages_tools.fk_site = '$this->site_id'
		")->current();	
		
		if(! is_object($tool_object) )
			die('Tool does not exist');
		
		$table_parent	= strtolower($tool_object->name).'s';
		$table_child	= strtolower($tool_object->name).'_items';
		$parent_id		= $tool_object->tool_id; 
		 
		# DELETE pages_tools row
		$db->delete('pages_tools', array('guid' => $tool_guid ) );		
			
		# DELETE tool parent table row ('tool's table) 
		$db->delete($table_parent, array('id' => $parent_id, 'fk_site' => $this->site_id) );	
		
		# DELETE all tool child items
		if('Text' != $tool_object->name)
			$db->delete($table_child, array('parent_id' => $parent_id, 'fk_site' => $this->site_id) );	

		# is tool protected?
		if('yes' == $tool_object->protected)
			yaml::delete_value($this->site_name, 'pages_config', $tool_object->page_name);
		
		die('Tool Deleted!<br>Updating...');
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
			SELECT pages_tools.*, tools_list.name 
			FROM pages_tools
			JOIN tools_list ON tools_list.id = pages_tools.tool
			WHERE pages_tools.guid = '$tool_guid'
			AND pages_tools.fk_site = '$this->site_id'
		")->current();
		$data_array = array(
			'guid'		=> $tool_data->guid,
			'name'		=> $tool_data->name,
			'name_id'	=> $tool_data->tool,
			'tool_id'	=> $tool_data->tool_id,
		);	
		/*
		 * guid			is for pages_tools table
		 * name			defines the tool table (plural) ex: album(s)
		 * name_id		tools_list id of the tool
		 * tool_id		gets the tool from the tool table
		 */
		$primary->data_array = $data_array;
		die($primary);
	}
}