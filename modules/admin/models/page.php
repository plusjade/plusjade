<?php defined('SYSPATH') OR die('No direct access allowed.');

class Page_Model extends ORM {
	
	#protected $has_many = array('pages_tools');
	protected $sorting = array('page_name' => 'asc');
	
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