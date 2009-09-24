<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * All public_tool controllers extend this class.
 * used to factor common functionality 
 * and provide an interface and overloading access point.
 */
 
abstract class Public_Tool_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();
		$this->wrapper = new View('public_tool_wrapper');
	}
	
	
/*
  Builds a wrapper for each tool instance,
  adding toolname, tool_id, and attributes to the wrapper.
  
  Additionally this intelligently modularizes the tools assets when in admin mode.
	in admin mode the tool is self-contained within the wrapper.
	It houses HTML, inline CSS, and inline Javascript.
	This allows full in-browser updates and editing.
	
	In public mode the public wrapper only houses the html output.
	CSS is handled via /get/css/tools/<page_id> which fetches all tool css based on page id
	
	JS is handled via the View library which builds an appended variable of js content.
	to load into the public_javascript variable @ shell view.
 */	
	public function wrap_tool($view, $toolname, $tool=NULL, $sub_tool=FALSE)
	{
		$this->wrapper->primary		= $view;
		$this->wrapper->toolname	= $toolname;
		$this->wrapper->parent_id	= (empty($tool)) ? '' : $tool->id;
		$this->wrapper->attributes	= (empty($tool)) ? '' : $tool->attributes;
		$this->wrapper->custom_css	= '';
		
		# should we modularize the CSS ?
		if($this->client->can_edit($this->site_id) OR TRUE === $sub_tool)
		{
			# if we switch themes while still in admin mode, the tool_css file
			# will not exist relative to the new theme. We have to create it.
			$custom_css	= $this->assets->themes_dir("$this->theme/tools/$toolname/_created/$tool->id/{$tool->type}_$tool->view.css");
			
			// ---- legacy cleanup: delete the old "css" folders
			$old_folder	= $this->assets->themes_dir("$this->theme/tools/$toolname/css");
			if(is_dir($old_folder))
				Jdirectory::remove($old_folder);
			// ---- end cleanup

			$css = (file_exists($custom_css))
				? file_get_contents($custom_css)
				: Tool_Controller::_generate_tool_css($toolname, $tool->id, $tool->type, $tool->view, $this->site_name, $this->theme, TRUE);

			$this->wrapper->custom_css = "
				<style type=\"text/css\" id=\"$toolname-$tool->id-style\">
					$css
				</style>
			";
		}
		return $this->wrapper;
	}

	
	
/*
 * places a tool's javascript in the appropriate place based on whether or not
 * the site owner is logged in, and also how the tool is being represented/loaded.
 
	$reload specifies whether or not to reload the javascript with the tool when in admin mode.
	** We dont want to reload delegated javascript (such as blog js).
 */ 
	public function place_javascript($js, $reload=FALSE)
	{
		# what do we do with the javascript?
		if($this->client->can_edit($this->site_id))
		{
			# inject the javascript via ajax?
				# GET[js] is only set for the /get/tool/html loader.
				# so we do this when NOT running /get/tool/html.
			if($reload OR !isset($_GET['js']) OR 'yes' == $_GET['js'])
			{
				$this->wrapper->readyJS = "
					<script type=\"text/javascript\">
						$(document).ready(function(){
							$js
						});
					</script>
				";
			}
			
			return null;
		}

		return $js; 
	}

	
/*
 * parses stored html for tool tokens and replaces those tokens with
 * appropriate HTML output.
 */
	public function public_parse($body)
	{
		# we are doing newsletter only as of now.
		str_replace('{newsletter}', '', $body, $count);
	
		if(0 < $count)
		{
			$pages_config = yaml::parse($this->site_name, 'pages_config');
			if(empty($pages_config['newsletter']))
				return $body;

			$parent_id = explode('-', $pages_config['newsletter']);
			$parent_id = $parent_id['1'];
		
			# get the newsletter HTML.
			$newsletter = new Newsletter_Controller;
			$body = str_replace('{{newsletter}}', $newsletter->_index($parent_id), $body);
		}
		return $body;
	}
	
	
/*
 * protected pages must maintain their page_name path
 * especially in cases of ajax requests or when on homepage
 # quick hack, optimize later...
 # we can probably do this using pages_config.yaml
 */
	public function get_page_name($page_name, $toolname, $parent_id)
	{
		if(! empty($page_name) )
			if('get' == $page_name)
				return yaml::does_value_exist($this->site_name, 'pages_config', "$toolname-$parent_id");
			else
				return $page_name;
		
		return $this->homepage;
	}	



/*
 * ajax handler for protected tools.
 */ 
	public function _ajax($url_array, $tool_id)
	{
		die('not a valid ajax request.');
	}
	
	
/*
 * is called when a tool gets added to the system.
 */
	public static function _tool_adder($parent_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{
			/* do some stuff to create sample assets */
		}

		return 'add';
	}	
	
	
	

} # End



