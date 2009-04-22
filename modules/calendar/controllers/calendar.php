<?php

class Calendar_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{
		tool_ui::validate_id($tool_id);
		
		$primary	= new View('calendar/index');	
		$calendar	= new Calendar;
	
		$month	= date('m');	
		$year	= date('Y');
		$date_array = array();
		
		# Create array for dates associated with this month
		$db = new Database;
		$dates = $db->query("SELECT * FROM calendar_items WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
			AND month = '$month'
			ORDER BY day
		");		

		# Count events
		foreach($dates as $date)
		{
			if(! empty($date_array[$date->day]) )
				(int) ++$date_array[$date->day];
			else
				(int) $date_array[$date->day] = 1;
		}
		
/*		
		# Display event count		
		function day_function($year, $month, $day, $date_data)
		{
			# blank cells before day 1 or after day 30/31
			if ($day == '')
				echo '&nbsp;'; # for IE table cells
				
			if( 'NULL' != $date_data )
			{
				$tense = 'events';
				if(1 == $date_data)
					$tense = 'event';
				
				echo $day;
				
				echo '<div class="event_count"><a href="/e/calendar/day/'."$month-$day-$year".'" rel="facebox">'. $date_data .' '. $tense.'</a></div>';
			}
			else
				echo $day;
		}
*/		

		$primary->calendar = $calendar->getPhpAjaxCalendar($month, $year, $date_array, 'day_function'); 

		# Javascript
		$edit_item_function ='';
		$edit_item_toolbar = '';
		if($this->client->logged_in())
		{
			$edit_item_toolbar = 'edit_calendar_items();';
			$edit_item_function ='
				function edit_calendar_items(){	
					$(".calendar_wrapper .calendar_item").each(function(){
						var id		= $(this).attr("rel");
						var edit	= "<a href=\"/e/edit_calendar/edit/" + id + "\" rel=\"facebox\">edit</a>";	
						var del		= "<a href=\"/e/edit_calendar/delete/" + id + "\" class=\"jade_delete_item\">delete</a>";
						var toolbar	= "<div id=\"blah\" class=\"jade_admin_item_edit\">" + edit + "-" + del + "</div>";				
						$(this).prepend(toolbar);	
					});
				};			
			';
		}
		
		
		$primary->global_readyJS($edit_item_function.'	
			$(".phpajaxcalendar_wrapper").click($.delegate({

				"a[rel*=ajax]" : function(e){			
					$("a[rel*=ajax]").removeClass("selected");
					$(e.target).addClass("selected");
					
					$("#loadImage").show();
					$("#calendar_event_details").load(e.target.href,{}, function(){
						$("#loadImage").hide();
						'.$edit_item_toolbar.'
					});
					return false;
				},
				
				"a.monthnav" : function(e){	
					$(".phpajaxcalendar_wrapper").load(e.target.href, {limit: 25}, function(){
					});
					return false;
				}		
			}));
		');
		
		return $primary;
	}

# Ajax query for month (last and next buttons)
	function month($month=NULL, $year=NULL)
	{
		tool_ui::validate_id($month);
		tool_ui::validate_id($year);
		
		$calendar = new Calendar;
		
		# Create array for dates associated with this month
		$db = new Database;
		$dates = $db->query("SELECT * FROM calendar_items 
			WHERE fk_site = '$this->site_id'
			AND year = '$year'
			AND month = '$month'
			ORDER BY day
		");		
		$date_array = array();

		# Count events
		foreach($dates as $date)
		{
			if(! empty($date_array[$date->day]) )
				(int) ++$date_array[$date->day];
			else
				(int) $date_array[$date->day] = 1;
		}
		
		
		echo $calendar->getPhpAjaxCalendar($month, $year, $date_array, 'day_function');
		
		die();
	}
	
	
# Ajax get a list of events for a certain date
	public function day($date=NULL)
	{
		# Format: month/day/year
		$pieces = explode('-', $date);
		
		tool_ui::validate_id($pieces['0']);
		tool_ui::validate_id($pieces['1']);
		tool_ui::validate_id($pieces['2']);
		
		$month	= $pieces['0'];
		$day	= $pieces['1'];
		$year	= $pieces['2'];
		$db = new Database;
		
		$events = $db->query("SELECT * FROM calendar_items 
			WHERE fk_site = '$this->site_id'
			AND year = '$year'  
			AND month = '$month'
			AND day = '$day'
		");
	
		if( $events->count() > 0 )
		{
			$primary = new View('calendar/day');
			$primary->events = $events;
			$primary->date = $date;
			$primary->render(TRUE);
		}
		else
		{
			echo 'no events on this day';
		}
		
		
		die();
		
	}

	
	public function event($id=NULL)
	{
		tool_ui::validate_id($id);
		$db = new Database;
		
		$event = $db->query("SELECT * FROM calendar_items WHERE id = '$id' AND fk_site = '$this->site_id' ")->current();
	

		if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest")
		{
			
			$primary = new View('calendar/event');
			$primary->event = $event;
			$primary->render(TRUE);
		}
		else
		{
			echo 'nice formatted page';
			die();
			
			$view = new View('shell');
			$primary = new View('calendar/event');
			$primary->event = $event;
			
			$view->render(TRUE);
		}
		
	
	}
	

}