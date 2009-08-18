<?php defined('SYSPATH') OR die('No direct access allowed.');


class Build_Page_Controller extends Template_Controller {

	function __construct()
	{
		parent::__construct();
	}
 
/*
 * Case B: is page_name.
 * Renders live client pages.
 * Grabs all tools associated with this page, places them correctly.
 * Outputs the view "shell" via template controller.
 * $page = (object) pages table row
 */
	function _index($page)
	{
		# deny access to disabled pages if not logged in
		if('no' == $page->enable AND !$this->client->can_edit($this->site_id))
		{
			Event::run('system.404');
			die('Page Not Found');		
		}
	
		$db	= new Database;
		$containers_array		= array(' ',' ',' ',' ',' ',' ');
		$tools_array			= array();
		$_SESSION['js_files']	= array();
		$primary				= '';
		$prepend				= '';
		$append					= '';
		

		$this->template->set_global('title', $page->title);
		$this->template->meta_tags('description', $page->meta);
		$this->template->set_global('this_page_id', $page->id);	
		
		# Grab tools for this page in pages_tools table
		# 0-10 are reserved for global tools. we only use 1-5 
		$tools = $db->query("
			SELECT *, LOWER(system_tools.name) AS name
			FROM pages_tools 
			JOIN system_tools ON system_tools.id = pages_tools.system_tool_id
			WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page->id')
			AND fk_site = '$this->site_id'
			ORDER BY container, position
		");

		# Load Admin CSS and Javascript (if logged in)
		# _load_admin() is in the template_controller
		$admin_mode = $this->_load_admin($page->id, $page->page_name);
		
		# plusjade homepage hack.
		if(ROOTACCOUNT === $this->site_name AND $this->homepage == $page->page_name)
			$containers_array['1'] = Home_Controller::_index();


		if($tools->count() > 0)
		{	
			foreach ($tools as $tool)
			{
				# If Logged in wrap classes around tools for Javascript
				# TODO: consider this with javascript
				if($this->client->can_edit($this->site_id))
				{
					$scope		= ('5' >= $tool->page_id) ? 'global' : 'local';
					$prepend	= '<span id="guid_' . $tool->guid . '" class="common_tool_wrapper '.$scope.'">';
					$append		= '</span>';

					# Throw tool into admin panel array
					$tools_array[$tool->guid] = array(
						'guid'		=> $tool->guid,
						'name'		=> $tool->name,
						'name_id'	=> $tool->system_tool_id,
						'tool_id'	=> $tool->tool_id,
						'scope'		=> $scope,
					);
				}

				# Create Tool object
				$tool_object  = $prepend;				
				$tool_object .= Load_Tool::factory($tool->name)->_index($tool->tool_id);
				$tool_object .= $append;
				
				# Add tools to correct container.
				# if page_id <= 5, its not a real page_id = global container.
				(int) $index = (5 <= $tool->page_id)
					? $tool->container
					: $tool->page_id ;
				$containers_array[$index] .= $tool_object;
			}
		}
		
		# Drop Tool array into admin Panel if logged in
		if($admin_mode)
		{
			$this->template->set_global('tools_array', $tools_array);
		}
		else
		{
			# load tool css.
			$this->template->linkCSS("get/tool_css/live/$page->id");
			$this->template->admin_linkJS('get/js/live?v=1.0');
			
			# Add requested javascript files if any are valid.
			if(!empty($_SESSION['js_files']))
			{
				# Load Javascript
				$this->template->linkJS($_SESSION['js_files']);
				# Troubleshoot
				# echo '<pre>';print_r($_SESSION['js_files']); echo'</pre>'; die();		
			}
		}
		# Renew Javascript file requests
		unset($_SESSION['js_files']);	
		
		# Build the output and send to view
		parent::build_output($containers_array, $page->template);

		# needed to hide 404 not found on controller name (its really a page_name)
		Event::clear('system.404');
		# needed to enable auto rendering of controllers
		Event::run('system.post_controller');		
	}
}
/* -- end of application/controllers/build_page.php -- */