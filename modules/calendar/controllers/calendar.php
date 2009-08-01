<?php

class Calendar_Controller extends Public_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{
		$url_array	= Uri::url_array();
		$page_name	= $this->get_page_name($url_array['0'], 'calendar', $tool_id);
		$action		= (empty($action)) ? 'nonsense' : $url_array['1'];
		$year		= $url_array['2'];
		$month		= $url_array['3'];
		$day		= $url_array['4'];
		$primary	= new View('public_calendar/index');
		
		switch($action)
		{
			case 'month':
				break;				
			case 'day':
				$primary->events = $this->day($tool_id, $year, $month, $day);
				break;			
			default:
				$year	= date('Y');
				$month	= date('m');
				break;
		}
		
		# Javascript
		if($this->client->logged_in())
			$primary->global_readyJS('
				$("#click_hook").click(function(){
					$().add_toolkit_items("calendar");
				});			
			');

		$primary->calendar = $this->month($page_name, $tool_id, $year, $month);	
		return $this->public_template($primary, 'calendar', $tool_id);
	}

/*
 * Ajax query for month (last and next buttons)
 */
	function month($page_name, $tool_id, $year=NULL, $month=NULL)
	{
		valid::id_key($tool_id);
		valid::year($year);
		valid::month($month);
		$date_array = array();

		$dates = ORM::factory('calendar_item')
			->where(array(
				'fk_site'		=> $this->site_id,
				'calendar_id'	=> $tool_id,
				'year'			=> $year,
				'month'			=> $month,
			))
			->find_all();		
		/*
		 * Create an array with key/value pairs = day/number of events on day
		 * This lets the calendar know which dates to show links for.
		 */
		for($x=0; $x<=31; ++$x)
			$date_array[$x] = 0;

		foreach($dates as $date)
			(int) ++$date_array[$date->day];
	
		$calendar = new Calendar;
		return $calendar->getPhpAjaxCalendar($page_name, $month, $year, $date_array, 'day_function');
	}
	
	
/* 
 * Ajax get a list of events for a certain date
 */
	public function day($tool_id, $year=NULL, $month=NULL, $day=NULL)
	{
		valid::year($year);
		valid::month($month);
		valid::day($day);

		$events = ORM::factory('calendar_item')
			->where(array(
				'fk_site'		=> $this->site_id,
				'calendar_id'	=> $tool_id,
				'year'			=> $year,
				'month'			=> $month,
				'day'			=> $day,
			))
			->find_all();	
	
		$primary = new View('public_calendar/day');
		$primary->events = $events;
		$primary->date = "$year $month $day";
		return $primary;
	}

/* 
 * OFFLINE
 */
	public function event($id=NULL)
	{
		die('offline');
		valid::id_key($id);
		$db = new Database;
		
		$event = $db->query("
			SELECT * FROM calendar_items 
			WHERE id = '$id' 
			AND fk_site = '$this->site_id'
		")->current();
		
		if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest")
		{
			$primary = new View('public_calendar/event');
			$primary->event = $event;
			echo $primary;
		}
		else
		{
			echo 'nice formatted page';
			die();
			
			$view = new View('shell');
			$primary = new View('public_calendar/event');
			$primary->event = $event;
			
			echo $view;
		}
	}
	
/*
 * ajax handler.
 */ 	
	function _ajax($url_array, $tool_id)
	{		
		/*
		$page_name = @$url_array['0'];
		$action	= @$url_array['1'];
		$year	= @$url_array['2'];
		$month	= @$url_array['3'];
		$day	= @$url_array['4'];
		*/
		if('month' == $url_array['1'])
			die( self::month($url_array['0'], $tool_id, $url_array['2'], $url_array['3']) );
		elseif('day' == $url_array['1'])
			die( self::day($tool_id, $url_array['2'], $url_array['3'], $url_array['4']) );
	
		die('something is wrong with the url');
	}
	
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{			
			$new_item = ORM::factory('calendar_item');
			$new_item->fk_site		= $site_id;
			$new_item->calendar_id	= $tool_id;
			$new_item->year			= date("Y");
			$new_item->month		= date("m");
			$new_item->day			= date("d");
			$new_item->title		= 'New Website Launch!';
			$new_item->desc			= "Pizza party at my house to celebrate my new website launch. Starts at 3pm, bring your buddies!";
			$new_item->save();		
		}
		return 'add';
	}
	
	
	
} # end 


