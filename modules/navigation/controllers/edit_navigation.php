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
			
		function rebuild_tree($local_parent, $left)
		{
		   // the right value of this node is the left value + 1
		   $right = $left+1;

		   // get all children of this node
		   $result = mysql_query("SELECT id FROM navigation_items WHERE local_parent='$local_parent' ORDER BY position");
								  
		   while ($row = mysql_fetch_array($result))
		   {
			   // recursive execution of this function for each
			   // child of this node
			   // $right is the current right value, which is
			   // incremented by the rebuild_tree function
			   $right = rebuild_tree($row['id'], $right);
		   }

		   // we've got the left value, and now that we've processed
		   // the children of this node we also know the right value
		   mysql_query('UPDATE navigation_items SET lft='.$left.', rgt='.
						$right.' WHERE id="'.$local_parent.'";');

		   // return the right value of this node + 1
		   return $right+1;
		} 
			echo rebuild_tree(1,1);



/*			
			# update right values
			$query = "UPDATE navigation_items SET rgt=rgt+2 
				WHERE parent_id = '$tool_id' 
				AND fk_site = '$this->site_id' 
				AND rgt > $right_value
			;";	
			$db->query($query);
			
			# Update left values
			$query = "UPDATE navigation_items SET lft=lft+2 
				WHERE parent_id = '$tool_id' 
				AND fk_site = '$this->site_id' 
				AND lft > $right_value
			;";
			$db->query($query);
			
			
			# Add new item
			$data = array(			
				'parent_id'		=> $tool_id,
				'fk_site'		=> $this->site_id,
				'lft'			=> $right_value+1,
				'rgt'			=> $right_value+2,
				'display_name'	=> 'grapes',
			);	
			$db->insert('navigation_items', $data);
			
			
*/
			echo 'Link added'; #status message
			die();
			
		#}
		#else
		#{			
		#	$this->_show_add_single('navigation', $tool_id);
		#}
		#die();		
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