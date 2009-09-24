<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * Public creation, management, and viewing of user accounts in +Jade.
 *
 * $this->account_user is centrally available via the Controller library core.	
*/

class Account_Controller extends Public_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

/* 
 * route the url
 * expects parent account table object
*/	
	public function _index($account)
	{
		$url_array	= Uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'account', $account->id);
		$username	= $url_array['2'];
		$action		= (empty($url_array['1']) OR 'tool' == $url_array['1'])
			? 'index'
			: $url_array['1'];

		switch($action)
		{					
			case 'index':
				$content = self::dashboard($page_name);
				break;
				
			case 'create':
				return $this->wrap_tool(self::create_account($page_name), 'account', $account);
				break;
				
			case 'profile':
				return $this->wrap_tool(self::profile($username), 'account', $account);
				break;

			case 'all':
				return $this->wrap_tool(self::all_users($page_name), 'account', $account);
				break;
				
			case 'edit_profile':
				$content = self::edit_profile($page_name);
				break;
			
			case 'change_password':
				$content = self::change_password($page_name);
				break;	

			case 'reset_password':
				$content = self::reset_password($page_name);
				break;	
				
			case 'logout':
				$this->account_user->logout();
				$primary = $this->display_login($page_name);
				return $this->wrap_tool($primary, 'account', $account);
				break;		


			/* plusjade stuff only */	
			case 'safe_mode':
				$content = self::safe_mode($page_name, $username);
				break;	
			case 'new_website':
				$content = self::new_website($page_name);
				break;
				
			default:
				die("$page_name : $action : trigger 404 not found");
		}
		
		# the logic above will determine whether the user is logged in/out
		$wrapper = ($this->account_user->logged_in($this->site_id))
			? new View('public_account/accounts/dashboard')
			: new View('public_account/accounts/index');
		$wrapper->content = $content;
		$wrapper->page_name = $page_name;
		return $this->wrap_tool($wrapper, 'account', $account);
	}

	
/*
 * shows a view of all users on this site.
 * output raw view.
 * settings should allow enable/disable of this.
 */
	private function all_users($page_name)
	{
		$primary = new View('public_account/accounts/all_users');
		$primary->page_name = $page_name;
		$primary->users =
			ORM::factory('account_user')
			->where('fk_site', $this->site_id)
			->find_all();
		return $primary;
	}
	


	
/*
 * show views based on if user is logged in or not.
 * output raw contents.
 */
	private function dashboard($page_name)
	{
		if($this->account_user->logged_in($this->site_id))
		{
			if(ROOTACCOUNT === $this->site_name)
			{
				$primary = self::plusjade_dashboard($page_name);
			}
			else
			{
				$primary = new View('public_account/accounts/dashboard_index');
				$primary->account_user = $this->account_user->get_user();
			}
		}
		elseif(! empty($_POST['username']) )
		{
			# atttempt to log user in.
			if($this->account_user->login($_POST['username'], (int)$this->site_id, $_POST['password'], TRUE))
			{
				if(ROOTACCOUNT === $this->site_name)
					$primary = self::plusjade_dashboard($page_name);
				else
				{
					$primary = new View('public_account/accounts/dashboard_index');
					$primary->account_user = $this->account_user->get_user();
				}
			}
			else
			{
				$primary = $this->display_login($page_name);
				$primary->errors = 'Invalid username or password';
			}
		}
		else
			$primary = $this->display_login($page_name);

		$primary->page_name = $page_name;
		return $primary;
	}

/*
 * display the login view
 */	
	private function display_login($page_name)
	{
		$account = ORM::factory('account')
			->where('fk_site', $this->site_id)
			->find();
			
		$view = new View('public_account/accounts/login');
		$view->page_name = $page_name;
		$view->account = $account;
		return $view;
	}
	

