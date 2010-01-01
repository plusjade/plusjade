<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * Renders fully wrapped site pages.
 * this is the interface from which full html pages are sent to the browser.
 * data, css, js, and admin resources are all bundled up HERE for output.
 * 
 * it also here where everything is cached and cache management happens in general.
 */
 
class Build_Page_Controller extends Controller {

  # currently non-protected pages can be fully cached.
  # should only be set to false for debugging.
  public $serve_page_cache = TRUE;
  public $reset_css_cache  = FALSE;
  public $cache_dir;
  public $cache_url;
  public $tool_dir;
  public $page_css = ''; #holder to build the page_css if not exists
  public $build_css = FALSE;
  public $save_page_cache_as = FALSE;
  
  
  function __construct()
  {
    parent::__construct();

    $this->template    = new View('shell');
    $this->cache_dir   =  $this->assets->themes_dir("$this->theme/cache");
    $this->cache_url   = "/_data/$this->site_name/themes/$this->theme/cache";
    $this->tool_dir    = $this->assets->themes_dir("$this->theme/tools");  

    # make sure the cache dir exists.
    if(!is_dir($this->cache_dir))
      mkdir($this->cache_dir);
      
    # Global CSS 
    $this->load_global_css();
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
    
    $this->serve_page_cache($page->id);
    
    $this->page_css_cache($page->id);
  
    $db  = Database::Instance();
    $_SESSION['js_files']  = array();
    $data          = array(' ',' ',' ',' ',' ',' ');
    $tools_array   = array();
    $prepend       = '';
    $append        = '';
    

    $this->template->set_global('title', $page->title);
    $this->template->meta_tags('description', $page->meta);
    $this->template->set_global('this_page_id', $page->id);  
    
    # Grab tools for this page referencing the pivot table: "pages_tools"
    # 0-10 are reserved for global tools. we only use 1-5 
    $tools = $db->query("
      SELECT *, LOWER(system_tools.name) AS name, system_tools.protected, pages_tools.id AS instance_id
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
      $data[1] = $this->plusjade_hook($page->page_name);


    # get the tools for this page.
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
            $scope    = ('5' >= $tool->page_id) ? 'global' : 'local';
            $prepend  = '<span id="instance_' . $tool->instance_id . '" class="common_tool_wrapper '.$scope.'" rel="tool_'. $tool->tool_id .'">';
            $append   = '</span>';

            # Throw tool into admin panel array
            $tools_array[$tool->instance_id] = array(
              'instance'  => $tool->instance_id,
              'tool_id'   => $tool->tool_id,
              'parent_id' => $tool->parent_id,
              'name'      => $tool->name,
              'name_id'   => $tool->system_tool_id,
              'scope'     => $scope,
            );
          }
        
          # build tool output
          $c_name = ucfirst($tool->name).'_Controller';
          $controller = new $c_name();
          $output = $controller->_index($parent); 
          $tool_view = "$prepend$output$append";
        
          # if we need to build the page_css file get the tool css.
          if($this->build_css)
          {
            # Is tool protected?
            if('yes' == $tool->protected)
            {
              # does a theme css template exist?
              $theme_templates  = $this->assets->themes_dir("$this->theme/css/tool_templates");
              #$templates = yaml::parse($this->site_name, 'config', "themes/$this->theme/css/tool_templates");
              if(file_exists("$theme_templates/{$parent->type}_$parent->view.sass"))
                $this->page_css .= Kosass::factory('compact')->compile(file("$theme_templates/{$parent->type}_$parent->view.sass"));
            
            }
            # does custom css file exist for tool?
            $custom_file = "$this->tool_dir/$tool->name/$tool->parent_id/{$parent->type}_$parent->view.css";
            if(file_exists($custom_file))
              $this->page_css .= file_get_contents($custom_file);
          }
        }
        elseif($this->client->can_edit($this->site_id))
        {
          # show the tool error when logged in.
          $tool_view = "$tool->name with id: $tool->parent_id could not be loaded.";
        }
        
        # Add output to correct container.
        # if page_id <= 5, its not a real page_id = global container.
        (int) $index = (5 <= $tool->page_id)
          ? $tool->container
          : $tool->page_id ;
        $data[$index] .= $tool_view;
      }
    }
    
    # cache the css for this page if set.
    if($this->build_css)
      file_put_contents("$this->cache_dir/$page->id.css", $this->page_css);
    
    
    # Tool array into admin Panel if logged in
    if($admin_mode)
      $this->template->set_global('tools_array', $tools_array);
    else
    {
      # load page css with tool css instances.
      $this->template->linkCSS("$this->cache_url/$page->id.css?v=1.0");

      # load the global javascript.
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
  
    # Should we cache this page?
    # always cache IF no existing cache, live and non-protected page
    if(!$this->client->can_edit($this->site_id) AND !file_exists(DATAPATH."$this->site_name/cache/$page->id.html"))
      if(FALSE === yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
        $this->save_page_cache_as = $page->id;
    
    $this->wrapper($data, $page->template);
  }

  
/*
 * output a nicer 404 error wrapped inside the sites template.
 * pretty 404 is enabled by the custom_404 hook.
 TODO: allow custom error message and custom 404 template.
 */
  public function _custom_404($message=NULL)
  {
    header("HTTP/1.0 404 Not Found");
    $this->serve_page_cache('404_not_found');
    
    if(empty($message))
      $message = 'This Page does not exist<br/>Please ensure the page name was spelled correctly. Thank you!';
      
    $this->template->set_global('title', 'Page Not Found.');
    $this->save_page_cache_as = '404_not_found';
    $this->wrapper($message, 'master', FALSE);
  }
  



#----------------------------------------------------
# private methods 
#----------------------------------------------------


/*
 * Serve a cached page if we are allowed to and it exists.
 */
  private function serve_page_cache($page_name)
  {
    $file = DATAPATH."$this->site_name/cache/$page_name.html";
    if($this->serve_page_cache AND !$this->client->can_edit($this->site_id) AND file_exists($file))
    {
      header('Content-Type: text/html; charset=iso-8859-1');
      readfile($file);
      die;
    }
  }
  
  private function page_css_cache($page_id)
  {
    # if we don't have a file, build it with $this->page_css;
    if($this->reset_css_cache OR !file_exists("$this->cache_dir/$page_id.css"))
    {
      # parse custom page sass file if it exists.
      $page_sass  = $this->assets->themes_dir("$this->theme/pages/$page_id.sass");
      if(file_exists($page_sass))
        $this->page_css =  Kosass::factory('compact')->compile(file($page_sass));
      
      $this->build_css = TRUE;
    }
    $this->build_css = FALSE;
  }
  
  
  
  
  
/*
 * data is composed of tools data sent from build_page.php or other admin data
 * from utada controller that needs to be wrapped in a theme-based shell.  
 * the final step for the plusjade pages. inputs $data into the site template.
 * expects a an array matching the appropriate containers
 * $exists = does this page exist? set to false for 404 not found wrapper.
 */
  private function wrapper($data, $template, $exists=TRUE)
  {
    $banner    = View::factory('_global/banner');
    $menu      = View::factory('_global/menu');
    $path      = $this->assets->themes_dir("$this->theme/templates");
    $template = (empty($template)) ? 'master' : $template;
    
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
    
    $this->save_page_cache();
    die($this->template);
  }
  
  
  
/* 
 * save the entire page cache if applicable.
 * save_page_cache_as defaults to FALSE, but will be 
 * set to a page_name if that page can be cached.
 */
  private function save_page_cache()
  {
    if($this->save_page_cache_as)
    {
      $date = date('m.d.y g:ia e');
      if(!is_dir(DATAPATH . "$this->site_name/cache"))
        mkdir(DATAPATH . "$this->site_name/cache");
      file_put_contents(
        DATAPATH . "$this->site_name/cache/$this->save_page_cache_as.html",
        $this->template->render() . "\n<!-- cached $date -->"
      );
    } 
  }
  
  

  
  
  
/*
 * Load Assets for Admin edit mode
 */ 
  private function load_admin($page_id, $page_name)
  {  
    if(!$this->client->can_edit($this->site_id))
      return FALSE;
      
    # load admin global css and javascript.
    if(!file_exists(DOCROOT . '_assets/css/admin.css'))
    {
      $css = new Css_Controller();
      $css->admin();
    }
    $this->template->linkCSS('/_assets/css/admin.css');
    $this->template->admin_linkJS('get/js/admin?v=1.1');

    # get list of protected tools to compare against so we can omit scope link      
    $protected_tools = ORM::factory('system_tool')
      ->where('protected', 'yes')
      ->find_all();
    $protected_array = array();
    foreach($protected_tools as $tool)
      $protected_array[] = $tool->id;

    # Log in the $account_user admin account.
    #if(!$this->account_user->logged_in($this->site_id))
    #  $this->account_user->force_login('admin', (int)$this->site_id);

    # activate admin_panel view.
    $this->template->admin_panel =
      view::factory(
        'admin/admin_panel',
        array(
          'protected_array'  => $protected_array,
          'page_id'          => $page_id,
          'page_name'        => $page_name,
          'global_css_path'  => "/_data/$this->site_name/themes/$this->theme/css/global.css?v=23094823-",
        )
      );
    return TRUE;
  }

  

  
/*
 * build a new global css for the website
 * generate everything as new and overwrite the current cache.
 * outputs css and saves as global.css in the theme cache folder.
 */
  private function load_global_css()
  {
    $this->template->linkCSS("$this->cache_url/global.css?v=1.0", 'global-sheet');
   
    if(!$this->reset_css_cache AND file_exists("$this->cache_dir/global.css"))
      return TRUE;

    # create the global css cache file.
    ob_start();
    # get the global sass file.
    $global_sass  = $this->assets->themes_dir("$this->theme/css/global.sass");
    if(file_exists($global_sass))
      echo Kosass::factory('compact')->compile(file($global_sass));
    
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
 * plusjade rootsite account hook functionality
 */
  private function plusjade_hook($page_name)
  {
    # does plusjade need anything special on this page?
    if('start' == $page_name)
    {
      $home = new Home_Controller();
      return $home->_index();  
    }
    
    if('utada' == $page_name AND $this->client->can_edit($this->site_id))
    {  
      $utada = new Utada_Controller();
      return $utada->_index();
    }
    return FALSE;
  }
    
  
}
/* -- end of application/controllers/build_page.php -- */