<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * newsletter module is our API to the Campaign Monitor API
 * allows us to completely white-label subsciber interaction.
 */
 
class Newsletter_Controller extends Public_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}


	
/*
 * index handler.
 * routes the url in no-ajax mode.
 * expects parent forum table object
 */ 
	public function _index($newsletter, $sub_tool=FALSE)
	{
		# this is a hack for allowed sub_tools only.
		if(!is_object($newsletter))
		{
			$newsletter = ORM::factory('newsletter')
				->where('fk_site', $this->site_id)
				->find($newsletter);	
			if(FALSE === $newsletter->loaded)
				return $this->wrap_tool('newsletter error, please contact support', 'album', $album);	
		}
		
		$url_array	= Uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'newsletter', $newsletter->id);
		$data		= $url_array['2'];
		$action		= (empty($_GET['action']))
			? 'index'
			: $_GET['action'];
		

		switch($action)
		{					
			case 'index':
				$view = self::form_handler($page_name, $newsletter);
				break;
			case 'add':
				$view = self::add($page_name, $newsletter->id, $data);
				break;		
			default:
				die("$page_name : $action : trigger 404 not found");
		}
		# get the custom javascript;
		# $view->global_readyJS(self::javascripts());
		return $this->wrap_tool($view, 'newsletter', $newsletter);
	}
	

/*
 * index view
 */
	private function show_list($page_name, $newsletter)
	{
		$view = new View('public_newsletter/newsletters/list');
		$view->page_name = $page_name;
		$view->newsletter = $newsletter;
		return $view;
	}
	
	
/*
 * clients can add a newsletter.
 */
	private function add()
	{
		$view = new View('public_newsletter/newsletters/add');
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
 * newsletter subscribe form handler.
 * validates and adds a new subscriber to the appropriate list.
 */
	private function form_handler($page_name, $newsletter)
	{	
		$view = new View('public_newsletter/newsletters/form');
		$view->page_name = $page_name;
		$values = array(
			'name'	=> '',
			'email'	=> ''
		);
		$view->values = $values;
		
		if($_POST)
		{
			$post = new Validation($_POST);
			$post->pre_filter('trim');
			$post->add_rules('name', 'required');
			$post->add_rules('email', 'required', 'valid::email'); 

			if(!$post->validate())
			{
				$view->errors = arr::overwrite($values, $post->errors('form_error_messages'));
				$view->values = arr::overwrite($values, $post->as_array());			
				return $view;
			}			
			
			
			include Kohana::find_file('vendor','CMBase');
			$cm = new CampaignMonitor(null, null, $newsletter->cm_list_id);
			$result = $cm->subscriberAdd($_POST['email'], $_POST['name']);
			
			if($result['Result']['Code'] != 0)
			{
				kohana::log('error', $result['Result']['Message']);
				return 'There was an error adding you to the emailing list. Please try again later.';
			}
			
			return 'Thank you! You have been adding to our mailing list.';
		}

		return $view;
	}



/*
 * output the appropriate javascript based on the newsletter view.
 */	
	private function javascripts($newsletter)
	{
		$js = '';
		# prepare the javascript
		switch($newsletter->type)
		{
			case 'faqs':
				$js = '
					$("#newsletter_wrapper_'.$newsletter->id.' dd.faq_answer").hide();
					
					// add open/close icons
					$("#newsletter_wrapper_'.$newsletter->id.' dt a.toggle").click(function(){		
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
					$("#newsletter_wrapper_'.$newsletter->id.' .email_form_wrapper").hide();
						
					$("#newsletter_wrapper_'.$newsletter->id.' .inline_form").click(function(){
						$("#newsletter_wrapper_'.$newsletter->id.' .email_form_wrapper").slideToggle("slow");
						return false;
					});

					//email form
					$("#newsletter_wrapper_'.$newsletter->id.' form.public_ajaxForm").ajaxForm({
						target: "#newsletter_wrapper_'.$newsletter->id.' form.public_ajaxForm",
						beforeSubmit: function(){
							if( $("#newsletter_wrapper_'.$newsletter->id.' form.public_ajaxForm input[type=text]").jade_validate() )
								return true;
							else
								return false;
						}
					});	

					//newsletter form
					$("#newsletter_wrapper_'.$newsletter->id.' #newsletter_form").ajaxForm({
						target: "#newsletter_wrapper_'.$newsletter->id.' #newsletter_form",
						beforeSubmit: function(){
							if( $("#newsletter_wrapper_'.$newsletter->id.' #newsletter_form input[type=text]").jade_validate() )
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
						$("#newsletter_wrapper_' . $newsletter->id . ' .newsletter_item").hide();
						$(".tabs_tab_list li a").removeClass("active");
						var id = $(this).addClass("active").attr("href");
						$(id).show();
						return false;
					});
					$(".tabs_tab_list li a:first").click();
				';
				break;
				
			default: # people
			
				switch($newsletter->view)
				{
					case 'filmstrip':
						$js = "
							$('#newsletter_filmstrip_wrapper .newsletter_item').hide();

							$('.people_thumb a').click(function(){
								$('.people_thumb a').removeClass('active');
								var id = $(this).addClass('active').attr('rel');
								$('#newsletter_filmstrip_wrapper .newsletter_item').hide();
								$('#newsletter_item_'+id).slideDown('fast');
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
 * Ajax request handler.
 * param $url_array = (array) an array of url signifiers
 * param $tool_id 	= (int) the tool id of the tool.
 */ 	
	public function _ajax($url_array, $parent_id)
	{
		list($page_name, $action, $username) = $url_array;
		$action = (empty($action) OR 'tool' == $action)
			? 'index'
			: $action;
		$action = (empty($_GET['action']))
			? 'index'
			: $_GET['action'];
		
		$newsletter = ORM::factory('newsletter', $parent_id);
		
		switch($action)
		{					
			case 'index':
				die(self::form_handler($page_name, $newsletter));
				break;
			case 'add':
				$view = self::add($page_name, $newsletter->id, $data);
				break;		
			default:
				die("$page_name : $action : trigger 404 not found");
		}
		die('<br>something is wrong with the url');
	}
	
	
/*
 * add the newsletter to the site.
 */ 
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{	/*
			$newsletter = ORM::factory('newsletter', $tool_id);
			$newsletter->body = 'yahboi';
			$newsletter->save();
			*/
		}

		return 'add';
	}	
	
	
	
	
	
	
}
/* -- end module newsletter controller */