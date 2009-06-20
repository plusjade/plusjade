<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 
 * The Utada Master Controller contains functions only the master account holder
 * can use. Only available at rootsite
 */
class Utada_Controller extends Template_Controller {

	function __construct()
	{
		parent::__construct();
		if(ROOTACCOUNT != $this->site_name OR !$this->client->logged_in(2) )
			die('invalid credentials');
			
		$this->template->linkCSS('css/admin_global.css');
	}

/*
 * Index view, everything loads via ajax into the index view.
 */	
	public function index()
	{
		$primary = new View('utada/index');
		$db = new Database;
		
		$user_id = Auth::instance()->get_user()->id;
		$sites = $db->query("
			SELECT sites_users.*, sites.subdomain, sites.site_id 
			FROM sites_users 
			JOIN sites ON sites_users.fk_site = sites.site_id
			WHERE sites_users.fk_users = '$user_id'
		");
		$primary->sites = $sites;
		$this->template->primary = $primary;
	}
	
/*
 * Display all users with attached websites.
 */	
	public function all_users()
	{			
		$primary = new View('utada/all_users');
		$db = new Database;
		
		$users = $db->query("
			SELECT users.id,users.username, sites_users.*, sites.subdomain, sites.site_id,
			GROUP_CONCAT(CONCAT(sites.site_id, ':', sites.subdomain) separator '|') as site_string
			FROM users
			LEFT JOIN sites_users ON users.id = sites_users.fk_users
			LEFT JOIN sites ON sites_users.fk_site = sites.site_id
			GROUP BY users.username 
			ORDER BY users.username
		");
		$primary->users = $users;
		die($primary);
	}

	
/*
 * Display all websites with users having admin access
 */	
	public function all_sites()
	{			
		$primary = new View('utada/all_sites');
		$db = new Database;
		$sites = $db->query("
			SELECT sites.*,
			GROUP_CONCAT(users.username) as users_string
			FROM sites
			LEFT JOIN sites_users ON sites.site_id = sites_users.fk_site
			LEFT JOIN users ON sites_users.fk_users = users.id
			GROUP BY sites.subdomain
			ORDER BY sites.subdomain
		");
		$primary->sites = $sites;
		die($primary);
	}

/*
 * Display a singular data-view of a given website
 */		
	public function get_site($site_id=NULL)
	{
		valid::id_key($site_id);		
		$primary = new View('utada/get_site');
		$db = new Database;
		
		$site = $db->query("
			SELECT * 
			FROM sites 
			WHERE site_id='$site_id'
		")->current();	
		$primary->site = $site;
		
		$users = $db->query("
			SELECT sites_users.*, users.username 
			FROM sites_users
			JOIN users ON sites_users.fk_users = users.id
			WHERE fk_site='$site_id'
		");	
		$primary->users = $users;
		
		die($primary);
	}

	
/*
 * Display a singular data-view of a given user
 */	
	public function get_user($user_id)
	{
		valid::id_key($user_id);		
		$primary = new View('utada/get_user');
		$db = new Database;
		$user = $db->query("
			SELECT * 
			FROM users 
			WHERE id='$user_id'
		")->current();	
		$primary->user = $user;	
		die($primary);
	}

	
/*
 * Allows the master account to grant himself temp access to another account.
 * default access is not allowed. we use this as a better security measure
 * since this must accept a password not stored in the db/site.
 */
	public function grant_access()
	{
		if('adjf8w9eu4589ua8a' === $_POST['password'])
		{
			$site_id = valid::id_key($_POST['site_id']);
			
			# Create a sites_users access row for the master account
			$db = new Database;
			$data = array(
				'fk_site'	=> $site_id,
				'fk_users'	=> $this->client->get_user()->id,
			);
			$db->insert('sites_users', $data);
			
			$first	= text::random('numeric', 5);
			$last	= text::random('numeric', 6);
			$link	= '<a href="http://'. ROOTDOMAIN ."/get/auth/manage?site=$first$site_id$last".'">Go to site</a>';
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
		$db = new Database;
		
		$user_id = ((NULL === $user_id)) ?
			$this->client->get_user()->id : $user_id;
		
		$data = array(
			'fk_site'	=> $site_id,
			'fk_users'	=> $user_id,
		);		
		$db->delete('sites_users', $data);
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
			
		$db = new Database;	
		
		# DELETE ALL DATABASE ENTRIES	
		$db->delete('sites', array('site_id' => $site_id));
		$db->delete('sites_users', array('fk_site' => $site_id));
		$db->delete('pages', array('fk_site' => $site_id));
		# do this for all db tables?
		
		# NOTE (see the clean_db method in this class)
	
		# not deleting user stuff...
		# $db->delete('users', array('fk_site' => $site_id));
		
		# DELETE DATA FOLDER
		$data_path = DATAPATH ."$site_name";
		
		if( Jdirectory::remove($data_path) )
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
			'contact_types',
			'roles',
			'roles_users',
			'sites',
			'themes',
			'tools_list',
			'users',
			'user_tokens',
			'sites_users',
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
		
		#troubleshoot
		#echo'<pre>'; print_r($table_names);echo '</pre>'; die();
		
		# Get all site ids
		$sites = $db->query("
			SELECT site_id FROM sites
		");	
		foreach($sites as $site)
			$site_ids[] = $site->site_id;

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
				FROM $table WHERE fk_site 
				NOT IN ($id_string)
			");		
			
			if( $table_object->count() > 0 )
			{
				foreach($table_object as $row)
					$results[$table] .= "$row->$id<br>";
				
				# If orphans exists, delete all rows having fk_site...
				$db->delete($table, array("fk_site" => "$row->fk_site") );
			}
			else
				$results[$table] = 'clean';
		}
	
		$primary->results = $results;
		die($primary);
	}
	

} # End Utada Master Controller