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
class Auth_Controller extends Controller {


	const plusjade_site_id = ROOTSITEID; # TODO this is hardcoded for now.
	
	function __construct()
	{
		parent::__construct();
	}


/*
 * Creates a new website.
 There are two ways to create a website.
	1. at homepage without registering.
	2. in user panel for registered users.
 *
 */ 
	public static function _create_website($site_name, $theme='base', $user_id=NULL)
	{
		# Make sure the site_name is unique.
		$site_name = valid::filter_php_url($site_name);	
		$site = ORM::factory('site');
		if($site->subdomain_exists($site_name))
			return 'Site name already exists.';
		
		# create data folder structure for site
		$source	= DOCROOT . '_assets/data/_stock';
		$dest	= DATAPATH . $site_name;			
		
		if(!is_dir($source))
			return '_stock folder does not exist.';

		if(! Jdirectory::copy($source, $dest) )
			return 'Unable to make data folder';

		# add the theme.
		Theme_Controller::_new_website_theme($site_name, $theme);		
		
		# create site table row.
		$new_site = ORM::factory('site');
		$new_site->subdomain = $site_name;
		$new_site->theme 	 = $theme;
		$new_site->save();
		

		# Establish user access and claim status.
		if(empty($user_id))
		{
			# by cookie if no_register
			cookie::set("unclaimed_$new_site->id", sha1("r-A-n_$new_site->id-D-o:m"));
			$claimed = null;
		}
		else
		{
			# or by user_id if user is registered.
			$new_site->add(ORM::factory('account_user', $user_id));
			$new_site->claimed = 'yes';
			$claimed = 'TRUE';			
		}

		# save the site.
		$new_site->save();

		# create site_config file.
		$replacements = array(
			$new_site->id,
			$site_name,
			$new_site->theme,
			'',
			'home',
			$claimed,
		);
		yaml::new_site_config($site_name, $replacements);

		
		# create static pages.
		$new_page = ORM::factory('page');
		$new_page->fk_site		= $new_site->id;
		$new_page->page_name	= 'home';
		$new_page->label		= 'Home';
		$new_page->position		= 0;
		$new_page->menu			= 'yes';
		$new_page->save();
		
		
		# add sample tools to homepage.
		$sample_tools = array(
			'Format',
			'Navigation',
			'Album',
			'Text'
		);
		
		#TODO establish types of tools, with views, etc.
		foreach($sample_tools as $name)
			Tool_Controller::_add_tool($new_page->id, $name, $site_name, FALSE, TRUE);
		
		
		# install page builders.
		$install = array(
			'Account',
			'Blog',
			'Forum',
			'Calendar'
		);
		foreach($install as $toolname)
			Tool_Controller::_auto_tool($toolname, $new_site->id, $site_name, 'base');

		# add account_page name to site_config.
		yaml::edit_site_value($site_name, 'site_config', 'account_page', 'account');

		if(empty($user_id))
			url::redirect("http://$site_name.". ROOTDOMAIN);
		
		return 'Website Created!';
	}
	

/*
 * Externally authenticate a user to edit their website
 * Uses token to validate user, then passes to appropriate website.
 */ 
	public function manage()
	{	
		if(!empty($_GET['tKn']))
		{		
			/*
			 * Externally authenticate a user to edit THIS site
			 * token is sent from plusjade.com user accounts.
			 * TODO: make sure the token changes periodically so old ones expire.
			 */
			$plusjade_user	= ORM::factory('account_user')
				->where(array(
					'fk_site'	=> self::plusjade_site_id, # Todo: change hard coded value for root website.
					'token'		=> $_GET['tKn']
				))
				->find();
			
			if(!$plusjade_user->loaded)
				url::redirect();
			
			# can this user edit this site?
			if($plusjade_user->has(ORM::factory('site', $this->site_id)))
			{
				# setup credentials via the auth library
				$this->client->force_login($plusjade_user);
			}

			url::redirect();
		}
		die('no token');
	}
	

/*
 * view and handler for claim a website
 */ 
	public function claim()
	{
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
		
		# create a new user account for plusjade account_user tool.
		if($_POST)
		{
			$post = new Validation($_POST);
			$post->pre_filter('trim');
			$post->add_rules('email', 'required', 'valid::email'); 
			$post->add_rules('username', 'required', 'valid::alpha_numeric');
			$post->add_rules('password', 'required', 'matches[password2]', 'valid::alpha_dash');
			$values = array(
				'email'		=> '',
				'username'	=> '',
				'password'	=> '',
				'password2'	=> '',
			);
			$values	= arr::overwrite($values, $post->as_array()); 			
			if(! $post->validate() )
			{
				$errors = $values;
				$errors	= arr::overwrite($errors, $post->errors('form_error_messages'));
				
				die(self::display_create($values, $errors));
			}
			# Create new user
			$account_user	= ORM::factory('account_user');	
			$username		= valid::filter_php_url(trim($_POST['username']));
			
			if($account_user->username_exists($username, self::plusjade_site_id))
				die(self::display_create($_POST, 'username already exists'));

			unset($_POST['password2']);
			$account_user->fk_site = self::plusjade_site_id;

			# load vars to user table
			foreach($_POST as $key => $val)
					$account_user->$key = $val;
		
			# set edit rights for this site.
			$account_user->add(ORM::factory('site', $this->site_id));
			
			
			if(!$account_user->save())
				die(self::display_create($values, 'There was a problem creating your account.'));
			
			# mark site as claimed. database it as well.
			yaml::edit_site_value($this->site_name, 'site_config', 'claimed', 'TRUE');

			$site = ORM::factory('site', $this->site_id);
			$site->claimed = 'yes';
			$site->save();
			
			# setup the auth session.
			$this->client->force_login($account_user);
			
			die(View::factory('auth/claim_success'));
			
		}
		die(self::display_create());
	}

	
/*
 * handler for claim login.
 */ 
	public function claim_login()
	{
		if(!$_POST)
			die('nothing sent');

		# atttempt to log user in.
		if($this->account_user->login($_POST['username'], (int)self::plusjade_site_id, $_POST['password']))
		{
			$plusjade_user = ORM::factory('account_user')
				->where('fk_site', self::plusjade_site_id)
				->find($_POST['username']);
			
			# add edit rights for this site.
			$plusjade_user->add(ORM::factory('site', $this->site_id));
			$plusjade_user->save();
			
			# mark site as claimed. database it as well.
			yaml::edit_site_value($this->site_name, 'site_config', 'claimed', 'TRUE');
			$site = ORM::factory('site', $this->site_id);
			$site->claimed = 'yes';
			$site->save();
			
			# setup the auth session.
			$this->client->force_login($plusjade_user);			
			
			# FYI: this might log some newly created "account_user" out.
			$this->account_user->logout();
			die('<div class="success">Thanks! This website has been claimed and added to your account.</div>');
		}

		die('<div class="error">Invalid username or password</div>');	
	}
	
	

/*
 * View for create account
 * this helps us to attach specific error messages to the view.
 */
	private function display_create($values=NULL, $errors=NULL)
	{
		$view = new View('auth/claim');
		$view->errors = $errors;
		$view->values = $values;
		return $view;
	}
	
	
	
/**
 * log user out by destroying the auth session
 */ 
	public function logout()
	{
		$this->client->logout();
		url::redirect();
	}
	
} # End Auth Controller