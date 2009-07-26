<?php
class Faq_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{
		$faq = ORM::factory('faq')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $faq->loaded)
			return $this->public_template('this faq id not found.', 'faq', $tool_id, $parent->attributes);
	
		$primary = new View("public_faq/index");
		$primary->faq = $faq;	
		return $this->public_template($primary, 'faq', $tool_id);
	}
} # end