<?php defined('SYSPATH') OR die('No direct access allowed.');

class Review_Model extends ORM {
	
	protected $has_many = array('review_items');
	
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
	

} // End