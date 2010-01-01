<?php defined('SYSPATH') OR die('No direct access allowed.');

class Blog_Model extends Tool {
  
  protected $has_many = array('blog_posts');
  
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
        $new_post = ORM::factory('blog_post');
        $new_post->fk_site  = $this->fk_site;
        $new_post->blog_id  = $this->id;
        $new_post->url      = 'my-first-blog-post';
        $new_post->title    = 'My First Blog Post';
        $new_post->body     = '<p>All sorts of interesting content...</p> And then some more content <p>Looking good!</p>';
        $new_post->created  = strftime("%Y-%m-%d %H:%M:%S");
        $new_post->status   = 'publish';
        $new_post->save();
        
        $db = Database::instance();
        $data = array(
           'fk_site'      => $this->fk_site,
           'blog_post_id' => $new_post->id,
           'blog_id'      => $this->id,
           'value'        => 'general',
        );
        $db->insert('blog_post_tags', $data);

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
      ORM::factory('blog_post')
        ->where(array(
          'fk_site'  => $this->fk_site,
          'blog_id'  => $this->id,
        ))
        ->delete_all();
        
      ORM::factory('blog_post_tag')
        ->where(array(
          'fk_site'  => $this->fk_site,
          'blog_id'  => $this->id,
        ))
        ->delete_all();

      ORM::factory('blog_post_comment')
        ->where(array(
          'fk_site'  => $this->fk_site,
          'blog_id'  => $this->id,
        ))
        ->delete_all();
      
      return parent::delete($this->id);
    }
    
  }
  
} // End