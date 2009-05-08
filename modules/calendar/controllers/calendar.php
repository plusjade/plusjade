<?php

class Calendar_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{
		valid::id_key($tool_id);		
		$primary	= new View('public_calendar/index');	
		$action		= uri::easy_segment('2');
		$year		= uri::easy_segment('3');
		$month		= uri::easy_segment('4');
		$day		= uri::easy_segment('5');

		switch($action)
		{
			case 'month':				
				$primary->calendar = $this->month($year, $month);
				break;
				
			case 'day':
				$primary->calendar = $this->month($year, $month);
				$primary->events = $this->day($year, $month, $day);
				break;
				
			default:
				$year	= date('Y');
				$month	= date('m');	
				$primary->calendar = $this->month($year, $month);
				break;
		}
		
		# Javascript
		if($this->client->logged_in())
			$primary->global_readyJS('
				$("#click_hook").click(function(){
					$().add_toolkit_items("calendar");
				});			
			');
		$primary->readyJS('calendar','index');		
		
		return $primary;
	}

	# Ajax query for month (last and next buttons)
	function month($year=NULL, $month=NULL)
	{
		valid::year($year);
		valid::month($month);
		$calendar	= new Calendar;
		$db			= new Database;
		$date_array = array();
		
		# Create array for dates associated with this month
		$dates = $db->query("SELECT * FROM calendar_items 
			WHERE fk_site = '$this->site_id'
			AND year = '$year'
			AND month = '$month'
			ORDER BY day
		");		

		# Count events
		foreach($dates as $date)
		{
			if(! empty($date_array[$date->day]) )
				(int) ++$date_array[$date->day];
			else
				(int) $date_array[$date->day] = '1';
		}
		return $calendar->getPhpAjaxCalendar($month, $year, $date_array, 'day_function');
	}
	
	
# Ajax get a list of events for a certain date
	public function day($year=NULL, $month=NULL, $day=NULL)
	{
		valid::year($year);
		valid::month($month);
		valid::day($day);
		$db = new Database;
		
		$events = $db->query("SELECT * FROM calendar_items 
			WHERE fk_site = '$this->site_id'
			AND year = '$year'  
			AND month = '$month'
			AND day = '$day'
		");
	
		$primary = new View('public_calendar/day');
		$primary->events = $events;
		$primary->date = "$year $month $day";
		return $primary;
	}

	
	public function event($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;
		
		$event = $db->query("SELECT * FROM calendar_items 
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
}