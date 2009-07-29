<?php

class Account_Controller extends Controller {

/*
 * Public creation, management, and viewing of user accounts in +Jade.
 *
 * $this->account_user is centrally available via the Controller library core.	
*/
	
	function __construct()
	{
		parent::__construct();
	}

/* 
 * route the url
*/	
	function _index($tool_id)
	{
		$url_array	= Uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'account', $tool_id);
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
				return $this->public_template(self::create_account($page_name), 'account', $tool_id);
				break;
				
			case 'profile':
				return $this->public_template(self::profile($username), 'account', $tool_id);
				break;

			case 'all':
				return $this->public_template(self::all_users($page_name), 'account', $tool_id);
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
				return $this->public_template($primary, 'account', $tool_id, '');
				break;		
				
			default:
				die("$page_name : $action : trigger 404 not found");
		}
		
		# the logic above will determine whether the user is logged in/out
		$wrapper = ($this->account_user->logged_in())
			? new View('public_account/dashboard')
			: new View('public_account/index');
		$wrapper->content = $content;
		$wrapper->page_name = $page_name;
		return $this->public_template($wrapper, 'account', $tool_id, '');
	}

	
/*
 * shows a view of all users on this site.
 * output raw view.
 * settings should allow enable/disable of this.
 */
	private function all_users($page_name)
	{
		$primary = new View('public_account/all_users');
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
		if($this->account_user->logged_in())
		{
			$primary = new View('public_account/dashboard_index');
			$primary->account_user = $this->account_user->get_user();	
		}
		elseif(! empty($_POST['username']) )
		{
			$account_user = ORM::factory('account_user', $_POST['username']);
			
			# TRUE means to save token for auto login
			if($this->account_user->login($account_user, (int)$this->site_id, $_POST['password'], TRUE))
			{
				$primary = new View('public_account/dashboard_index');
				$primary->account_user = $this->account_user->get_user();
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
 * Create a new account for this website.
 * must return a view.
 */	 
	private function create_account($page_name)
	{
		if($this->account_user->logged_in())
		{
			#TODO: consider removing this duplication (of the dashboard)
			$wrapper = new View('public_account/dashboard');
			$wrapper->page_name = $page_name;
			$wrapper->content = new View('public_account/dashboard_index');
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
			if(! $post->validate() )
			{
				$errors = $values;
				$errors	= arr::overwrite($errors, $post->errors('form_error_messages'));
				
				return self::display_create($page_name, $values, $errors);
			}
			# Create new user
			$account_user = ORM::factory('account_user');	
			$site_name = $_POST['username'];
			
			if($account_user->username_exists($site_name, $this->site_id))
				return self::display_create($page_name, $values, 'username already exists');

			unset($_POST['password2']);
			$account_user->fk_site = $this->site_id;

			# load vars to user table
			foreach($_POST as $key => $val)
					$account_user->$key = $val;
		
			# create new user with appropriate roles
				# omit roles for now.
				// if(!$account_user->save() OR !$account_user->add(ORM::factory('role', 'login')))
				// die('There was a problem creating a new user.');
			
			if(!$account_user->save())
				return self::display_create($page_name, $values, 'There was a problem creating account.');
			
			
			/*
			 * HACK 
			 * duplicate this account to the plusjade users database table.
			 * if this site is the rootaccount
			 */
			if(ROOTACCOUNT === $this->site_name)
			{
				$new_user = ORM::factory('user');
				foreach($_POST as $key => $val)
					$new_user->$key = $val;
					
				$new_user->save();
			}
			
			
			# Log user in
			if(! $this->account_user->login($account_user, (int)$this->site_id, $_POST['password']))
				die('account created but login failed.');
			
			# return the user dashboard.
			$wrapper = new View('public_account/dashboard');
			$wrapper->page_name = $page_name;
			$wrapper->content = new View('public_account/dashboard_index');
			$wrapper->content->account_user = $this->account_user->get_user();
			$wrapper->content->page_name = $page_name;
			return $wrapper; # login success
		}
		
		return self::display_create($page_name);
	}	

/*
 * outputs the view for create account view
 * this helps us to attach specific error messages to the view.
 */
	private function display_create($page_name, $values=NULL, $errors=NULL)
	{
		$account = ORM::factory('account')
			->where('fk_site', $this->site_id)
			->find();
		
		$wrapper = new View('public_account/index');
		$wrapper->page_name = $page_name;
		$wrapper->content = new View('public_account/create_account');
		$wrapper->content->errors = $errors;
		$wrapper->content->values = $values;
		$wrapper->content->page_name = $page_name;
		$wrapper->content->account = $account;
		return $wrapper;
	}
	
/*
 * get a singular profile of a user?
 * note: settings should allow public/private profiles.
 */
	private function profile($username)
	{
		$primary = new View('public_account/profile');
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
 * allow the logged in user to edit his/her profile.
 */	
	private function display_login($page_name)
	{
		$account = ORM::factory('account')
			->where('fk_site', $this->site_id)
			->find();
			
		$view = new View('public_account/login');
		$view->page_name = $page_name;
		$view->account = $account;
		return $view;
	}
/*
 * allow the logged in user to edit his/her profile.
 */
	private function edit_profile($page_name)
	{
		if(!$this->account_user->logged_in())
			return $this->display_login($page_name);
			
		$primary	= new View('public_account/edit_profile');
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
						'value'				=> $_POST['bio']
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
 * Change the password of the current logged in user
 */
	private function change_password($page_name)
	{
		if(!$this->account_user->logged_in())
			return $this->display_login($page_name);

		$primary = new View('public_account/change_password');
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
 * Ajax request handler.
 * param $url_array = (array) an array of url signifiers
 * param $tool_id 	= (int) the tool id of the tool.
 */ 	
	function _ajax($url_array, $tool_id)
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
				
			default:
				die("$page_name : <b>$action</b> : trigger 404 not found");
		}
		die('<br>something is wrong with the url');
	}
	

	
}  # end -- /



