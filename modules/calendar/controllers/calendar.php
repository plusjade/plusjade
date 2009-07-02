<?php

class Calendar_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{
		$primary	= new View('public_calendar/index');
		$action		= uri::easy_segment('2');
		$year		= uri::easy_segment('3');
		$month		= uri::easy_segment('4');
		$day		= uri::easy_segment('5');

		switch($action)
		{
			case 'month':				
				$primary->calendar = $this->month($tool_id, $year, $month);
				break;
				
			case 'day':
				$primary->calendar = $this->month($tool_id, $year, $month);
				$primary->events = $this->day($tool_id, $year, $month, $day);
				break;
				
			default:
				$year	= date('Y');
				$month	= date('m');	
				$primary->calendar = $this->month($tool_id, $year, $month);
				break;
		}
		
		# Javascript
		if($this->client->logged_in())
			$primary->global_readyJS('
				$("#click_hook").click(function(){
					$().add_toolkit_items("calendar");
				});			
			');
			
		return $this->public_template($primary, 'calendar', $tool_id);
	}

	# Ajax query for month (last and next buttons)
	function month($tool_id, $year=NULL, $month=NULL)
	{
		valid::id_key($tool_id);
		valid::year($year);
		valid::month($month);
		$calendar	= new Calendar;
		$db			= new Database;
		$date_array = array();
		
		$calendar_page_name	= uri::easy_segment('1');
		$calendar_page_name = $this->get_page_name($calendar_page_name, 'calendar', $tool_id);
		
		# Create array for dates associated with this month
		$dates = $db->query("
			SELECT day FROM calendar_items 
			WHERE fk_site = '$this->site_id'
			AND parent_id = '$tool_id'
			AND year = '$year'
			AND month = '$month'
			ORDER BY day
		");		
		
		/*
		 * Create an array with key/value pairs = day/number of events on day
		 * This lets the calendar know which dates to show links for.
		 */

		for($x=0; $x<=31; ++$x)
			$date_array[$x] = 0;

		foreach($dates as $date)
			(int) ++$date_array[$date->day];
	
		return $calendar->getPhpAjaxCalendar($calendar_page_name, $month, $year, $date_array, 'day_function');
	}
	
	
# Ajax get a list of events for a certain date
	public function day($tool_id, $year=NULL, $month=NULL, $day=NULL)
	{
		valid::year($year);
		valid::month($month);
		valid::day($day);
		$db = new Database;
		
		$events = $db->query("
			SELECT * FROM calendar_items 
			WHERE fk_site = '$this->site_id'
			AND parent_id = '$tool_id'
			AND year = '$year'  
			AND month = '$month'
			AND day = '$day'
		");
	
		$primary = new View('public_calendar/day');
		$primary->events = $events;
		$primary->date = "$year $month $day";
		return $primary;
	}

	# not in use
	public function event($id=NULL)
	{
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
 * page builders frequently use ajax to update their content
 * common method for handling ajax requests.
 * param $url_array = (array) an array of url signifiers
 * param $tool_id 	= (int) the tool id of the tool.
 */ 	
	function _ajax($url_array, $tool_id)
	{		
		/*
		$action	= @$url_array['2'];
		$year	= @$url_array['3'];
		$month	= @$url_array['4'];
		$day	= @$url_array['5'];
		*/
		if('month' == $url_array['2'])
			die( $this->month($tool_id, $url_array['3'], $url_array['4']) );
		elseif('day' == $url_array['2'])
			die( $this->day($tool_id, $url_array['3'], $url_array['4'], $url_array['5']) );
	}
	
	
}