<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 
	TODO: make this work like my homepage controller.
	
 * The Utada Master Controller contains functions only the master account holder
 * can use. Only available at rootsite
 * It allows us to manupulate and act on sites as objects throughout the system.
 * This is different in that all other functions are *within* the scope of a particular site.
 */
 
class Utada_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		# $this->client->get_user()->id
		# is the account_user who can_edit this site. 
		# but checking on this requires that i first enter "can_edit" mode.
		
		if(ROOTACCOUNT != $this->site_name
			OR !$this->client->can_edit($this->site_id)
			OR 'jade' != $this->client->get_user()->username)
				die('invalid credentials');
	}

/*
 * Index view, everything loads via ajax into the index view.
 */	
	public function _index()
	{
		$user = ORM::factory('account_user', $this->account_user->get_user()->id);
		$primary = new View('utada/index');		
		$primary->sites = $user->sites;		
		return $primary;
	}
	
/*
 * Display all users with attached websites.
 */	
	public function all_users()
	{			
		$primary = new View('utada/all_users');
		$primary->users = ORM::factory('account_user')
			->where('fk_site', $this->site_id)
			->find_all();
		die($primary);
	}

	
/*
 * Display all websites with users having admin access
 */	
	public function all_sites()
	{			
		$primary = new View('utada/all_sites');
		$primary->sites = ORM::factory('site')->find_all();
		die($primary);
	}

/*
 * Display a singular data-view of a given website
 */		
	public function get_site($site_id=NULL)
	{
		valid::id_key($site_id);		
		$site = ORM::factory('site', $site_id);
		$primary = new View('utada/get_site');
		$primary->site = $site;
		die($primary);
	}

	
/*
 * Display a singular data-view of a given user
 */	
	public function get_user($user_id)
	{
		valid::id_key($user_id);		
		$primary = new View('utada/get_user');
		$primary->user = ORM::factory('user', $user_id);
		die($primary);
	}

	
/*
 * Allows the master account to grant himself temp access to another account.
 * default access is not allowed. we use this as a better security measure
 * since this must accept a password not stored in the db/site.
 */
	public function grant_access()
	{
		if('supausah' === $_POST['password'])
		{
			# Create access row for the master account
			$user = ORM::factory('account_user', $this->account_user->get_user()->id);
			$user->add(ORM::factory('site', valid::id_key($_POST['site_id'])));
			$user->save();
			
			$link	= '<a href="http://'."$_POST[site_name].". ROOTDOMAIN ."/get/auth/manage?tKn=$user->token".'">Go to site</a>';
			die("access granted: Remember to delete when finished.<br>$link");
		}
		die('invalid');
	}

/*
 * Remove access roles from sites_users table
 * accepts site_id and optional $user_id
 */	
	public function remove_access($site_id, $user_id=NULL)
	{
		valid::id_key($site_id);
		$user_id = ((NULL === $user_id)) ?
			$this->account_user->get_user()->id : $user_id;
		
		$user = ORM::factory('account_user', $user_id);
		$user->remove(ORM::factory('site', $site_id));
		$user->save();
		die('access removed');
	}
	
/*
 * Destroy a website's table and folder data.
 * IMPORTANT: this should work with every database entry relating to the site. 
 * For now we specify only what we want to delete
 * enable user destory too (might remove later)	
 */	 
	public function destroy_site($site_id = NULL, $site_name = NULL, $confirm = FALSE)
	{	
		valid::id_key($site_id);
		valid::url($site_name);
			
		if(FALSE === $confirm)
			die('add confirm to the url...');

		# delete pages rows
		ORM::factory('page')
			->where('fk_site', $site_id)
			->delete_all();

		# delete site rows.
		$site = ORM::factory('site', $site_id);
		#$site->remove(ORM::factory('site', $site_id));
		$site->delete();
		
		#hack to remove access privelages to sites that dont exist.
		$db = new Database;
		$db->delete('account_users_sites', "site_id = '$site_id'");
		
		# NOTE (see the clean_db method in this class)

		# DELETE DATA FOLDER		
		if(Jdirectory::remove(DATAPATH . $site_name))
			die('Site destroyed! =(');			
		
		die('Unable to destory data folder'); # error
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
		$primary = new View('utada/clean_db');
		$site_ids = array();
		$table_names = array();
		$protected_tables = array(
			'account_roles',
			'account_users_sites',
			'account_user_tokens', #these will expire after awhile.
			'contact_types',
			'system_tools',
			'system_tool_types',
			'sites',
			'themes',
			'version',
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
			if(! empty($table_names[$table]) )
				unset($table_names[$table]);	
		

		
		# Get all site ids
		$all_sites = ORM::factory('site')->find_all();
		foreach($all_sites as $site)
			$site_ids[] = $site->id;

		$id_string = implode(',', $site_ids);
		
		# Id string to view for convenience
		$primary->site_count = count($site_ids);
		$primary->id_string = $id_string;
		

		# Find all orphaned assets based on fk_site
		$results = array();
		foreach($table_names as $table)
		{
			$results[$table] = '';
			$id = (('pages_tools' == $table)) ? $id = 'guid' : 'id';

			$table_object = $db->query("
				SELECT fk_site 
				FROM $table
				WHERE fk_site 
				NOT IN ($id_string)
			");		
			
			#If orphans exists, delete all rows having fk_site...
			if($table_object->count() > 0)
			{
				foreach($table_object as $row)
				{
					$results["$table"] .= "$row->fk_site<br>";
					$db->delete($table, array('fk_site' => $row->fk_site));
				}
			}
			else
				$results[$table] = 'clean';
		}

		# troubleshoot
		# echo $id_string;
		# echo'<pre>'; print_r($table_names);echo '</pre>'; die();
		#echo'<pre>'; print_r($results);echo '</pre>'; die();
		
		$primary->results = $results;
		die($primary);
	}
	

} # End Utada Master Controller