<?php defined('SYSPATH') or die('No direct script access.');

/**
 * sitewide admin functions for the current site
 * this is basic client login view , global sitewide settings etc.
 * note the user in this scope is $this->client
 * and checks would include $this->client->can_edit($site_id);
 
	Technically within the system a client is an instance of 
	the account_user model *that* belong to the root site.
	
	That is to say that all account_users of plusjade.com are also
	clients. And clients can ONLY be valid plusjade account_users.
 */
 
class Admin_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	
/*
 * view and handler for on-site client login. 
 */	 
	public function index()
	{
		if($this->client->can_edit($this->site_id))
			die('Already Logged In');

		$view = new View('admin/wrapper');			
		$view->linkCSS("/_assets/css/wrapper.css");
		$view->admin_linkJS('get/js/live?v=1.0');
		$view->primary = new View('admin/login');
		$values = array(
			'username'	=> '',
			'password'	=> ''
		);
		
		if($_POST)
		{
			$post = new Validation($_POST);
			$post->pre_filter('trim');
			$post->add_rules('username', 'required', 'valid::alpha_numeric');
			$post->add_rules('password', 'required', 'valid::alpha_dash');
			$values = array(
				'username'	=> '',
				'password'	=> ''
			);
			$values	= arr::overwrite($values, $post->as_array()); 			
			if(!$post->validate())
			{
				# $view->error = arr::overwrite($values, $post->errors('form_error_messages'));
				$view->error = 'Invalid Username or Password.';
				$view->primary->values = $_POST;
				die($view);
			}
			
			# atttempt to log user in to the root site accounts.
			if($this->account_user->login($_POST['username'], (int)ROOTSITEID, $_POST['password'], FALSE))
			{
				$plusjade_user = $this->account_user->get_user();
				
				# can this user edit the site?
				if($plusjade_user->has(ORM::factory('site', $this->site_id)))
				{
					# setup credentials via the auth library
					$this->client->force_login($plusjade_user);
					url::redirect();
				}
				
				$view->primary->values = $_POST;
				$view->error = 'Cannot edit this site.';
				die($view);
			}
			
			$view->primary->values = $_POST;
			$view->error = 'Invalid username or password';
			die($view);		
		}
		
		$view->primary->values = $values;
		die($view);
	}
	
	
/**
 * set some sitewide settings
 */	
	public function settings()
	{
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
			
		$site = ORM::factory('site', $this->site_id);
		if(!$site->loaded)
			die('site not found');
			
		if($_POST)
		{
			$homepage = explode(':', $_POST['homepage']);
			
			$site->custom_domain = $_POST['custom_domain'];
			$site->homepage		 = $homepage[0];
			$site->save();
			
			# update site_config.yml if new homepage
			# and force page to be enabled.
			if($this->homepage != $homepage[0])
			{
				yaml::edit_site_value($this->site_name, 'site_config', 'homepage', $_POST['homepage']);
				$page = ORM::factory('page', $homepage[1]);
				$page->enable = 'yes';
				$page->save();
			}
			die('Sitewide settings saved.');
		}
		
		$pages = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->orderby('page_name')
			->find_all();
		
		$primary = new View('admin/settings');
		$primary->pages = $pages;
		$primary->custom_domain = $site->custom_domain;
		$primary->js_rel_command = 'close-base';
		die($primary);
	}

/**
 * log user out by destroying the auth session
 */ 
	public function logout()
	{
		$this->client->logout();
		url::redirect();
	}
	
} /* End of admin.php */
