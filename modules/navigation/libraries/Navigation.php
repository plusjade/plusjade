<?php defined('SYSPATH') OR die('No direct access allowed.');
	/* 
	 *  Ryboe Ajax Calendar
	 *
	 *    Version: 0.03
	 *
	 *  
	 *  Author: Sean Sullivan
	 *  Website: www.ryboe.com
	 *  Copyright 2008 Sean Sullivan under the GNU GENERAL PUBLIC [GPL] LICENSE: http://www.gnu.org/licenses/gpl.txt
	 *
	 *  Copyright 2008 Dave Brondsema http://brondsema.net
	*/
class Navigation_Core {

	# Takes an array of list items from navigation_items
	# and displays a neat nested ul/li list.
	function display_tree($items)
	{	  
		$db = new Database;
				
		# start with an empty $right stack
		(array) $items;
		$right		= array();	
		$compare	= array(0); 
		$q			= 1;
	
		# count how many unique ul lists there are
		$global_list_id	= 0;
		# used to track current list id element belongs to
		$list_id	= 0;
		$position_array = array(0);
		
		
		ob_start();
		echo '<div class="navigation_wrapper">';
		
		# display each row
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
			$new		= $compare[$q]	= count($right);
			$old		= $compare[$q-1];
			

			/*
			 * Output the list entries.
			 * Case 1: New level = Old level OR is root level.
			 *		element is a sibling
			 * Case 2: New Level != old level
			 */
			if($new == $old)
			{
				# First element is a root holder so we don't echo it.
				if( '1' != $q )
				{
					echo "</li>\n";
					echo '  <li id="item_' . $item->id . '" class="position_'.++$position_array[$list_id].'" >' . $item->display_name;
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
						++$list_id;
						$position_array[$list_id] = 1;
						echo "\n".'<ul id="list_' . ++$global_list_id . '" class="depth_'.count($right).' sortable">'."\n";						
						echo '  <li id="item_' . $item->id . '" class="position_1" >' . $item->display_name;
					}
					
				}
				elseif($new < $old)
				{
					echo "</li>\n";
					for($x = $new ; $x < $old; $x++)
					{
						echo "\n</ul></li>\n";
						--$list_id;;
					}			
					
					echo '  <li id="item_' . $item->id . '" class="position_'.++$position_array[$list_id].'">' . $item->display_name;

				}
			}
						
			# add this node to the stack
			$right[] = $item->rgt;
			++$q;
		}	
	
		# Get level of last "new" row
		# add that many closing tags for that many levels back to root
		for($x = 0; $x < $new; $x++)
		{
			echo "</li></ul>\n";
		}
		
		echo '</div>';
		return ob_get_clean();
	} 		
	


} // End Calendar