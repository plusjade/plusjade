<?php defined('SYSPATH') OR die('No direct access allowed.');

class Showroom_Cat_Model extends ORM {
	
	#protected $has_many = array('pages_tools');
	protected $sorting = array('lft' => 'asc');
	
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
 * Allows a model to be loaded by id or url
 */
	public function unique_key($id)
	{
		if ( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
		{
			return 'url';
		}

		return parent::unique_key($id);
	}
	
	
	
} // End