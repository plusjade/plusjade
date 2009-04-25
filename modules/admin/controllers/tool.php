<?php
class Tool_Controller extends Controller {

	/**
	 *	SCOPE: Performs CRUD for tools
	 *	Tools belong to pages, but manipulating tools themselves,
	 *  Should be scoped OUT of page manipulation
	 *
	 */
	
	function __construct()
	{
		parent::__construct();
		if(! $this->client->logged_in()
			OR $this->client->get_user()->client_site_id != $this->site_id )
				die();
	}
	
# List ALL TOOLS for this site.
	function index()
	{
		$db = new Database;
		$primary = new View('tool/manage');

		# Get all tool references in pages_tools
		# owned by this site.
		$tools = $db->query("
			SELECT pages_tools.*, pages.page_name, tools_list.* 
			FROM pages_tools 
			LEFT JOIN tools_list ON pages_tools.tool = tools_list.id
			LEFT JOIN pages ON pages_tools.page_id = pages.id
			WHERE pages_tools.fk_site = '$this->site_id' 
			ORDER BY tools_list.id, pages_tools.page_id
		");
	
		# Get all pages belonging to this site.
		$pages = $db->query("SELECT id, page_name FROM pages WHERE fk_site = '$this->site_id' ORDER BY page_name");
		
		$primary->tools = $tools;
		$primary->pages = $pages;
		echo $primary;
		die();
	}

/*
 *	ADD single tool to specific page.
 *  No tool can start out as an orphan.
 *
 */
	function add($page_id=NULL)
	{
		tool_ui::validate_id($page_id);		
		$db = new Database;		

		if(! empty($_POST['tool']) )
		{
			(int) $id = $_POST['tool'];
			
			# GET tool name
			$tool = $db->query("SELECT name FROM tools_list WHERE id='$id' ")->current();
			$table = strtolower($tool->name).'s';
				
			# INSERT row in tool parent table
			$data = array(
				'fk_site'	=> $this->site_id
			);			
			$tool_insert = $db->insert($table, $data);

			# GET max position of tools on page			
			$tools = $db->query("SELECT MAX(position) as highest FROM pages_tools WHERE page_id ='$page_id' ")->current();			
			if( empty($tools->highest) ) 
				$highest = 1;
			else
				$highest = $tools->highest; 
			
			# INSERT pages_tools row inserting tool parent id
			$data = array(
				'page_id'	=> $page_id,
				'fk_site'	=> $this->site_id,
				'tool'		=> $id,
				'tool_id'	=> $tool_insert->insert_id(),
				'position'	=> ++$highest
			);
			$pages_tools_insert = $db->insert('pages_tools', $data);
			
			Load_Tool::after_add($tool->name, $tool_insert->insert_id() );
			
			# Pass output the facebox
			echo strtolower($tool->name).'/add/'.$tool_insert->insert_id();

			die();
		}	
		else
		{			
			$primary = new View('tool/new_tool');
			$tools = $db->query('SELECT * FROM tools_list');
			$primary->tools_list = $tools;
			$primary->page_id = $page_id;
			echo $primary; 
			die();
		}		
	}
	
/*
 * Moves a tool from one page to another
 * Moves orphaned tools to a page.
 *
 */ 
	function move($tool_guid=NULL)
	{
		tool_ui::validate_id($tool_guid);
		$db = new Database;
		
		if($_POST)
		{
			$data = array(
				'page_id'	=> $_POST['new_page']
			);
			$db->update('pages_tools', $data, array( 'guid' => "$tool_guid", 'fk_site' => "$this->site_id" ) );			
			echo 'Tool moved!!';
		}
		else
		{
			$primary	= new View('tool/move');
			$pages		= $db->query("SELECT id, page_name FROM pages WHERE fk_site = '$this->site_id' ORDER BY page_name");
			$primary->pages = $pages;
			$primary->tool_guid = $tool_guid;
			echo $primary;	
		}
		die();
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
		tool_ui::validate_id($tool_guid);		
		$db = new Database;	
	
		$tool_object = $db->query("SELECT * FROM pages_tools
			JOIN tools_list ON pages_tools.tool = tools_list.id
			WHERE guid = '$tool_guid' AND fk_site='$this->site_id' ")->current();	
		
		if(! is_object($tool_object) ) die();
		
		$table_parent	= strtolower($tool_object->name).'s';
		$table_child	= strtolower($tool_object->name).'_items';
		$parent_id		= $tool_object->tool_id; 
		 
		# DELETE pages_tools row
		$db->delete('pages_tools', array('guid' => $tool_guid ) );		
			
		# DELETE tool parent table row ('tool's table) 
		$db->delete($table_parent, array('id' => $parent_id, 'fk_site' => $this->site_id) );	
		
		# DELETE all tool child items
		if($tool_object->name != 'Text')
			$db->delete($table_child, array('parent_id' => $parent_id, 'fk_site' => $this->site_id) );	
		
		echo 'Tool Deleted!<br>Updating...';
		die();
	}
}

/* End of file tools.php */