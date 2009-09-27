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
	public function manage($parent_id=NULL)
	{
		valid::id_key($parent_id);
		
		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($parent_id);	
		if(FALSE === $format->loaded)
			die('invalid format id');

		$primary = new View('edit_format/manage');
		$primary->items = $format->format_items;
		$primary->parent_id = $parent_id;
		die($primary);
	}

/*
 * add a format item
 */	
	public function add($parent_id=NULL)
	{
		valid::id_key($parent_id);	
		if($_POST)
		{
			$max = ORM::factory('format_item')
				->select('MAX(position) as highest')
				->where('format_id', $parent_id)
				->find();		

			$new_item = ORM::factory('format_item');
			$new_item->fk_site		= $this->site_id;
			$new_item->format_id	= $parent_id;
			$new_item->title		= $_POST['title'];
			$new_item->type			= (isset($_POST['type'])) ? $_POST['type'] : '';
			$new_item->meta			= (isset($_POST['meta'])) ? $_POST['meta'] : '';
			$new_item->album		= (isset($_POST['album'])) ? $_POST['album'] : '';
			$new_item->body			= $_POST['body'];
			$new_item->position		= ++$max->highest;
			$new_item->save();			
			die('Item added'); #success
		}

		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($parent_id);	
		if(!$format->loaded)
			die('invalid format id');
				
		$view = new View("edit_format/add_$format->type");
		$view->parent_id = $parent_id;	
		$view->js_rel_command = "update-format-$parent_id";
		die($view);
	}


/*
 * edit a format item
 */
	public function edit($id=NULL)
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
			$format_item->type	= (isset($_POST['type'])) ? $_POST['type'] : '';
			$format_item->meta	= (isset($_POST['meta'])) ? $_POST['meta'] : '';
			$format_item->album	= (isset($_POST['album'])) ? $_POST['album'] : '';
			$format_item->body	= $_POST['body'];
			$format_item->save();
			die('format item updated');
		}

		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($format_item->format_id);	
		if(FALSE === $format->loaded)
			die('invalid format id');		

		$view = new View("edit_format/edit_$format->type");
		$view->item = $format_item;
		$view->img_path = $this->assets->assets_url();
		$view->js_rel_command = "update-format-$format_item->format_id";
		die($view);
	}

/*
 * delete a format item
 */
	public function delete($id=NULL)
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
	public function save_sort()
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
	public function settings($parent_id=NULL)
	{
		valid::id_key($parent_id);		
		
		$format = ORM::factory('format')
			->where('fk_site', $this->site_id)
			->find($parent_id);	
		if(FALSE === $format->loaded)
			die('invalid format');
			
		if($_POST)
		{
			$format->name = $_POST['name'];
			$format->view = $_POST['view'];
			$format->params = (isset($_POST['params'])) ? $_POST['params'] : '';
			$format->save();
			die('Format Settings Saved.');
		}
		
		# setup view toggling based on format type.
		switch($format->type)
		{
			case 'people':
				$type_views = array('list','filmstrip');
				break;
			case 'contacts':
				$type_views = array('list');
				break;
				
			case 'faqs':
				$type_views = array('simple');
				break;
			case 'tabs':
				$type_views = array('stock');
				break;
			case 'forms':
				$type_views = array('list');
				break;
			default:
				$type_views = array();
				break;
		}
		
		$view = new View('edit_format/settings');
		$view->format			= $format;
		$view->type_views		= $type_views;
		$view->js_rel_command	= "update-format-$parent_id";			
		die($view);
	}


/*
 * callback function when this tool is deleted.
 * cleans up extra data
 */ 	
	public static function _tool_deleter($parent_id, $site_id)
	{
		ORM::factory('format_item')
			->where(array(
				'fk_site'	=> $site_id,
				'format_id'	=> $parent_id,
				))
			->delete_all();

		return TRUE;
	}
	
} /* end */


