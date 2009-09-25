<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * Renders fully wrapped site pages.
 * this is the interface from which full html pages are sent to the browser.
 * data, css, js, and admin resources are all bundled up HERE for output.
 * 
 */
 
class Build_Page_Controller extends Controller {

	public $serve_cache = FALSE;
	public $serve_plusjade_home = FALSE;
	
	function __construct()
	{
		parent::__construct();
		# $this->profiler = new Profiler;		
		$this->template = new View('shell');	
		
		# Global CSS			
		if(!$this->client->can_edit($this->site_id))
			$this->template->linkCSS("_data/$this->site_name/themes/$this->theme/css/global.css?v=1.0");
	}
 
/*
 * gets tools associated with a page, and formats them properly for inclusion
 * into the page wrapper.
 * $page = (object) pages table row
 */
	public function _index($page)
	{
		# is the page public?
		if('no' == $page->enable AND !$this->client->can_edit($this->site_id))
			Event::run('system.404');
				
		# can we serve a cached page?
		if($this->serve_cache AND !$this->client->can_edit($this->site_id) AND file_exists(DATAPATH."$this->site_name/cache/$page->id.html"))
		{
			header('Content-Type: text/html; charset=iso-8859-1');
			readfile(DATAPATH."$this->site_name/cache/$page->id.html");
			die();
		}
			
		$db	= new Database;
		$data					= array(' ',' ',' ',' ',' ',' ');
		$tools_array			= array();
		$_SESSION['js_files']	= array();
		$primary				= '';
		$prepend				= '';
		$append					= '';
		

		$this->template->set_global('title', $page->title);
		$this->template->meta_tags('description', $page->meta);
		$this->template->set_global('this_page_id', $page->id);	
		
		# Grab tools for this page referencing the pivot table: "pages_tools"
		# 0-10 are reserved for global tools. we only use 1-5 
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
		
		# Load Admin CSS and Javascript (if logged in)
		$admin_mode = $this->load_admin($page->id, $page->page_name);
		
		
		# plusjade rootsite account hook functionality
		if(ROOTACCOUNT === $this->site_name)
		{
			# do we serve plusjade homepage?
			# do we serve plusjade utada controller?
			if($this->serve_plusjade_home AND $this->homepage == $page->page_name)
			{
				$home = new Home_Controller();
				$data['1'] = $home->_index();	
			}
			else if('utada' == $page->page_name AND $this->client->can_edit($this->site_id))
			{	
				$utada = new Utada_Controller();
				$data['1'] = $utada->_index();
			}
		}

		if($tools->count() > 0)
		{	
			foreach ($tools as $tool)
			{		
				# load the tool parent
				$parent = ORM::factory($tool->name)
					->where('fk_site', $this->site_id)
					->find($tool->parent_id);	
				if($parent->loaded)
				{
					# If Logged in wrap classes around tools for Javascript
					# TODO: consider this with javascript
					if($this->client->can_edit($this->site_id))
					{
						$scope		= ('5' >= $tool->page_id) ? 'global' : 'local';
						$prepend	= '<span id="instance_' . $tool->instance_id . '" class="common_tool_wrapper '.$scope.'" rel="tool_'. $tool->tool_id .'">';
						$append		= '</span>';

						# Throw tool into admin panel array
						$tools_array[$tool->instance_id] = array(
							'instance'	=> $tool->instance_id,
							'tool_id'	=> $tool->tool_id,
							'parent_id'	=> $tool->parent_id,
							'name'		=> $tool->name,
							'name_id'	=> $tool->system_tool_id,
							'scope'		=> $scope,
						);
					}
				
					# build tool output
					$tool_object  = $prepend;				
					$tool_object .= Load_Tool::factory($tool->name)->_index($parent);
					$tool_object .= $append;
				}
				elseif($this->client->can_edit($this->site_id))
				{
					# show the tool error when logged in.
					$tool_object = "$tool->name with id: $tool->parent_id could not be loaded.";
				}
				
				# Add output to correct container.
				# if page_id <= 5, its not a real page_id = global container.
				(int) $index = (5 <= $tool->page_id)
					? $tool->container
					: $tool->page_id ;
				$data[$index] .= $tool_object;
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
				# kohana::debug($_SESSION['js_files']); die();		
			}
		}
		# Renew Javascript file requests
		unset($_SESSION['js_files']);	
	
		# Can we cache this page?
		# TODO need a good way to properly regulate this.
		$cache = FALSE;
		if(!$this->client->can_edit($this->site_id) AND !file_exists(DATAPATH."$this->site_name/cache/$page->id.html"))
			if(FALSE === yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
			{
				if(!is_dir(DATAPATH . "$this->site_name/cache"))
					mkdir(DATAPATH . "$this->site_name/cache");
					
				$cache = $page->id;
			}
		
		$this->wrapper($data, $cache);
	}

	
/*
 * output a nicer 404 error wrapped inside the sites template.
 * pretty 404 is enabled by the custom_404 hook.
 */
	public function _custom_404($message=NULL)
	{
		if(empty($message))
		{
			#get the custom error message from the site settings?
			$message = 'This Page does not exist<br/>Please ensure the page name was spelled correctly. Thank you!';
		}
		$this->template->set_global('title', 'Page Not Found.');
		
		# can we serve a cached page?
		if($this->serve_cache AND !$this->client->can_edit($this->site_id) AND file_exists(DATAPATH."$this->site_name/cache/404_not_found.html"))
		{
			header('Content-Type: text/html; charset=iso-8859-1');
			readfile(DATAPATH."$this->site_name/cache/404_not_found.html");
			die();
		}
		
		header("HTTP/1.0 404 Not Found");
		$this->wrapper($message, '404_not_found', FALSE);
	}
	

/*
 * data is composed of tools data sent from build_page.php or other admin data
 * from auth/utada.php that needs to be wrapped in a theme-based shell.	
 * the final step for the plusjade pages. inputs $data into the site template.
 * expects a an array matching the appropriate containers
 */
	private function wrapper($data, $cache=FALSE, $exists=TRUE)
	{

 
		$banner		= View::factory('_global/banner');
		$menu		= View::factory('_global/menu');
		$template 	= (empty($page->template)) ? 'master' : $page->template;
		$path		= $this->assets->themes_dir("$this->theme/templates");
		
		ob_start();
		# fetch the template
		if (file_exists("$path/$template.html"))
			readfile("$path/$template.html");	
		else
		{
			if(!file_exists("$path/master.html"))
			{
				$rootsite = ROOTDOMAIN;
				die("Missing 'master.html' for theme: $this->theme : <a href=\"http://$rootsite/get/auth\">enter safe-mode</a>");
			}
			readfile("$path/master.html");
			$this->template->error = "<h1 class=\"aligncenter\">Invalid '$template.html' for theme: $this->theme, using master.html</h1>";
		}

		# filter the template to only include data between <body> tags.
		$string = " ". ob_get_clean();
		$ini = strpos($string, '<body>');
		if ($ini == 0)
			$master = '';
		else
		{
			$ini += strlen('<body>');   
			$len = strpos($string, '</body>', $ini) - $ini;
			$master = substr($string, $ini, $len);
		}
		
		# format the main content data.
		$keys = array(
			'%BANNER%',
			'%MENU%',
		);
		$replacements = array(
			$banner,
			$menu,
		);

		# 5 containers
		if(!is_array($data))
			$data = array(' ', $data,' ', ' ', ' ', ' ');
			
		foreach($data as $key => $content)
		{
			array_push($keys, "%CONTAINER_$key%");
			array_push($replacements, $content);
		}
		
		
		# Add login to +Jade
		if(ROOTACCOUNT == $this->site_name )
		{
			array_push($keys, "%LOGIN%");
			array_push($replacements, View::factory("_global/login"));
		}
		
		# TODO: Look into compression for this ...
		# put the formatted data into the template.
		$this->template->output = str_replace($keys, $replacements , $master);

		# build the end_body contents
		# It is bad to open 2 buffers, fix this
		if (file_exists(DATAPATH . "$this->site_name/tracker.html"))
		{
			readfile(DATAPATH . "$this->site_name/tracker.html");	
			$this->template->end_body = ob_get_clean();
		}
		
		# do we cache the fully rendered page?
		if($cache)
		{
			$date = date('m.d.y g:ia e');
			if(!is_dir(DATAPATH . "$this->site_name/cache"))
				mkdir(DATAPATH . "$this->site_name/cache");
			file_put_contents(DATAPATH . "$this->site_name/cache/$cache.html", $this->template->render() . "\n<!-- cached $date -->");
		}
		
		die($this->template);		
	}
	
	
/*
 * Load Assets for Admin edit mode
 */ 
	private function load_admin($page_id, $page_name)
	{	
		if($this->client->can_edit($this->site_id))
		{
			# pass global css as modular INLINE.
			$css_path = $this->assets->themes_dir("$this->theme/css/global.css");
			$css = (file_exists($css_path))
				? file_get_contents($css_path)
				: '/* global.css file does not exist. Please create it.*/';
			$this->template->inline_global_css = "<style type=\"text/css\" id=\"global-style\">\n$css\n</style>\n";
			
			# load admin global css and javascript.
			$this->template->linkCSS('get/tool_css/admin');
			$this->template->admin_linkJS('get/js/admin?v=1.1');

			# get list of protected tools to compare against so we can omit scope link			
			$protected_tools = ORM::factory('system_tool')
				->where('protected', 'yes')
				->find_all();
			$protected_array = array();
			foreach($protected_tools as $tool)
				$protected_array[] = $tool->id;

			# Log in the $account_user admin account.
			if(!$this->account_user->logged_in($this->site_id))
				$this->account_user->force_login('admin', (int)$this->site_id);
			
			
			# is this website claimed?
			$days	= 0;
			$hours	= 0;
			$mins	= 0;
			/*
			if(empty($this->claimed) AND !empty($_SESSION['created']))
			{
				$expires = $_SESSION['created'] + (86400*7);
				$diff = $expires - time();
				if($diff > 0)
				{
					$days = floor($diff/86400);
					$diff = $diff - ($days*86400);
					if($diff > 0)
					{
						$hours = floor($diff/3600);
						$diff = $diff - ($hours*3600);
						if($diff > 0)
							$mins = floor($diff/60);
					}
				}
			}
			*/
			
			# activate admin_panel view.
			$this->template->admin_panel =
				view::factory(
					'admin/admin_panel',
					array(
						'protected_array'	=> $protected_array,
						'page_id'			=> $page_id,
						'page_name'			=> $page_name,
						'global_css_path'	=> "/_data/$this->site_name/themes/$this->theme/css/global.css?v=23094823-",
						'expires'			=> array('days' => $days, 'hours' => $hours, 'mins' => $mins)
					)
				);
			return TRUE;
		}
		return FALSE;
	}

	
}
/* -- end of application/controllers/build_page.php -- */