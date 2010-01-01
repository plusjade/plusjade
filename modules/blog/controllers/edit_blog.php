<?php
class Edit_Blog_Controller extends Edit_Tool_Controller {

/*
 *  Handles all editing logic for blog module.
 */
  function __construct()
  {
    parent::__construct();
  }

/*
 * show all drafts, TODO:comment moderation.
 */ 
  function manage()
  {
    valid::id_key($this->pid);
    $db = Database::instance();

    # Show drafts
    $items = $db->query("
      SELECT blog_posts.*, 
      DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on 
      FROM blog_posts
      WHERE blog_posts.blog_id = '$this->pid'
      AND blog_posts.fk_site = '$this->site_id'          
      AND status = 'draft'
      GROUP BY blog_posts.id 
      ORDER BY created DESC
    ");
    
    $view = new View('edit_blog/manage');
    $view->items = $items;
    die($view);
  }

/*
 * add a new blog post
 */ 
  function add()
  {
    valid::id_key($this->pid);  
    if($_POST)
    {
      $new_post = ORM::factory('blog_post');
      $new_post->fk_site  = $this->site_id;
      $new_post->blog_id  = $this->pid;
      $new_post->url      = $_POST['url'];
      $new_post->status   = $_POST['status'];
      $new_post->title    = $_POST['title'];
      $new_post->body     = $_POST['body'];
      $new_post->created  = strftime("%Y-%m-%d %H:%M:%S");
      $new_post->save();

      # save tags
      self::save_tags($_POST['tags'], $new_post->id, $this->pid);
      
      # update sticky post
      if(isset($_POST['sticky']))
      {
        $blog = $this->get_parent('blog');
        $sticky_posts = '';
        
        if('stick' == $_POST['sticky'])
          $sticky_posts = (empty($blog->sticky_posts))
            ? $sticky_posts = $new_post->id
            : $sticky_posts = "$blog->sticky_posts,$new_post->id";

        $blog->sticky_posts = $sticky_posts;  
        $blog->save();
      }
      
      die('New Post added'); # success
    }

    $view = new View('edit_blog/add_item');
    $view->js_rel_command = "update-blog-$this->pid";
    die($view);  
  }

/*
 * edit a blog post
 */ 
  function edit()
  {
    valid::id_key($this->item_id);
    if($_POST)
    {
      $post = $this->get_item('blog_post');
      $post->url     = $_POST['url'];
      $post->title   = $_POST['title'];
      $post->body    = $_POST['body'];
      $post->status  = $_POST['status'];
      $post->save();
      
      # save the tags
      self::save_tags($_POST['tags'], $this->item_id, $_POST['blog_id']);
      
      # update sticky post
      if(isset($_POST['sticky']))
      {
        $blog = $this->get_parent('blog', $_POST['blog_id']);          
        $sticky_posts = '';
        
        if('stick' == $_POST['sticky'])
        {
          $sticky_posts = (empty($blog->sticky_posts))
            ? $this->item_id
            : $sticky_posts = "$blog->sticky_posts,$this->item_id";
        }
        elseif('unstick' == $_POST['sticky'])
        {
          $sticky_posts = explode(',', $blog->sticky_posts);
          
          # this will return false if the value is found but key happens to be zero... stupid!
          $key = array_search($this->item_id, $sticky_posts);
          
          if(FALSE !== $key)
            unset($sticky_posts[$key]);

          $sticky_posts = implode(',', $sticky_posts);
        }
        $blog->sticky_posts = $sticky_posts;  
        $blog->save();
      }
      die('Post Saved');
    }

    $db = Database::instance();
    $post = $db->query("
      SELECT blog_posts.*, DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, 
      GROUP_CONCAT(DISTINCT blog_post_tags.value, CONCAT('_',blog_post_tags.id) ORDER BY blog_post_tags.value  separator ',') as tag_string
      FROM blog_posts 
      LEFT JOIN blog_post_tags ON blog_posts.id = blog_post_tags.blog_post_id
      WHERE blog_posts.id = '$this->item_id'
      AND blog_posts.fk_site = '$this->site_id'
    ")->current();
    
    $blog = $this->get_parent('blog', $post->blog_id);  
    $sticky_posts = explode(',', $blog->sticky_posts);
  
    $view = new View("edit_blog/edit_item");
    $view->item           = $post;
    $view->is_sticky      = (in_array($this->item_id, $sticky_posts)) ? TRUE : FALSE ;
    $view->js_rel_command = "update-blog-$post->blog_id";
    die($view);
  }

/*
 * Save tags to database.
 * (string) $tags (comma dilemenated)
 */
  private function save_tags($tags, $blog_post_id, $blog_id)
  {
    $tags = trim($tags);
    if(empty($tags))
      return FALSE;
      
    $db = Database::instance();
    
    # sort by space.
    $tags = explode(' ', $tags);
    
    foreach($tags as $tag)
    {
      $tag = trim($tag); 
      $data = array(
         'fk_site'      => $this->site_id,
         'blog_post_id' => $blog_post_id,
         'blog_id'      => $blog_id,
         'value'        => valid::filter_php_url($tag),          
      );
      $db->insert('blog_post_tags', $data);
    }
    return TRUE;
  }
/*
 * delete a single blog post
 * should also delete blog post metadata: comments/tags
 */
  public function delete()
  {
    valid::id_key($this->item_id);

    ORM::factory('blog_post')
      ->where('fk_site', $this->site_id)
      ->delete($this->item_id);
      
    ORM::factory('blog_post_tag')
      ->where(array(
        'fk_site'      => $this->site_id,
        'blog_post_id' => $this->item_id,
      ))
      ->delete_all();

    ORM::factory('blog_post_comment')
      ->where(array(
        'fk_site'      => $this->site_id,
        'blog_post_id' => $this->item_id,
      ))
      ->delete_all();
      
    die('Post deleted!'); #status
  }

/*
 * delete a single tag
 */
  public function delete_tag($id=NULL)
  {
    valid::id_key($id);
    ORM::factory('blog_post_tag')
      ->where(array(
        'fk_site' => $this->site_id,
      ))
      ->delete($id);  
    die('Tag deleted!'); #status
  }  

/*
 * delete a single comment
 */
  public function delete_comment($id=NULL)
  {
    valid::id_key($id);
    ORM::factory('blog_post_comment')
      ->where(array(
        'fk_site' => $this->site_id,
      ))
      ->delete($id);  
    die('Comment deleted!'); #status
  }
  
/*
 * show settings view
 */  
  public function settings()
  {
    $blog = $this->get_parent('blog');  
    if($_POST)
    {
      die('testing');
      
      $blog->title = $_POST['title'];
      $blog->save();
      die('Blog settings updated.');
    }
    
    $view = new View('edit_blog/settings');
    $view->blog = $blog;
    $view->js_rel_command = "update-blog-$blog->id";      
    die($view);
  }

  
 
}
