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
		$primary->readyJS('calendar','index');		
		$primary->tool_id = $tool_id;
		return $primary;
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
		
		#quick hack, optimize later...
		if('get' == $calendar_page_name)
		{
			# get tools_list id of the tool from db ...
			$tool = 7;
			$page = $db->query("
				SELECT pages.page_name
				FROM pages_tools
				JOIN pages ON pages_tools.page_id = pages.id
				WHERE pages_tools.fk_site = '$this->site_id'
				AND pages_tools.tool = '$tool'
				AND pages_tools.tool_id = '$tool_id'
			")->current();			
			
			$calendar_page_name = $page->page_name;
		}
		
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
		$action	= @$url_array['2'];
		$year	= @$url_array['3'];
		$month	= @$url_array['4'];
		$day	= @$url_array['5'];

		if('month' == $action)
		{
			die( $this->month($tool_id, $year, $month) );
		}
		elseif('day' == $action)
		{
			die( $this->day($tool_id, $year, $month, $day) );
		}
	}
	
	
}