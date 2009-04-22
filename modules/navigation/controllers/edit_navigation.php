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
		$primary	= new View('navigation/edit/manage');
		$db			= new Database;
		
		# Grab items belonging to this tool.
		$items	= $db->query("SELECT * FROM navigation_items 
			WHERE parent_id = '$tool_id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC ");		
			
		#Javascript
		$this->template->rootJS('
			// start simple tree mode
			$simpleTreeCollection = $(".facebox .simpleTree").simpleTree({
				autoclose: true,
				animate:true
			});
			
			// add delete icons
			$(".facebox li:not(.root)>span").after(" <img src=\"/images/navigation/cross.png\" class=\"li_delete\" alt=\"\">");
			
			// activate delete icons
			$(".facebox .li_delete").click(function(){
				$(this).parent().remove();	
			});
			
			
			// Gather and send nest data.
			$(".facebox #link_save_sort").click(function() {
				var output = "";
				var tool_id = $(this).attr("rel");
				
				$(".facebox #admin_navigation_wrapper ul").each(function(){
					var parentId = $(this).parent().attr("rel");
					if(!parentId) parentId = 0;
					var $kids = $(this).children("li:not(.root, .line,.line-last)");
					
					// Data set format: "id:local_parent_id:position#"
					$kids.each(function(i){
						output += $(this).attr("rel") + ":" + parentId + ":" + i + "#";
					});
				});
				
				//alert (output); return false;
				
				
				$.facebox(function() {
						$.post("/get/edit_navigation/save_sort/"+tool_id, {output: output}, function(data){
							$.facebox(data, "status_reload", "facebox_response");
							location.reload();
						})
					}, 
					"status_reload", 
					"facebox_response"
				);

				
			});		
		');
		
		$primary->tree = Tree::display_tree('navigation', $items, TRUE);
		$primary->tool_id = $tool_id;	
		$this->template->primary = $primary;
		echo $this->template;
		die();
	}

/*
 * Add links(s)
 */ 
	public function add($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		
		if($_POST)
		{
			$db = new Database;
			/*
			echo'<pre>';print_r($_POST['item']);echo'</pre>';		
			echo'<pre>';print_r($_POST['type']);echo'</pre>';
			echo'<pre>';print_r($_POST['data']);echo'</pre>';
			die();
			*/
			# Get parent
			$parent	= $db->query("SELECT * FROM navigations 
				WHERE id = '$tool_id' 
				AND fk_site = '$this->site_id' ")->current();
			
			foreach($_POST['item'] as $key => $item)
			{			
				$data_string = ( empty($_POST['data'][$key]) ) ? '' : $_POST['data'][$key];

				$data = array(
					'parent_id'		=> $tool_id,
					'fk_site'		=> $this->site_id,
					'display_name'	=> $item,
					'type'			=> $_POST['type'][$key],
					'data'			=> $data_string,
					'local_parent'	=> $parent->root_id,
					'position'		=> '0'
				);	
				$db->insert('navigation_items', $data); 	
			}
			# Update left and right values
			Tree::rebuild_tree('navigation_items', $parent->root_id, '1');

			echo 'Links added'; #status message
			die();
			
		}
		else
		{
			$primary = new View('navigation/edit/new_item');
			$db = new Database;
			$pages = $db->query("SELECT page_name FROM pages WHERE fk_site = '$this->site_id' ORDER BY page_name");			
			
					
			$primary->pages = $pages;
			
			$primary->tool_id = $tool_id;	
			$this->template->rootJS ='
				$(".facebox .toggle_type").each(function(){
					var field_id = $(this).attr("rel");
					
					$(this).change(function(){
						var span = "#" + $(this).val() + "_" + field_id;
						
						// Disable to start over
						$(".hide_" + field_id).hide();
						$(".hide_" + field_id + " > :input").attr("disabled","disabled").removeAttr("rel");
						
						// Enable selection
						$(span + " > :input").removeAttr("disabled").attr("rel","text_req");
						$(span).show();
					});
				});
			';
			
			$this->template->primary = $primary;
			
			echo $this->template;
		}
		die();		
	}

/*
 * Saves the nested positions of the menu links
 * Can also delete any links removed from the list.
 *
 */ 
	function save_sort($tool_id)
	{
		if($_POST)
		{
			tool_ui::validate_id($tool_id);
			echo Tree::save_tree('navigations', 'navigation_items', $tool_id, $_POST['output']);
		}
		die();
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

	}

	public function settings($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);		
		$db = new Database;
			
		# Edit item
		if($_POST)
		{
			$data = array(
				'title'	=> $_POST['title'],
			);		
			$db->update('navigations', $data, "id = '$tool_id' AND fk_site = '$this->site_id'");
				
			echo 'Settings Saved!<br>Updating...';	
		}
		else
		{		
			$parent = $db->query("SELECT * FROM navigations WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();			
			$primary = new View("navigation/edit/settings");
			
			$primary->parent = $parent;
			$this->template->primary = $primary;
			echo $this->template;
		}
		
		die();				
	}
	
}

/* -- end of application/controllers/showroom.php -- */