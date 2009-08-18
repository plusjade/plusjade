<?php

class Forum_Controller extends Public_Tool_Controller {

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
		
		$wrapper = new View('public_forum/index');
		
		switch($action)
		{					
			case 'index':
				$wrapper->content = self::posts_wrapper($page_name, $tool_id, 'all');
				break;
			case 'category':
				$wrapper->content = self::posts_wrapper($page_name, $tool_id, $data);
				break;
			case 'view':
				$wrapper->content = self::comments_wrapper($page_name, $tool_id, $data, $data2);
				break;
			case 'vote':
				$wrapper->content = self::vote($page_name, $tool_id, $data, $data2);
				break;
			case 'submit':
				$wrapper->content = self::submit($page_name, $tool_id);
				break;
			case 'edit':
				$wrapper->content = self::edit($page_name, $tool_id, $data, $data2);
				break;
			case 'my':
				$wrapper->content = self::my($page_name, $tool_id, $data, $data2);
				break;				
			default:
				die("$page_name : $action : trigger 404 not found");
		}
		$wrapper->page_name		= $page_name;
		$wrapper->categories	= self::categories($tool_id);
		return $this->public_template($wrapper, 'forum', $tool_id, '');
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
			return FALSE;
			
		return $categories;
	}

/*
 * return a properly created selected array in order to highlight the 
 * correct sort tab
 */
	private static function tab_selected($default='votes')
	{
		$selected = array('votes' => '', 'newest'=> '', 'oldest'=> '', 'active'=> '');

		if(empty($_GET['sort']))
			$selected[$default] = 'class="selected"';
		elseif(array_key_exists($_GET['sort'], $selected))
			$selected["$_GET[sort]"] = 'class="selected"';
		else
			$selected[$default] = 'class="selected"';
		
		return $selected;
	}
	
	
/* 
 * output a posts_wrapper showing tabs for sorting the posts.
 */
	private function posts_wrapper($page_name, $tool_id, $category)
	{
		$primary			 = new View('public_forum/posts_wrapper');
		$primary->category	 = (empty($category)) ? 'all' : $category;
		$primary->page_name	 = $page_name;	
		$primary->posts_list = self::posts_list($page_name, $primary->category);
		$primary->selected	 = self::tab_selected('newest');
		return $primary;
	}

	
/*
 * output a list of posts based on the urlname of the parent category.
 * Can also be "all" where all posts from all categories are included.
	$sort_by = the method to sort by: newest, votes, active
 */
	private function posts_list($page_name, $category)
	{
		$sort_by = (empty($_GET['sort']) OR 'newest' == $_GET['sort'])
			? 'forum_cat_post_comment:created'
			: (('votes' == $_GET['sort'])
				? 'forum_cat_post_comment:vote_count'
				: 'forum_cat_posts.last_active');

		$where_filter = array('forum_cats.fk_site' => $this->site_id);
		if('all' != $category)
			$where_filter['forum_cats.url'] = $category;
		
		$posts = ORM::factory('forum_cat_post')
			->select('forum_cat_posts.*, forum_cats.name, forum_cats.url')
			->with('forum_cat_post_comment')
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where($where_filter)
			->orderby("$sort_by", 'desc')
			->find_all();
		if(0 == $posts->count())
			return 'No posts in this category';
		
		$primary = new View('public_forum/posts_list');		
		$primary->posts = $posts;
		$primary->page_name	= $page_name;
		return $primary;
	}
	
	
/*
 * output the full post view of a category post.
 * output raw view contents. initiated by "view" action.
 */
	private function comments_wrapper($page_name, $tool_id, $post_id)
	{
		valid::id_key($post_id);
		if($_POST AND $this->account_user->logged_in($this->site_id))
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
			#die('Thank you, your comment has been added!'); # send data to javascript if enabled.
		}


		$account_user_id = ($this->account_user->logged_in($this->site_id))
			? $this->account_user->get_user()->id
			: FALSE;
			
		# get the post with child comment.
		$post = ORM::Factory('forum_cat_post', $post_id)
			->select("
				forum_cat_posts.*, forum_cats.name, forum_cats.url,
				(SELECT account_user_id 
					FROM forum_comment_votes
					WHERE forum_cat_post_comment_id = forum_cat_posts.forum_cat_post_comment_id
					AND account_user_id = '$account_user_id'
				) AS has_voted
			")
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where(array(
				'forum_cat_posts.fk_site' => $this->site_id,
				'forum_cat_posts.id' => $post_id
			))
			->find();
		if(TRUE != $post->loaded)
			die('render 404 not found');

		$primary = new View('public_forum/posts_comments_wrapper');
		$primary->post			= $post;
		$primary->page_name 	= $page_name;	
		$primary->is_logged_in	= $this->account_user->logged_in($this->site_id);	
		$primary->account_user	= $account_user_id;
		$primary->comments_list = self::comments_list($page_name, $post_id);
		$primary->selected		= self::tab_selected('votes');		
		return $primary;
	}


