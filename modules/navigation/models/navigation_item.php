<?php defined('SYSPATH') OR die('No direct access allowed.');

class Navigation_Item_Model extends ORM {
	
	#protected $belongs_to = array('navigation');
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
	

} // End