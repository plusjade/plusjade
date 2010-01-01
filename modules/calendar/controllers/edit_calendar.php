<?php

class Edit_Calendar_Controller extends Edit_Tool_Controller {

/*
 *  Handles all editing logic for calendar module.
 */
 
  function __construct()
  {
    parent::__construct();  
  }


/*
 * Add Event(s)
 */ 
  public function add()
  {    
    valid::id_key($this->pid);  
    if($_POST)
    {
      $dates = explode('/', $_POST['date']);
      
      $new_item = ORM::factory('calendar_item');
      $new_item->fk_site      = $this->site_id;
      $new_item->calendar_id  = $this->pid;
      $new_item->year         = $dates['2'];
      $new_item->month        = $dates['0'];
      $new_item->day          = $dates['1'];
      $new_item->title        = $_POST['title'];
      $new_item->desc         = $_POST['desc'];
      $new_item->save();
      die("New Event added id:$new_item->id");
    }

    $view = new View("edit_calendar/add_item");
    $view->js_rel_command = "update-calendar-$this->pid";
    die($view);
  }
  
/*
 * Edit single Item
 */
  public function edit($id=NULL)
  {
    valid::id_key($id);

    $item = ORM::factory('calendar_item')
      ->where('fk_site', $this->site_id)
      ->find($id);
    if(FALSE === $item->loaded)
      die('invalid calendar item.');

    if($_POST)
    {
      $item->title = $_POST['title'];
      $item->desc = $_POST['desc'];
      $item->save();
      die('Event Saved');
    }

    $primary = new View("edit_calendar/edit_item");
    $primary->item = $item;
    $primary->js_rel_command = "update-calendar-$item->calendar_id";
    die($primary);
  }

/*
 * delete a calendar item.
 */
  public function delete($id=NULL)
  {
    valid::id_key($id);
    
    ORM::factory('calendar_item')
      ->where('fk_site', $this->site_id)
      ->delete($id);
      
    die('Calendar item deleted.');
  }

/*
 * calendar settings.
 */
  function settings()
  {
    die('Calendar Settings have been disabled while we update our code. Thanks!');
  }
  


  
  public static function _tool_deleter($tool_id, $site_id)
  {
    ORM::factory('calendar_item')
      ->where(array(
        'fk_site'    => $site_id,
        'calendar_id'  => $tool_id,
        ))
      ->delete_all();  

    return TRUE;
  }
}