<?php

class Forum_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

/*
 * forum index.
 * routes the url in no-ajax mode.
 */ 
	function _index($tool_id)
	{
		$url_array	= Uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'forum', $tool_id);
		$data		= $url_array['2'];
		$data2		= $url_array['3'];
		$action		= (empty($url_array['1']) OR 'tool' == $url_array['1'])
			? 'index'
			: $url_array['1'];
		
		switch($action)
		{					
			case 'index':
				$content = self::home($page_name, $tool_id);
				break;
				
			case 'category':
				$content = self::category_posts($page_name, $tool_id, $data);
				break;

			case 'submit':
				$content = self::submit($page_name, $tool_id, $data);
				break;

			case 'view':
				$content = self::post_view($page_name, $tool_id, $data);
				break;

			case 'vote':
				$content = self::vote($page_name, $tool_id, $data, $data2);
				break;

			case 'edit':
				$content = self::edit($page_name, $tool_id, $data, $data2);
				break;
				
			default:
				die("$page_name : $action : trigger 404 not found");
		}
		
		# the logic above will determine whether the user is logged in/out
		$wrapper = ($this->account_user->logged_in())
			? new View('public_forum/index')
			: new View('public_forum/index');
			
		$wrapper->content		= $content;
		$wrapper->page_name		= $page_name;
		$wrapper->categories	= self::categories($tool_id);
		return $this->public_template($wrapper, 'forum', $tool_id, '');
	}


/*
 * show views based on if user is logged in or not.
 * output raw contents.
 */
	private function home($page_name, $tool_id)
	{
		$primary = new View('public_forum/home');
		$primary->page_name = $page_name;	
		$primary->account_user =
			($this->account_user->logged_in())
			? $this->account_user->get_user()->id
			: FALSE;
			
		$posts = ORM::factory('forum_cat_post')
			->select('forum_cat_posts.*, forum_cats.name, forum_cats.url')
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where(array('forum_cats.fk_site' => $this->site_id))
			->find_all();
		#foreach($posts as $post){echo '<pre>';print_r($post); echo '</pre>';} die();
		
		$primary->posts = $posts;
		return $primary;
	}
	
/*
 * 
 * get a list of posts based on the urlname of the parent category.
 */
	private function category_posts($page_name, $tool_id, $category)
	{
		$primary = new View('public_forum/category_posts');
		$primary->page_name = $page_name;	
		$primary->account_user =
			($this->account_user->logged_in())
			? $this->account_user->get_user()->id
			: FALSE;
			
		$category_posts = ORM::factory('forum_cat_post')
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where(array(
				'forum_cats.url'	 => $category,
				'forum_cats.fk_site' => $this->site_id
			))
			->find_all();
		#foreach($category_posts as $post){echo '<pre>';print_r($post); echo '</pre>';} die();
		
		$primary->category_posts = $category_posts;
		$primary->category = $category;
		return $primary;
	}


