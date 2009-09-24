<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * displays title/body pairs in various cool ways man.
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
	function _index($review)
	{
		$url_array	= Uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'review', $review->id);
		$data		= $url_array['2'];
		$action		= (empty($_GET['action']))
			? 'index'
			: $_GET['action'];
		
		if($_POST)
			return $this->wrap_tool(
				self::post_review($page_name, $review->id), 
				'review', 
				$review
			);
		
		switch($action)
		{					
			case 'index':
				$view = self::show_list($page_name, $review);
				break;
			case 'add':
				$view = self::add($page_name, $review->id, $data);
				break;		
			default:
				die("$page_name : $action : trigger 404 not found");
		}
		$view->page_name	= $page_name;
		# get the custom javascript;
		# $view->global_readyJS(self::javascripts());
		return $this->wrap_tool($view, 'review', $review);
	}
	

/*
 * index view
 */
	private function show_list($page_name, $review)
	{
		$view = new View('public_review/reviews/list');
		$view->page_name = $page_name;
		$view->review = $review;
		return $view;
	}
	
	
/*
 * clients can add a review.
 */
	private function add()
	{
		$view = new View('public_review/reviews/add');
		$view->name = '';
		$view->email = '';
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
		$new_item = ORM::factory('review_item');
		$new_item->review_id	= $review_id;
		$new_item->fk_site		= $this->site_id;
		$new_item->body			= $_POST['body'];
		$new_item->rating		= $_POST['rating'];
		$new_item->name			= $_POST['name'];
		$new_item->save();
		
		
		$view = new View('public_review/reviews/add');
		return $view;
	}





	
/*
 * reviews the view to list people
 */
	private function people($review)
	{
		# which view?
		$which_view = (empty($review->view)) ? 'list' : $review->view;
		$view		= new View("public_review/people/$which_view");
		
		switch($which_view)
		{
			case 'list':
				
				break;
			case 'filmstrip':
				break;
		}
		$view->img_path = $this->assets->assets_url();
		return $view;
	}

/*
 * reviews the view to list frequently asked questions
 */
	private static function faqs($review)
	{
		$view = new View("public_review/faqs/simple");
		return $view;
	}


/*
 * reviews the view to list contacts
 */
	private static function contacts($review)
	{
		$view = new View("public_review/contacts/list");
		return $view;
	}

/*
 * reviews the view to list frequently asked questions
 */
	private static function tabs($review)
	{
		$view = new View("public_review/tabs/stock");
		return $view;
	}
	
/*
 * reviews the view to display as slides
 */
	private static function slides($review)
	{
		$view = new View("public_review/slides");
		# Javascript
		$view->add_root_js_files('slide/slide_4.js');
		return $view;
	}

/*
 * output the appropriate javascript based on the review view.
 */	
	private function javascripts($review)
	{
		$js = '';
		# prepare the javascript
		switch($review->type)
		{
			case 'faqs':
				$js = '
					$("#review_wrapper_'.$review->id.' dd.faq_answer").hide();
					
					// add open/close icons
					$("#review_wrapper_'.$review->id.' dt a.toggle").click(function(){		
							$dt = $(this).parent("dt");
							current = $dt.attr("class");	
							if("minus" == current) opposite = "plus";
							else opposite = "minus";

							$dt.removeClass(current).addClass(opposite)
							   .next("dd.faq_answer").slideToggle("fast");
							return false;		
					});		
				';					
				break;
			case 'contacts':
				$js = '
					$("#review_wrapper_'.$review->id.' .email_form_wrapper").hide();
						
					$("#review_wrapper_'.$review->id.' .inline_form").click(function(){
						$("#review_wrapper_'.$review->id.' .email_form_wrapper").slideToggle("slow");
						return false;
					});

					//email form
					$("#review_wrapper_'.$review->id.' form.public_ajaxForm").ajaxForm({
						target: "#review_wrapper_'.$review->id.' form.public_ajaxForm",
						beforeSubmit: function(){
							if( $("#review_wrapper_'.$review->id.' form.public_ajaxForm input[type=text]").jade_validate() )
								return true;
							else
								return false;
						}
					});	

					//newsletter form
					$("#review_wrapper_'.$review->id.' #newsletter_form").ajaxForm({
						target: "#review_wrapper_'.$review->id.' #newsletter_form",
						beforeSubmit: function(){
							if( $("#review_wrapper_'.$review->id.' #newsletter_form input[type=text]").jade_validate() )
								return true;
							else
								return false;
						},
						success: function(data) {
							$.facebox(data, "status_reload", "facebox_2");
							return false;		
						}			
					});
				';					
				break;
			
			case 'tabs':
				$js = '
					$(".tabs_tab_list li a").click(function(){
						$("#review_wrapper_' . $review->id . ' .review_item").hide();
						$(".tabs_tab_list li a").removeClass("active");
						var id = $(this).addClass("active").attr("href");
						$(id).show();
						return false;
					});
					$(".tabs_tab_list li a:first").click();
				';
				break;
				
			default: # people
			
				switch($review->view)
				{
					case 'filmstrip':
						$js = "
							$('#review_filmstrip_wrapper .review_item').hide();

							$('.people_thumb a').click(function(){
								$('.people_thumb a').removeClass('active');
								var id = $(this).addClass('active').attr('rel');
								$('#review_filmstrip_wrapper .review_item').hide();
								$('#review_item_'+id).slideDown('fast');
								return false;
							});
							
							$('.people_thumb a:first').click();
						";
						break;
				}
				break;
		}
		# place the javascript.
		return $this->place_javascript($js, TRUE);
	}

/*
 * add the review to the site.
 */ 
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{	/*
			$review = ORM::factory('review', $tool_id);
			$review->body = 'yahboi';
			$review->save();
			*/
		}

		return 'add';
	}	
	
	
	
	
	
	
}
/* -- end of application/controllers/home.php -- */