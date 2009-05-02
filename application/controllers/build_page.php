<?php
class Build_Page_Controller extends Template_Controller {

	/*
	 * Renders live client pages.
	 * Queries for page_name
	 * Grabs all tools associated with this page, places them correctly.
	 * and  outputs the view "shell" via template controller.
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}
  
	function _index()
	{
		$db	= new Database;

		# get the page_name from the url
		$pieces = explode('/', $_SERVER['REQUEST_URI']);
		$page_name = $main	= ( empty($pieces['1']) ) ? 'home' : $pieces['1'];
		
		#PAGE NAME CHECKS (reserved names are "showroom" and "blog" )
		if(! empty($pieces['1']) AND 'showroom' != $pieces['1'] AND 'blog' != $pieces['1'] )
		{
			$page_name = strstr($_SERVER['REQUEST_URI'], '/');
			$page_name = ltrim($page_name, '/');
		}
		
		# Grant access to all pages if logged in.
		$check_enabled = " AND enable = 'yes' ";
		if( $this->client->logged_in() )
			$check_enabled = '';
			
		# Grab the page row
		$page = $db->query("SELECT * FROM pages 
			WHERE fk_site = '$this->site_id' 
			AND page_name = '$page_name' $check_enabled
		")->current();
		
		# if page doesnt exist
		if (! is_object($page) )
		{
			echo '<div class="aligncenter">Page Not Found</div>';
			Event::run('system.404');
			die();
		}
		
		$containers_array		= array(' ',' ',' ',' ',' ');
		$_SESSION['js_files']	= array();
		$tools_array			= array();
		$generic_tools			= array();
		$all_tools				= array();		
		$primary	= '';
		$prepend	= '';
		$append		= '';
		
		$this->template->title 	= $page->title;
		$this->template->meta_tags('description', $page->meta);
		$this->template->set_global('selected', $page->page_name);	
		$this->template->set_global('page_id', $page->id);
		
		/*
		 * Grab tools for this page in pages_tools table
		 * 0-10 are reserved for global tools. we only use 1-5
		 */		 
		$tools = $db->query("SELECT * FROM pages_tools 
			JOIN tools_list ON tools_list.id = pages_tools.tool
			WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page->id')
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
		
		#echo '<PRE>';print_r($containers_array);echo '</PRE>'; die();
		
		# needed to hide 404 not found on controller name
		Event::clear('system.404');
		
		# this hook needed to enable auto rendering of controllers
		Event::run('system.post_controller');		
		
	}
}
/* -- end of application/controllers/build_page.php -- */