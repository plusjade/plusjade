<?php

class Navigation_Controller extends Edit_Module_Controller {
/*
 * Edit a navigation menu
 *
 */
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * Manage Function display a sortable list of tool resources (items)
 */
	function manage($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		
		$calendar = new Calendar;
		
		$month = date('m');
	
		$year = date('Y');


		# Create array for dates associated with this month
		$db = new Database;
		$dates = $db->query("SELECT * FROM calendar_items WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
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

				
		# SIMPLE		
		function day_function($year, $month, $day, $date_data)
		{
			# blank cells before day 1 or after day 30/31
			if ($day == '')
				echo '&nbsp;'; # for IE table cells
				
			if( 'NULL' != $date_data )
			{
				echo '<a href="/e/edit_calendar/day/'."$month-$day-$year".'" rel="admin_ajax" class="day_link_simple">'. $day .'</a>';
			}
			else
				echo '<div class="day_simple">'.$day.'</div>';
		}
				
		$javascript .= tool_ui::js_delete_init('calendar');
				
		$this->template->rootJS($javascript);
		
		
		$calendar = $calendar->getPhpAjaxCalendar($month, $year, $date_array, 'day_function');
		$calendar .= '<div id="admin_calendar_event_details">hello</div>'; 
		
		$this->template->primary = $calendar;
		$this->template->render(TRUE);
		die();

		


	}

/*
 * Add Event(s)
 */ 
	public function add($tool_id=NULL)
	{		
		tool_ui::validate_id($tool_id);
		
		if($_POST)
		{

			$db = new Database;
			$dates = explode('/', $_POST['date']);
						
			$data = array(			
				'parent_id'	=> $tool_id,
				'fk_site'	=> $this->site_id,
				'year'		=> $dates['2'],
				'month'		=> $dates['0'],
				'day'		=> $dates['1'],
				'title'		=> $_POST['title'],
				'desc'		=> $_POST['desc'],				
			);	

			# Upload image if sent
			if(!empty($_FILES['image']['name']))
				if (! $data['image'] = $this->_upload_image($_FILES) )
					echo 'Image must be jpg, gif, or png.';
				
				
			$db->insert('calendar_items', $data);
			
			echo 'Event added'; #status message
		}
		else
		{
			#Javascript
			$this->template->rootJS = '	
					$("#datepicker").datepicker({
						altField: "#date_field",
						changeMonth: true
						//changeYear: true
					});
			';
			
			$this->_show_add_single('calendar', $tool_id);
		}
		die();		
	}
	
/*
 * Edit single Item
 */
	public function edit($id=NULL)
	{
		tool_ui::validate_id($id);
		
		$db = new Database;
			
		# Edit item
		if(! empty($_POST['title']) )
		{
			$data = array(
				'title'	=> $_POST['title'],
				'desc'	=> $_POST['desc'],		
			);		
			$db->update('calendar_items', $data, "id = '$id' AND fk_site = '$this->site_id'");
			
			#echo '<script>$.jGrowl("Event updated")</script>'; #status message		
			echo 'Event Saved<br>Updating...';
		
		}
		else
		{		
			$parent = $db->query("SELECT * FROM calendar_items WHERE id = '$id' AND fk_site = '$this->site_id' ")->current();			
			$primary = new View("calendar/edit/single_item");
			
			$primary->item = $parent;
			$this->template->primary = $primary;
			$this->template->render(true);
		}
		
		die();		
	}


	public function delete($id=NULL)
	{
		tool_ui::validate_id($id);				
		echo 'hello!';die();
		# db delete
		$this->_delete_single_common('calendar', $id);
		die();
	}


}

/* -- end of application/controllers/showroom.php -- */