<?php defined('SYSPATH') OR die('No direct access allowed.');

class Calendar_Controller extends Public_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

	
	public function _index($calendar)
	{
		# $date format = year-month-day
		list($page_name, $action, $date) = Uri::url_array();
		$page_name	= $this->get_page_name($page_name, 'calendar', $calendar->id);
		$action		= (empty($action)) ? 'nonsense' : $action;
		if('tool' == $action)
			$date = null;
		
		$primary = new View('public_calendar/small/index');

		switch($action)
		{			
			case 'day':
				$primary->events = $this->day($calendar->id, $date);
				break;
		}
		$primary->calendar = $this->month($page_name, $calendar->id, $date);
		
		# get the custom javascript;
		$primary->global_readyJS(self::javascripts());
		
		return $this->wrap_tool($primary, 'calendar', $calendar);
	}
	

/*
 * get a specific month
 */
	private function month($page_name, $tool_id, $date)
	{
		valid::id_key($tool_id);
		if(empty($date))
		{
			$year	= date('Y');
			$month	= date('m');		
		}
		else
		{	
			$date = explode('-' , $date);
			# strictly need the formated date, else throw page not found.
			if(1 > count($date) OR count($date) > 3)
				Event::run('system.404');
				
			list($year, $month) = $date; 
			valid::year($year);
			valid::month($month);
		}
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
 * get a list of events for a certain date
 */
	public function day($tool_id, $date)
	{
		if(empty($date))
			Event::run('system.404');	
		$date = explode('-' , $date);
		# strictly need the formated date, else throw page not found.
		if(3 != count($date))
			Event::run('system.404');

		list($year, $month, $day) = $date; 		
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

		$primary = new View('public_calendar/small/day');
		$primary->events = $events;
		$primary->date = "$year $month $day";
		$primary->logged_in = ($this->client->can_edit($this->site_id)) ? TRUE : FALSE;
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
			$primary = new View('public_calendar/small/event');
			$primary->event = $event;
			echo $primary;
		}
		else
		{
			echo 'nice formatted page';
			die();
			
			$view = new View('shell');
			$primary = new View('public_calendar/small/event');
			$primary->event = $event;
			
			echo $view;
		}
	}

/*
 * output the appropriate javascript based on the calendar view.
 * currently we just have one though
 */	
	private function javascripts()
	{
		$js = '
		
			$("body").click($.delegate({
				".phpajaxcalendar_wrapper a[rel=ajax]": function(e){
					$("a[rel*=ajax]").removeClass("selected");
					$(this).addClass("selected");
					
					$("#calendar_event_details").html("<div class=\"ajax_loading\">Loading...</div>");
					$("#calendar_event_details").load(e.target.href,{}, function(){
						$("#click_hook").click();
					});
					return false;	
				},
				
				".phpajaxcalendar_wrapper a.monthnav": function(e){
					$(".phpajaxcalendar_wrapper").html("<div class=\"ajax_loading\">Loading...</div>");
					$(".phpajaxcalendar_wrapper").load(e.target.href);
					return false;	
				}
			}));	
		';
		# place the javascript.
		return $this->place_javascript($js, FALSE);
	}
	
/*
 * ajax handler.
 */ 	
	public function _ajax($url_array, $tool_id)
	{
		list($page_name, $action, $date) = $url_array;
		
		if('month' == $action)
			die(self::month($page_name, $tool_id, $date));
		elseif('day' == $action)
			die(self::day($tool_id, $date));
	
		die('something is wrong with the url');
	}

/*
 * add the calendar and some sample content
 */
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
	}
	
	
	
} # end 


