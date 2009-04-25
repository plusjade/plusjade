<?php

class Edit_Calendar_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for Showroom module.
 *	Extends the module template to build page quickly for ajax rendering.
 *	Only Logged in users should have access
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
		
/*
		foreach($dates as $date)
		{
			if(! empty($date_array[$date->day]) )
				$date_array[$date->day] .= '
					<div id="calendar_'.$date->id.'">
					<a href="/e/edit_calendar/edit/'. $date->id .'" rel="facebox" id="'.$date->id.'">'.$date->title.'</a>
					<br><a href="/e/edit_calendar/delete/'.$date->id.'" class="delete_calendar" id="'.$date->id.'">Delete!</a>
					</div>
				';
			else
				$date_array[$date->day] = '
				<div id="calendar_'.$date->id.'">
				<a href="/e/edit_calendar/edit/'. $date->id .'" rel="facebox" id="'.$date->id.'">'.$date->title.'</a>
				<br><a href="/e/edit_calendar/delete/'.$date->id.'" class="delete_calendar" id="'.$date->id.'">Delete!</a>
				</div>
				';
		}
*/		
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
			
			echo $this->_show_add_single('calendar', $tool_id);
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

/*
 * DELETE showroom (item) single
 * Success Response via inline JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of showroom item row 
 */
	public function delete($id=NULL)
	{
		tool_ui::validate_id($id);				
		echo 'hello!';die();
		# db delete
		$this->_delete_single_common('calendar', $id);
		die();
	}

/*
 * SAVE items sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		$this->_save_sort_common($_GET['showroom'], 'showroom_items');
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
			$primary = new View('calendar/edit/day');
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
	
/*
 * Upload an image to showroom
 * @Param array $file = $_FILES array
 */ 	
	private function _upload_image($_FILES)
	{		
		$files = new Validation($_FILES);
		$files->add_rules('image', 'upload::valid','upload::type[gif,jpg,png]', 'upload::size[1M]');
		
		if ($files->validate())
		{
			# Temp file name
			$filename	= upload::save('image');
			$image		= new Image($filename);			
			$ext		= $image->__get('ext');
			$file_name	= basename($filename).'.'.$ext;
			$directory	= DOCROOT."data/{$this->site_name}/assets/images/showroom";			
			
			if(! is_dir($directory) )
				mkdir($directory);	
			
			if( $image->__get('width') > 350 )
				$image->resize(350, 650);
			
			$image->save("$directory/$file_name");
		 
			# Remove temp file
			unlink($filename);
			
			return $file_name;
			#status message
			#return '<script> $.jGrowl("Image uploaded!")</script>';
		}
		else
			return FALSE;

		
	}
	
}

/* -- end of application/controllers/showroom.php -- */