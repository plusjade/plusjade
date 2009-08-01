<?php
class Edit_Blog_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for blog module.
 */
	function __construct()
	{
		parent::__construct();
	}

/*
 * show all drafts, TODO:comment moderation.
 */ 
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$db = new Database;
		$primary = new View('edit_blog/manage');
		
		# Show drafts
		$items = $db->query("
			SELECT blog_posts.*, 
			DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on 
			FROM blog_posts
			WHERE blog_posts.blog_id = '$tool_id'
			AND blog_posts.fk_site = '$this->site_id'					
			AND status = 'draft'
			GROUP BY blog_posts.id 
			ORDER BY created DESC
		");
		$primary->items = $items;
		die($primary);
	}

/*
 * add a new blog post
 */ 
	function add($tool_id=NULL)
	{
		valid::id_key($tool_id);	
		if($_POST)
		{
			$new_post = ORM::factory('blog_post');
			$new_post->fk_site	= $this->site_id;
			$new_post->blog_id	= $tool_id;
			$new_post->url		= $_POST['url'];
			$new_post->title	= $_POST['title'];
			$new_post->body		= $_POST['body'];
			$new_post->created	= strftime("%Y-%m-%d %H:%M:%S");
			$new_post->save();


			self::save_tags($_POST['tags'], $new_post->id, $tool_id);
			die('New Post added'); # success
		}

		$primary = new View('edit_blog/add_item');
		$primary->tool_id = $tool_id;
		$primary->js_rel_command = "update-blog-$tool_id";
		die($primary);	
	}

/*
 * edit a blog post
 */ 
	function edit($id=NULL)
	{
		valid::id_key($id);
		
		
		if($_POST)
		{
			$post = ORM::factory('blog_post')
				->where('fk_site', $this->site_id)
				->find($id);
			if(FALSE === $post->loaded)
				die('invalid post id');
			
			$post->url		= $_POST['url'];
			$post->title	= $_POST['title'];
			$post->body		= $_POST['body'];
			$post->status	= $_POST['status'];
			$post->save();
			
			self::save_tags($_POST['tags'], $id, $_POST['blog_id']);
			
			if(isset($_POST['sticky']))
			{
				$sticky_posts = '';
				
				if('stick' == $_POST['sticky'])
					$sticky_posts = $_POST['sticky_posts'] . ",$id";
				elseif('unstick' == $_POST['sticky'])
				{
					$sticky_posts = explode(',', $_POST['sticky_posts']);
					#print_r($sticky_posts);die();
					if($key = array_search($id, $sticky_posts))
						unset($sticky_posts[$key]);
						
					$sticky_posts = implode(',', $sticky_posts);
				}
	
				$blog = ORM::factory('blog')
					->where('fk_site', $this->site_id)
					->find($_POST['blog_id']);
				if(FALSE === $blog->loaded)
					die('invalid blog id');
					
				$blog->sticky_posts = $sticky_posts;	
				$blog->save();
			}
			die('Post Saved'); #status			
		}

		$db = new Database;
		$post = $db->query("
			SELECT blog_posts.*, DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, 
			GROUP_CONCAT(DISTINCT blog_post_tags.value, CONCAT('_',blog_post_tags.id) ORDER BY blog_post_tags.value  separator ',') as tag_string
			FROM blog_posts 
			LEFT JOIN blog_post_tags ON blog_posts.id = blog_post_tags.blog_post_id
			WHERE blog_posts.id = '$id'
			AND blog_posts.fk_site = '$this->site_id'
		")->current();
		
		$blog = ORM::factory('blog')
			->where('fk_site', $this->site_id)
			->find($post->blog_id);
		if(FALSE === $blog->loaded)
			die('invalid blog id');

	
		$sticky_posts = explode(',', $blog->sticky_posts);
	
		$primary = new View("edit_blog/edit_item");
		$primary->item			 = $post;
		$primary->sticky_posts	 = $blog->sticky_posts;
		$primary->is_sticky		 = (in_array($id, $sticky_posts)) ? TRUE : FALSE ;
		$primary->js_rel_command = "update-blog-$post->blog_id";
		die($primary);
	}

/*
 * Save tags to database.
 * (string) $tags (comma dilemenated)
 */
	private function save_tags($tags, $blog_post_id, $blog_id)
	{
		$tags = trim($tags);
		if (empty($tags))
			return FALSE;
			
		$db = new Database;
		$tags = explode(',', $tags);
		
		foreach($tags as $tag)
		{
			$tag = trim($tag);
			$tag = preg_replace("(/W)", '_', $tag);
			$data = array(
			   'fk_site'	=> $this->site_id,
			   'blog_post_id'	=> $blog_post_id,
			   'blog_id'	=> $blog_id,
			   'value'		=> $tag,					
			);
			$db->insert('blog_post_tags', $data);
		}
		return TRUE;
	}
/*
 * delete a single blog post
 * should also delete blog post metadata: comments/tags
 */
	function delete($id=NULL)
	{
		valid::id_key($id);

		ORM::factory('blog_post')
			->where(array(
				'fk_site' => $this->site_id,
			))
			->delete($id);
			
		ORM::factory('blog_post_tag')
			->where(array(
				'fk_site'		=> $this->site_id,
				'blog_post_id'	=> $id,
			))
			->delete_all();

		ORM::factory('blog_post_comment')
			->where(array(
				'fk_site'		=> $this->site_id,
				'blog_post_id'	=> $id,
			))
			->delete_all();
			
		die('Post deleted!'); #status
	}

/*
 * delete a single tag
 */
	function delete_tag($id=NULL)
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
	function delete_comment($id=NULL)
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
	function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		$blog = ORM::factory('blog')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $blog->loaded)
			die('invalid blog id');
	
		if($_POST)
		{
			die('testing');
			
			$blog->title = $_POST['title'];
			$blog->save();
			die('Blog settings updated.');
		}
		
		$primary = new View('edit_blog/settings');
		$primary->tool = $blog;
		$primary->js_rel_command = "update-blog-$tool_id";			
		die($primary);
	}


/*
 * logic executed after this blog tool is deleted from site.
 */	
	public static function _tool_deleter($tool_id, $site_id)
	{
		ORM::factory('blog_post')
			->where(array(
				'fk_site'	=> $site_id,
				'blog_id'	=> $tool_id,
			))
			->delete_all();
			
		ORM::factory('blog_post_tag')
			->where(array(
				'fk_site'	=> $site_id,
				'blog_id'	=> $tool_id,
			))
			->delete_all();

		ORM::factory('blog_post_comment')
			->where(array(
				'fk_site'	=> $site_id,
				'blog_id'	=> $tool_id,
			))
			->delete_all();
		return TRUE;
	}
}
