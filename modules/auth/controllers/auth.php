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
		$this->template->linkCSS('/_assets/css/admin_global.css');
		$this->template->linkJS('jquery_latest.js');
		$this->template->linkJS('ui/ui_latest_lite.js');	
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
			$primary = $this->display_dashboard();
		}
		elseif($_POST)
		{
			$user = ORM::factory('user', $_POST['username']);
			
			# TRUE means to save token for auto login
			if (Auth::instance()->login($user, $_POST['password'], TRUE))
				$primary = $this->display_dashboard();
			else
			{
				$primary = new View('auth/login');
				$primary->errors = 'Invalid username or password';
			}
		}
		else
		{
			$this->template->title = 'User Login';
			$primary = new View('auth/login');
		}
		
		parent::build_output($primary);
	}
	
/*
 * Internal function used to setup the dashboard view.
 */	
	private function display_dashboard()
	{
		$this->template->title = 'My dashboard';	
		$primary = new View("auth/dashboard");
		$primary->user = Auth::instance()->get_user();	
		
		$user_id = Auth::instance()->get_user()->id;
		
		$db = new Database;
		$sites = $db->query("
			SELECT sites_users.*, sites.subdomain, sites.site_id 
			FROM sites_users 
			JOIN sites ON sites_users.fk_site = sites.site_id
			WHERE sites_users.fk_users = '$user_id'
		");		
		foreach($sites as $site)
		{
			$first	= text::random('numeric', 5);
			$last	= text::random('numeric', 6);
			$sites_array[$site->subdomain] = "$first$site->site_id$last";
		}
		$primary->sites_array = $sites_array;
		
		return $primary;
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
			
			# if token does not match any user...
			if( empty($user->username) )
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
			$site_id		= (string) $_GET['site'];
			$site_id		= substr($site_id, 5);
			$length			= strlen($site_id) - 6;
			(int)$site_id	= substr($site_id, 0, $length);	
			$token			= $this->client->get_user()->token;	
			
			$db = new Database;
			$site = $db->query("
				SELECT subdomain 
				FROM sites 
				WHERE site_id = '$site_id'
			")->current();
			
			if(! is_object($site) )
				die('invalid input');
				
			$user_site = "http://$site->subdomain.". ROOTDOMAIN ."/get/auth/manage?tKn=$token";
			url::redirect($user_site);
		}
		die();
	}
	
	
/*
 * Create a new user account + website. Website has username as sitename
 */	 
	public function create()
	{
		if(ROOTACCOUNT != $this->site_name)
			url::redirect();
	
		if($_POST)
		{
			# validate
			if('DOTHEDEW' != $_POST['beta'])
				$this->display_create('The beta code is not valid', $_POST); # this dies

			$post = new Validation($_POST);
			$post->pre_filter('trim');
			$post->add_rules('email', 'required', 'valid::email'); 
			$post->add_rules('username', 'required', 'valid::alpha_numeric');
			$post->add_rules('password', 'required', 'matches[password2]', 'valid::alpha_dash');
			
			if(! $post->validate() )
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
				$this->display_create($errors, $values);
				die('im dead');
			}
			
			# Create new user
			$user = ORM::factory('user');	
			$site_name = $_POST['username'];
			
			if($user->username_exists($site_name))
				die('domain already exists');

			if($user->username_exists($_POST['email']))
				die('email already exists');
				
			
			# HACK function for creating token for users.
			# GET THIS OUT OF HERE
			function quick_token()
			{
				$db = new Database;
				while (TRUE)
				{
					$token = text::random('alnum', 32);

					# Make sure the token does not already exist
					if ($db->select('id')->where('token', $token)->get('users')->count() === 0)
						return $token;
				}
			}	
			$_POST['token'] = quick_token();

			# load vars to user table
			foreach ($_POST as $key => $val)
				if($key != 'password2' AND 'beta' != $key)
					$user->$key = $val;
		
			# create new user with appropriate roles
			if(!$user->save() OR !$user->add(ORM::factory('role', 'login')))
				die('There was a problem creating a new user.');
			
			# create the website.
			#self::create_website($user->id, $site_name);
			# Log user in
			Auth::instance()->login($user, $_POST['password']);
			# Take to user dashboard
			url::redirect('get/auth');
		}
		
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
		
		/*
		#Javascript
		$this->template->global_readyJS('
			$("form input, form select").focus(function(){
				$("form input, form select").removeClass("input_focus");
				$(this).addClass("input_focus");
			});	
		');
		*/
		parent::build_output($primary);
	}

