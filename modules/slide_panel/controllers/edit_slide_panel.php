<?php
class Edit_Slide_Panel_Controller extends Edit_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

/*
 * MANAGE slide_panels 
 * @PARM (INT) $page_id = page id (table pages) contact tool is installed
 */
	function manage($tool_id=Null)
	{
		tool_ui::validate_id($tool_id);
		echo $this->_view_manage_tool_items('slide_panel', $tool_id);	
		die();
	}

/*
 * ADD slide panel item single 
 * Loads into tier 1 Facebox
 * @PARM (INT) $page_id = page id (table pages) contact tool is installed
 */ 
	function add($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		
		if($_POST)
		{
			$db = new Database;
			
			# Get highest position
			$get_highest = $db->query("SELECT MAX(position) as highest FROM slide_panel_items WHERE parent_id = '$tool_id' ");
			$highest =  ++$get_highest->current()->highest;

			# Add new
			$data = array(
				'fk_site'	=> $this->site_id,
				'parent_id'	=> $tool_id,
				'title'		=> $_POST['title'],
				'body'		=> $_POST['body'],
				'position'	=> $highest
			);
			$db->insert('slide_panel_items', $data);
			
			echo 'Panel Created'; #status message			
		}
		else
		{
			echo $this->_view_add_single('slide_panel', $tool_id);
		}
		
		die();
	}

/*
 * EDIT slide panel item single 
 * Loads into tier 2 Facebox
 * @PARM (INT) $page_id = page id (table pages) contact tool is installed
 */
	function edit($id = Null)
	{
		tool_ui::validate_id($id);
		
		if($_POST)
		{
			$db = new Database;
			$data = array(
				'title'		=> $_POST['title'],
				'body'		=> $_POST['body'],
			);
			$db->update('slide_panel_items', $data, "id = '$id'");
			echo 'Panel edited!!<br>Updating...'; #status message			
		}
		else
		{
			$this->_view_edit_single('slide_panel', $id);
		}
		die();
	}

	
/*
 * DELETE slide_panel item single
 * Success Response via inline JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of slide_panels row 
 */
	public function delete($id=NULL)
	{
		$this->_delete_single_common('slide_panel', $id);
		die();
	}

/*
 * SAVE panels sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		$this->_save_sort_common($_GET['slide_panel'], 'slide_panel_items');
		die();	
	}

/*
 * SAVE slide panel parent settings
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function settings($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		$db = new Database;
		
		if($_POST)
		{
		}
		else
		{
			$this->_view_edit_settings('slide_panel', $tool_id);	
		}
		die();
	}
	
	
}
/* -- end of application/controllers/home.php -- */