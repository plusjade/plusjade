<?php defined('SYSPATH') OR die('No direct access allowed.');

class Text_Model extends Tool {
  
  /**
   * Overload saving to set the created time and to create a new token
   * when the object is saved.
   */
  public function save($sample=FALSE)
  {
    if($this->loaded === FALSE)
    {
      if($sample)
      {
        $this->body = View::factory('public_text/sample')->render();
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

    
      
      return parent::delete($this->id);
    }
    
  }
  
} // End


