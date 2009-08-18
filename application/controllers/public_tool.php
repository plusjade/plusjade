<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * All public_tool controllers extend this class.
 */
 
abstract class Public_Tool_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();
		$this->template = new View('public_tool_wrapper');
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
	
		## rename to "tool_view_template"
 */	
	public function public_template($primary, $toolname, $tool_id, $attributes='', $sub_tool=FALSE)
	{
		$this->template->primary		= $primary;
		$this->template->toolname		= $toolname;
		$this->template->tool_id		= $tool_id;
		$this->template->attributes		= $attributes;
		$this->template->custom_css		= '';
		
		# should we modularize the CSS ?
		if($this->client->can_edit($this->site_id) OR TRUE === $sub_tool)
		{
			$custom_css	= $this->assets->themes_dir("$this->theme/tools/$toolname/css/$tool_id.css");
			$css = (file_exists($custom_css))
				? file_get_contents($custom_css)
				: Tool_Controller::_generate_tool_css($toolname, $tool_id, $this->site_name, $this->theme, TRUE);

			$this->template->custom_css = "
				<style type=\"text/css\" id=\"$toolname-$tool_id-style\">
					$css
				</style>
			";
		}
		return $this->template;
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
				$this->template->readyJS = "
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
 * protected pages must maintain their page_name path
 * especially in cases of ajax requests or when on homepage
 # quick hack, optimize later...
 # we can probably do this using pages_config.yaml
 */
	public function get_page_name($page_name, $toolname, $tool_id)
	{
		if(! empty($page_name) )
			if('get' == $page_name)
				return yaml::does_value_exist($this->site_name, 'pages_config', "$toolname-$tool_id");
			else
				return $page_name;
		
		return $this->homepage;
	}	

	

	
	
	

} # End