/*
 * View enabling the creation of another website for the currently logged in user.
 */	
	public function new_website()
	{
		if(ROOTACCOUNT != $this->site_name OR !$this->client->logged_in())
			url::redirect();

		if($_POST)
		{
			$site_name = trim($_POST['site_name']);
			$site_name = valid::filter_php_url($_POST['site_name']);	
		
			# make sure this sitename does not exist
			$db = new Database;
			$site = $db->query("
				SELECT subdomain
				FROM sites 
				WHERE subdomain = '$site_name'
			")->current();
			
			if(is_object($site))
				die('sitename already exists');
			
			# Create the website
			self::create_website($this->client->get_user()->id, $site_name);
			# Display the dashboard
			parent::build_output($this->display_dashboard());
		}
		else
			die();
		
	}

	
/*
 * Creates a new website instance for a particular user.
 * user must already exist
 * param $user_id = the user this site will belong to
 * param $site_name = the name of the new website.	
 */
	private static function create_website($user_id, $site_name)
	{
		# make sure we always know the site_name does not exist.
		
		# create data folder structure for site
		$source	= DATAPATH . '_stock';
		$dest	= DATAPATH . $site_name;			
		
		if(! Jdirectory::copy($source, $dest) )
			die('Unable to make data folder!'); #status message	

		$db = new Database;

		# create db sites record
		$data = array(
			'subdomain'	=> $site_name,
			'theme'		=> 'base',		
		);
		$site_id = $db->insert('sites', $data)->insert_id();
	
		# create sites_users record so this user can edit this site.
		$data = array(
			'fk_users' => "$user_id",
			'fk_site' => "$site_id"
		);
		$db->insert('sites_users', $data);
			
		# create home page
		$data = array(
			'fk_site'	=> $site_id,
			'page_name'	=> 'home',
			'label'		=> 'Home',
			'position'	=> '0',
			'menu'		=> 'yes',									
		);
		$query = $db->insert('pages', $data);
		
		# create about page
		$data = array(
			'fk_site'	=> $site_id,
			'page_name'	=> 'about',
			'label'		=> 'About',
			'position'	=> '1',
			'menu'		=> 'yes',								
		);
		$query = $db->insert('pages', $data);
		
		return TRUE;
	}
	
/*
 * Change the password of the current logged in user
 */
	function change_password()
	{
		if(ROOTACCOUNT != $this->site_name OR !$this->client->logged_in())
			url::redirect();	
			
		$this->template->title = 'Change Password';
		$primary = new View('auth/change_password');
		$primary->success = FALSE;
		$primary->error = '';
		
		if($_POST)
		{
			$old_password	= $_POST['old_password'];
			$auth			= Auth::instance();	
			$salt			= $auth->find_salt($auth->get_user()->password);		
			$old_password	= $auth->hash_password($old_password, $salt);
			unset($_POST['old_password']);
			
			if($old_password == $auth->get_user()->password)
			{
				if( $auth->get_user()->change_password($_POST, $save = TRUE) )
					$primary->success = TRUE;
				else
					$primary->error = 'New Password Error';
			}
			else
				$primary->error = 'Old password is incorrect';
		}

		parent::build_output($primary);
	}
	
/*
 * Reset the password and send email instructions to a given user.
 */
	public function reset_password()
	{
		if(ROOTACCOUNT != $this->site_name OR !$this->client->logged_in())
			url::redirect();	
		
		if($_POST)
		{
			$email = $_POST['email']; 
			if(!valid::email($email))
				die('email is not valid');
				
			$db = new Database;
			$user_db = $db->query("
				SELECT id, username
				FROM users 
				WHERE email = '$email'
			")->current();
			
			if(! is_object($user_db))
				die('This email does not exist in our records.');
				
			# Load the user model	
			$user = ORM::factory('user', $user_db->id);
			
			$new_password = text::random('alnum', 10);
			$user->password = $new_password;
			
			if($user->save())
				die("new password has been generated: $user->username: $new_password");
		
			# Remember to send the email!!!
		}
		die();
	}
	
/**
 * log user out by destroying the session
 */ 
	function logout()
	{
		Auth::instance()->logout();
		url::redirect();
	}
} # End Auth Controller