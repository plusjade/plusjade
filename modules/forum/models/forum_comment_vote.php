<?php defined('SYSPATH') OR die('No direct access allowed.');

class Forum_Comment_Vote_Model extends ORM {

	// Relationships
	protected $has_and_belongs_to_many = array('account_users');
	protected $primary_key = 'account_user_id';

	#protected $belongs_to = array('forum_cat_post');
	#protected $has_one = array('account_user');
	//protected $has_many = array('forum_cat_post_comment');
	

	public function __set($key, $value)
	{
		if ($key === 'password')
		{
		
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
	 * Overload saving to set the created time and to create a new token
	 * when the object is saved.
	 */
	public function save()
	{
		if ($this->loaded === FALSE)
		{
			#TODO: increase the post reply count by one.
		}
		return parent::save();
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