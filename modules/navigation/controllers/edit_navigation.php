<?php

class Edit_Navigation_Controller extends Edit_Tool_Controller {
/*
 * Edit a navigation menu
 *
 */
  function __construct()
  {
    parent::__construct();  
  }
  
/*
 * Manage Function display a sortable list of tool resources (items)
 */
  function manage()
  {
    valid::id_key($this->pid);
    
    $navigation_items = ORM::factory('navigation_item')
      ->where(array(
        'fk_site'       => $this->site_id,
        'navigation_id' => $this->pid,
      ))
      ->find_all();  
    if(0 == $navigation_items->count())
      die('Error: this navigation has no root node.');


    $pages = ORM::factory('page')
      ->where('fk_site', $this->site_id)
      ->find_all();  

    $view = new View('edit_navigation/manage');
    $view->tree     = Tree::display_tree('navigation', $navigation_items, NULL, NULL, 'render_edit_navigation', TRUE);
    $view->tool_id  = $this->pid;
    $view->pages    = $pages;
    die($view);
  }

/*
 * Add navigation items (links)  to a navigation 
 */ 
  public function add()
  {
    if(!$_POST)
      die('Nothing Sent.');
    
    $navigation = $this->get_parent('navigation');      
    $_POST['data'] = (empty($_POST['data'])) ? '' : $_POST['data'];      
    
    # if for any reason local_parent is null, just add to root.
    $_POST['local_parent'] = (empty($_POST['local_parent'])) ?
      $navigation->root_id : $_POST['local_parent'];

    $new_item = ORM::factory('navigation_item');
    $new_item->navigation_id  = $this->pid;
    $new_item->fk_site        = $this->site_id;
    $new_item->display_name   = $_POST['item'];
    $new_item->type           = $_POST['type'];
    $new_item->data           = $_POST['data'];
    $new_item->local_parent   = $_POST['local_parent'];
    $new_item->save();

    # Update left and right values
    Tree::rebuild_tree('navigation_item', $navigation->root_id, $this->site_id, '1');
    
    die("$new_item->id"); # output to javascript

  }

/*
 * Saves the nested positions of the menu links
 * Can also delete any links removed from the list.
 *
 */ 
  function save_tree()
  {
    if($_POST)
    {
      valid::id_key($this->pid);
      $json = json_decode($_POST['json']);
      if(NULL === $json OR !is_array($json))
        die('invalid json');
        
      echo Tree::save_tree('navigation', 'navigation_item', $this->pid, $this->site_id, $json);
    }
    die();
  }
  
/*
 * Edit single navigation Item
 */
  public function edit()
  {
    $navigation = $this->get_item('navigation_item');
    if($_POST)
    {
      $_POST['data'] = (empty($_POST['data'])) 
      ? ''
      : $_POST['data'];
      
      $navigation_item->display_name = $_POST['item'];
      $navigation_item->type = $_POST['type'];
      $navigation_item->data = $_POST['data'];
      $navigation_item->save();
      die('Navigation item updated.');
    }
    
    $pages = ORM::factory('page')
      ->where('fk_site', $this->site_id)
      ->find_all();
    
    $primary = new View('edit_navigation/edit_item');
    $primary->item  = $navigation_item;
    $primary->pages = $pages;
    die($primary);
  }

/*
 * configure navigation settings
 */ 
  public function settings()
  {
    $navigation = $this->get_parent('navigation');
    if($_POST)
    {
      $navigation->name = $_POST['name'];
      $navigation->save();
      die('Navigation Settings Saved');  
    }

    $view = new View("edit_navigation/settings");
    $view->navigation = $navigation;
    $view->js_rel_command = "update-navigation-$navigation->id";
    die($view);
  }


} // end



