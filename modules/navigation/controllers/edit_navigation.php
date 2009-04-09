<?php

class Edit_Navigation_Controller extends Edit_Module_Controller {
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
		
		$db = new Database;
		$parent = $db->query("SELECT * FROM navigations WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();
		$items = $db->query("SELECT * FROM navigation_items WHERE parent_id = '$parent->id' AND fk_site = '$this->site_id' ORDER BY lft ASC ");		
		
		echo  Navigation::display_tree($items);
		die();
	}

/*
 * Add links(s)
 */ 
	public function add($tool_id=NULL, $right_value=NULL)
	{
		tool_ui::validate_id($tool_id);
		
		#if($_POST)
		#{
			$db = new Database;
					
			
			echo rebuild_tree(1,1);


			echo 'Link added'; #status message
			die();
			
		#}
		#else
		#{			
		#	$this->_show_add_single('navigation', $tool_id);
		#}
		#die();		
	}

	function save_sort()
	{
		$output	= rtrim($_POST['output'], '#');
		$links	= explode('#', $output);
		$db		= new Database;	
		
		
		# Get all items so we can delete items not sent.
		$items = $db->query("SELECT id FROM navigation_items WHERE parent_id = '1' AND local_parent != '0'");
	
		$all_items = array();
		foreach($items as $item)
		{
			$all_items[$item->id] = $item->id;
		}		
		# Get the parent table and get the root_id
		$root_id = 1;
		
		
		/* Trouble shoot
		echo '<div style="font-size:1.4em; width:300px; height:300px">';
		echo '<pre>';print_r($links);echo'</pre>';echo '</div>';die();
		*/
		

	
		# Data Format is : "id:local_parent:position"
		foreach($links as $link)
		{
			$pieces 	= explode(':', $link);
			$id			= $pieces['0'];
			$parent		= $pieces['1'];
			$position	= $pieces['2'];
			
			# If no parent, assign to root id
			if( '0' == $parent ) $parent = 1;
			
			# if id = -1 this means its new.
			if( '-1' != $id)
			{
				$data = array(
					'local_parent'	=> $parent,
					'position'		=> $position
				);
				
				$db->update('navigation_items', $data, "id = '$id' AND fk_site = $this->site_id"); 	
				
			}
			else
			{
				$data = array(
					'parent_id'		=> 1,
					'fk_site'		=> $this->site_id,
					'display_name'	=> 'NEW',
					'local_parent'	=> $parent,
					'position'		=> $position
				);
				
				$db->insert('navigation_items', $data); 	
			}
			unset($all_items[$id]);
		}

		$id_string = implode(',', $all_items);
		$db->delete('navigation_items', "id IN ($id_string) AND fk_site = '$this->site_id'" ); 


		#echo '<pre>';print_r($all_items);echo '</pre>';	die();
		
		echo Navigation::rebuild_tree($root_id, '1');
		
		echo 'Sort Order Saved!!<br>Updating...'; # status response	
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