<?php defined('SYSPATH') OR die('No direct access allowed.');

class Showroom_Model extends ORM {
	
	protected $family = array('showroom_cat', 'showroom_cat_items');
	
	protected $has_many = array('showroom_cats');
	#protected $sorting = array('page_name' => 'asc');
	
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