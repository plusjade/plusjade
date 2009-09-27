<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * displays title/body pairs in various cool ways man.
 */
 
class Format_Controller extends Public_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	
/*
 * index handler
 * expects the parent format table object
 */ 
	public function _index($format)
	{		
		# determine the correct type.
		$which_type = (empty($format->type)) ? 'people' :  $format->type;
		$view = $this->$which_type($format);
		$view->format = $format;
		
		# add custom javascript;
		$view->global_readyJS(self::javascripts($format));

		return $this->wrap_tool($view, 'format', $format);
	}

/*
 * Formats the view to list people
 */
	private function people($format)
	{
		# which view?
		$which_view = (empty($format->view)) ? 'list' : $format->view;
		$view		= new View("public_format/people/$which_view");
		
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
 * Formats the view to list frequently asked questions
 */
	private static function faqs($format)
	{
		$view = new View("public_format/faqs/simple");
		return $view;
	}


/*
 * Formats the view to list contacts
 */
	private static function contacts($format)
	{
		$view = new View("public_format/contacts/list");
		return $view;
	}

/*
 * Formats the view to list frequently asked questions
 */
	private static function tabs($format)
	{
		$view = new View("public_format/tabs/stock");
		return $view;
	}
	
/*
 * Formats the view to display as slides
 */
	private static function slides($format)
	{
		$view = new View("public_format/slides");
		# Javascript
		$view->add_root_js_files('slide/slide_4.js');
		return $view;
	}

	
/*
 * Formats the view to show an html form
 */
	private static function forms($format)
	{
		if($_POST)
		{
			$values = array();
			$post = new Validation($_POST);
			$post->pre_filter('trim');
			
			# setup the form requirements.
				# TODO add more specific rule filters based on type.
				# e.g. validate, phone, email, website input where applicable.
			foreach($format->format_items as $item)
			{
				$field_name	= "$item->id:" . valid::filter_php_url($item->title);
				$values[$field_name] = '';
				
				if(!empty($item->album))
					$post->add_rules("$field_name", 'required');
			}
			
			# on error
			if(!$post->validate())
			{
				$view = new View("public_format/forms/list");
				$view->errors = $post->errors();
				$view->values = $_POST;
				$view->format = $format; # this is only necessary for ajax mode, which we shouldnt need often.
				return $view;
			}
			
			# on success send the email and display status message.
			$to = (empty($format->params))
				? 'superjadex12@gmail.com'
				: $format->params;
			$subject = 'Customer message from: '.url::site();			
			$headers = 'From: webmaster@example.com' . "\r\n" .
				'Reply-To: webmaster@example.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				
			ob_start();
			unset($_POST['post_handler']);
			foreach ($_POST as $name => $value)
			{
				$name = explode(':', $name);
				echo "<b>{$name[1]}</b>: $value\r\n";
			}
			$view = new View("public_format/forms/status");
			$view->success = FALSE;
			
			# REMEMBER, this is for dev host only.
			$view->output = ob_get_clean();
			return $view;	
			
			if(mail($to, $subject, ob_get_clean(), $headers))
				$view->success = TRUE;

			return $view;			
		}
		
		$view = new View("public_format/forms/list");
		return $view;
	}
	
	
/*
 * output the appropriate javascript based on the format view.
 */	
	private function javascripts($format)
	{
		$js = '';
		# prepare the javascript
		switch($format->type)
		{
			case 'faqs':
				$js = '
					$("#format_wrapper_'.$format->id.' dd.faq_answer").hide();
					
					// add open/close icons
					$("#format_wrapper_'.$format->id.' dt a.toggle").click(function(){		
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
					$("#format_wrapper_'.$format->id.' .email_form_wrapper").hide();
						
					$("#format_wrapper_'.$format->id.' .inline_form").click(function(){
						$("#format_wrapper_'.$format->id.' .email_form_wrapper").slideToggle("slow");
						return false;
					});

					//email form
					$("#format_wrapper_'.$format->id.' form.public_ajaxForm").ajaxForm({
						target: "#format_wrapper_'.$format->id.' form.public_ajaxForm",
						beforeSubmit: function(){
							if( $("#format_wrapper_'.$format->id.' form.public_ajaxForm input[type=text]").jade_validate() )
								return true;
							else
								return false;
						}
					});	

					//newsletter form
					$("#format_wrapper_'.$format->id.' #newsletter_form").ajaxForm({
						target: "#format_wrapper_'.$format->id.' #newsletter_form",
						beforeSubmit: function(){
							if( $("#format_wrapper_'.$format->id.' #newsletter_form input[type=text]").jade_validate() )
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
						$("#format_wrapper_' . $format->id . ' .format_item").hide();
						$(".tabs_tab_list li a").removeClass("active");
						var id = $(this).addClass("active").attr("href");
						$(id).show();
						return false;
					});
					$(".tabs_tab_list li a:first").click();
				';
				break;
				
			case 'forms':
				$js = '
					$(".tabs_tab_list li a").click(function(){
						$("#format_wrapper_' . $format->id . ' .format_item").hide();
						$(".tabs_tab_list li a").removeClass("active");
						var id = $(this).addClass("active").attr("href");
						$(id).show();
						return false;
					});
					$(".tabs_tab_list li a:first").click();
				';
				break;
				
			default: # people
			
				switch($format->view)
				{
					case 'filmstrip':
						$js = "
							$('#format_filmstrip_wrapper .format_item').hide();

							$('.people_thumb a').click(function(){
								$('.people_thumb a').removeClass('active');
								var id = $(this).addClass('active').attr('rel');
								$('#format_filmstrip_wrapper .format_item').hide();
								$('#format_item_'+id).slideDown('fast');
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
 * ajax handler.
 */ 	
	public function _ajax($url_array, $parent_id)
	{
		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($parent_id);
		if(!$format->loaded)
			die('invalid format id');
			
		
		die(self::forms($format));
		
		
		die('something is wrong with the url');
	}

	
	
	
/*
 * add the format to the site.
 */ 
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{
			$format = ORM::factory('format', $tool_id);
			$format->body = 'yahboi';
			$format->save();
		}

		return 'add';
	}	
	
	
	
	
	
	
}
/* -- end of application/controllers/home.php -- */