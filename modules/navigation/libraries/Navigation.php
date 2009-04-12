<?php defined('SYSPATH') OR die('No direct access allowed.');
	/* 
	 *
	 *
	 *    
	 * 
	*/
class Navigation_Core {

	/* 
	 * Takes an object of list items from navigation_items
	 * and displays a neat nested ul/li list.
	 *    
	 * 
	*/
	function display_tree($items, $show_root=FALSE)
	{	  
		$db = new Database;
		# start with an empty $right stack
		$right		= array();	
		# compare prev and current item nest positions
		$compare	= array(0); 
		$q			= 1;	
		# count how many unique ul lists there are
		$global_list_id	= 0;
		
		ob_start();
		# Display each row
		foreach ($items as $item) 
		{
			# only check stack if there is one
			if ( count($right) > 0 )
			{
				# check if we should remove a node from the stack
				while ( $right[count($right)-1] < $item->rgt )
				{
					array_pop($right);
				}
			}
		
			# these vars are used to compare level of new row to level of old row
			#so that we can built the ul/li list appropropriately. 
			$new	= $compare[$q]	= count($right);
			$old	= $compare[$q-1];
			
			/*
			 * Output the list entries.
			 * Case 1: New level = Old level OR is root level.
			 *		element is a sibling
			 * Case 2: New Level != old level
			 */
			
			if( TRUE === $show_root )
			{
				$entry =' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span>' . $item->display_name . '</span>'; 
			
			}
			else
			{
				$type = ( empty($item->type) ) ? 'none' : $item->type;
	
				switch($type)
				{
					case 'none':
						$entry =' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span>' . $item->display_name . '</span>'; 
					break;

					case 'page':
						
						$entry =' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><a href="'. url::site($item->data) .'">' . $item->display_name . '</a></span>'; 
				
					break;
					
					case 'url':
						$entry =' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><a href="http://'. $item->data .'">' . $item->display_name . '</a></span>'; 
				
					break;	
					
					case 'email':
						$entry =' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span><a href="mailto:'.$item->data.'">' . $item->display_name . '</a></span>'; 
				
					break;					
				}
		
			}
			

			
			if($new == $old)
			{
				# First element is a root holder so we don't echo it.
				if( '1' != $q )
				{
					echo "</li>\n";
					echo $entry;
				}
				elseif( TRUE === $show_root)
				{
					echo '<ul class="simpleTree">',"\n",'<li class="root" id="1"><span>Navigation Root</span>';				
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
					{
						echo "\n</ul></li>\n";
					}
					
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
		{
			echo "</li></ul>\n";
		}
		
		# Close the root holder
		if( TRUE === $show_root )
			echo '</li></ul>';
	
		return ob_get_clean();
	} 		

	# Rebuilds the tree anytime updates are made.
	# Needed to renew the left and right values.
	# $local_parent starts with root_id,
	# $left starts with 1
	
	public function rebuild_tree($local_parent, $left)
	{
	   # the right value of this node is the left value + 1
	   $right = $left+1;

	   # get all children of this node
	   $result = mysql_query("SELECT id FROM navigation_items 
			WHERE local_parent='$local_parent' 
			AND fk_site = '$this->site_id' ORDER BY position");
							  
	   while ($row = mysql_fetch_array($result))
	   {
		   # recursive execution of this function for each
		   # child of this node
		   # $right is the current right value, which is
		   # incremented by the rebuild_tree function
		   $right = Navigation::rebuild_tree($row['id'], $right);
	   }

	   # we've got the left value, and now that we've processed
	   # the children of this node we also know the right value
	   mysql_query("UPDATE navigation_items 
		SET lft='$left', rgt='$right' 
		WHERE id='$local_parent' AND fk_site = '$this->site_id'");

	   # return the right value of this node + 1
	   return $right+1;
	} 
		

} # End Calendar