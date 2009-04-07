<?php
class Build_Page_Controller extends Template_Controller {

	/*
	 * Every live client page is rendered via this controller.
	 * Accepts a valid page w/ data from the db sent from build_page HOOK.
	 * Queries for all tools on this page and displays them accordingly.
	 * Proceeds to build the page.
	 */
	function __construct()
	{
		parent::__construct();
	}
  
	# Data = page table row data in array format
	function _index($data)
	{
		$db				= new Database;
		$page_id		= $data['id'];
		$tools_array			= array();
		$_SESSION['js_files']	= array();
		$primary		= '';
			
		# Load assets from pages table
		$this->template->title 	= $data['title'];
		$this->template->meta_tags('description', $data['meta']);
		$this->template->set_global('selected', $data['page_name']);	
		$this->template->set_global('page_id', $page_id);
			
		# Grab tools for this page ORDER by position
		# searches "pages_tools" for matching page_id
		$tools = $db->query("SELECT * 
			FROM pages_tools 
			JOIN tools_list ON tools_list.id = pages_tools.tool
			WHERE page_id = '$page_id'
			AND fk_site = '$this->site_id'
			ORDER BY position");

		# Load Admin CSS and Javascript (if logged in)
		$admin_mode = $this->_load_admin();
		
		if( $tools->count() > 0 )
		{
			$css_array				= array();
			$prepend				= '';
			$append					= '';
			
			# If Logged in wrap classes around tools for Javascript
			if( $this->client->logged_in() )
			{
				$prepend	= '<span class="common_tool_wrapper">';
				$append		= '</span>';
			}
			
			# Loop through all tools on page
			foreach ($tools as $tool)
			{
				# Create unique Tool array for CSS			
				$css_array[$tool->name] = $tool->name;	

				# Throw tool into admin panel array
				$tools_array[$tool->position] = $tool->guid.'|'.$tool->name.'|'.$tool->tool_id;
						
				# Create Tool object
				$tool_object = Load_Module::factory($tool->name);			
								
				# Render tool output to page view
				$primary .= $prepend;
				$primary .= $tool_object->_index($tool->tool_id);
				$primary .= $append;
			}
			
			# Load Public CSS For Tools
			$css_string = implode('-', $css_array);
			$this->template->linkCSS("css/tools/$this->site_name/$this->theme/$css_string");		
			
		}
		else
		{
			$primary .= '<div style="text-align:center">This page is blank</div>';
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
				if(file_exists($path))
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
			
		# Render the view
		$this->template->primary = $primary;
		
		# needed to hide 404 not found on controller name
		Event::clear('system.404');
		
		# this hook needed to enable auto rendering of controllers
		Event::run('system.post_controller');		
		
	}
}

/* -- end of application/controllers/about.php -- */