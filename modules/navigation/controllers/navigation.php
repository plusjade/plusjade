<?php defined('SYSPATH') OR die('No direct access allowed.');


class Navigation_Controller extends Public_Tool_Controller {

  function __construct()
  {
    parent::__construct();
  }

/*
 * Displays a nestable navigation element menu
 * now expects the parent table object.
 */   
  public function _index($navigation)
  {
    # There will always be a root_holder so no items is actually =1
    if('1' == $navigation->navigation_items->count())
      return $this->wrap_tool('(no items)', 'navigation', $navigation);
    
    $view = new View('public_navigation/lists/stock');  
    $view->navigation = $navigation;
    # public node_generation function is contained in the tree class...
    $view->tree = Tree::display_tree('navigation', $navigation->navigation_items);
    return $this->wrap_tool($view, 'navigation', $navigation);
  }
 


  
}  /* -- end -- */

