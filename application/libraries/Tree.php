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
	public static function render_node_navigation($item)
	{
		$type = (empty($item->type)) ? 'none' : $item->type;

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
	public static function display_tree($toolname, $items, $page_name=null, $admin=FALSE)
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
 * $parent_model	= name of tool table model
 * $item_model		= name of name of the tool item table model
 * $tool_id			= tool id
 * $output			= unformatted string from ul list
		
		output variable comes via ajax post request
		Data Format: < id:local_parent:position| >
 */ 
	public static function save_tree($parent_model, $item_model, $tool_id, $site_id, $output)
	{
		$all_items	= array();
		$elements	= explode('|', rtrim($output, '|'));
		
		$parent_object = ORM::factory($parent_model)
			->where('fk_site', $site_id)
			->find($tool_id);
	
		# Get all items (omit root) so we can delete items not sent.
		$items = ORM::factory($item_model)
			->where(array(
				'fk_site' => $site_id,
				"{$parent_model}_id" => $tool_id,
				'local_parent != ' => '0'
			))
			->find_all();
	
		foreach($items as $item)
			$all_items[$item->id] = $item->id;

		# echo '<pre>';print_r($elements);echo'</pre>';die();
		
		# if more than one exists ...
		if(1 < count($elements))
		{
			foreach($elements as $element)
			{
				$element_data = explode(':', $element);
				
				# validate the data so corrupt data does not break the tree.
				foreach($element_data as $data)
					if(!ctype_digit($data))
						continue 2;
				
				list($id, $parent, $position) = $element_data;

				# If no parent, assign to root_id // Javascript assigns "0" to elements returning no parent
				if('0' == $parent)
					$parent = $parent_object->root_id;

				# todo: reference fk_site.
				$item = ORM::factory($item_model, $id); 
				$item->local_parent = $parent;
				$item->position = $position;
				$item->save();	
				
				# Item exists so remove from the delete array.
				unset($all_items[$id]);
			}
		}
			
		# Delete elements.
		if(0 < count($all_items))
		{
			ORM::factory($item_model)
				->where('fk_site', $site_id)
				->delete_all($all_items);
		}

		# Update Left and right values of whole tree
		self::rebuild_tree($item_model, $parent_object->root_id, $site_id, '1');
		
		return 'Tree Saved'; # status response	
	}


	
/*
 * Rebuilds the tree anytime updates are made.
 * Needed to renew the left and right values.
 * $local_parent starts with root_id,
 * $left starts with 1
 */
	public static function rebuild_tree($model, $local_parent, $site_id, $left)
	{
		# the right value of this node is the left value + 1
		$right = $left+1;
		
		$navigation_items = ORM::factory($model)
			->where(array(
				'fk_site'		=> $site_id,
				'local_parent'	=> $local_parent,
			))
			->orderby('position', 'asc')
			->find_all();	

		foreach($navigation_items as $item)
		{
		   # recursive function for each child of this node
		   # $right is the current right value;
		   # incremented by the rebuild_tree function
		   $right = Tree::rebuild_tree($model, $item->id, $site_id, $right);
		}
		
		# we've got the left value, and now that we've processed
		# the children of this node we also know the right value
		$item = ORM::factory($model, $local_parent); 
		$item->lft = $left;
		$item->rgt = $right;
		$item->save();		

		# return the right value of this node + 1
		return $right+1;
	} 
	
	
} # End