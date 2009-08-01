<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * User authorization library. Handles user login and logout, as well as secure
 * password hashing.
 *
 * @package    Auth
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Auth_Core {

	// Session instance
	protected $session;

	// Configuration
	protected $config;

	/**
	 * Create an instance of Auth.
	 *
	 * @return  object
	 */
	public static function factory($config = array())
	{
		return new Auth($config);
	}

	/**
	 * Return a static instance of Auth.
	 *
	 * @return  object
	 */
	public static function instance($claimed=FALSE, $config = array())
	{
		static $instance;

		// Load the Auth instance
		empty($instance) and $instance = new Auth($claimed, $config);

		return $instance;
	}

	/**
	 * Loads Session and configuration options.
	 *
	 * @return  void
	 */
	public function __construct($claimed=FALSE, $config = array())
	{
		# is this website claimed ? has account attached to it.
		$this->claimed = $claimed;
		
		// Append default auth configuration
		$config += Kohana::config('auth');

		// Clean up the salt pattern and split it into an array
		$config['salt_pattern'] = preg_split('/,\s*/', Kohana::config('auth.salt_pattern'));

		// Save the config in the object
		$this->config = $config;

		// Set the driver class name
		$driver = 'Auth_'.$config['driver'].'_Driver';

		if ( ! Kohana::auto_load($driver))
			throw new Kohana_Exception('core.driver_not_found', $config['driver'], get_class($this));

		// Load the driver
		$driver = new $driver($config);

		if ( ! ($driver instanceof Auth_Driver))
			throw new Kohana_Exception('core.driver_implements', $config['driver'], get_class($this), 'Auth_Driver');

		// Load the driver for access
		$this->driver = $driver;

		Kohana::log('debug', 'Auth Library loaded');
	}

	/**
	 * Check if there is an active session. Optionally allows checking for a
	 * specific role.
	 *
	 * @param   string   role name
	 * @return  boolean
	 */
	public function logged_in($role = NULL)
	{
		return $this->driver->logged_in($role);
	}

	
	/**
	 * Check if there there active auth session that can edit this loaded site.
		this is an authentication class, therefore it should 
		be used to enable or restrict access to THIS site.
		other checks belong elsewhere.
	 */
	public function can_edit($site_id)
	{
		# if site has not been claimed, enable based on cookie.
		if(empty($this->claimed))
		{
			if(sha1("r-A-n_$site_id-D-o:m") == cookie::get("unclaimed_$site_id"))
				return TRUE;

			return FALSE;
		}
		
		# check if auth session exists (so we dont run a query needlessly);
		if(!$this->logged_in())
			return FALSE;
			
		
		$user = ORM::factory('account_user', $this->get_user()->id);
		if($user->has(ORM::factory('site', $site_id)))
			return TRUE;


		return FALSE;
	}	
	
	/**
	 * Returns the currently logged in user, or FALSE.
	 *
	 * @return  mixed
	 */
	public function get_user()
	{
		return $this->driver->get_user();
	}


	/**
	 * Attempt to automatically log a user in.
	 *
	 * @return  boolean
	 */
	public function auto_login()
	{
		return $this->driver->auto_login();
	}

	/**
	 * Force a login for a specific username.
	 *
	 * @param   mixed    username
	 * @return  boolean
	 */
	public function force_login($username)
	{
		return $this->driver->force_login($username);
	}

	/**
	 * Log out a user by removing the related session variables.
	 *
	 * @param   boolean  completely destroy the session
	 * @return  boolean
	 */
	public function logout($destroy = FALSE)
	{
		return $this->driver->logout($destroy);
	}
	
	

} // End Auth