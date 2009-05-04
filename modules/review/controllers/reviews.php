<?php

class Reviews_Controller extends Template_Controller {

	function __construct()
	{
		parent::__construct();
		$this->template->title = "Reviews!!";	
		$this->template->set_global('selected', 'reviews');
	}

	function _index()
	{	
		$this->template->primary = new View('reviews/primary_reviews'); 
	}
  
}

/* -- end of application/controllers/reviews.php -- */