/*
 * View for create account
 * this helps us to attach specific error messages to the view.
 */
	private function display_create($page_name, $values=NULL, $errors=NULL)
	{
		$account = ORM::factory('account')
			->where('fk_site', $this->site_id)
			->find();
		
		$wrapper = new View('public_account/accounts/index');
		$wrapper->page_name = $page_name;
		$wrapper->content = new View('public_account/accounts/create_account');
		$wrapper->content->errors = $errors;
		$wrapper->content->values = $values;
		$wrapper->content->page_name = $page_name;
		$wrapper->content->account = $account;
		return $wrapper;
	}
	
/*
 * handler for Create a new account for this website.
 * returns a view with output message.
 */	 
	private function create_account($page_name)
	{
		if($this->account_user->logged_in($this->site_id))
		{
			# TODO: consider removing this duplication (of the dashboard)
			$wrapper = new View('public_account/accounts/dashboard');
			$wrapper->page_name = $page_name;
			$wrapper->content = new View('public_account/accounts/dashboard_index');
			$wrapper->content->account_user = $this->account_user->get_user();
			$wrapper->content->page_name = $page_name;
			return $wrapper;
		}
		
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
			if(!$post->validate())
			{
				$errors = $values;
				$errors	= arr::overwrite($errors, $post->errors('form_error_messages'));
				
				return self::display_create($page_name, $values, $errors);
			}
			# Create new user
			$account_user = ORM::factory('account_user');
			
			if($account_user->username_exists($_POST['username'], $this->site_id))
				return self::display_create($page_name, $values, 'username already exists');

			unset($_POST['password2']);
			$account_user->fk_site = $this->site_id;

			# load vars to user table
			foreach($_POST as $key => $val)
					$account_user->$key = $val;

			# save the user 
			if(!$account_user->save())
				return self::display_create($page_name, $values, 'There was a problem creating account.');
			
			# Log user in
			if(! $this->account_user->login($account_user, (int)$this->site_id, $_POST['password']))
				die('account created but login failed.');
			
			## create new campaign monitor instance for plusjade accounts ##
			if(ROOTACCOUNT === $this->site_name)
			{
				$user = $this->account_user->get_user();
				
				include Kohana::find_file('vendor','CMBase');
				
				# Create new account.
				$company	= $user->username;		
				$name		= $user->username;
				$email		= $user->email;
				$country	= 'United States of America';
				$timezone	= '(GMT-08:00) Pacific Time (US & Canada)';

				$cm = new CampaignMonitor;
				$result = $cm->clientCreate($company, $name, $email, $country, $timezone);
				
				if(is_string($result['anyType']))
				{
					$user->cm_id = $result['anyType'];
					$user->save();
					
					/*
					$accessLevel = '63';
					$username = 'apiusername';
					$password = 'apiPassword';
					$billingType = 'ClientPaysWithMarkup';
					$currency = 'USD';
					$deliveryFee = '7';
					$costPerRecipient = '3';
					$designAndSpamTestFee = '10';

					$result = $cm->clientUpdateAccessAndBilling(
						$result['anyType'],
						$accessLevel, 
						$user->username, 
						$password, 
						$billingType, 
						$currency, 
						$deliveryFee, 
						$costPerRecipient, 
						$designAndSpamTestFee
					);
					*/
				}
				else
				{
					kohana::log('error', "{$result['anyType']['message']} : CM client $user->username");
					#echo kohana::debug($result);
				}
			}
			
			
			# return the user dashboard.
			$wrapper = new View('public_account/accounts/dashboard');
			$wrapper->page_name = $page_name;
			$wrapper->content = new View('public_account/accounts/dashboard_index');
			$wrapper->content->account_user = $this->account_user->get_user();
			$wrapper->content->page_name = $page_name;
			return $wrapper; # login success
		}
		
		return self::display_create($page_name);
	}	


	
