<?php defined('SYSPATH') OR die('No direct access allowed.');

class Navigation_Model extends Tool {
  
  protected $has_many = array('navigation_items');
  

/*
 * overload saving
 */
  public function save($sample=FALSE)
  {
    if($this->loaded === FALSE)
    {
      $this->save();
      $new_item = ORM::factory('navigation_item');
      $new_item->navigation_id = $this->id;
      $new_item->fk_site       = $this->fk_site;
      $new_item->display_name  = 'ROOT';
      $new_item->type          = 'none';
      $new_item->data          = 0;
      $new_item->local_parent  = 0;
      $new_item->save();

      $this->root_id = $new_item->id;

      if($sample)
      {
        $new_item->clear();    
        $new_item->navigation_id  = $this->id;
        $new_item->fk_site        = $this->fk_site;
        $new_item->display_name   = 'Sample list item';
        $new_item->type           = 'none';
        $new_item->data           = '';
        $new_item->local_parent   = $navigation->root_id;
        $new_item->save();

        $new_item->clear();    
        $new_item->navigation_id  = $this->id;
        $new_item->fk_site        = $this->fk_site;
        $new_item->display_name   = 'Link to Home';
        $new_item->type           = 'page';
        $new_item->data           = 'home';
        $new_item->local_parent   = $navigation->root_id;
        $new_item->save();

        $new_item->clear();    
        $new_item->navigation_id  = $this->id;
        $new_item->fk_site        = $this->fk_site;
        $new_item->display_name   = 'External Google Link';
        $new_item->type           = 'url';
        $new_item->data           = 'google.com';
        $new_item->local_parent   = $navigation->root_id;
        $new_item->save();
        # Update left and right values
        Tree::rebuild_tree('navigation_item', $navigation->root_id, $this->fk_site, '1');
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
      ORM::factory('navigation_item')
        ->where(array(
          'fk_site'       => $this->fk_site,
          'navigation_id' => $this->id,
          ))
        ->delete_all();  
      
      return parent::delete($this->id);
    }
    
  }

      
      
} // End