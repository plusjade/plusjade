<?php
class Faq_Controller extends Template_Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($page_id)
	{
		$db = new Database;
		$primary = new View("faq/primary_faq");
		
		# Javascript
		$this->template->linkJS('accordion/jquery.accordion.js');
		$embed_js = "jQuery().ready(function(){ jQuery('#faq_container').accordion({ header: 'dt', active: false, alwaysOpen: false, autoheight: false }); }); ";
		$this->template->rootJS($embed_js);

		# get faqs for current page
		$result = $db->query("SELECT * FROM faqs WHERE fk_site = '{$this->site_id}' AND page_id = '$page_id' ORDER BY position");
		$primary->faqs = $result;	
		$this->template->primary = $primary;		
	}
}

/* -- end of application/controllers/faq.php -- */