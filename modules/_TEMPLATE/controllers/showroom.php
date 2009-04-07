<?php

class Showroom_Controller extends Template_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index()
	{	
		$this->template->primary = new View('showroom/primary_showroom'); 
	}
  
}

/* -- end of application/controllers/showroom.php -- */