
How to add Javascript files and javascript inline functions
within the jquery document ready function

Public Tool Objects (public tool controllers):
	1. Add Javascript file to root page.
		<?php $view->add_root_js_files();?>
		
	2. Add inline Javascript to root ready function.
		<?php $view->global_readyJS();?>

Admin Edit_Tool Controllers:

	Assumptions: All Admin panels are loaded via AJAX into the facebox.


	1. Add Javascript file to root page.
		TODO: Make this possible.
		Currently have to add into public tool controller.
		
	2. Add inline Javascript to root ready function.
		Disabled. No sense in adding to the root because
		all editing panels are loaded in ajax.
		
	3. Add inline Javascript to ajax page.
		needs to add to the "ajax" parent view
		<?php $this->template->rootJS();?>
	
		
	
		
		
		
		
		
<?

/*
 * Add inline Javascript within the jquery document ready function
 * Adds to the initial root page
 * Takes only strings
 */
	$view->global_readyJS();

/*
 * Add request for javascript file to be loaded in the root document
 * Send both public and admin requests here to fiter duplicates
 * Avoids duplicates and collisions
 * 
 */
	$view->add_root_js_files();
	
/*
 * 
 * Appends any inline Javascript to the $rootJS variable
 * $rootJS is loaded within <script> tags in the ajax View
 * Ajax view is used for all ajax calls to facebox (editing)
 * TODO: change the name because it is NOT loaded in the ROOT
 */	
	$view->rootJS();
