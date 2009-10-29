<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * ORM Account driver.
 *
 * $Id: ORM.php 3810 2008-12-18 12:59:56Z samsoir $
 *
 * @package    Account
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Account_ORM_Driver extends Account_Driver {

	/**
	 * Checks if a session is active.
	 *
	 * @param   string   role name
	 * @param   array    collection of role names
	 * @return  boolean
	 */
	public function logged_in($site_id, $role)
	{
		$status = FALSE;

		// Get the user from the session
		$user = $this->session->get($this->config['session_key']);

		if (is_object($user) AND $user instanceof Account_User_Model AND $user->loaded)
		{
			# does the user belong to this site?
			if($user->fk_site == $site_id)
				return TRUE;
				
			return FALSE;
				
			// Everything is okay so far
			$status = TRUE;
			/*
			if ( ! empty($role))
			{

				// If role is an array
				if (is_array($role))
				{
					// Check each role
					foreach ($role as $role_iteration)
					{
						if ( ! is_object($role_iteration))
						{
							$role_iteration = ORM::factory('role', $role_iteration);
						}
						// If the user doesn't have the role
						if( ! $user->has($role_iteration))
						{
							// Set the status false and get outta here
							$status = FALSE;
							break;
						}
					}
				}
				else
				{
				// Else just check the one supplied roles
					if ( ! is_object($role))
					{
						// Load the role
						$role = ORM::factory('role', $role);
					}

					// Check that the user has the given role
					$status = $user->has($role);
				}
			}
			*/
		}

		return $status;
	}

	/**
	 * Logs a user in.
	 *
	 * @param   string   username
	 * @param   string   password
	 * @param   boolean  enable auto-login
	 * @return  boolean
	 */
	public function login($user, $fk_site, $password, $remember)
	{
		if ( ! is_object($user))
		{
			// Load the user
			$user = ORM::factory('account_user')
				->where('fk_site', $fk_site)
				->find($user);
		}
		// the passwords match, perform a login
		if ($user->password === $password)
		{
			if ($remember === TRUE)
			{
				// Create a new autologin token
				$token = ORM::factory('account_user_token');

				// Set token data
				$token->account_user_id = $user->id;
				$token->expires = time() + $this->config['lifetime'];
				$token->save();

				// Set the autologin cookie
				cookie::set('accountautologin', $token->token, $this->config['lifetime']);
			}

			// Finish the login
			$this->complete_login($user);

			return TRUE;
		}

		// Login failed
		return FALSE;
	}

	/**
	 * Forces a user to be logged in, without specifying a password.
	 * this has to be an object.
	 * @param   mixed    username
	 * @return  boolean
	 */
	public function force_login($user, $site_id)
	{
		if (!is_object($user))
		{
			echo('account:force_login = user is not an object');
			echo kohana::backtrace(debug_backtrace());
			die();
		}

		// Mark the session as forced, to prevent users from changing account information
		$_SESSION['auth_forced'] = TRUE;

		// Run the standard completion
		$this->complete_login($user);
	}

	/**
	 * Logs a user in, based on the accountautologin cookie.
	 *
	 * @return  boolean
	 */
	public function auto_login()
	{
		if ($token = cookie::get('accountautologin'))
		{
			// Load the token and user
			$token = ORM::factory('account_user_token', $token);

			if ($token->loaded AND $token->user->loaded)
			{
				if ($token->user_agent === sha1(Kohana::$user_agent))
				{
					// Save the token to create a new unique token
					$token->save();

					// Set the new token
					cookie::set('accountautologin', $token->token, $token->expires - time());

					// Complete the login with the found data
					$this->complete_login($token->user);

					// Automatic login was successful
					return TRUE;
				}

				// Token is invalid
				$token->delete();
			}
		}

		return FALSE;
	}

	/**
	 * Log a user out and remove any auto-login cookies.
	 *
	 * @param   boolean  completely destroy the session
	 * @return  boolean
	 */
	public function logout($destroy)
	{
		if (cookie::get('accountautologin'))
		{
			// Delete the autologin cookie to prevent re-login
			cookie::delete('accountautologin');
		}

		return parent::logout($destroy);
	}

	/**
	 * Get the stored password for a username.
	 *
	 * @param   mixed   username
	 * @return  string
	 */
	public function password($fk_site, $user)
	{
		if ( ! is_object($user))
		{
			// Load the user
			$user = ORM::factory('account_user')
				->where('fk_site', $fk_site)
				->find($user);
		}

		return $user->password;
	}

	/**
	 * Complete the login for a user by incrementing the logins and setting
	 * session data: user_id, username, roles
	 *
	 * @param   object   user model object
	 * @return  void
	 */
	protected function complete_login(Account_User_Model $user)
	{
		// Update the number of logins
		$user->logins += 1;

		// Set the last login date
		$user->last_login = time();

		// Save the user
		$user->save();

		return parent::complete_login($user);
	}

} // End Account_ORM_Driver