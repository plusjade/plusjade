<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Public_Tool_Controller extends Controller {

/*
 * All public_tool controllers extend this class.
 */
	public function __construct()
	{
		parent::__construct();
	}

/*
  Each initial tool view is called view <toolname>::_index()
  in public view the index displays only html
  in admin view each tool_output has to be 100% self_contained.
	so we inject appropriate CSS, html, and javascript =D
	
	## rename to "tool_view_template"
 */	
	public function public_template($primary, $toolname, $tool_id, $attributes='')
	{
		$template				= new View('public_tool_wrapper');		
		$template->primary		= $primary;
		$template->toolname		= $toolname;
		$template->tool_id		= $tool_id;
		$template->attributes	= $attributes;
		$template->readyJS		= '';
		$template->custom_css	= '';
		
		if($this->client->can_edit($this->site_id))
		{
			# Get CSS
			$custom_css	= $this->assets->themes_dir("$this->theme/tools/$toolname/css/$tool_id.css");
			$contents	= (file_exists($custom_css))
				? file_get_contents($custom_css)
				: Tool_Controller::_generate_tool_css($toolname, $tool_id, $this->site_name, $this->theme, TRUE);

			$template->custom_css = "
				<style type=\"text/css\" id=\"$toolname-$tool_id-style\">
					$contents
				</style>
			";			
			
			# Get Javascripts
			# grab the index javascript and insert it inline.
			$js_file = MODPATH . "$toolname/views/public_$toolname/js/index.js";
			if(file_exists($js_file))
			{
				$contents = file_get_contents($js_file);			
				$contents = str_replace('%VAR%', $tool_id , $contents);
				$template->readyJS = "
					<script type=\"text/javascript\">
						$(document).ready(function(){
							$contents
						});
					</script>
				";
			}
		}
		else
		{
			/* # public view:
			 *	css is handled via /get/css/tools/page_id link
			 *	js is handled in the same way @ view library
			 */
			$template->readyJS($toolname, 'index', $tool_id);
		}	
		
		return $template;
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