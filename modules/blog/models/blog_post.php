<?php defined('SYSPATH') OR die('No direct access allowed.');

class Blog_Post_Model extends ORM {
	
	protected $belongs_to = array('blog');
	
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