<?php

class Edit_Calendar_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for calendar module.
 *	Extends the module template to build page quickly for ajax rendering.
 *	Only Logged in users should have access
 *
 */
 
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * THIS DOES NOT WORK, SHOULD NOT WORK
 */
	function manage($tool_id=NULL)
	{
		die('blah');
		valid::id_key($tool_id);
		$calendar = new Calendar;
		$db = new Database;
		# Create array for dates associated with this month
		$dates = $db->query("
			SELECT * FROM calendar_items 
			WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
		");
		
	}

/*
 * Add Event(s)
 */ 
	public function add($tool_id=NULL)
	{		
		valid::id_key($tool_id);	
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
			die('Event added'); #status message
		}
		else
		{			
			die( $this->_view_add_single('calendar', $tool_id) );
		}	
	}
	
/*
 * Edit single Item
 */
	public function edit($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;
		if($_POST)
		{
			$data = array(
				'title'	=> $_POST['title'],
				'desc'	=> $_POST['desc'],		
			);		
			$db->update('calendar_items', $data, "id = '$id' AND fk_site = '$this->site_id'");
			die('Event Saved<br>Updating...');
		}
		else
		{
			$primary = new View("edit_calendar/single_item");		
			$parent = $db->query("
				SELECT * FROM calendar_items 
				WHERE id = '$id' 
				AND fk_site = '$this->site_id'
			")->current();
			$primary->item = $parent;
			$primary->js_rel_command = "update-calendar-$parent->parent_id";
			die($primary);
		}	
	}

/*
 * DELETE showroom (item) single
 * Success Response via inline JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of showroom item row 
 */
	public function delete($id=NULL)
	{
		valid::id_key($id);				
		die( $this->_delete_single_common('calendar', $id) );
	}

	function settings()
	{
		die('Edit Calendar settings...');
	}
	
# Ajax get a list of events for a certain date
	public function day($date=NULL)
	{
		# Format: month/day/year
		$pieces = explode('-', $date);
		
		valid::id_key($pieces['0']);
		valid::id_key($pieces['1']);
		valid::id_key($pieces['2']);
		
		$month	= $pieces['0'];
		$day	= $pieces['1'];
		$year	= $pieces['2'];
		$db = new Database;
		
		$events = $db->query("
			SELECT * FROM calendar_items 
			WHERE fk_site = '$this->site_id'
			AND year = '$year'  
			AND month = '$month'
			AND day = '$day'
		");
	
		if( $events->count() > 0 )
		{
			$primary = new View('edit_calendar/day');
			$primary->events = $events;
			$primary->date = $date;
			die($primary);
		}
		else
			die('no events on this day');

	}
	
/*
 * Upload an image
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
		 
			unlink($filename);
			
			return $file_name;
		}
		else
			return FALSE;
	}
	
	static function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}
}