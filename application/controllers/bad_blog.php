<?php
class Offline_Blog_Controller extends Template_Controller {

	/*
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}
  
	function _index()
	{
		$pieces = explode('/', $_SERVER['REQUEST_URI']);
		$action = ( empty($pieces['2']) ) ?  FALSE : $pieces['2']; 
		$params = ( empty($pieces['3']) ) ?  FALSE : $pieces['3']; 

		require_once SYSPATH . 'vendor/chyrp/includes/common.php';
		
		if('admin' == $action)
		{
			$admin	= AdminController::current();
			$route	= Route::current($admin);
			$params = strstr($params, '=');
			$params = trim($params, '=');

			if ( empty($params) )
				echo $route->init();
			else
				echo $route->init($params);
		
			die();
		}
		else
		{
			if ('entry' == $action) $action = 'view';
			
			$main = MainController::current();
			$route = Route::current($main);

			if ( empty($action) )
				$this->template->primary = $route->init();
			else
				$this->template->primary = $route->init($action);
	
		}
		#ECHO '<pre>';print_r($_SESSION);echo '</pre>';
		$trigger->call("end", $route);
		
		# needed to hide 404 not found on controller name
		Event::clear('system.404');
		
		# this hook needed to enable auto rendering of controllers
		Event::run('system.post_controller');		
		
	}
}
/* end*/