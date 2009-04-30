<?php
class Blog_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{
		require_once SYSPATH . 'vendor/chyrp/includes/common.php';

		# Prepare the controller.
		#$main = MainController::current();

		# Parse the route.
		#$route = Route::current($main);
		
		#$contents = $main->index();
		
		return 'asdf';
		
		
		ob_end_flush();
		
		/*
		# Execute the appropriate Controller responder.
		$route->init();

		# If the route failed or nothing was displayed, check for:
		#     1. Module-provided pages.
		#     2. Feather-provided pages.
		#     3. Theme-provided pages.
		if (!$route->success and !$main->displayed) {
			$displayed = false;

			foreach ($config->enabled_modules as $module)
				if (file_exists(MODULES_DIR."/".$module."/pages/".$route->action.".php"))
					$displayed = require MODULES_DIR."/".$module."/pages/".$route->action.".php";

			if (!$displayed)
				foreach ($config->enabled_feathers as $feather)
					if (file_exists(FEATHERS_DIR."/".$feather."/pages/".$route->action.".php"))
						$displayed = require FEATHERS_DIR."/".$feather."/pages/".$route->action.".php";

			if (!$displayed and $theme->file_exists("pages/".$route->action))
				$main->display("pages/".$route->action);
			elseif (!$displayed)
				show_404();
		}

		$trigger->call("end", $route);

		#echo '<pre>'; print_r($route);echo '</pre>';
		
		ob_end_flush();

		*/
		
		/*
		ob_start();
		$path = "http://localhost.com/blog/";
		
		#include($path);
		
		$handle = fopen("http://localhost.com/blog/", "rt");
		$source_code = fread($handle,9000); 

		return $source_code;
		return ob_get_contents();
		*/		
	}
}

/* -- end of application/controllers/faq.php -- */