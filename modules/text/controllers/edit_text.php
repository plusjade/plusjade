<?php defined('SYSPATH') OR die('No direct access allowed.');

class Edit_Text_Controller extends Edit_Tool_Controller {
/*
 * stores and displays text.
 */
 
  function __construct()
  {
    parent::__construct();  
  }
  
/*
 * add single Item
 */
  public function add()
  {
    $text = $this->get_parent('text');

    if($_POST)
    {
      $text->body = $_POST['body'];
      # update the cache
      $text->cache = $this->parse_tokens($text->body);
      $text->save();
      die('Text Changes Saved');
    }
    
    $view = new View("edit_text/add_item");
    $view->item = $text;
    $view->js_rel_command = "update-text-$text->id";
    die($view);
  }
  
  
/*
 * edit a single item, uses the same logic as add so we're all good.
 */
  public function edit()
  {
    $this->add();
  }
  

  public static function _tool_deleter($parent_id, $site_id)
  {
    return true;
  }
}

/* -- end of application/controllers/showroom.php -- */