<?php defined('SYSPATH') OR die('No direct access allowed.');


/*
 *	Handles all editing logic for format module.
 *
 */
class Edit_Format_Controller extends Edit_Tool_Controller {


	function __construct()
	{
		parent::__construct();
	}

	
/*
 *	rearrange format-item positions
 */
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $format->loaded)
			die('invalid format id');

		$primary = new View('edit_format/manage');
		$primary->items = $format->format_items;
		$primary->tool_id = $tool_id;
		die($primary);
	}

/*
 * add a format item
 */	
	function add($tool_id=NULL)
	{
		valid::id_key($tool_id);	
		if($_POST)
		{
			$max = ORM::factory('format_item')
				->select('MAX(position) as highest')
				->where('format_id', $tool_id)
				->find();		

			$new_item = ORM::factory('format_item');
			$new_item->fk_site		= $this->site_id;
			$new_item->format_id	= $tool_id;
			$new_item->title		= $_POST['title'];
			$new_item->image		= (isset($_POST['image'])) ? $_POST['image'] : '';
			$new_item->body			= $_POST['body'];
			$new_item->position		= ++$max->highest;
			$new_item->save();			
			die('Item added'); #success
		}

		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $format->loaded)
			die('invalid format id');		
		
		$primary = new View("edit_format/add_$format->type");
		$primary->tool_id = $tool_id;	
		$primary->js_rel_command = "update-format-$tool_id";
		die($primary);
	}


/*
 * edit a format item
 */
	function edit($id=NULL)
	{
		valid::id_key($id);		

		$format_item = ORM::factory('format_item')
			->where('fk_site', $this->site_id)
			->find($id);	
		if(FALSE === $format_item->loaded)
			die('invalid format item id');
			
		if($_POST)
		{
			$format_item->title = $_POST['title'];
			$format_item->image	= (isset($_POST['image'])) ? $_POST['image'] : '';
			$format_item->body	= $_POST['body'];
			$format_item->save();
			die('format item updated');
		}

		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($format_item->format_id);	
		if(FALSE === $format->loaded)
			die('invalid format id');		
		
		$primary = new View("edit_format/edit_$format->type");
		$primary->item = $format_item;
		$primary->img_path = $this->assets->assets_url();
		$primary->js_rel_command = "update-format-$format_item->format_id";
		die($primary);
	}

/*
 * delete a format item
 */
	function delete($id=NULL)
	{
		valid::id_key($id);
		
		ORM::factory('format_item')
			->where('fk_site', $this->site_id)
			->delete($id);
		die('format item deleted');
	}

/* 
 * save the positions of the format questions
 * the ids are passed directly from the DOM so we don't need a tool_id
 */
	function save_sort()
	{
		if(empty($_GET['item']))
			die('No items to sort');

		$db = new Database;	
		foreach($_GET['item'] as $position => $id)
			$db->update('format_items', array('position' => $position), "id = '$id'"); 	
		
		die('format item order saved.');
	}

/*
 * Configure format tool settings
 */ 
	public function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);		
		
		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $format->loaded)
			die('invalid format');
			
		if($_POST)
		{
			$format->name = $_POST['name'];
			$format->type = $_POST['type'];
			#$format->params = $_POST['params'];
			$format->save();
			die('Format Settings Saved.');
		}

		$view = new View('edit_format/settings');
		$view->format = $format;
		$view->js_rel_command = "update-format-$tool_id";			
		die($view);
	}


/*
 * callback function when this tool is deleted.
 * cleans up extra data
 */ 	
	public static function _tool_deleter($tool_id, $site_id)
	{
		ORM::factory('format_item')
			->where(array(
				'fk_site'	=> $site_id,
				'format_id'	=> $tool_id,
				))
			->delete_all();

		return TRUE;
	}
	
} /* end */


