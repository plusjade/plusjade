<?php defined('SYSPATH') OR die('No direct access allowed.');

class Calendar_Model extends Tool {
  
  protected $has_many = array('calendar_items');
  
  /**
   * Overload saving to set the created time and to create a new token
   * when the object is saved.
   */
  public function save()
  {
    if ($this->loaded === FALSE)
    {
      if($sample)
      {      
        $new_item = ORM::factory('calendar_item');
        $new_item->fk_site      = $this->fk_site;
        $new_item->calendar_id  = $this->id;
        $new_item->year         = date("Y");
        $new_item->month        = date("m");
        $new_item->day          = date("d");
        $new_item->title        = 'New Website Launch!';
        $new_item->desc         = "Pizza party at my house to celebrate my new website launch. Starts at 3pm, bring your buddies!";
        $new_item->save();    
      }
    }
    return parent::save();
  }
  

/*
 * delete this tool in its entirety,
 * including any extra meta data etc.
 */
  public function delete_tool()
  {
    if ($this->loaded)
    {
      ORM::factory('calendar_item')
        ->where(array(
          'fk_site'     => $this->fk_site,
          'calendar_id' => $this->id,
          ))
        ->delete_all(); 
        
      return parent::delete($this->id);
    }
    
  }
 
} // End