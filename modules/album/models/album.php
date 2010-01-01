<?php defined('SYSPATH') OR die('No direct access allowed.');

class Album_Model extends Tool {
  
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
        $album->name   = 'My Photo Album';
        $album->view   = 'lightbox';
        $album->images = '[
          {
            "path": "images/sunflower.jpg",
            "caption": "a sunflower"
          },
          {
            "path": "images/sun.jpg",
            "caption": "a very cool looking sun"
          },
          {
            "path": "images/goose.jpg",
            "caption": "a goose"
          },
          {
            "path": "images/lens.jpg",
            "caption": "a techy camera lens"
          },
          {
            "path": "images/sand-castle.jpg",
            "caption": "a tall sand castle"
          }
        ]';
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


