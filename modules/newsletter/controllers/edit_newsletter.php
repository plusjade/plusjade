<?php defined('SYSPATH') OR die('No direct access allowed.');


/*
 *	Handles all editing logic for review module.
 *
 */
class Edit_Newsletter_Controller extends Edit_Tool_Controller {


	function __construct()
	{
		parent::__construct();
	}

	
/*
 *	rearrange review-item positions
 */
	public function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		$review = ORM::factory('review')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $review->loaded)
			die('invalid review id');

		$primary = new View('edit_review/manage');
		$primary->items = $review->review_items;
		$primary->tool_id = $tool_id;
		die($primary);
	}

/*
 * add a review item
 */	
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);	
		if($_POST)
		{
			$max = ORM::factory('review_item')
				->select('MAX(position) as highest')
				->where('review_id', $tool_id)
				->find();		

			$new_item = ORM::factory('review_item');
			$new_item->fk_site		= $this->site_id;
			$new_item->review_id	= $tool_id;
			$new_item->title		= $_POST['title'];
			$new_item->image		= (isset($_POST['image'])) ? $_POST['image'] : '';
			$new_item->body			= $_POST['body'];
			$new_item->position		= ++$max->highest;
			$new_item->save();			
			die('Item added'); #success
		}

		$review = ORM::factory('review')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $review->loaded)
			die('invalid review id');		
		
		$primary = new View("edit_review/add_$review->type");
		$primary->tool_id = $tool_id;	
		$primary->js_rel_command = "update-review-$tool_id";
		die($primary);
	}


/*
 * edit a review item
 */
	public function edit($id=NULL)
	{
		valid::id_key($id);		

		$review_item = ORM::factory('review_item')
			->where('fk_site', $this->site_id)
			->find($id);	
		if(FALSE === $review_item->loaded)
			die('invalid review item id');
			
		if($_POST)
		{
			$review_item->title = $_POST['title'];
			$review_item->image	= (isset($_POST['image'])) ? $_POST['image'] : '';
			$review_item->body	= $_POST['body'];
			$review_item->save();
			die('review item updated');
		}

		$review = ORM::factory('review')
			->where('fk_site', $this->site_id)
			->find($review_item->review_id);	
		if(FALSE === $review->loaded)
			die('invalid review id');		
		
		$primary = new View("edit_review/edit_$review->type");
		$primary->item = $review_item;
		$primary->img_path = $this->assets->assets_url();
		$primary->js_rel_command = "update-review-$review_item->review_id";
		die($primary);
	}

/*
 * delete a review item
 */
	public function delete($id=NULL)
	{
		valid::id_key($id);
		
		ORM::factory('review_item')
			->where('fk_site', $this->site_id)
			->delete($id);
		die('review item deleted');
	}

/* 
 * save the positions of the review questions
 * the ids are passed directly from the DOM so we don't need a tool_id
 */
	public function save_sort()
	{
		if(empty($_GET['item']))
			die('No items to sort');

		$db = new Database;	
		foreach($_GET['item'] as $position => $id)
			$db->update('review_items', array('position' => $position), "id = '$id'"); 	
		
		die('review item order saved.');
	}

/*
 * Configure review tool settings
 */ 
	public function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);		
		
		$review = ORM::factory('review')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $review->loaded)
			die('invalid review');
			
		if($_POST)
		{
			$review->name = $_POST['name'];
			$review->view = $_POST['view'];
			#$review->params = $_POST['params'];
			$review->save();
			die('review Settings Saved.');
		}

		switch($review->type)
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
			default:
				$type_views = array();
				break;
		}
		
		$view = new View('edit_review/settings');
		$view->review			= $review;
		$view->type_views		= $type_views;
		$view->js_rel_command	= "update-review-$tool_id";			
		die($view);
	}


/*
 * callback function when this tool is deleted.
 * cleans up extra data
 */ 	
	public static function _tool_deleter($tool_id, $site_id)
	{
		ORM::factory('review_item')
			->where(array(
				'fk_site'	=> $site_id,
				'review_id'	=> $tool_id,
				))
			->delete_all();

		return TRUE;
	}
	
} /* end */


