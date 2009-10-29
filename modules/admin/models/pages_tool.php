<?php defined('SYSPATH') OR die('No direct access allowed.');

class Pages_Tool_Model extends ORM {
	
	#protected $has_many = array('tools' => 'pages_tools');
	#protected $has_and_belongs_to_many = array('tools');
	#protected $has_many = array('pages_tools');
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