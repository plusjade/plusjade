<?php defined('SYSPATH') OR die('No direct access allowed.');

class Format_Model extends Tool {
  
  protected $has_many = array('format_items');
  
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
        $this->body = 'yahboi';
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
    if($this->loaded)
    {
      ORM::factory('format_item')
        ->where(array(
          'fk_site'   => $this->fk_site,
          'format_id' => $this->id,
          ))
        ->delete_all();
      
      return parent::delete($this->id);
    }
    
  }
} // End