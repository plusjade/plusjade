<?php
class Edit_Faq_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for FAQ module.
 *
 */
	function __construct()
	{
		parent::__construct();
	}

	
/*
 *	rearrange faq-item positions
 */
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		$faq = ORM::factory('faq')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $faq->loaded)
			die('invalid faq id');

		$primary = new View('edit_faq/manage');
		$primary->items = $faq->faq_items;
		$primary->tool_id = $tool_id;
		die($primary);
	}

/*
 * add a faq item
 */	
	function add($tool_id=NULL)
	{
		valid::id_key($tool_id);	
		if($_POST)
		{
			$max = ORM::factory('faq_item')
				->select('MAX(position) as highest')
				->where('faq_id', $tool_id)
				->find();		

			$new_item = ORM::factory('faq_item');
			$new_item->fk_site	= $this->site_id;
			$new_item->faq_id	= $tool_id;
			$new_item->question	= $_POST['question'];
			$new_item->answer	= $_POST['question'];
			$new_item->position	= ++$max->highest;
			$new_item->save();			
			die('Question added'); #success
		}
		
		$primary = new View("edit_faq/add_item");
		$primary->tool_id = $tool_id;	
		$primary->js_rel_command = "update-faq-$tool_id";
		die($primary);
	}


/*
 * edit a faq item
 */
	function edit($id=NULL)
	{
		valid::id_key($id);		

		$faq_item = ORM::factory('faq_item')
			->where('fk_site', $this->site_id)
			->find($id);	
		if(FALSE === $faq_item->loaded)
			die('invalid faq item id');
			
		if($_POST)
		{
			$faq_item->question = $_POST['question'];
			$faq_item->answer = $_POST['answer'];
			$faq_item->save();
			die('Faq item updated');
		}
		
		$primary = new View('edit_faq/edit_item');
		$primary->item = $faq_item;
		$primary->js_rel_command = "update-faq-$faq_item->faq_id";
		die($primary);
	}

/*
 * delete a faq item
 */
	function delete($id=NULL)
	{
		valid::id_key($id);
		
		ORM::factory('faq_item')
			->where('fk_site', $this->site_id)
			->delete($id);
		die('Faq item deleted');
	}

/* 
 * save the positions of the faq questions
 * the ids are passed directly from the DOM so we don't need a tool_id
 */
	function save_sort()
	{
		if(empty($_GET['item']))
			die('No items to sort');

		$db = new Database;	
		foreach($_GET['item'] as $position => $id)
			$db->update('faq_items', array('position' => $position), "id = '$id'"); 	
		
		die('Faq item order saved.');

		/* does not work		
		$faq_item = ORM::factory('faq_item');
		foreach($_GET['item'] as $position => $id)
		{
			$faq_item->position = $position;
			$faq_item->save($id);
		}
		*/
	}

/*
 * Configure faq tool settings
 */ 
	public function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);
		die("Faq settings are temporarily disabeled while we update our system. Thanks!");
	}


	
	public static function _tool_deleter($tool_id, $site_id)
	{
		ORM::factory('faq_item')
			->where(array(
				'fk_site'	=> $site_id,
				'faq_id'	=> $tool_id,
				))
			->delete_all();

		return TRUE;
	}
	
} /* end */


