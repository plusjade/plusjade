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
  Builds a wrapper for each tool instance, adding toolname, tool_id, and attributes to the wrapper.
  
  Intelligently modularizes the tools assets when in admin mode.
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
    $this->wrapper->primary     = $view;
    $this->wrapper->toolname    = $toolname;
    $this->wrapper->parent_id   = (empty($tool)) ? '' : $tool->id;
    $this->wrapper->attributes  = (empty($tool)) ? '' : $tool->attributes;
    $this->wrapper->custom_css  = '';
    
    # should we modularize the CSS ?
    if($this->client->can_edit($this->site_id) OR TRUE === $sub_tool)
    {
      # load any custom tool css.
      $custom_file  = $this->assets->themes_dir("$this->theme/tools/$toolname/$tool->id/{$tool->type}_$tool->view.css");
      $css = (file_exists($custom_file))
        ? file_get_contents($custom_file)
        : '';
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
      
      return NULL;
    }

    return $js; 
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



