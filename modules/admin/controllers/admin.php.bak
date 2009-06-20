<?php
class Admin_Controller extends Template_Controller {

	/**
	 * The admin interface should only be available at plusjade.com
	 *
	 */
	
	function __construct()
	{
		parent::__construct();
		if(! $this->client->logged_in() )
			url::redirect();
	}
	
# dashboard
	function index()
	{
		$this->template->global_readyJS('$("#admin_generic_tab_nav").tabs({ fx: { opacity: "toggle",duration: "fast"} });');
		
		$primary = new View("admin/dashboard");	
		$this->template->title = 'admin dashboard';
		$this->template->primary = $primary; 
	}
		
# logout
	function logout()
	{
		Auth::instance()->logout();
		url::redirect('/get/auth');
	}
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */