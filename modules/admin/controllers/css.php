<?php
class Css_Controller extends Controller {

	/**
	 * Compile the tools css for each page
	 * edit css files for each tool
	 * This controller shoudl be renamed to a more specific "tool" css controller
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * Build css for the tools on the page
 * $generic_tools = The different tools on the page (non-repeats)
 * $all_tools = every tool on the page
 * (string) $all_tools = "5.5" = "tools_list_id.tool_id"
 */
	function tools($all_tools=NULL)
	{
		$primary		= new View('css/tools');
		$db				= New Database;
		$all_tools		= explode('-', $all_tools);
		$tools			= $db->query('SELECT * FROM tools_list');		
		$tools_list 	= array();
		$unique_tools	= array();
		$all_tool_instances = array();
		
		# Build assoc array for all tools
		foreach ($tools as $tool)
		{
			$tools_list[$tool->id] = $tool->name;
		}
		
		# get all unique tools
		foreach ($all_tools as $tool)
		{
			$pieces		= explode('.', $tool);
			$name_id	= (int) $pieces['0'];
			$tool_id	= (! empty($pieces['1']) ) ? (int)$pieces['1'] : '0';
			$name 		= (! empty($tools_list[$name_id]) ) ? strtolower($tools_list[$name_id]) : '0';
	
			$unique_tools[$name_id]	= $name;
			$all_tool_instances[]	= $name . '.' . $tool_id;
		}		
		
		$primary->unique_tools	= $unique_tools;
		$primary->all_tools		= $all_tool_instances;
		echo $primary;
		die();
	}

/*
 * Edit a custom css file associated with a tool.
 * Custom files are auto created if none exists.
 * Stored in /data/tools_css
 */
	function edit($name_id=NULL, $tool_id=NULL)
	{
		if(! $this->client->logged_in()
			OR $this->client->get_user()->client_site_id != $this->site_id )
				die();

		tool_ui::validate_id($name_id);	
		tool_ui::validate_id($tool_id);		
		
		$css_file_path = DOCROOT."data/$this->site_name/tools_css";
		$db = new Database;
		$tool		= $db->query("SELECT name FROM tools_list WHERE id='$name_id'")->current();
		$tool_name	= strtolower($tool->name);
		$table		= $tool_name.'s';
		
		# Overwrite old file with new file contents;
		if($_POST)
		{
			$attributes = $_POST['attributes'];	
			$db->update($table, array('attributes' => $attributes ), "id='$tool_id' AND fk_site = '$this->site_id'");
			
			echo Css::save_custom_css($tool_name, $tool_id, $_POST['contents'] );
		}
		else
		{
			$primary = new View('css/edit_single');			
			$primary->contents	= Css::get_css_file($tool_name, $tool_id);
			$primary->tool_id	= $tool_id;
			$primary->name_id	= $name_id;
			$primary->tool_name	= $tool_name;
			
			$parent = $db->query("SELECT attributes FROM $table
				WHERE id='$tool_id'
			")->current();
			$primary->attributes = $parent->attributes;
			
			echo $primary;
		}		
		die();

	}
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */