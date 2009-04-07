<?php
class Admin_Controller extends Admin_View_Controller {

	/**
	 *	SCOPE: Sitewide assets.
	 *	
	 *
	 */
	
	function __construct()
	{
		parent::__construct();
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
		url::redirect('auth');
	}
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */