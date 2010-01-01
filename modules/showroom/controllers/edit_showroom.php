<?php defined('SYSPATH') OR die('No direct access allowed.');


class Edit_Showroom_Controller extends Edit_Tool_Controller {

  function __construct()
  {
    parent::__construct();  
  }
  
/*
 * Display the categories drilldown
 * UPDATE category positions
 */
  public function manage()
  {
    $showroom = $this->get_parent('showroom');
    # show category list.
    $db = Database::instance();
    $items = $db->query("
      SELECT cat.*, COUNT(items.id) AS item_count
      FROM showroom_cats AS cat
      LEFT JOIN showroom_cat_items AS items ON cat.id = items.showroom_cat_id
      WHERE showroom_id = '$showroom->id' 
      AND cat.fk_site = '$this->site_id'
      GROUP BY cat.id
      ORDER BY cat.lft ASC 
    ");

    $view = new View("edit_showroom/manage");
    $view->tree = Tree::display_tree('showroom', $items, NULL, NULL, 'showroom_admin', TRUE);
    die($view);
  }


/*
 * Save nested positions of the category menus
 * Can also delete any links removed from the list.
 
 TODO: deleting a category should also delete the items. 
  or at least put them in a purgatory!
 
 * Gets output positions from this::manage
 */ 
  public function save_tree()
  {
    valid::id_key($this->pid);
    if(isset($_POST['json']))
    {
      $json = json_decode($_POST['json']);
      if(NULL === $json OR !is_array($json))
        die('invalid json');
        
      echo Tree::save_tree('showroom', 'showroom_cat', $this->pid, $this->site_id, $json);
    }
    die;
  }  
  
/*
 * Add categories
 */ 
  public public function add()
  {
    valid::id_key($this->pid);
    if($_POST)
    {
      if(empty($_POST['category']))
        die('category name is required');

      $showroom = $this->get_parent('showroom');

      $url = trim($_POST['url']);
      $url = (empty($url)) ? trim($_POST['category']) : $url; 

      $_POST['local_parent'] = 
        ((empty($_POST['local_parent']) OR !is_numeric($_POST['local_parent'])))
        ? $showroom->root_id : $_POST['local_parent'];

      $new_cat = ORM::factory('showroom_cat');
      $new_cat->showroom_id  = $this->pid;
      $new_cat->fk_site      = $this->site_id;
      $new_cat->url          = valid::filter_php_url($url);
      $new_cat->name         = trim($_POST['category']);
      $new_cat->local_parent = $_POST['local_parent'];
      $new_cat->position     = 0;
      $new_cat->save();
      
      # Update left and right values
      Tree::rebuild_tree('showroom_cat', $showroom->root_id, $this->site_id, 1);
      die("$new_cat->id");  # need for javascript
    }

    $view = new View('edit_showroom/add_category');
    $view->tool_id = $this->pid;
    die($view);  
  }

/*
 * edit a category
 */ 
  public function edit_category()
  {
    $category = $this->get_item('showroom_cat');
    
    if($_POST)
    {
      if(empty($_POST['category']))
        die('category name is required');
  
      $url = trim($_POST['url']);
      $url = (empty($url)) ? trim($_POST['category']) : $url; 
    
      $category->url   = valid::filter_php_url($url);
      $category->name  = trim($_POST['category']);
      $category->intro = $_POST['intro'];
      $category->save();
      die('Showroom category updated.');
    }

    $view = new View('edit_showroom/edit_category');
    $view->cat = $category;
    die($view);  
  }

/*
 * manage items view for a particular category
 */ 
  public function items()
  {
    valid::id_key($this->pid);
    valid::id_key($this->item_id);
  
    $items = ORM::factory('showroom_cat_item')
      ->where(array(
        'fk_site'         => $this->site_id,
        'showroom_cat_id' => $this->item_id,
      ))
      ->find_all();
    if(0 === $items->count())
      die('<span class="on_close two">close-2</span> No items');  

    $view = new View('edit_showroom/manage_items');
    $view->items = $items;
    die($view);
  }
  
/*
 * Add Item(s)
 */ 
  public function add_item()
  {  
    valid::id_key($this->pid);

    if($_POST)
    {
      if(empty($_POST['name']))
        die('Name is required'); # error
      
      $max = ORM::factory('showroom_cat_item')
        ->select('MAX(position) as highest')
        ->where('showroom_cat_id', $_POST['category_id'])
        ->find();  
        
      # sanitize url
      $url = trim($_POST['url']);
      $url = (empty($url)) ? $_POST['name'] : $url;

      # verify image JSON
      if(NULL === json_decode($_POST['images']))
        $_POST['images'] = '';
        
      $new_item = ORM::factory('showroom_cat_item');
      $new_item->fk_site          = $this->site_id;
      $new_item->url              = valid::filter_php_url($url);
      $new_item->showroom_cat_id  = $_POST['category_id'];
      $new_item->name             = $_POST['name'];
      $new_item->intro            = $_POST['body'];
      $new_item->images           = $_POST['images'];
      $new_item->position         = ++$max->highest;
      $new_item->save();
      die('Showroom item added');
    }

    # Get list of categories
    $showroom = $this->get_parent('showroom');

    $view = new View("edit_showroom/add_item");
    $view->categories  = Tree::display_tree('showroom', $showroom->showroom_cats, NULL, NULL, 'render_edit_showroom');  
    die($view);
  }


  public function bulk_add()
  {
    valid::id_key($this->pid);

    if($_POST)
    {
      if(empty($_POST['category_id']))
        die('category id is required');
      
      # TODO: possibly validate that category id exists relative to
      # the parent_id and this site.
      
      $max = ORM::factory('showroom_cat_item')
        ->select('MAX(position) as highest')
        ->where('showroom_cat_id', $_POST['category_id'])
        ->find();  
        
      $new_item = ORM::factory('showroom_cat_item');
        
      foreach($_POST['name'] as $key => $name)
      {
        if(empty($name))
          continue;
          
        # jsonize the image string. what is coming
        # is just the raw short-path. 
        # clearly this only lets you put ONE image!!        
        $images = '[{"path": "' . $_POST['images'][$key] . '", "caption": ""}]';
        
        $new_item->fk_site          = $this->site_id;
        $new_item->showroom_cat_id  = $_POST['category_id'];            
        $new_item->name             = $name;
        $new_item->intro            = $_POST['intro'][$key];
        $new_item->body             = $_POST['body'][$key];
        $new_item->images           = $images;
        $new_item->position         = ++$max->highest;
        $new_item->save();
        $new_item->clear();
      }
      die('Showroom items added');
    }

    # Get list of categories
    $showroom = $this->get_parent('showroom');
    
    $view = new View("edit_showroom/bulk_add");
    $view->categories  = Tree::display_tree('showroom', $showroom->showroom_cats, NULL, NULL, 'render_edit_showroom');  
    die($view);
  }

  
/*
 * quick utility to check if specified images exist relative to the given path
 */  
  public function check_img()
  {  
    if($_POST['images'] AND is_array($_POST['images']))
    {
      $dir = $this->assets->assets_dir();
      $results = array();
      foreach($_POST['images'] as $key => $image)
        if(file_exists("$dir/$image"))
          $results[$key] = 'good';
        else
          $results[$key] = 'bad';
          
      $results = json_encode($results);
      die($results);
    }
    
    die();
  }

  
/*
 * get the item data in table form from a particular category
 */
  public function data()
  {
    $items = ORM::factory('showroom_cat_item')
      ->where(array(
        'fk_site'         => $this->site_id,
        'showroom_cat_id' => $this->item_id
      ))
      ->find_all();  
    
    $view = new View("edit_showroom/bulk_edit_data");
    $view->items = $items;
    die($view);    
  }
  
/*
 * edit lots of items at once. pure data view.
 */ 
  public function bulk_edit()
  {
    valid::id_key($this->pid);

    if($_POST)
    {
      # just edit the main data for now.
      foreach($_POST['name'] as $key => $name)
      {
        $item = ORM::factory('showroom_cat_item')
          ->where('fk_site', $this->site_id)
          ->find($key);
        if(!$item->loaded)
          continue;
          
        $item->name    = $name;
        $item->intro   = $_POST['intro'][$key];
        $item->body    = $_POST['body'][$key];
        $item->save();
      }
      die('Showroom items edited');
    }

    # Get list of categories
    $showroom = $this->get_parent('showroom');

    $view = new View("edit_showroom/bulk_edit");
    $view->categories  = Tree::display_tree('showroom', $showroom->showroom_cats, NULL, NULL, 'render_edit_showroom');  
    die($view);
  }  
  

  
  
  private function get_node($id=FALSE)
  {
    if(!$id)
    {
      # get parent_id
      $id = (isset($_GET['node_id']))
        ? valid::id_key($_GET['node_id'])
        : NULL;
    }
    valid::id_key($id);
    
    $node = ORM::factory('showroom_cat_item')
      ->where('fk_site', $this->site_id)
      ->find($id);  
    if(!$node->loaded)
      die('invalid showroom item id');
      
    return $node;
  }
  
  
/*
 * Edit single Item
 * NOTE: plusjade autosends an item_id but this is actually a node.
 */
  public function edit()
  {
    $item = $this->get_node($this->item_id);
    
    if($_POST)
    {
      if(empty($_POST['name']))
        die('Name is required'); # error
        
      # sanitze url
      $url = trim($_POST['url']);
      $url = (empty($url)) ? $_POST['name'] : $url;

      # verify image JSON
      if(NULL === json_decode($_POST['images']))
        $_POST['images'] = '';

        
      $item->url              = valid::filter_php_url($url);
      $item->showroom_cat_id  = $_POST['category_id'];
      $item->name             = $_POST['name'];
      $item->intro            = $_POST['intro'];
      $item->body             = $_POST['body'];
      $item->images           = $_POST['images'];
      $item->save();
      die('Showroom item saved');
    }

    // TODO: this seems apsurdly slow...  1.5 seconds.

    # which category does this item belong to?
    $category = ORM::factory('showroom_cat')
      ->where('fk_site', $this->site_id)
      ->find($item->showroom_cat_id);
    
    # Get list of categories
    $showroom = ORM::factory('showroom', $category->showroom_id);
      
    # parse images  
    $images = json_decode($item->images);
    if(NULL === $images)
      $images = array();
    foreach($images as $image)
      $image->thumb = image::thumb($image->path);
        
    $view = new View("edit_showroom/edit_item");
    $view->categories  = Tree::display_tree('showroom', $showroom->showroom_cats, NULL, NULL, 'render_edit_showroom');
    $view->category_id = $category->id;
    $view->item        = $item;
    $view->images      = $images;
    $view->img_path    = $this->assets->assets_url();  
    die($view);  
  }

/*
 * delete a single showroom item
 */
  public function delete()
  {
    $this->get_node($this->item_id)->delete();
    die('Showroom item Deleted');
  }

/*
 * SAVE items sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
  public function save_sort()
  {
    if(empty($_GET['item']))
      die('No items to sort');

    $db = Database::instance();  
    foreach($_GET['item'] as $position => $id)
      $db->update('showroom_cat_items', array('position' => $position), "id = '$id' AND fk_site = '$this->site_id'");   
    
    die('Showroom item order saved.');
  }
  
/*
 * showroom parent settings
 */
  public function settings()
  {
    $showroom = $this->get_parent('showroom');

    if($_POST)
    {
      $showroom->name       = $_POST['name'];
      $showroom->view       = $_POST['view'];
      $showroom->params     = $_POST['params'];
      $showroom->attributes = $_POST['attributes'];
      $showroom->save();
      die('showroom Settings Saved.');
    }
    
    $view = new View('edit_showroom/settings');
    $view->showroom = $showroom;
    $view->js_rel_command = "update-showroom-$this->pid";      
    die($view);
  }
  



} /* -- end -- */

