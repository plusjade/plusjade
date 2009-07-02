<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 
 * Tree traversal to build nested lists
 * used to build navigation lists for navigation tool
 * used to build nested categories in showroom tool
 * 
*/
class Tree_Core {

/*
 * display node data for public navigation tool since
 * more multiple navigations can be on same page.
 */
	static function render_node_navigation($item)
	{
		$type = ( empty($item->type) ) ? 'none' : $item->type;

		switch($type)
		{
			case 'none':
				$entry = $item->display_name; 
				break;
			case 'page':
				$entry = '<a href="'. url::site($item->data) .'">' . $item->display_name . '</a>'; 						
				break;
			case 'url':
				$entry = "<a href=\"http://$item->data\">$item->display_name</a>"; 	
				break;	
			case 'email':
				$entry = "<a href=\"mailto:$item->data\">$item->display_name</a>"; 
				break;					
		}	
		return "<li id=\"item_$item->id\"><span>$entry</span>";
	}
		
/* 
 * Uses Tree traversal method to display neat nested ul/li list.
 * $items (object) are required to have lft/rgt values
*/
	static function display_tree($toolname, $items, $page_name=null, $admin=FALSE)
	{	  
		# start with an empty $right stack
		$right		= array();	
		# compare prev and current item nest positions
		$compare	= array(0); 
		$q			= 1;	
		# count how many unique ul lists there are
		$global_list_id	= 0;
		
		# Display each row
		ob_start();
		foreach ($items as $item) 
		{
			# only check stack if there is one
			if ( count($right) > 0 )
			{
				# check if we should remove a node from the stack
				while ( $right[count($right)-1] < $item->rgt )
					array_pop($right);
			}
			
			# these vars are used to compare level of new row to level of old row
			#so that we can built the ul/li list appropropriately. 
			$new	= $compare[$q]	= count($right);
			$old	= $compare[$q-1];

			# generate output for each node	
			if( is_callable("render_node_$toolname") )
				$entry = call_user_func("render_node_$toolname", $item, $page_name);
			else
				$entry = call_user_func(array('Tree', 'render_node_navigation'), $item);
			
			/*
			 * Output the list entries.
			 * 	Case 1: New level = Old level OR is root level.
			 *		element is a sibling
			 * 	Case 2: New Level != old level
			 */
			
			if($new == $old)
			{
				# First element is always the root holder.
				if( '1' == $q AND TRUE === $admin)
				{
					echo '<ul class="simpleTree">',"\n",'<li class="root" id="item_'. $item->id .'" rel="'. $item->id .'"><span>(Root)</span>';				
					# needed so simple_tree js has a place to add to an empty root.
					if('1' == count($items) )
						echo '<ul><li class="line"></li></ul>';
				}
				elseif( '1' != $q )
				{
					echo "</li>\n";
					echo $entry;
				}

			}			
			else
			{
				/*
				 * IF level is higher then is a new UL depth.
				 * IF level is lower then close old list 
				 * 		count # of levels higher New is and close same # of </ul></li> to get there.			
				 * 		add new.
				 */
				 
				if($new > $old )
				{		
					for($x = $old ; $x < $new; $x++)
					{
						echo "\n".'<ul id="list_' . ++$global_list_id . '">'."\n";						
						echo $entry;
					}
				}
				else
				{
					echo "</li>\n";
					
					for($x = $new ; $x < $old; $x++)
						echo "\n</ul></li>\n";
					
					echo $entry;
				}
			}

			# add this node to the stack
			$right[] = $item->rgt;
			++$q;
		}	
	
		# Get level of last "NEW" row
		# add that many closing tags for that many levels back to root
		for($x = 0; $x < $new; $x++)
			echo "</li></ul>\n";
		
		# Close the root holder
		if( TRUE === $admin )
			echo '</li></ul>';
	
		return ob_get_clean();
	} 		

	
/*
 * Saves the nested positions of the menu elements
 * Can also delete any elements removed from the list.
 * $parent_table	= name of the parent table
 * $item_table		= name of the items table
 * $tool_id			= tool id
 * $output			= unformatted string from ul list
 */ 
	function save_tree($parent_table, $item_table, $tool_id, $output)
	{
		$db = new Database;
		$all_items = array();
		
		/* output variable comes via ajax post request
		 * Data Format: < id:local_parent:position| >
		 */
		$output	= rtrim($output, '|');
		$elements	= explode('|', $output);
		
		# Get parent table to find children 
		# *root_id* of the root child.
		$parent_object = $db->query("
			SELECT * FROM $parent_table 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();
	
		# Get all items (omit root) so we can delete items not sent.
		$items = $db->query("
			SELECT id FROM $item_table 
			WHERE parent_id = '$tool_id' 
			AND local_parent != '0'
		");
	
		foreach($items as $item)
			$all_items[$item->id] = $item->id;
		
		# Trouble shoot
		# echo '<div style="font-size:1.4em; width:300px; height:300px"><pre>';print_r($elements);echo'</pre>';echo '</div>';die();
		
		# If at least one still exists...
		if(! empty($elements['0']) )
		{
			# Data Format is : "id:local_parent:position"
			foreach($elements as $element)
			{
				$element_data = explode(':', $element);
				list($id, $parent, $position) = $element_data;
				
				# If no parent, assign to root_id
				# Javascript assigns "0" to elements returning no parent
				if( '0' == $parent ) $parent = $parent_object->root_id;

				$data = array(
					'local_parent'	=> $parent,
					'position'		=> $position
				);
				$db->update($item_table, $data, "id = '$id' AND fk_site = $this->site_id"); 	
		
				# Item exists so remove from the delete array.
				unset($all_items[$id]);
			}
		}
			
		# Delete elements.
		if( 0 < count($all_items) )
		{
			$id_string = implode(',', $all_items);
			$db->delete($item_table, "id IN ($id_string) AND fk_site = '$this->site_id'" ); 
		}

		# Update Left and right values of whole tree
		Tree::rebuild_tree($item_table, $parent_object->root_id, '1');
		
		return 'Tree Saved'; # status response	
	}


	
/*
 * Rebuilds the tree anytime updates are made.
 * Needed to renew the left and right values.
 * $local_parent starts with root_id,
 * $left starts with 1
 */
	public function rebuild_tree($table, $local_parent, $left)
	{
	   # the right value of this node is the left value + 1
	   $right = $left+1;

	   # get all children of this node
	   $result = mysql_query("
			SELECT id FROM $table 
			WHERE local_parent='$local_parent' 
			AND fk_site = '$this->site_id'
			ORDER BY position
		");

	   while ($row = mysql_fetch_array($result))
	   {
		   # recursive execution of this function for each
		   # child of this node
		   # $right is the current right value, which is
		   # incremented by the rebuild_tree function
		   $right = Tree::rebuild_tree($table, $row['id'], $right);
	   }

	   # we've got the left value, and now that we've processed
	   # the children of this node we also know the right value
	   mysql_query("
		UPDATE $table 
		SET lft='$left', rgt='$right' 
		WHERE id='$local_parent' AND fk_site = '$this->site_id'");

	   # return the right value of this node + 1
	   return $right+1;
	} 
	
	
} # End