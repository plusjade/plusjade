<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 
 * Functions exclusive to plusjade.com only
 *
 * $Id: user.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http:#kohanaphp.com/license.html
 */
class Auth_Controller extends Template_Controller {

	function __construct()
	{
		parent::__construct();
		$this->template->linkCSS("/_data/$this->site_name/themes/$this->theme/css/global.css?v=1.0");
		$this->template->linkCSS('/_assets/css/admin_global.css');
		$this->template->admin_linkJS('get/js/live?v=1.0');
	}

/*
 * User login screen for logged out users.
 * User dashboard for logged in users.
 */	 
	public function index()
	{
		if(ROOTACCOUNT != $this->site_name)
			url::redirect();	
		
		if( Auth::instance()->logged_in() )
		{
			$this->display_dashboard();
		}
		elseif($_POST)
		{
			$user = ORM::factory('user', $_POST['username']);
			
			# TRUE means to save token for auto login
			if (Auth::instance()->login($user, $_POST['password'], TRUE))
				$this->display_dashboard();
			else
			{
				$primary = new View('auth/login');
				$primary->errors = 'Invalid username or password';
				parent::build_output($primary);
			}
		}
		else
		{
			$this->template->title = 'User Login';
			$primary = new View('auth/login');
			parent::build_output($primary);
		}
	}

/*
 * Internal function used to setup the dashboard view.
 */	
	private function display_dashboard($message=NULL, $view='dashboard')
	{
		$this->template->title = 'My dashboard';	
		$user = ORM::factory('user', Auth::instance()->get_user()->id);
		
		foreach($user->sites as $site)
		{
			$first	= text::random('numeric', 5);
			$last	= text::random('numeric', 6);
			$sites_array[$site->subdomain] = "$first$site->id$last";
		}
		
		$wrapper = new View("auth/dashboard_wrapper");
		$wrapper->message = $message;
		
		$wrapper->content = new View("auth/$view");
		$wrapper->content->user = Auth::instance()->get_user();	
		if('dashboard' == $view)
			$wrapper->content->sites_array = $sites_array;
		
		return parent::build_output($wrapper);
	}

	
	
