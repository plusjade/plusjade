<?php defined('SYSPATH') or die('No direct script access.');

/**
 * the exporter importer class for tools. 
 
 * export tools to be imported as new tools to live site.
   export tools to be imported AS UPDATES to existing tools on live site.
 */
 
class Export_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}

/** NOT WORKING
 * try to export a page and all its tools.
  
 */	
	private function asfaf_page($page_id)
	{
		valid::id_key($page_id);
		
		$page = ORM::factory('page', $page_id);
		
		if(!$page->loaded)
			die('invalid page');

		$db = new Database;
		# get the tools on this page.
		$tools = $db->query("
			SELECT *, LOWER(system_tools.name) AS name, pages_tools.id AS instance_id
			FROM pages_tools 
			JOIN tools ON pages_tools.tool_id = tools.id
			JOIN system_tools ON tools.system_tool_id = system_tools.id
			WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page->id')
			AND pages_tools.fk_site = '$this->site_id'
			ORDER BY pages_tools.container, pages_tools.position
		");
		 # echo kohana::debug($tools); die();
		# foreach($tools as  $tool) echo kohana::debug($tool); die();

		$view = new View('export/page');
		$view->page = $page;
		$view->tools = $tools;
		die($view);
	}
	
/* 
 * export a single tool
 */ 
	public function tool()
	{
		if(empty($_GET['tool_id']))
			die('invalid tool_id');	
		$tool_id = valid::id_key($_GET['tool_id']);
		
		$tool = ORM::factory('tool', $tool_id);
		if(!$tool->loaded)
			die('invalid tool');
		
		$toolname = strtolower($tool->system_tool->name);
		
		# load the tool parent
		$parent = ORM::factory($toolname, $tool->parent_id);
		if(!$parent->loaded)
			die('invalid parent table');

		# build the object.
		$export = new stdClass;
		$export->name = $toolname;
		
		# export the parent table.
		$parent_table = new stdClass;
		foreach($parent->table_columns as $key => $value)
			$parent_table->$key = $parent->$key;

		$export->parent_table = $parent_table;

		# export any child tables.
		$child_tables = new stdClass;
		
		# loop through data from available child tables.
		foreach($parent->has_many as $table_name)
		{
			$table_name = inflector::singular($table_name);
			$child_tables->$table_name = array(); 
			
			# get the child table model so we can iterate through the fields.
			$table = ORM::factory($table_name);
			
			# get any rows beloning to the parent.
			$rows = ORM::factory($table_name)
				->where(array(
					'fk_site' => $this->site_id,
					"{$toolname}_id" => $parent->id
				))->find_all();
			
			foreach($rows as $row)
			{
				$object = new stdClass;
				foreach($table->table_columns as $key => $value)
					$object->$key = $row->$key;

				array_push($child_tables->$table_name, $object);
			}
		}		
		$export->child_tables = $child_tables;
		
		# get the css file.
		$export->css = file_get_contents($this->assets->themes_dir("$this->theme/tools/$toolname/_created/$parent->id/{$parent->type}_$parent->view.css"));
		
		$json = json_encode($export);
		
		echo '<h2>Copy this exactly and place into the importer=)</h2>';
		echo "<textarea style='width:99%;height:400px;'>$json</textarea>";
		die();
		
		echo kohana::debug($export); die();
		
		
		# just testing ...
		echo self::import($json);
		die();
	}

	
}  /* End of export.php */