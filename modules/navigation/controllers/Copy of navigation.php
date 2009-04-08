<?php

class Navigation_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{	
		tool_ui::validate_id($tool_id);	
		$primary = new View('navigation/index');	
		$db = new Database;
		$parent = $db->query("SELECT * FROM navigations WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();
	
		$items = $db->query("SELECT * FROM navigation_items WHERE parent_id = '$parent->id' AND fk_site = '$this->site_id' ");		
		
		$primary->items = $items;
		
		
		function display_tree($root) {
		  
		  $db = new Database;
		  // retrieve the left and right value of the $root node
		  $parent = $db->query("SELECT lft, rgt FROM navigation_items WHERE display_name='$root'")->current();
		  
		

		   
		   // start with an empty $right stack
		   $right = array();		   
		   
		   
		   
		$children = $db->query("SELECT display_name, lft, rgt FROM navigation_items 
			 WHERE lft BETWEEN $parent->lft AND $parent->rgt ORDER BY lft ASC");


		# echo '<pre>';print_r($children);echo '</pre>'; die();
		 
		 // display each row
		   foreach ($children as $child) 
		   {
			   // only check stack if there is one
			   if ( count($right)>0 )
			   {
				   // check if we should remove a node from the stack
				   while ( $right[count($right)-1] < $child->rgt )
				   {
					   array_pop($right);
				   }
			   }

			   // display indented node title
			   echo str_repeat('-',count($right)) . $child->display_name."\n";

			   // add this node to the stack
			   $right[] = $child->rgt;
			}
		} 		
		
		
		echo '<pre>';
		echo display_tree('fruit');
		echo '</pre>';
		die();
		
		return $primary;
	}
  
}

/* -- end of application/controllers/showroom.php -- */