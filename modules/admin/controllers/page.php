<?php
class Page_Controller extends Admin_View_Controller {

	/**
	 *	Provides CRUD for pages 
	 *	
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	

# Manage all site pages 
	function index()
	{		
		$db			= new Database;				
		$primary	= new View("page/all_pages");
		
		# Javascript
		$rootJS ='		
			$("#container-1").tabs({ fx: { opacity: "toggle",duration: "fast"} });
			
			$("#generic_sortable_list").sortable({ 
				handle	: ".handle",
				axis	: "y"
			});
		';		
		$rootJS .= tool_ui::js_delete_init('page');
		$rootJS .= tool_ui::js_save_sort_init('page', 'page');
			
		$this->template->rootJS = $rootJS;
				
		# Grab all site menu/pages
		$result = $db->query("SELECT * FROM menus WHERE fk_site = '{$this->site_id}' ORDER BY position");			
		$primary->menu_items = $result;
		$this->template->primary = $primary;	
		
	}

	
# ADD page
	function add()
	{
		# Create new page
		if($_POST)
		{
			#sanitize data 
			$post = new Validation($_POST);			
			$post->add_rules('display_name', 'required');
			
			if($post->validate())
			{
				$db = new Database;
				# Sanitize display/link names
				if(!empty($_POST['display_name']))
				{
					$page_name = trim($_POST['page_name']);
					if(empty($page_name))
						$page_name = $_POST['display_name'];
					
					# Make URL friendly
					$pattern = "(\W)";					
					$page_name = preg_replace($pattern, '_', $page_name);
				}

				# Get highest position
				$max = $db->query("SELECT MAX(position) as highest FROM menus WHERE fk_site = '{$this->site_id}' ")->current();			
			
				# add to pages table
				$data = array(
					'fk_site'	=> $this->site_id,
					'page_name'	=> $page_name,
				);
				$query = $db->insert('pages', $data);
				$pages_insert_id = $query->insert_id();
				
				# add to menus table
				$data = array(
					'fk_site'		=> $this->site_id,
					'page_id'		=> $pages_insert_id,
					'page_name'		=> $page_name,
					'display_name'	=> $_POST['display_name'],
					'position'		=> ++$max->highest
				);
				$db->insert('menus', $data);

				#status message
				echo 'Page Created!!<br>Updating...';			
			}
			else
				echo 'Name is required'; #status message	
				
			die();
		}
		else
		{		
			$primary = new View("page/new_page");
			$this->template->primary = $primary;		
		}

	}
	
# DELETE single page from pages table
# Note: does not delete any tools owned by this page.

	function delete($page_id=NULL, $menu_id=NULL)
	{
		tool_ui::validate_id($page_id);
		tool_ui::validate_id($menu_id);
		$db = new Database;
		
		# Delete page and menu instance
		$db->delete('menus', array('id' => $menu_id));
		
		$data = array(
			'id'		=> $page_id,
			'fk_site'	=> $this->site_id,		
		);
		$db->delete('pages', $data);
		
		#status message
		echo 'Page deleted!!';			

		die();
	}


# Sort the tools on the page.

	function tools($page_id=NULL)
	{
		tool_ui::validate_id($page_id);		
		$db = new Database;
	
		if(! empty($_GET['tool']) )
		{	
			#echo '<PRE>';print_r($_GET);echo '</PRE>';
			#die();		
			foreach($_GET['tool'] as $position => $tool_guid)
			{
				# Update the positions
				$data = array(
					'position'	=> $position+1,
				);
				$db->update('pages_tools', $data, "guid = '$tool_guid' AND page_id = '$page_id'");						
			}
			
			echo 'Order Updated!';
			die();
		}
		else
		{
			$primary = new View('page/sort_tools');
			
			$tools_list = $db->query('SELECT * FROM tools_list');
			$primary->tools_list = $tools_list;
						
			#Grab tools on this page
			$tools = $db->query("SELECT * FROM pages_tools 
				JOIN tools_list ON pages_tools.tool = tools_list.id
				WHERE page_id = '$page_id' ORDER BY position ");			
			$primary->page_id = $page_id;
			$primary->tools = $tools;

			$embed_js ='
			  // Make Sortable
				$("#generic_sortable_list").sortable({ axis : "y" });
				
				var url = $("#custom_ajaxForm").attr("action");
						
				$("#custom_ajaxForm").submit(function(){
					var order = $("#generic_sortable_list").sortable("serialize");

					var options = {
						url: url+"?"+order,
						success:	function(data) { 
										$.facebox(data, "ajax_status", "facebox_")
										setTimeout(function(){location.reload();},1000);
									}					
					};				
					$(this).ajaxSubmit(options);				
					return false;
				});
			';
			
			$embed_js .= tool_ui::js_delete_init('tool');
			
			# $embed_js .= tool_ui::js_save_sort_init('page', 'page');

			$this->template->rootJS = $embed_js;		
			$this->template->primary = $primary; 		
		}

	}
	
		
# Save Page position order 
	public function save_sort()
	{
		$db = new Database;
		foreach($_GET['page'] as $position => $id)
			$db->update('menus', array('position' => "$position"), "id = '$id'"); 	
			
		echo '<div class="center">Sort Order Saved!</div>'; # status response	
		die();
	}


# Configure page settings	
	function settings($page_id=NULL)
	{
		tool_ui::validate_id($page_id);
		
		$db = new Database;

		if(! empty($_POST) )
		{
			if( !empty($_POST['display_name']) )
			{
				# Sanitize display/link names
				$page_name = trim($_POST['page_name']);
				if(empty($page_name))
					$page_name = $_POST['display_name'];
				
				# Make URL friendly
				$pattern = "(\W)";					
				$page_name = preg_replace($pattern, '_', $page_name);

				# Update pages
				$data = array(
					'page_name'	=> $page_name,
					'title'		=> $_POST['title'],
					'meta'		=> $_POST['meta'],
					'enable'	=> $_POST['page_enable'],
				);
				$db->update('pages', $data, "id = '$page_id' AND fk_site = '$this->site_id' "); 			

				# Update Menus
				if($_POST['page_enable'] == 'no') $_POST['menu_enable'] = 'no';
					
				$data = array(
					'page_name'		=> $page_name,
					'display_name'	=> $_POST['display_name'],
					'enable'		=> $_POST['menu_enable'],
				);
				$db->update('menus', $data, "id = {$_POST['id']} AND fk_site = '$this->site_id' ");	
			
				#status message
				echo 'Changes Saved!<br>Updating...';
			}
			else
				echo 'Display Name is required';	
		
		
			# Delete page and menu instance
			if(!empty($_POST['delete']))
			{
				$db->delete('menus', array('id' => $_POST['id']));
				
				$data = array(
					'id'		=> $_POST['page_id'],		
					'fk_site'	=> $this->site_id,
				);
				$db->delete('pages', $data);
				
				echo '<script>$.jGrowl("Page deleted!!")</script>';		
			}
		}
		else
		{
			# Grab page row
			$page = $db->query("SELECT * FROM pages WHERE id = '$page_id' AND fk_site = '{$this->site_id}' ");		
			
			if( $page->count() > 0 )
			{
				$primary = new View("page/page_settings");	
				$primary->page = $page->current();	

				# get Menu entry for this page
				$result = $db->query("SELECT * FROM menus WHERE page_id = '$page_id'");			
				$primary->menu = $result->current();
				$primary->render(TRUE);
			}
			else
			{
				echo 'Page does not exist';
			}
		}
		
		die();
	
	}
	
}
/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */