<?php defined('SYSPATH') OR die('No direct access allowed.');

class Showroom_Model extends Tool {
  
  protected $family = array('showroom_cat', 'showroom_cat_items');
  
  protected $has_many = array('showroom_cats');
  #protected $sorting = array('page_name' => 'asc');
  

/*
 * Need this to enable nested showroom categories
 * Need to add a root child to items list for every other
 * child to belong to
 * Add root child id to parent for easier access.
 */  
  public function save()
  {
    if ($this->loaded === FALSE)
    {
      $new_cat = ORM::factory('showroom_cat');
      $new_cat->showroom_id  = $this->id;
      $new_cat->fk_site      = $this->fk_site;
      $new_cat->name         = 'ROOT';
      $new_cat->local_parent = 0;
      $new_cat->position     = 0;
      $new_cat->save();

      $showroom->root_id = $new_cat->id;
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
      $db = Database::instance();
      $db->query("
        DELETE cats.*, items.*
        FROM showroom_cats as cats, showroom_cat_items as items
        WHERE cats.fk_site = '$this->fk_site'
        AND cats.showroom_id = '$this->id'
        AND cats.id = items.showroom_cat_id
      ");
      
      # hack to remove the root node which has no items on it.
      ORM::factory('showroom_cat')
        ->where(array(
          'fk_site'     => $this->fk_site,
          'showroom_id' => $this->id,
        ))
        ->delete_all();
        return parent::delete($this->id);
      }
  }
  
  
} // End

