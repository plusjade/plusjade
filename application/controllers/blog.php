<?php
class Blog_Controller extends Template_Controller {

	/*

	 *
	 */
	function __construct()
	{
		parent::__construct();
	}
  
	function _index()
	{
		require_once SYSPATH . 'vendor/chyrp/includes/common.php';

		# Prepare the controller.
		$main = MainController::current();

		# Parse the route.
		$route = Route::current($main);
		
		#$this->template->linkCSS('screen.css','http://localhost.chyrp/themes/base/stylesheets/');
		
		
		
		#action=view&url=sunny-day
		
		$this->template->primary = $main->view();
		
		
		#$this->template->primary = $main->index();
		
		# needed to hide 404 not found on controller name
		Event::clear('system.404');
		
		# this hook needed to enable auto rendering of controllers
		Event::run('system.post_controller');		
		
	}
}
/* end*/