<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * display reviews interface
 * this is a protected tool =)
 
	 the reviews system should be able to toggle between 
	 open reviews, email newsletter reviews, or account only reviews.
 */
 
class Review_Controller extends Public_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * index handler.
 * routes the url in no-ajax mode.
 * expects parent forum table object
 */ 
	public function _index($review)
	{
		$url_array	= Uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'review', $review->id);
		$data		= $url_array['2'];
		
		$view = self::show_list($page_name, $review);
		
		if($_POST)
			$add_handler = self::post_review($page_name, $review->id);
		else
		{
			$add_handler = new View('public_review/reviews/add_form');
			$add_handler->page_name = $page_name;
			$add_handler->values = array('body' =>'', 'name' => '', 'email' => '');
		}
		
		$view->add_handler = $add_handler;
		$view->page_name = $page_name;
		# get the custom javascript;
		#$view->global_readyJS(self::javascripts($review));
		return $this->wrap_tool($view, 'review', $review);
	}
	

/*
 * show a list of all the reviews.
 */
	private function show_list($page_name, $review)
	{
		$view = new View('public_review/reviews/list');
		$view->page_name = $page_name;
		$view->review = $review;
		
		# get the review counts, OPTIMIZE THIS later.	
		$rating_counts = ORM::factory('review_item')
			->select('rating, COUNT(id) AS count')
			->where(array('fk_site' => $this->site_id, 'review_id' => $review->id))
			->orderby('rating')
			->groupby('rating')
			->find_all();
		$view->rating_counts = $rating_counts; 
		return $view;
	}
	
	
/*
 * clients can add a review.
 */
	private function add()
	{
		$view = new View('public_review/reviews/add');
		$view->allowed = TRUE;
		
		if(isset($_GET['id']))
		{
			# the id should be a hash?
			# and we setup the load the email/name fields.
		}

		return $view;
	}
	

/*
 * post review handler.
 * validates and adds the new review to the site.
 */
	private function post_review($page_name, $review_id)
	{
		# validate the form values.
		$post = new Validation($_POST);
		$post->pre_filter('trim');
		$post->add_rules('body', 'required');
		$post->add_rules('name', 'required');
		$post->add_rules('email', 'required');
		
		# on error
		if(!$post->validate())
		{
			$view = new View('public_review/reviews/add_form');
			$view->page_name = $page_name;
			$view->errors = $post->errors();
			$view->values = $_POST;
			return $view;
		}
		
		# on success
		$new_item = ORM::factory('review_item');
		$new_item->review_id	= $review_id;
		$new_item->fk_site		= $this->site_id;
		$new_item->body			= $_POST['body'];
		$new_item->rating		= $_POST['rating'];
		$new_item->name			= $_POST['name'];
		$new_item->save();
		
		$view = new View('public_review/reviews/status');
		$view->success = true;
		return $view;
	}



/*
 * output the appropriate javascript based on the review view.
 */	
	private function javascripts($review)
	{
		# javascript is added inline because it needs to be able to reinit itself.
		# this is OFF.
		$js = '
			$("#add_review_toggle").click(function(){
				$(".review_add_form_wrapper").slideToggle("fast");
			});
			//$(".review_add_form_wrapper").hide();
		';
		# place the javascript.
		return $this->place_javascript($js, TRUE);
	}

	
/*
 * ajax handler
 */ 	
	public function _ajax($url_array, $parent_id)
	{		
		list($page_name, $first_node) = $url_array;
		if($_POST)
			die(self::post_review($page_name, $parent_id));
			
		die('invalid data');
	}
	
	
/*
 * add the review to the site.
 */ 
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{
			/*
			$review = ORM::factory('review', $tool_id);
			$review->body = 'yahboi';
			$review->save();
			*/
		}
	}	
	
	
}
/* -- end review.php -- */