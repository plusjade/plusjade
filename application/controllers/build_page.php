<?php
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
		if('no' == $page->enable AND !$this->client->logged_in() )
		{
			Event::run('system.404');
			die('Page Not Found');		
		}
	
		$db	= new Database;
		$containers_array		= array();
		$containers_array		= array_pad($containers_array, 5, ' ');
		$_SESSION['js_files']	= array();
		$tools_array			= array();
		$all_tools				= array();		
		$primary				= '';
		$prepend				= '';
		$append					= '';
		$all_tools_string		= null;
		
		
		$this->template->title 	= $page->title;
		$this->template->meta_tags('description', $page->meta);
		$this->template->set_global('this_page_id', $page->id);	
		
		# Grab tools for this page in pages_tools table
		# 0-10 are reserved for global tools. we only use 1-5 
		$tools = $db->query("
			SELECT * FROM pages_tools 
			JOIN tools_list ON tools_list.id = pages_tools.tool
			WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page->id')
			AND fk_site = '$this->site_id'
			ORDER BY container, position
		");

		# Load Admin CSS and Javascript (if logged in)
		# _load_admin() is in the template_controller
		$admin_mode = $this->_load_admin($page->id, $page->page_name);
		
		
		if( $tools->count() > 0 )
		{	
			foreach ($tools as $tool)
			{
				# If Logged in wrap classes around tools for Javascript
				# TODO: consider this with javascript
				if( $this->client->logged_in() )
				{
					$scope = ('5' >= $tool->page_id) ? 'global' : 'local';
					$prepend	= '<span id="guid_' . $tool->guid . '" class="common_tool_wrapper '.$scope.'">';
					$append		= '</span>';

					# Throw tool into admin panel array
					$tools_array[$tool->guid] = array(
						'guid'		=> $tool->guid,
						'name'		=> strtolower($tool->name),
						'name_id'	=> $tool->tool,
						'tool_id'	=> $tool->tool_id,
						'scope'		=> $scope,
					);
				}

				# Create unique Tool array so we can fetch only tool-css needed for this page.	
				$all_tools[] = "$tool->tool.$tool->tool_id";					


				# Create Tool object
				$tool_object  = $prepend;				
				$tool_object .= Load_Tool::factory($tool->name)->_index($tool->tool_id);
				$tool_object .= $append;
				
				# Add tools to correct container.				
				$index = $tool->container;
				if('5' >= $tool->page_id ) #...if global
					$index = $tool->page_id; 

				$containers_array[$index] .= $tool_object;		
			}		
		}
		
		# Drop Tool array into admin Panel if logged in
		if($admin_mode)
			$this->template->set_global('tools_array', $tools_array);
		else
			$this->template->linkCSS("get/css/tools/$page->id", url::site() );
		

		# Load Javascript files if they exist.
		if (! empty($_SESSION['js_files']) AND is_array($_SESSION['js_files']) )
		{
			# Load Javascript
			$this->template->linkJS($_SESSION['js_files']);

			# Load PUBLIC CSS required for Javascript associated with Tools
		# TODO:: I probably dont need this anymore - look into it.
			foreach($_SESSION['js_files'] as $file => $javascript)
			{
				$path = DOCROOT . "assets/js/$file/style.css";
				if( file_exists($path) )
				{
					# avoid duplicates for assets Admin uses
					if('facebox' != $file)
						$this->template->linkCSS("js/$file/style.css");
					elseif(! $this->client->logged_in() )
						$this->template->linkCSS("js/$file/style.css");
				}
			}
			
			# Troubleshoot
			# echo '<pre>';print_r($_SESSION['js_files']); echo'</pre>'; die();		
		}
		# Renew Javascript file requests
		unset($_SESSION['js_files']);		
			
		# Send the containers to the view (shell)
		$this->template->containers = $containers_array;			
		
		# needed to hide 404 not found on controller name (its really a page_name)
		Event::clear('system.404');
		
		# needed to enable auto rendering of controllers
		Event::run('system.post_controller');		
	}
}
/* -- end of application/controllers/build_page.php -- */