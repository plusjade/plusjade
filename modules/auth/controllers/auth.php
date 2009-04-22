<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Auth module demo controller. This controller should NOT be used in production.
 * It is for demonstration purposes only!
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
		$this->template->linkCSS('css/auth.css');
		$js_array = array('jgrowl/jquery.jgrowl_minimized.js');	
		#$this->template->global_linkJS($js_array);		
		
		# $this->profiler = new Profiler;		
	}
	
	/*
	 * User login screen for logged out users.
	 * User dashboard for logged in users.
	 */	 
	public function index()
	{
		# dashboard only available at plusjade
		if($this->site_id != 1) url::redirect();
		
		# begin with no access 
		$access = FALSE;
		$primary = new View("auth/login");
		
		# if logged in
		if(Auth::instance()->logged_in())
		{
			$access = TRUE;
			$primary = new View("auth/dashboard");
		}
		elseif ($_POST) # trying to log in...
		{
			# Load the user
			$user = ORM::factory('user', $_POST['username']);
			
			# Login successful 
			# TODO: force facebox init
			# TRUE means to save token for auto login
			if (Auth::instance()->login($user, $_POST['password'], TRUE))
			{			
				$access = TRUE;	
				$primary = new View("auth/dashboard");
			}
			else
				$primary->errors = 'Invalid username or password';
		}
			
		if ($access === TRUE)
		{
			# User dashboard screen	
			$primary->user = Auth::instance()->get_user();
			$this->template->title = 'Auth dashboard';
			$this->template->primary = $primary; 
		}
		else
		{
			# Log in screen
			$this->template->title = 'User Login';
			$this->template->primary = $primary;
			$primary = new View('auth/login');		
		}
	}


	/*
	 * Externally authenticate a user to edit their website
	 * Uses token to validate user
	 * no view
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
			if(empty($user->username)) url::redirect();
			
			# Log user in ONLY IF site ids match (user is attached to this site)
			if($user->client_site_id == $this->site_id)
				Auth::instance()->force_login($user->username);

			url::redirect();
		}
		else
		{
			/*
			 * Execute "Edit Website" link on client dashboard (auth/index).
			 * This is pretty safe since the link will ONLY
			 * build the auth token link to the website owned by the logged in user
			 */
			
			if(!$this->client->logged_in()) url::redirect();
			
			$user	= $this->client->get_user()->username;	
			$token	= $this->client->get_user()->token;	
			
			$user_site = "http://$user.".ROOTDOMAIN."/get/auth/manage?tKn=$token";
			url::redirect($user_site);
		}
	
	}
	
	/*
	 * Create a new user account (creates website as well)
	 */
	 
	public function create()
	{
		if($this->site_name != 'jade') url::redirect();
		if(!$this->client->logged_in('admin')) url::redirect();	
		
		$this->template->title = 'Create User';		
		$primary = new View("auth/create_user");
		
		if ($_POST)
		{	
			# setup and initialize your form field names
			$form = array(
				'email'		=> '',
				'username'	=> '',
				'password'	=> '',
				'password2'	=> '',
			);		 
			#  copy the form as errors, so the errors will be stored with keys corresponding to the form field names
			$errors = $form;

			# Create new user
			$user = ORM::factory('user');			
			
			$post = new Validation($_POST);
			$post->pre_filter('trim');
			$post->add_rules('email', 'required', 'valid::email'); 
			$post->add_rules('username', 'required', 'valid::alpha_numeric');
			$post->add_rules('password', 'required', 'matches[password2]', 'valid::alpha_dash');
			
			if ($post->validate())
			{
				if ( ! $user->username_exists($_POST['username']))
				{
					# HACK function for creating token for users.
					# GET THIS OUT OF HERE
					function quick_token()
					{
						$db = new Database;
						while (TRUE)
						{
							// Create a random token
							$token = text::random('alnum', 32);

							// Make sure the token does not already exist
							if ($db->select('id')->where('token', $token)->get('users')->count() === 0)
							{
								// A unique token has been found
								return $token;
							}
						}
					}	
					$_POST['token'] = quick_token();

					# load vars to user table
					foreach ($_POST as $key => $val)
					{
						# Set user data
						if($key != 'password2')
							$user->$key = $val;
					}
					
					# save user and save login role data
					# this condition physically sets up the site
					if ($user->save() AND $user->add(ORM::factory('role', 'login')))
					{
						# create data folder structure for site
						$copy_data	= new Data_Folder;	
						$source		= DOCROOT.'/data/_stock';
						$dest		= DOCROOT."/data/{$_POST['username']}";			
						
						if($copy_data->dir_copy($source, $dest))
						{
							$db = new Database;
							
							# create db sites record
							$data = array(
								'url'	=> $_POST['username'],
								'theme'	=> 'redcross',		
							);
							$query = $db->insert('sites', $data);
							$sites_insert_id = $query->insert_id();

							# update users table to reflect new site id
							$db->update('users', array('client_site_id' => "$sites_insert_id"), array('username' => "{$_POST['username']}"));

							# create pages home record
							$data = array(
								'fk_site'	=> $sites_insert_id,
								'page_name'	=> 'home',
								'label'		=> 'Home',
								'position'	=> '0',								
							);
							$query = $db->insert('pages', $data);
							$pages_insert_id = $query->insert_id();
							
							#log user in
							Auth::instance()->login($user, $_POST['password']);
								
							#take to user dashboard
							url::redirect('get/auth');							
						}
						else
							echo 'Unable to make data folder!'; #status message	

					}
				}
				else
					$primary->error = 'domain already exists';

			}
			else
			{
				# repopulate the form fields
				$form = arr::overwrite($form, $post->as_array()); 
				# populate the error fields, if any
				# We need to already have created an error message file, for Kohana to use
				# Pass the error message file name to the errors() method
				$errors = arr::overwrite($errors, $post->errors('form_error_messages'));
				$primary->error = $errors;
			}	
		}
		
		# Display the form
		$this->template->primary = $primary;
			
	}
	
	/*
	 * destroy a complete site (only jade can access)
	 * IMPORTANT: this should work with every database entry relating to the site. 
	 * For now we specify only what we want to delete
	 * enable user destory too (might remove later)	
	 */
	 
	public function destroy($site_id = NULL, $site_name = NULL, $confirm = NULL)
	{
		if($this->site_name != 'jade') url::redirect();
		if(!$this->client->logged_in(2)) url::redirect();	
		
		$db = new Database;	
		$primary = new View('auth/destroy');
		
		if(!empty($site_id) AND empty($confirm))
			$primary->confirm_link = '<a href="/e/auth/destroy/' . $site_id . '/' . $site_name . '/true">Are you sure??</a>';
		
		if(!empty($site_id) AND !empty($site_name) AND !empty($confirm))
		{
			# DELETE ALL DATABASE ENTRIES
			$db->delete('users', array('client_site_id' => $site_id));		
			$db->delete('pages', array('fk_site' => $site_id));
			$db->delete('sites', array('site_id' => $site_id));
			# do this for all db tables?
			# note (see the clean_db method in this class)
		
			# DELETE DATA FOLDER
			$data_path = DOCROOT."data/$site_name";
			$directory = new Data_Folder;
			
			if($directory->rmdir_recurse($data_path))
				echo 'Site destroyed! =('; #success message	 			
			else
				echo 'Unable to destory data folder'; #error message
		
		}		
		$result = $db->query("SELECT site_id, url FROM sites");
		$primary->sites = $result;
		
		$this->template->primary = $primary;
	}

