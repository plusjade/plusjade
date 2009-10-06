<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 
 * provides standalone functions that can be embedded or otherwise
 * need their own rendering?
 */
 
class App_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

		
/*
	a view to interact with the google map api
	docs: http://code.google.com/apis/maps/documentation/introduction.html#The_Hello_World_of_Google_Maps
	get lat/long from a human readable address::
	http://code.google.com/apis/maps/documentation/geocoding/index.html
	http://maps.google.com/maps/geo?q=1600+Amphitheatre+Parkway,+Mountain+View,+CA&output=json&oe=utf8&sensor=true_or_false&key=your_api_key
	*/	
	public function map()
	{
		die(View::factory('app/map'));
	}

} # End app Controller