	public function account()
	{
		if(ROOTACCOUNT !== $this->site_name OR !$this->client->logged_in())
			url::redirect();	

		if($_POST)
		{
			$old_password	= $_POST['old_password'];
			$auth			= Auth::instance();	
			$salt			= $auth->find_salt($auth->get_user()->password);		
			$old_password	= $auth->hash_password($old_password, $salt);
			unset($_POST['old_password']);
			
			if($old_password == $auth->get_user()->password)
				if($auth->get_user()->change_password($_POST, $save = TRUE))
					return $this->display_dashboard('Your password has been changed!<p>An email has been sent to confirm these changes.</p>Thanks. =D', 'account');
				else
					return $this->display_dashboard('New Password Error', 'account');
			else
				return $this->display_dashboard('Old password is incorrect', 'account');
		}
		else
			return $this->display_dashboard(null, 'account');
	}
	
/*
 * View for Create a new user account + website.
 * Website has username as sitename
 */	 
	public function create()
	{
		if(ROOTACCOUNT != $this->site_name)
			url::redirect();
	
		if($_POST)
			$this->create_account();
		else
			$this->display_create();
	}

/*
 * Internal function used to setup the create view.
 */	
	private function display_create($errors=NULL, $values=NULL)
	{
		$this->template->title = 'Create Account';		
		$primary = new View("auth/create_user");
		$primary->errors = $errors;
		$primary->values = $values;
		return parent::build_output($primary);
	}
	
/*
 * actually create the account
 */		
	private function create_account()
	{	
		# beta code
		if('DOTHEDEW' != $_POST['beta'])
			return $this->display_create('The beta code is not valid', $_POST);

		# field validation	
		$post = new Validation($_POST);
		$post->pre_filter('trim');
		$post->add_rules('email', 'required', 'valid::email'); 
		$post->add_rules('username', 'required', 'valid::alpha_numeric');
		$post->add_rules('password', 'required', 'matches[password2]', 'valid::alpha_dash');
		
		if(!$post->validate())
		{
			$values = array(
				'beta'		=> '',
				'email'		=> '',
				'username'	=> '',
				'password'	=> '',
				'password2'	=> '',
			);	
			$errors = $values;
			$errors	= arr::overwrite($errors, $post->errors('form_error_messages'));
			$values	= arr::overwrite($values, $post->as_array()); 
			return $this->display_create($errors, $values);
		}

		# Create new user
		$user = ORM::factory('user');
		
		if($user->username_exists($_POST['username']))
			return $this->display_create('username already exists', $_POST);
			
		# load vars to user table
		foreach ($_POST as $key => $val)
			if($key != 'password2' AND 'beta' != $key)
				$user->$key = $val;
	
		# create new user with appropriate roles
		if(!$user->add(ORM::factory('role', 'login')) OR !$user->save())
			return $this->display_create('There was a problem creating the new user.', $_POST);
			

		self::create_website($user->id, $_POST['username']);
		Auth::instance()->login($user, $_POST['password']);
		url::redirect('/get/auth');
	}


/*
 * View enabling the creation of another website for the currently logged in user.
 */	
	public function new_website()
	{
		if(ROOTACCOUNT != $this->site_name OR !$this->client->logged_in())
			url::redirect();

		if(!$_POST)
			die();
			
		$site_name = valid::filter_php_url(trim($_POST['site_name']));	
		$site = ORM::factory('site');
		if($site->subdomain_exists($site_name))
			$this->display_dashboard('site name already exists');
		else
		{
			# attempt to create the website
			if(self::create_website($this->client->get_user()->id, $site_name))
				$this->display_dashboard('Website Created!');
			else
				$this->display_dashboard('There was a problem creating the website.');
		}
	}

	
/*
 * Creates a new website instance for a particular user.
 * user must already exist
 * param $user_id = the user this site will belong to
 * param $site_name = the name of the new website.	
 */		
	private static function create_website($user_id, $site_name)
	{
		# create data folder structure for site
		$source	= DOCROOT . '_assets/data/_stock';
		$dest	= DATAPATH . $site_name;			
		
		if(!is_dir($source))
			die('_stock folder does not exist.');

		if(! Jdirectory::copy($source, $dest) )
			die('Unable to make data folder');

		# add the theme.
		Theme_Controller::_new_website_theme($site_name, 'base');		
		
		# create site table row.
		$new_site = ORM::factory('site');
		$new_site->subdomain = $site_name;
		$new_site->theme 	 = 'base';
		$new_site->add(ORM::factory('user', $user_id));
		$new_site->save();

		# create site_config file.
		$replacements = array(
			$new_site->id,
			$site_name,
			$new_site->theme,
			'',
			'home'
		);
		yaml::new_site_config($site_name, $replacements);

		# install page builders.
		$install = array(
			'Account',
			#'Blog',
			'Forum',
			#'Calendar'
		);
		foreach($install as $toolname)
			Tool_Controller::_auto_tool($toolname, $new_site->id, $site_name, 'base');


		# add account_page name to site_config.
		yaml::edit_site_value($site_name, 'site_config', 'account_page', 'account');


		# create static pages.
		$new_page = ORM::factory('page');
		$new_page->fk_site		= $new_site->id;
		$new_page->page_name	= 'home';
		$new_page->label		= 'Home';
		$new_page->position		= 0;
		$new_page->menu			= 'yes';
		$new_page->save();
		
		# add a text tool with welcome message.
		Tool_Controller::_add_tool($new_page->id, 'Text', $site_name, FALSE, TRUE);
		
		return TRUE;
	}

	
/*
 * Reset the password and send email instructions to a given user.
 */
	public function reset_password()
	{
		if(ROOTACCOUNT != $this->site_name)
			url::redirect();	
		
		if(!empty($_POST['username']))
		{
			$user = ORM::factory('user', $_POST['username']);	
			if(FALSE === $user->loaded)
				die('This username does not exist in our records.');
				
			$user->password = text::random('alnum', 10);
			
			if($user->save())
				die("New password has been generated for: $user->username. PW: $user->password");
		
			# Remember to send the email!!!
		}
		die('nothing sent');
	}


/*
 * Revert a site to a "safe_mode" theme.
 * Useful when a an active theme is missing or has corrupted files which
 * locks a user out of editing the website.
 */
	public function safe_mode($site_name)
	{
		if(ROOTACCOUNT != $this->site_name OR !$this->client->logged_in())
			url::redirect();	
			
		$user_id = Auth::instance()->get_user()->id;
		$site = ORM::factory('site', $site_name);
		if(!$site->has(ORM::factory('user', $user_id)))
			return $this->display_dashboard('You cannot edit this site.');
			
		$theme_path = DATAPATH . "$site_name/themes/safe_mode";	
		
		# delete safe-mode if it exists (might be tainted)
		if(is_dir($theme_path))
			Jdirectory::remove($theme_path);
	
		# create it from stock.
		if(!is_dir(DOCROOT . "_assets/themes/safe_mode"))
			return $this->display_dashboard('Safe_mode theme does not exist. Please contact support@plusjade.com!!');

		if(! Jdirectory::copy(DOCROOT . "_assets/themes/safe_mode", $theme_path) )
			return $this->display_dashboard('Uh oh, not even this worked. Please contact support@plusjade.com!!');

		$site = ORM::factory('site', $this->site_id);
		$site->theme = 'safe_mode';
		$site->save();
	
		if(yaml::edit_site_value($site_name, 'site_config', 'theme', 'safe_mode'))
			return $this->display_dashboard("Safe-mode activated for <b>$site_name</b>");

		return $this->display_dashboard('safe-mode theme could not be activated');
	}


/*
 * Externally authenticate a user to edit their website
 * Uses token to validate user, then passes to appropriate website.
 */ 
	public function manage()
	{
		if(! empty($_GET['tKn']) )
		{		
			/*
			 * Externally authenticate a user to edit THIS site
			 * Accepts ONLY GET token
			 * This is dangerous so MAKE SURE to authenticate token
			 * with credentials FROM THIS SITE ONLY - else fail.		 
			 * (token should be sent FROM plusjade TO client site but not required)
			 */
			$token	= $_GET['tKn'];
			$user	= ORM::factory('user')->where('token', $token)->find();
			
			if(!$user->loaded)
				url::redirect();
			
			# Log user in ONLY IF site ids match (user is attached to this site)
			if(Auth::instance()->can_edit($this->site_id, $user->id))
				Auth::instance()->force_login($user->username);

			url::redirect();
		}
		elseif(! empty($_GET['site']) )
		{
			/*
			 * Execute "Edit Website" link on client dashboard (auth/index).
			 * This is pretty safe since the link will only
			 * build the auth token link to the website owned by the logged in user
			 */
			valid::id_key($_GET['site']);
			if(! $this->client->logged_in() )
				die('Please login');
			
			# get the real site_id using the correct offsets
			$site_id		= substr($_GET['site'], 5);
			$length			= strlen($site_id) - 6;
			(int)$site_id	= substr($site_id, 0, $length);	
			$token			= $this->client->get_user()->token;	
			
			$site = ORM::factory('site', $site_id);
			if(!$site->loaded)
				die('invalid input');
				
			$user_site = "http://$site->subdomain.". ROOTDOMAIN ."/get/auth/manage?tKn=$token";
			url::redirect($user_site);
		}
		die();
	}
	

	
/**
 * log user out by destroying the session
 */ 
	public function logout()
	{
		Auth::instance()->logout();
		url::redirect();
	}
} # End Auth Controller