/*
 * output a list of comments from a specified post.
 * $sort_by = the method to sort by: newest, votes, active
 */
	private function comments_list($page_name, $post_id)
	{
		$order = (empty($_GET['sort']) OR 'oldest' != $_GET['sort'])
			? 'desc' : 'asc';
		$sort_by = (empty($_GET['sort']) OR 'votes' == $_GET['sort'])
			? 'vote_count' : 'created';

		$account_user_id = ($this->account_user->logged_in($this->site_id))
			? $this->account_user->get_user()->id
			: FALSE;
			
		$comments = ORM::Factory('forum_cat_post_comment')
			->select("
				forum_cat_post_comments.*,
				(SELECT account_user_id 
					FROM forum_comment_votes
					WHERE forum_cat_post_comment_id = forum_cat_post_comments.id
					AND account_user_id = '$account_user_id'
				) AS has_voted
			")
			->where(array(
				'forum_cat_post_comments.forum_cat_post_id' => $post_id,
				'forum_cat_post_comments.fk_site' => $this->site_id,
				'forum_cat_post_comments.is_post !=' => '1',
			))
			->orderby("forum_cat_post_comments.$sort_by", "$order")
			->find_all();
		if(0 == $comments->count())
			return 'No comments yet';

		$primary = new View('public_forum/comments_list');
		$primary->is_logged_in	= $this->account_user->logged_in($this->site_id);	
		$primary->page_name		= $page_name;
		$primary->account_user	= $account_user_id;			
		$primary->comments		= $comments;
		return $primary;
	}
	
/*
 * output a view wrapper for the "my" data view.
 * the data is: posts, comments, starred posts (not live yet)
 */
	private function my($page_name, $tool_id, $type)
	{
		if(!$this->account_user->logged_in($this->site_id))
			return new View('public_forum/login');

		if(empty($type))
			$type = 'posts';
		$allowed = array('posts', 'comments', 'starred');
		if(in_array($type, $allowed))
		{
			$wrapper = new View('public_forum/my_index');
			$wrapper->page_name = $page_name;
			$wrapper->type = $type;

			$type = "my_{$type}_list";
			$wrapper->items_list = self::$type($page_name);

			$wrapper->selected = self::tab_selected('newest'); 
			return $wrapper;
		}
		die('trigger 404 not found for (invalid "my" type)');
	}
	
	
/*
 * return a list of posts belonging to the logged in user.
 */
	private function my_posts_list($page_name)
	{
		$order = (empty($_GET['sort']) OR 'oldest' != $_GET['sort'])
			? 'desc' : 'asc';
		$sort_by = (empty($_GET['sort']) OR 'newest' == $_GET['sort'] OR 'oldest' == $_GET['sort'])
			? 'created' : 'vote_count';

		$posts = ORM::factory('forum_cat_post')
			->select('forum_cat_posts.*, forum_cats.name, forum_cats.url')
			->with('forum_cat_post_comment')
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where(array(
				'forum_cat_post_comment.fk_site'		 => $this->site_id,
				'forum_cat_post_comment.account_user_id' => $this->account_user->get_user()->id,
			))
			->orderby("forum_cat_post_comment.$sort_by", $order)
			->find_all();
		if(0 == $posts->count())
			return 'No posts created yet.';

		$primary			= new View('public_forum/posts_list');	
		$primary->posts 	= $posts;
		$primary->page_name = $page_name;
		return $primary;
	}

/*
 * return a list of comments belonging to the logged in user.
 */
	private function my_comments_list($page_name)
	{
		$order = (empty($_GET['sort']) OR 'oldest' != $_GET['sort'])
			? 'desc' : 'asc';
		$sort_by = (empty($_GET['sort']) OR 'newest' == $_GET['sort'] OR 'oldest' == $_GET['sort'])
			? 'created' : 'vote_count';

		$comments = ORM::factory('forum_cat_post_comment')
			->with('forum_cat_post')
			->where(array(
				'forum_cat_post_comments.fk_site'	=> $this->site_id,
				'account_user_id'		=> $this->account_user->get_user()->id,
				'is_post'	=> '0'
			))
			->orderby("forum_cat_post_comments.$sort_by", $order)
			->find_all();
		if(0 == $comments->count())
			return 'No comments added yet.';
			
		$view = new View('public_forum/my_comments_list');
		$view->comments = $comments;
		$view->page_name = $page_name;
		return $view;
	}

/*
 * return a list of posts starred by the logged in user.
 * THIS IS OFFLINE.
 */
	private function my_starred_list($page_name)
	{
		return 'this is the starred list';
		$order	 = (empty($_GET['sort']) OR 'oldest' != $_GET['sort'])
			? 'desc' : 'asc';
		$sort_by = (empty($_GET['sort']) OR 'votes' == $_GET['sort'])
			? 'vote_count' : 'created';		
		
		$posts = ORM::factory('forum_cat_post')
			->select('forum_cat_posts.*, forum_cats.name, forum_cats.url')
			->with('forum_cat_post_comment')
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where(array(
				'forum_cat_post_comment.fk_site'		 => $this->site_id,
				'forum_cat_post_comment.account_user_id' => $this->account_user->get_user()->id,
			))
			->orderby("forum_cat_post_comment.$sort_by", $order)
			->find_all();
		if(0 == $posts->count())
			return 'No posts have been starred yet.';

		$primary = new View('public_forum/posts_list');	
		$primary->posts = $posts;
		$primary->page_name = $page_name;
		return $primary;
	}
	



/*
 * submit a new post to a category.
 */
	private function submit($page_name, $tool_id)
	{
		if(!$this->account_user->logged_in($this->site_id))
			return new View('public_forum/login');
			
		if($_POST)
		{
			if(empty($_POST['title']) OR empty($_POST['body']))
				die('Title and Body cannot empty');

			# add to post table
			$new_post = ORM::Factory('forum_cat_post');
			$new_post->fk_site		= $this->site_id;
			$new_post->forum_cat_id	= $_POST['forum_cat_id'];
			$new_post->title		= $_POST['title'];
			$new_post->save();
			
			# add the child comment.
			$new_comment = ORM::Factory('forum_cat_post_comment');
			$new_comment->fk_site			= $this->site_id;
			$new_comment->forum_cat_post_id	= $new_post->id;
			$new_comment->account_user_id	= $this->account_user->get_user()->id;
			$new_comment->body				= $_POST['body'];
			$new_comment->created			= time();
			$new_comment->is_post			= '1';
			$new_comment->save();
			
			# update post with comment_id.
			$new_post->forum_cat_post_comment_id = $new_comment->id;
			$new_post->save();
			#TODO: output a success message.
		}
		
		$primary = new View('public_forum/submit');
		$primary->page_name = $page_name;
		$primary->categories = self::categories($tool_id);
		return $primary;
	}

/*
 * cast a vote
 * users can vote for comments/posts either up or down.
 * vote is added to comments, and logged so user can only vote once per comment.
 * TODO: degrade this for non-ajax requests.
 */	
	private function vote($page_name, $tool_id, $comment_id, $vote)
	{
		valid::id_key($comment_id);
		if(!$this->account_user->logged_in($this->site_id))
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
		$comment->vote_count = ($comment->vote_count + $vote);
		$comment->save();		

		# log the vote.
		$log_vote = ORM::factory('forum_comment_vote');
		$log_vote->account_user_id = $this->account_user->get_user()->id;
		$log_vote->forum_cat_post_comment_id = $comment_id;
		$log_vote->fk_site = $this->site_id; # for site garbage collection.
		$log_vote->save();

		die('Vote has been accepted!');
	}	
	
/*
 * edit a comment
 * make sure the comment belongs to the logged in user.
 */
	private function edit($page_name, $tool_id, $type, $id)
	{
		valid::id_key($id);
		if(!$this->account_user->logged_in($this->site_id))
			die('Please Login');
			
		if($_POST)
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
			die('Changes Saved'); # send data to javascript if enabled.
		}

		$primary = new View('public_forum/edit');
		$primary->page_name = $page_name;
		
		switch($type)
		{
			case 'post':
				$post = ORM::Factory('forum_cat_post')->find($id);
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
		
		$primary->comment	= ORM::Factory('forum_cat_post_comment')->find($comment_id);
		$primary->type		= $type;
		$primary->id		= $id;
		return $primary;
	}


/*
 * Ajax request handler.
 * param $url_array = (array) an array of url signifiers
 * param $tool_id 	= (int) the tool id of the tool.
 */ 	
	function _ajax($url_array, $tool_id)
	{
		list($page_name, $action, $data, $data2) = $url_array;
		$action = (empty($action) OR 'tool' == $action)
			? 'index'
			: $action;

		switch($action)
		{				
			case 'category':				
				if(empty($_GET['sort']))
					die(self::posts_wrapper($page_name, $tool_id, $data));

				die(self::posts_list($page_name, $data));
				break;
				
			case 'view':
				if(empty($_GET['sort']))
					die(self::comments_wrapper($page_name, $tool_id, $data));
				
				die(self::comments_list($page_name, $data));
				break;
				
			case 'my':
				# no sorter default to posts.
				if(empty($_GET['sort']))
					die(self::my($page_name, $tool_id, $data));
				
				# has sorter, so we fetch raw content lists
				if('posts' == $data)
					die(self::my_posts_list($page_name));
				if('comments' == $data)
					die(self::my_comments_list($page_name));
				if('starred' == $data)
					die(self::my_starred_list($page_name));
				die('trigger 404');
				break;

			case 'submit':
				die(self::submit($page_name, $tool_id));
				break;

			case 'vote':
				die(self::vote($page_name, $tool_id, $data, $data2));
				break;
			case 'edit':
				die(self::edit($page_name, $tool_id, $data, $data2));
				break;
			default:
				die("$page_name : <b>$action</b> : trigger 404 not found");
		}
		die('<br>something is wrong with the url');
	}
	
	
/*
 */	
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{	
			# sample category
			$new_cat			= ORM::factory('forum_cat');
			$new_cat->forum_id	= $tool_id;
			$new_cat->fk_site	= $site_id;
			$new_cat->name		= 'Feedback';
			$new_cat->url		= 'feedback';
			$new_cat->save();
			
			// Load the admin user
			$account_user = ORM::factory('account_user')
				->where('fk_site', $site_id)
				->find('admin');

				
			# sample post
			$new_post = ORM::Factory('forum_cat_post');
			$new_post->fk_site		= $site_id;
			$new_post->forum_cat_id	= $new_cat->id;
			$new_post->title		= 'Add pictures to your homepage.';
			$new_post->save();

	
			# add the child comment.
			$new_comment = ORM::Factory('forum_cat_post_comment');
			$new_comment->fk_site			= $site_id;
			$new_comment->forum_cat_post_id	= $new_post->id;
			$new_comment->account_user_id	= $account_user->id;
			$new_comment->body				= 'First impressions matter! Having nice, visually appealing pictures on your homepage, makes your website more attractive. Just my two cents. =D';
			$new_comment->created			= time();
			$new_comment->is_post			= '1';
			$new_comment->save();
			
			# update post with comment_id.
			$new_post->forum_cat_post_comment_id = $new_comment->id;
			$new_post->save();
			
			
			# another sample post
			$new_post->clear();
			$new_post->fk_site		= $site_id;
			$new_post->forum_cat_id	= $new_cat->id;
			$new_post->title		= 'I like this forum!';
			$new_post->save();

	
			# add the child comment.
			$new_comment->clear();
			$new_comment->fk_site			= $site_id;
			$new_comment->forum_cat_post_id	= $new_post->id;
			$new_comment->account_user_id	= $account_user->id;
			$new_comment->body				= 'This forum is very user friendly!<br> I like the ability to sort by newest, most active, and vote.<br>Pretty good!';
			$new_comment->created			= time()-60;
			$new_comment->is_post			= '1';
			$new_comment->save();
			
			# update post with comment_id.
			$new_post->forum_cat_post_comment_id = $new_comment->id;
			$new_post->save();
			
			
		}
		return 'manage';
	}
	
} /*end*/

#echo'<pre>'; print_r($primary->posts);echo'</pre>';die('asdf');