/*
 * View for singular profile of a user?
 * note: settings should allow public/private profiles.
 */
	private function profile($username)
	{
		$primary = new View('public_account/accounts/profile');
		$account_user = ORM::factory('account_user', $username);

		if(FALSE == $account_user->loaded)
		{
			$primary->account_user = FALSE;
			return $primary;
		}
		
		$primary->account_user = $account_user;
		# get user meta
		$db = new Database;
		$meta = $db->query("
			SELECT *
			FROM account_user_meta
			WHERE account_user_id = '$account_user->id'
			AND fk_site = '$this->site_id'
		");
		$primary->meta = $meta;
		return $primary;
	}

	
	
/*
 * View to allow the logged in user to edit his/her profile.
 */
	private function edit_profile($page_name)
	{
		if(!$this->account_user->logged_in($this->site_id))
			return $this->display_login($page_name);
			
		$primary	= new View('public_account/accounts/edit_profile');
		$db			= new Database;
		$account_user		= $this->account_user->get_user();
		$primary->account_user = $account_user;	
		$primary->page_name = $page_name;	
		
		if($_POST)
		{
			if(! empty($_POST['bio']) )
				$db->insert(
					'account_user_meta',
					array(
						'fk_site'			=> $this->site_id,
						'account_user_id'	=> $account_user->id,
						'key'				=> 'bio',
						'value'				=> text::auto_p($_POST['bio'])
					)
				);
			else
				foreach($_POST as $id => $data)
					$db->update(
						'account_user_meta',
						array('value' => $data),
						"id = '$id' AND fk_site = $this->site_id"
					);
			
			$primary->status = 'Profile Saved!';
		}
		$meta = $db->query("
			SELECT *
			FROM account_user_meta
			WHERE account_user_id = '$account_user->id'
			AND fk_site = '$this->site_id'
		");
		$primary->meta = (0 == $meta->count()) ? FALSE : $meta;
		return $primary;
	}
	

/*
 * View and handler for password change of the current logged in user
 */
	private function change_password($page_name)
	{
		if(!$this->account_user->logged_in($this->site_id))
			return $this->display_login($page_name);

		$primary = new View('public_account/accounts/change_password');
		$primary->success = FALSE;
		$primary->error = '';
		$primary->page_name = $page_name;
		
		if($_POST)
		{
			$old_password	= $_POST['old_password'];
			$salt			= $this->account_user->find_salt($this->account_user->get_user()->password);		
			$old_password	= $this->account_user->hash_password($old_password, $salt);
			unset($_POST['old_password']);
			
			if($old_password == $this->account_user->get_user()->password)
				if($this->account_user->get_user()->change_password($_POST, TRUE))
					$primary->success = TRUE;
				else
					$primary->error = 'New Password Error';
			else
				$primary->error = 'Old password is incorrect';
		}
		return $primary;
	}

	
/*
 * Reset the password and send email instructions to a given user.
 # not working.
 */
	private function reset_password()
	{		
		if($_POST)
		{
			if(!valid::email($_POST['email']))
				die('email is not valid');
				
			$db = new Database;
			$account_user_db = $db->query("
				SELECT id, username
				FROM account_users
				WHERE fk_site = '$this->site_id'
				AND email = '{$_POST['email']}'
			")->current();
			
			if(! is_object($account_user_db))
				die('This email does not exist in our records.');
				
			# Load the user model	
			$account_user = ORM::factory('account_user', $account_user_db->id);
			
			$new_password = text::random('alnum', 10);
			$account_user->password = $new_password;
			
			if($account_user->save())
				die("new password has been generated: $account_user->username: $new_password");
		
			# TODO: Remember to send the email!!!
		}
		die();
	}

	
/*
 * -----------------------------------------------------------
 * Functions used specifically for plusjade user accounts only.
 * -----------------------------------------------------------
 */
 
	private function plusjade_dashboard($page_name, $message=NULL)
	{
		$user = ORM::factory('account_user', $this->account_user->get_user()->id);
		$sites_array = array();
		
		foreach($user->sites as $site)
		{
			$first	= text::random('numeric', 5);
			$last	= text::random('numeric', 6);
			$sites_array["$site->subdomain"] = "$user->token";
		}
		
		$view = new View('public_account/jade/dashboard_wrapper');
		$view->message = $message;
		
		$view->content = new View('public_account/jade/dashboard');
		$view->content->user = $this->account_user->get_user();	
		$view->content->sites_array = $sites_array;
		$view->content->page_name = $page_name;
		
		$view->content->is_admin =
			('jade' === $this->account_user->get_user()->username)
			? TRUE
			: FALSE;
	
		return $view;
	}


/*
 * Post handler for creating another website from user panel.
 */	
	public function new_website($page_name)
	{
		if(ROOTACCOUNT != $this->site_name)
			die('return a 404 not found');
		
		if(!$_POST)
			return $this->plusjade_dashboard($page_name, 'Nothing Sent.');
			
		$site_name = valid::filter_php_url(trim($_POST['site_name']));	
		$site = ORM::factory('site');
		if($site->subdomain_exists($site_name))
			return $this->plusjade_dashboard($page_name, 'site name already exists');

		# attempt to create the website
		$status = Site_Controller::_create_website($site_name, 'base', $this->account_user->get_user()->id);
		return $this->plusjade_dashboard($page_name, $status);
	}
	
	
	
/*
 * Revert a site to a "safe_mode" theme.
 * Useful when a an active theme is missing or has corrupted files which
 * locks a user out of editing the website.
 */
	private function safe_mode($page_name, $site_name)
	{
		if(ROOTACCOUNT != $this->site_name)
			die('return 404 not found');	
		
		$site = ORM::factory('site', $site_name);
		if(!$site->has(ORM::factory('account_user', $this->account_user->get_user()->id)))
			return $this->plusjade_dashboard($page_name, 'You cannot edit this site.');
			
		$theme_path = DATAPATH . "$site_name/themes/safe_mode";	
		
		# delete safe-mode if it exists (might be tainted)
		if(is_dir($theme_path))
			Jdirectory::remove($theme_path);
	
		# create it from stock.
		if(!is_dir(DOCROOT . "_assets/themes/safe_mode"))
			return $this->plusjade_dashboard($page_name, 'Safe_mode theme does not exist. Please contact support@plusjade.com!!');

		if(! Jdirectory::copy(DOCROOT . "_assets/themes/safe_mode", $theme_path) )
			return $this->plusjade_dashboard($page_name, 'Uh oh, not even this worked. Please contact support@plusjade.com!!');


		$site->theme = 'safe_mode';
		$site->save();
	
		if(yaml::edit_site_value($site_name, 'site_config', 'theme', 'safe_mode'))
			return $this->plusjade_dashboard($page_name, "Safe-mode activated for <b>$site_name</b>");

		return $this->plusjade_dashboard($page_name, 'safe-mode theme could not be activated');
	}
/* 
 * end plusjade functions
 * ----------------------------------------------------------- 
 */


 
/*
 * Ajax request handler.
 * param $url_array = (array) an array of url signifiers
 * param $tool_id 	= (int) the tool id of the tool.
 */ 	
	public function _ajax($url_array, $tool_id)
	{
		list($page_name, $action, $username) = $url_array;
		$action = (empty($action) OR 'tool' == $action)
			? 'index'
			: $action;

		switch($action)
		{					
			case 'index':
				die(self::dashboard($page_name));
				break;
				
			case 'new':
				$primary->content = self::create_account($page_name);
				break;
				
			case 'profile':
				$primary->content = self::profile($username);
				break;

			case 'edit_profile':
					die(self::edit_profile($page_name));
				break;
			
			case 'change_password':
					die(self::change_password($page_name));
				break;	

			case 'reset_password':
				die(self::reset_password($page_name));
				break;	
				
			/* plusjade stuff only */	
			case 'safe_mode':
				die(self::safe_mode($page_name, $username));
				break;	
			case 'new_website':
				die(self::new_website($page_name));
				break;	
			
			default:
				die("$page_name : <b>$action</b> : trigger 404 not found");
		}
		die('<br>something is wrong with the url');
	}
	
/*
 *
 */
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		# other tools may depend on this admin user being logged in at site creation!
		if($sample)
		{
			# add an admin user.
			$account_user = ORM::factory('account_user');
			$account_user->fk_site	= $site_id;
			$account_user->email	= 'change_this@sample.com';
			$account_user->username = 'admin';
			$account_user->password = 'change_this_password';
			$account_user->save();
		}
		return 'add';
	}
	
}  # end