/*
 * Searches all tables for fk_site ids that no longer exist.
 * In order to work, all tables must have fk_site fields
 * And must have id field named "id"
 * Only one exception @ pages_tools where id = guid
 */
	function clean_db()
	{
		$db = new Database;
		$primary = new View('auth/clean_db');
		$site_ids = array();
		$table_names = array();
		$protected_tables = array(
			'contact_types',
			'roles',
			'roles_users',
			'sites',
			'themes',
			'tools_list',
			'users',
			'user_tokens',
		);
		
		# Get all tables from database
		$all_tables = $db->query("SHOW TABLES from plusjade");
		$all_tables->result(FALSE);		
		foreach($all_tables as $table)
		{
			$name = $table['Tables_in_plusjade'];
			$table_names[$name] = $name;
		}
		
		# Remove protected Tables
		foreach ($protected_tables as $table)
		{
			if(! empty($table_names[$table]) )
				unset($table_names[$table]);
		}	
		
		#troubleshoot
		#echo'<pre>'; print_r($table_names);echo '</pre>'; die();
		
		# Get all site ids
		$sites = $db->query("SELECT site_id FROM sites");	
		foreach($sites as $site)
		{
			$site_ids[] = $site->site_id;
		}
		$id_string = implode(',', $site_ids);
		
		# Id string to view for convenience
		$primary->site_count = count($site_ids);
		$primary->id_string = $id_string;

		$results = array();
		# Find all orphaned assets based on fk_site
		foreach($table_names as $table)
		{
			$results[$table] ='';
			$id = 'id';
			if( 'pages_tools' == $table )
				$id = 'guid';
							
			$table_object = $db->query("SELECT fk_site FROM $table WHERE fk_site NOT IN ($id_string) ");		
			
			if( $table_object->count() > 0 )
			{
				foreach($table_object as $row)
				{
					$results[$table] .= $row->$id . '<br>';
				}
				/*
				If orphans exists,
				run delete query on all rows having fk_site...
				*/
				$db->delete($table, array("fk_site" => "$row->fk_site") );
				
			}
			else
			{
					$results[$table] = 'clean';
			}
		}
	
		$primary->results = $results;
		$this->template->primary = $primary;
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