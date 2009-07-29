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
		
		$this->template->linkCSS("/_data/$this->site_name/themes/$this->theme/css/global.css?v=23094823-");
		$this->template->linkCSS('/_assets/css/admin_global.css');
		$this->template->linkJS('jquery_latest.js');
	}

/*
 * Index view, everything loads via ajax into the index view.
 */	
	public function index()
	{
		$user = ORM::factory('user', $this->client->get_user()->id);
		$primary = new View('utada/index');		
		$primary->sites = $user->sites;
		parent::build_output($primary);
	}
	
/*
 * Display all users with attached websites.
 */	
	public function all_users()
	{			
		$primary = new View('utada/all_users');
		$primary->users = ORM::factory('user')->find_all();
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
		
		$db = new Database;
		$sites = $db->query("
			SELECT sites.*,
			GROUP_CONCAT(users.username) as users_string
			FROM sites
			LEFT JOIN sites_users ON sites.id = sites_users.site_id
			LEFT JOIN users ON sites_users.user_id = users.id
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
		
		$site = ORM::factory('site', $site_id);
		
		$db = new Database;
		$users = $db->query("
			SELECT sites_users.*, users.username 
			FROM sites_users
			JOIN users ON sites_users.user_id = users.id
			WHERE sites_users.site_id='$site_id'
		");	
		$primary = new View('utada/get_site');
		$primary->site = $site;
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
		if('adjf8w9eu4589ua8a' === $_POST['password'])
		{
			# Create a sites_users access row for the master account
			$user = ORM::factory('user', $this->client->get_user()->id);
			$user->add(ORM::factory('site', valid::id_key($_POST['site_id'])));
			$user->save();
			
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
		$user_id = ((NULL === $user_id)) ?
			$this->client->get_user()->id : $user_id;
		
		$user = ORM::factory('user', $user_id);
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
		$site->remove(ORM::factory('site', $site_id));
		$site->delete();
		
		
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
			'account_user_tokens',
			'contact_types',
			'roles',
			'roles_users',
			'sites',
			'themes',
			'tools_list',
			'users',
			'user_tokens',
			'sites_users',
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
		
		#troubleshoot
		echo'<pre>'; print_r($table_names);echo '</pre>'; die();
		
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