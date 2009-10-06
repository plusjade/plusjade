<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Compile the tools css for each page
 * edit css files for each tool
 */
 
class Tool_Css_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
	
/*
 * get user custom css for all tools on a page
 * these files should be 100% ready for output.
 * That is any tokens should have been parsed before saved.
 * tool-css files are saved relative to the installed theme.
 * ex: /_data/<site_name>/themes/<theme_name>/tools/<toolname>/<tool_id>.css 
 */
	public function live($page_id=NULL)
	{
		header("Content-type: text/css");
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		
		valid::id_key($page_id);
		
		$db = new Database;
		# get all tools that are added to this page.
		$tool_data = $db->query("
			SELECT *, LOWER(system_tools.name) AS name, tools.id AS guid
			FROM pages_tools 
			JOIN tools ON pages_tools.tool_id = tools.id
			JOIN system_tools ON tools.system_tool_id = system_tools.id
			WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page_id')
			AND pages_tools.fk_site = '$this->site_id'
			ORDER BY pages_tools.container, pages_tools.position
		");
		
		# load custom tool css files
		ob_start();
		
		$static_helpers = DOCROOT . '_assets/css/static_helpers.css';
		if (file_exists($static_helpers))
			readfile($static_helpers);

		# $tool_types = array();
		foreach($tool_data as $tool)
		{
			# get the type and the view from the system.
			# TODO: try and optimize this later.
			$table = ORM::factory($tool->name)
				->where('fk_site', $this->site_id)
				->find($tool->parent_id);


			// ------------------- start legacy support -------------------
			if(empty($table->type) OR empty($table->view))
			{
				$system_tool = ORM::factory('system_tool')
					->select('*, LOWER(name) AS name')
					->find($tool->name);
				
				# legacy support for no type:
				if(empty($type))
					$table->type = $system_tool->type;
				
				# legacy support for no view
				if(empty($table->view))
				{
					$system_tool_type = ORM::factory('system_tool_type')
						->where(array(
							'system_tool_id' => $system_tool->id,
							'type'			 => $table->type
						))
						->find();
					$table->view = $system_tool_type->view;
				}
				
				$table->save();
			}	
			// ------------------- end legacy support -------------------
			
		
		
			$custom_file = $this->assets->themes_dir("$this->theme/tools/$tool->name/_created/$tool->parent_id/{$table->type}_$table->view.css");
			if(file_exists($custom_file))
				readfile($custom_file);
			else # this should only happen when changing themes initially.
				echo Tool_Controller::_generate_tool_css($tool->name, $tool->parent_id, $table->type, $table->view, $this->site_name, $this->theme, TRUE);
				
			# get a list of all unique tooltypes:	
				# $tool_types["$tool->name"] = $tool->name;
		}
		
		# Load any tool-css needed for javascript functionality.
			# provide a way to automatically load stuff based on tool config file?
			# for now the only instance is the lightbox css.
			# so blah just always load it.
		if(file_exists(DOCROOT . "_assets/js/lightbox/style.css"))
			readfile(DOCROOT . "_assets/js/lightbox/style.css");
			
		die();
	}
	
/*
 * load admin_global.css & all admin css from all tools. 
 * and load it as one file. useful when in admin mode
 */
	public function admin()
	{
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
		
		header("Content-type: text/css");
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		
		ob_start();	

		$static_helpers = DOCROOT . '_assets/css/static_helpers.css';
		if (file_exists($static_helpers))
			readfile($static_helpers);
			
		# get the admin_global.css content
		$admin_global = DOCROOT . '_assets/css/admin_global.css';
		if(file_exists($admin_global))
			readfile($admin_global);
		
		# load all admin-mode tool css files.
		$system_tools = ORM::factory('system_tool')
			->select('LOWER(name) AS name')
			->find_all();

		foreach($system_tools as $tool)
		{
			$admin_css	= MODPATH . "$tool->name/views/edit_$tool->name/admin.css";
			
			if(file_exists($admin_css))
				readfile($admin_css);
		}

		# Load any tool-css needed for javascript functionality.
			# provide a way to automatically load stuff based on tool config file?
			# for now the only instance is the lightbox css.
		$lightbox_css = DOCROOT . '_assets/js/lightbox/style.css';
		if(file_exists($lightbox_css))
			readfile($lightbox_css);

		die(ob_get_clean());
	}
	
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */