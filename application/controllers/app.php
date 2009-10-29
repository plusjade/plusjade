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
	
	code Lookup:
	http://maps.google.com/maps/geo?q=1600+Amphitheatre+Parkway,+Mountain+View,+CA&output=json&oe=utf8&sensor=false&key=ABQIAAAATl3ouz6mDWUryXLiBg_56hTaRle_T4ihdSUpL4p8Tw_T5cVK1RQVO3SKhybAmz1Hb7JecRXQiqnSUA
	*/	
	public function map()
	{
		switch($this->site_name)
		{
			case 'larasgift':
				$address = '2259 Honolulu Ave, Glendale, CA 91020';
				$coordinates = '34.2053, -118.2269';
				break;

			case 'jade':
				$address = 'Alhambra, CA 91803';
				$coordinates = '34.0952, -118.1270';
				break;				

			case 'demo':
				$address = 'Alhambra, CA 91803';
				$coordinates = '34.0952, -118.1270';
				break;
				
			case 'pasadenafurniture':
				$address = '365 S. Rosemead Blvd.Pasadena, CA 91107';
				$coordinates = '34.1402, -118.0736';
				break;				
		}
		
		$width = (isset($_GET['w']) AND is_numeric($_GET['w'])) ? $_GET['w'] : 440;
		$height = (isset($_GET['h']) AND is_numeric($_GET['h'])) ? $_GET['h'] : 300;
		
		die(View::factory('app/map', array('address'=> $address, 'coordinates'=> $coordinates, 'width' => $width,'height' => $height)));
	}

} # End app Controller