/*
 * get the full post view of a category post.
 * output raw view contents.
 */
	private function post_view($page_name, $tool_id, $post_id)
	{
		valid::id_key($post_id);
		if($_POST AND $this->account_user->logged_in())
		{
			if(empty($_POST['body']))
				die('Reply cannot be empty.');
			
			$new_comment = ORM::Factory('forum_cat_post_comment');
			$new_comment->fk_site			= $this->site_id;
			$new_comment->forum_cat_post_id = $post_id;
			$new_comment->account_user_id	= $this->account_user->get_user()->id;
			$new_comment->body				= $_POST['body'];
			$new_comment->created			= time();
			$new_comment->save();
	
			#die('Thank you, your comment has been added!');
			# send data to javascript if enabled.
		}

		$primary = new View('public_forum/post_view');
		$primary->page_name = $page_name;	
		$primary->is_logged_in = $this->account_user->logged_in();	
		$primary->account_user =
			($this->account_user->logged_in())
			? $this->account_user->get_user()->id
			: FALSE;
			
		$post = ORM::Factory('forum_cat_post', $post_id)
			->select("
				forum_cat_posts.*,
				(SELECT account_user_id 
					FROM forum_comment_votes
					WHERE forum_cat_post_comment_id = forum_cat_posts.forum_cat_post_comment_id
					AND account_user_id = '$primary->account_user'
				) AS has_voted
			")
			->where(array(
				'forum_cat_posts.id' => $post_id,
			))
			->find();
		
		$comments = ORM::Factory('forum_cat_post_comment')
			->select("
				forum_cat_post_comments.*,
				(SELECT account_user_id 
					FROM forum_comment_votes
					WHERE forum_cat_post_comment_id = forum_cat_post_comments.id
					AND account_user_id = '$primary->account_user'
				) AS has_voted
			")
			->where(array(
				'forum_cat_post_comments.forum_cat_post_id' => $post_id,
				'forum_cat_post_comments.fk_site' => $this->site_id,
				'forum_cat_post_comments.id !=' => $post->forum_cat_post_comment_id,
			))
			->find_all();

		$primary->post = $post;
		$primary->comments = $comments;
		return $primary;
	}


/*
 * edit a comment
 */
	private function edit($page_name, $tool_id, $type, $id)
	{
		valid::id_key($id);
		if(!$this->account_user->logged_in())
			die('Please Login');
			
		if($_POST AND $this->account_user->logged_in())
		{
			if(empty($_POST['body']))
				die('Reply cannot be empty.');
			
			if('post' == $type)
			{
				$post = ORM::Factory('forum_cat_post', $id);
				$post->title = $_POST['title'];
				$id =  $post->forum_cat_post_comment_id;
				
				$post->save();
			}
			$comment = ORM::Factory('forum_cat_post_comment', $id);
			$comment->body = $_POST['body'];
			$comment->save();
	
			#die('Thank you, your comment has been added!');
			# send data to javascript if enabled.
		}

		$primary = new View('public_forum/edit');
		$primary->page_name = $page_name;
		

		switch($type)
		{
			case 'post':
				$post = ORM::Factory('forum_cat_post', $id)->find();
				$primary->post = (TRUE == $post->loaded) ? $post : FALSE ;
				$comment_id = $post->forum_cat_post_comment_id;
				break;
				
			case 'comment':
				$primary->post = FALSE;
				$comment_id = $id;
				break;
				
			default:
				die('Invalid type');
		}
		
		$primary->comment	= ORM::Factory('forum_cat_post_comment', $comment_id)->find();
		$primary->type		= $type;
		$primary->id		= $id;
		return $primary;
	}
	
	
/*
 * get categories object from this forum
 */
	private function categories($forum_id)
	{
		$categories = ORM::factory('forum_cat')
			->where(array('forum_id' => $forum_id, 'fk_site' => $this->site_id))
			->find_all();

		if(0 == $categories->count())
			return 'no categories';
			
		return $categories;
	}


/*
 * submit a new post to a category.
 */
	private function submit($page_name, $tool_id)
	{
		if(!$this->account_user->logged_in())
			return new View('public_forum/login');
			
		if($_POST)
		{
			if(empty($_POST['title']) OR empty($_POST['body']))
				die('Title and Body cannot empty');

			# add to post table
			$new_post = ORM::Factory('forum_cat_post');
			$new_post->fk_site			= $this->site_id;
			$new_post->forum_cat_id		= $_POST['forum_cat_id'];
			$new_post->title			= $_POST['title'];
			$new_post->save();
			
			# add the child comment.
			$new_comment = ORM::Factory('forum_cat_post_comment');
			$new_comment->fk_site			= $this->site_id;
			$new_comment->forum_cat_post_id	= $new_post->id;
			$new_comment->account_user_id	= $this->account_user->get_user()->id;
			$new_comment->body				= $_POST['body'];
			$new_comment->created			= time();
			$new_comment->save();
			
			# update post with comment_id.
			$new_post->forum_cat_post_comment_id = $new_comment->id;
			$new_post->save();
		}
		
		$primary = new View('public_forum/submit');
		$primary->page_name = $page_name;
		$primary->categories = self::categories($tool_id);
		return $primary;
	}

/* cast a vote */	
	private function vote($page_name, $tool_id, $comment_id, $vote)
	{
		valid::id_key($comment_id);
		if(!$this->account_user->logged_in())
			die('Please login to vote.');
			
		$has_voted = ORM::factory('forum_comment_vote')
			->where(array(
				'account_user_id'		 	=> $this->account_user->get_user()->id,
				'forum_cat_post_comment_id' => $comment_id
			))
			->find();	
		if(TRUE == $has_voted->loaded)
			die('already voted.');
			
		$vote = ('down' == $vote) ? -1 : 1 ;
		
		$comment = ORM::factory('forum_cat_post_comment', $comment_id);
		
		if(1 == $vote)
			$comment->vote_count = ++$comment->vote_count;
		else
			$comment->vote_count = --$comment->vote_count;
		
		$comment->save();		

		# log the vote.
		$log_vote = ORM::factory('forum_comment_vote');
		$log_vote->account_user_id = $this->account_user->get_user()->id;
		$log_vote->forum_cat_post_comment_id = $comment_id; 
		$log_vote->save();

		die('Vote has been accepted!');
	}	
	

/*
 * Ajax request handler.
 * param $url_array = (array) an array of url signifiers
 * param $tool_id 	= (int) the tool id of the tool.
 */ 	
	function _ajax($url_array, $tool_id)
	{
		list($page_name, $action, $username) = $url_array;
		$data	= $url_array['2'];
		$data2	= $url_array['3'];
		$action = (empty($action) OR 'tool' == $action)
			? 'index'
			: $action;

		switch($action)
		{					
			case 'index':
				die(self::home($page_name));
				break;
				
			case 'category':
				die(self::category_posts($page_name, $tool_id, $data));
				break;
				
			case 'recent':
				die(self::recent($page_name, $tool_id));
				break;

			case 'submit':
				die(self::submit($page_name, $tool_id));
				break;

			case 'vote':
				die(self::vote($page_name, $tool_id, $data, $data2));
				break;

			case 'view':
				die(self::post_view($page_name, $tool_id, $data));
				break;
				
			default:
				die("$page_name : <b>$action</b> : trigger 404 not found");
		}
		die('<br>something is wrong with the url');
	}
	
}/*end*/



