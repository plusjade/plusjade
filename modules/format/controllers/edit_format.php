<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
 *  Handles all editing logic for format module.
 *
 */
class Edit_Format_Controller extends Edit_Tool_Controller {

  function __construct()
  {
    parent::__construct();
  }

  
/*
 *  rearrange format-item positions
 */
  public function manage()
  {
    $format = $this->get_parent('format');
    $view = new View('edit_format/manage');
    $view->items = $format->format_items;
    die($view);
  }

/*
 * add a format item
 */  
  public function add()
  {
    valid::id_key($this->pid);
    if($_POST)
    {
      $max = ORM::factory('format_item')
        ->select('MAX(position) as highest')
        ->where('format_id', $this->pid)
        ->find();    

      $new_item = ORM::factory('format_item');
      $new_item->fk_site   = $this->site_id;
      $new_item->format_id = $this->pid;
      $new_item->title     = $_POST['title'];
      $new_item->type      = (isset($_POST['type'])) ? $_POST['type'] : '';
      $new_item->meta      = (isset($_POST['meta'])) ? $_POST['meta'] : '';
      $new_item->album     = (isset($_POST['album'])) ? $_POST['album'] : '';
      $new_item->body      = $_POST['body'];
      $new_item->position  = ++$max->highest;
      $new_item->save();      
      die('Item added'); #success
    }

    $format = $this->get_parent('format');
    
    $view = new View("edit_format/add_$format->type");
    $view->js_rel_command = "update-format-$this->pid";
    die($view);
  }


/*
 * edit a format item
 */
  public function edit()
  {
    $format_item = $this->get_item('format_item');

    if($_POST)
    {
      $format_item->title = $_POST['title'];
      $format_item->type  = (isset($_POST['type'])) ? $_POST['type'] : '';
      $format_item->meta  = (isset($_POST['meta'])) ? $_POST['meta'] : '';
      $format_item->album = (isset($_POST['album'])) ? $_POST['album'] : '';
      $format_item->body  = $_POST['body'];
      $format_item->save();
      die('format item updated');
    }

    $format = $this->get_parent('format', $format_item->format_id);

    $view = new View("edit_format/edit_$format->type");
    $view->item = $format_item;
    $view->img_path = $this->assets->assets_url();
    $view->js_rel_command = "update-format-$format_item->format_id";
    die($view);
  }

/*
 * delete a format item
 */
  public function delete()
  {
    valid::id_key($this->item_id);
    
    ORM::factory('format_item')
      ->where('fk_site', $this->site_id)
      ->delete($this->item_id);
    die('format item deleted');
  }

/* 
 * save the positions of the format questions
 * the ids are passed directly from the DOM so we don't need a tool_id
 */
  public function save_sort()
  {
    if(empty($_GET['item']))
      die('No items to sort');

    $db = Database::instance();
    foreach($_GET['item'] as $position => $id)
      $db->update('format_items', array('position' => $position), "id = '$id'");   
    
    die('format item order saved.');
  }

/*
 * Configure format tool settings
 */ 
  public function settings()
  {
    $format = $this->get_parent('format');
      
    if($_POST)
    {
      $format->name = $_POST['name'];
      $format->view = $_POST['view'];
      $format->params = (isset($_POST['params'])) ? $_POST['params'] : '';
      $format->save();
      die('Format Settings Saved.');
    }
    
    # setup view toggling based on format type.
    switch($format->type)
    {
      case 'people':
        $type_views = array('list','filmstrip');
        break;
      case 'contacts':
        $type_views = array('list');
        break;
        
      case 'faqs':
        $type_views = array('simple');
        break;
      case 'tabs':
        $type_views = array('stock');
        break;
      case 'forms':
        $type_views = array('list');
        break;
      default:
        $type_views = array();
        break;
    }
    
    $view = new View('edit_format/settings');
    $view->format      = $format;
    $view->type_views    = $type_views;
    $view->js_rel_command  = "update-format-$this->pid";      
    die($view);
  }

  
} /* end */


