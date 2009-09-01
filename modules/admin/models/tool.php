<?php defined('SYSPATH') OR die('No direct access allowed.');

class Tool_Model extends ORM {


	protected $has_and_belongs_to_many = array('pages');
	protected $has_one = array('system_tool');
	protected $load_with = array('system_tool');
	# protected $has_many = array('system_tool_types');
	# protected $sorting = array('page_name' => 'asc');
	
	/**
	 * Overload saving to set the created time and to create a new token
	 * when the object is saved.
	 */
	public function save()
	{
		if ($this->loaded === FALSE)
		{

		}
		return parent::save();
	}
	
	/**
	 * Allows a model to be loaded by username or email address.
	 */
	public function unique_key($id)
	{
		if( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
		{
			return 'name';
		}

		return parent::unique_key($id);
	}
	
	
} // End