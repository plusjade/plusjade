<?php defined('SYSPATH') or die('No direct script access.');

/**
 * the exporter importer class for tools. 
 
 * export tools to be imported as new tools to live site.
   export tools to be imported AS UPDATES to existing tools on live site.
 */
 
class Import_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}

	
/* 
 * test importing tool data json
 * expects a valid json string representing a tool.
  the importer simply imports any tool including protected tools
  imported tools exist within the system but do not reside on any pages.
  ** use the tool manager to add tools to existing pages which handle protected/unprotected logic.
  TODO: css stylesheet parsing
 */	
	public function tool()
	{
		if(!isset($_POST['json']))
		{
			# show the view.
			$view = new View('import/tool');
			die($view);
		}
		
		$tool = json_decode($_POST['json']);
		if(NULL === $tool or !is_object($tool))
			die('invalid json');
		
		$toolname = strtolower($tool->name);
		
		# is this a valid tool?
		$system_tool = ORM::factory('system_tool', $tool->name);
		if(!$system_tool->loaded)
			die('invalid system tool');	
			

		# import the parent table.
		$new_parent = ORM::factory($tool->name);
		foreach($tool->parent_table as $field => $value)
			if('id' != $field)
				$new_parent->$field = $value;
		$new_parent->fk_site = $this->site_id;

		if($new_parent->save())
		{
			# add new global tool record
			$global_tool = ORM::factory('tool');
			$global_tool->fk_site = $this->site_id;
			$global_tool->system_tool_id = $system_tool->id;
			$global_tool->parent_id = $new_parent->id;
			$global_tool->save();		
		}
		
		# import the child tables
		foreach($tool->child_tables as $table => $rows)
		{
			$new_data = ORM::factory($table);
			foreach($rows as $row)
			{
				foreach($row as $field => $value)
				{
					if("{$toolname}_id" == $field)
						$new_data->$field = $new_parent->id;
					elseif('id' != $field)
						$new_data->$field = $value;
				}
				$new_data->fk_site = $this->site_id;
				$new_data->save();
				$new_data->clear();
			}
		}
		
		
		# create the tool_css file
		if(!empty($tool->css))
		{
			$css_data	 = preg_replace("/_(\d+)/", "_$new_parent->id", $tool->css);
			$custom_dir	 = $this->assets->themes_dir("$this->theme/tools/$toolname/_created/$new_parent->id");
			$custom_file = "{$new_parent->type}_$new_parent->view.css";
			
			if(!is_dir($custom_dir))
				mkdir($custom_dir);
				
			file_put_contents("$custom_dir/$custom_file", $css_data);
		}
		
		die('tool has been imported!');
	}
	
}  /* End of import.php */