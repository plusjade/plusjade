<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
 * stores and displays text.
 */
class Edit_Text_Controller extends Edit_Tool_Controller {

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
 * edit a single item, 
 * Name is easier to put on the toolbar 
 * even though it does the same thing as add.
 */
  public function edit()
  {
    $this->add();
  }
  

}

/* -- end text tool editor -- */