<?php
class Build_Page_Controller extends Template_Controller {

	/*
	 * Renders live client pages.
	 * Accepts a valid page w/ data from the db sent from build_page HOOK.
	 * Grabs all tools associated with this page, places them correctly.
	 * Proceeds to build the page.
	 * Output to "shell" via template controller 
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}
  
	# $data = (array) plusjade.pages row-data
	function _index($data)
	{
		$db			= new Database;
		$page_id	= $data['id'];	
		$_SESSION['js_files']	= array();
		$containers_array		= array(' ',' ',' ',' ',' ');		
		$tools_array			= array();
		$generic_tools			= array();
		$all_tools				= array();		
		$primary	= '';
		$prepend	= '';
		$append		= '';
		
		$this->template->title 	= $data['title'];
		$this->template->meta_tags('description', $data['meta']);
		$this->template->set_global('selected', $data['page_name']);	
		$this->template->set_global('page_id', $page_id);
		
		/*
		 * Grab tools for this page in pages_tools table
		 * 0-10 are reserved for global tools. we only use 1-5 though
		 * 
		 */		 
		$tools = $db->query("SELECT * FROM pages_tools 
			JOIN tools_list ON tools_list.id = pages_tools.tool
			WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page_id')
			AND fk_site = '$this->site_id'
			ORDER BY container, position
		");

		# Load Admin CSS and Javascript (if logged in)
		$admin_mode = $this->_load_admin();
		
		if( $tools->count() > 0 )
		{		
			foreach ($tools as $tool)
			{
				# If Logged in wrap classes around tools for Javascript
				# TODO: consider this with javascript
				if( $this->client->logged_in() )
				{			
					$scope = 'local';
					if( '5' >= $tool->page_id ) $scope = 'global';
		
					$prepend	= '<span id="' . $tool->guid . '" class="common_tool_wrapper guid_'. $tool->guid . '" rel="' . $scope . '">';
					$append		= '</span>';
				}
				
				# Create unique Tool array for CSS	
				$all_tools[] = "$tool->tool.$tool->tool_id";

				# Throw tool into admin panel array
				$tools_array[$tool->guid] = array(
					'guid'		=> $tool->guid,
					'name'		=> strtolower($tool->name),
					'name_id'	=> $tool->tool,
					'tool_id'	=> $tool->tool_id,
				);

				# Create Tool object
				$tool_object  = $prepend;				
				$tool_object .= Load_Tool::factory($tool->name)->_index($tool->tool_id);
				$tool_object .= $append;
				
				# Add tools to correct container.				
				$index = $tool->container;
				if('5' >= $tool->page_id ) $index = $tool->page_id; #...if global

				$containers_array[$index] .= $tool_object;		
			}
			
			# Load Public CSS For Tools
			$generic_tools	= implode('-', $generic_tools);
			$all_tools		= implode('-', $all_tools);
			
			$this->template->linkCSS("get/css/tools/$all_tools", url::site() );		
			
		}
		
		
		# Drop Tool array into admin Panel if logged in
		if($admin_mode)
			$this->template->set_global('tools_array', $tools_array);			
	
		# Load Javascript files if they exist.
		if (! empty($_SESSION['js_files']) AND is_array($_SESSION['js_files']) )
		{
			# Load Javascript
			$this->template->linkJS($_SESSION['js_files']);

			# Load PUBLIC CSS required for Javascript associated with Tools		
			foreach($_SESSION['js_files'] as $file => $javascript)
			{
				$path = DOCROOT."js/$file/style.css";
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
		
		#echo '<PRE>';print_r($containers_array);echo '</PRE>'; die();
		
		# needed to hide 404 not found on controller name
		Event::clear('system.404');
		
		# this hook needed to enable auto rendering of controllers
		Event::run('system.post_controller');		
		
	}
}
/* -- end of application/controllers/build_page.php -- */