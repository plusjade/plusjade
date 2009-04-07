<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Admin_View_Controller extends Controller {

	public $auto_render = TRUE;

	public function __construct()
	{
		parent::__construct();
		
		# Require Login and validated user/site 
		if(! $this->client->logged_in() )
			url::redirect();
		elseif($this->client->get_user()->client_site_id != $this->site_id)
			url::redirect();
			
		$this->template = new View("ajax");	
	
		# View variables						
		$data = array(
			'theme_name'		=> $this->theme,
			'site_name'			=> $this->site_name,
			'js_path'			=> 'http://' . ROOTDOMAIN . '/js',
			'data_path'			=> 'http://' . ROOTDOMAIN . "/data/{$this->site_name}",
			'custom_include'	=> DOCROOT."data/{$this->site_name}/themes/{$this->theme}/",
		);	
		$this->template->set_global($data);

		
		# Render Template immediately after controller method	
		if ($this->auto_render == TRUE)
			Event::add('system.post_controller', array($this, '_render'));
	}

	# Render loaded template when class is destroyed
	public function _render()
	{
		if ($this->auto_render == TRUE)
			$this->template->render(TRUE);
	}
} # End Template_Controller