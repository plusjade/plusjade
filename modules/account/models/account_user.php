<?php defined('SYSPATH') OR die('No direct access allowed.');

class Account_User_Model extends ORM {

	// Relationships
	protected $has_many = array('account_user_tokens');
	protected $has_and_belongs_to_many = array('account_user_roles', 'forum_comment_votes');

	// Columns to ignore
	protected $ignored_columns = array('password_confirm');

	public function __set($key, $value)
	{
		if ($key === 'password')
		{
			// Use Account to hash the password
			# todo fix this.
			$value = Account::instance()->hash_password($value);
		}

		parent::__set($key, $value);
	}

	/**
	 * Validates and optionally saves a new user record from an array.
	 *
	 * @param  array    values to check
	 * @param  boolean  save the record when validation succeeds
	 * @return boolean
	 */
	public function validate(Validation $array, $save = FALSE)
	{
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('email', 'required', 'length[4,127]', 'valid::email', array($this, 'username_exists'))
			->add_rules('username', 'required', 'length[4,32]', 'chars[a-zA-Z0-9_.]', array($this, 'username_exists'))
			->add_rules('password', 'required', 'length[5,42]')
			->add_rules('password_confirm', 'matches[password]');

		return parent::validate($array, $save);
	}

	/**
	 * Validates login information from an array, and optionally redirects
	 * after a successful login.
	 *
	 * @param  array    values to check
	 * @param  string   URI or URL to redirect to
	 * @return boolean
	 */
	public function login(array & $array, $redirect = FALSE)
	{
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('username', 'required', 'length[4,127]')
			->add_rules('password', 'required', 'length[5,42]');

		// Login starts out invalid
		$status = FALSE;

		if ($array->validate())
		{
			// Attempt to load the user
			$this->find($array['username']);

			if ($this->loaded AND Account::instance()->login($this, $array['password']))
			{
				if (is_string($redirect))
				{
					// Redirect after a successful login
					url::redirect($redirect);
				}

				// Login is successful
				$status = TRUE;
			}
			else
			{
				$array->add_error('username', 'invalid');
			}
		}

		return $status;
	}

	
	/**
	 * Overload saving to set the created time and to create a new token
	 * when the object is saved.
	 */
	public function save()
	{
		if ($this->loaded === FALSE)
		{
			# when the user is first created, assign a token for him.
			# we may not even need this for account_users though!!
			$this->token = $this->create_token();
		}
		return parent::save();
	}
	
	
	/**
	 * Validates an array for a matching password and password_confirm field.
	 *
	 * @param  array    values to check
	 * @param  string   save the user if
	 * @return boolean
	 */
	public function change_password(array & $array, $save = FALSE)
	{
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('password', 'required', 'length[5,127]')
			->add_rules('password_confirm', 'matches[password]');
		
		if ($status = $array->validate())
		{
			// Change the password
			$this->password = $array['password'];

			if ($save !== FALSE AND $status = $this->save())
			{
				if (is_string($save))
				{
					// Redirect to the success page
					url::redirect($save);
				}
			}
		}

		return $status;
	}

	/**
	 * Finds a new unique token, using a loop to make sure that the token does
	 * not already exist in the database. This could potentially become an
	 * infinite loop, but the chances of that happening are very unlikely.
	 *
	 * @return  string
	 */
	protected function create_token()
	{
		while (TRUE)
		{
			// Create a random token
			$token = text::random('alnum', 32);

			// Make sure the token does not already exist
			if ($this->db->select('id')->where('token', $token)->get($this->table_name)->count() === 0)
			{
				// A unique token has been found
				return $token;
			}
		}
	}
	
	
	/**
	 * Tests if a username exists in the database. This can be used as a
	 * Valdidation rule.
	 *
	 * @param   mixed    id to check
	 * @return  boolean
	 */
	public function username_exists($id)
	{
		return (bool) $this->db
			->where($this->unique_key($id), $id)
			->count_records($this->table_name);
	}

	/**
	 * Allows a model to be loaded by username or email address.
	 */
	public function unique_key($id)
	{
		if ( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
		{
			return valid::email($id) ? 'email' : 'username';
		}

		return parent::unique_key($id);
	}

} // End Account User Model