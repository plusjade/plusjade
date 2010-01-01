<?php defined('SYSPATH') or die('No direct script access.');


/**
 * dynamically compile the css for the site.
 * this includes global and per-page css.
 * this should always cache the results.
 */
 
class Css_Controller extends Controller {

  private $cache_dir;
  
  
  function __construct()
  {
    parent::__construct();
    
    $this->cache_dir =  $this->assets->themes_dir("$this->theme/cache");
    if(!is_dir($this->cache_dir))
      mkdir($this->cache_dir);
  }

  /**
   * Returns a singleton instance of css.
   *
   * @return  object
   */
  public static function instance()
  {
    static $instance;

    if ($instance == NULL)
    {
      // Initialize the URI instance
      $instance = new Css_Controller;
    }

    return $instance;
  }
/*
 * build a new global css for the website
 * generate everything as new and overwrite the current cache.
 * outputs css and saves as global.css in the theme cache folder.
 */
  public function master()
  {
    ob_start();

    # get the global sass file.
    $global_sass  = $this->assets->themes_dir("$this->theme/css/global.sass");
    if(file_exists($global_sass))
      echo Kosass::factory('compact')->compile(file($global_sass));
    
    /*
    # attempt to get the theme templates for tools.
    $theme_templates  = $this->assets->themes_dir("$this->theme/css/tool_templates");
    
    # parse the config file to see which we should add.
    if(file_exists("$theme_templates/config.yml"))
    {
      $templates = yaml::parse($this->site_name, 'config', "themes/$this->theme/css/tool_templates");
      
      foreach($templates as $file => $add)
        if('yes' == $add AND file_exists("$theme_templates/$file.sass"))
          echo Kosass::factory('compact')->compile(file("$theme_templates/$file.sass"));
    }
    */
    # add the static helpers.
    $static_helpers = DOCROOT . '_assets/css/static_helpers.css';
    if (file_exists($static_helpers))
      readfile($static_helpers);  
      
    # Load any tool-css needed for javascript functionality.
      # provide a way to automatically load stuff based on tool config file?
      # for now the only instance is the lightbox css.
      # so blah just always load it.
    if(file_exists(DOCROOT . "_assets/js/lightbox/style.css"))
      readfile(DOCROOT . "_assets/js/lightbox/style.css");
  
  
    # cache the full result as the live global css file.
    file_put_contents("$this->cache_dir/global.css", ob_get_clean());
    
    return TRUE;
  }
  
  
  
  
/*
 * get user custom css for all tools on a page
 * these files should be 100% ready for output.
 * That is any tokens should have been parsed before saved.
 * tool-css files are saved relative to the installed theme.
 * ex: /_data/<site_name>/themes/<theme_name>/tools/<toolname>/<tool_id>.css 
 */
  public function page($page_id=NULL)
  {
    valid::id_key($page_id);

    ob_start();
    
    #TODO: if any tools are protected on this page, search for a possible
    # theme template and load that within the page css.
    # Is page_name protected?
    #$page_config_value = yaml::does_key_exist($this->site_name, 'pages_config', $page_name);
    
      
    # parse custom page sass file if it exists.
    $page_sass  = $this->assets->themes_dir("$this->theme/pages/$page_id.sass");
    if(file_exists($page_sass))
      echo Kosass::factory('compact')->compile(file($page_sass));

    # load custom tool css files
    $db = new Database;
    # get all tools that are added to this page.
    $tool_data = $db->query("
      SELECT *, LOWER(system_tools.name) AS name, tools.id AS guid
      FROM pages_tools 
      JOIN tools ON pages_tools.tool_id = tools.id
      JOIN system_tools ON tools.system_tool_id = system_tools.id
      WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page_id')
      AND pages_tools.fk_site = '$this->site_id'
      ORDER BY pages_tools.container, pages_tools.position
    ");
  
    $tool_dir = $this->assets->themes_dir("$this->theme/tools");
    foreach($tool_data as $tool)
    {
      # get the type and the view from the system.
      # TODO: try and optimize this later.
      $table = ORM::factory($tool->name)
        ->where('fk_site', $this->site_id)
        ->find($tool->parent_id);
    
      $custom_file = "$tool_dir/$tool->name/$tool->parent_id/{$table->type}_$table->view.css";
      if(file_exists($custom_file))
        readfile($custom_file);
    }
    
    # cache the full result as the live global css file.
    file_put_contents("$this->cache_dir/$page_id.css", ob_get_clean());

    return TRUE;
  }


  
/*
 * load admin_global.css & all admin css from all tools. 
 * and load it as one file. useful when in admin mode
 */
  public function admin()
  {
    if(!$this->client->can_edit($this->site_id))
      die('Please login');

    ob_start();  

    $static_helpers = DOCROOT . '_assets/css/static_helpers.css';
    if (file_exists($static_helpers))
      readfile($static_helpers);
      
    # get the admin_global.css content
    $admin_global = DOCROOT . '_assets/css/admin_global.css';
    if(file_exists($admin_global))
      readfile($admin_global);
    
    # load all admin-mode tool css files.
    $system_tools = ORM::factory('system_tool')
      ->select('LOWER(name) AS name')
      ->find_all();

    foreach($system_tools as $tool)
    {
      $admin_css  = MODPATH . "$tool->name/views/edit_$tool->name/admin.css";
      
      if(file_exists($admin_css))
        readfile($admin_css);
    }

    # Load any tool-css needed for javascript functionality.
      # provide a way to automatically load stuff based on tool config file?
      # for now the only instance is the lightbox css.
    $lightbox_css = DOCROOT . '_assets/js/lightbox/style.css';
    if(file_exists($lightbox_css))
      readfile($lightbox_css);

    # cache the full result as the live global css file.
    file_put_contents(DOCROOT . '_assets/css/admin.css', ob_get_clean());
  }

  

  
  
}




/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */