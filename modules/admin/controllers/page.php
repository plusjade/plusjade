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
		$pages = $db->query("SELECT * FROM pages 
			WHERE fk_site = '$this->site_id' 
			ORDER BY position
		");			
		$primary->pages = $pages;
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
			$post->add_rules('label', 'required');
			
			if($post->validate())
			{
				$db = new Database;
				# Sanitize display/link names
				if(! empty($_POST['label']) )
				{
					$page_name = trim($_POST['page_name']);
					if( empty($page_name) )
						$page_name = $_POST['label'];
					
					# Make URL friendly
					$pattern = "(\W)";					
					$page_name = preg_replace($pattern, '_', $page_name);
				}

				# Get highest page position
				$max = $db->query("SELECT MAX(position) as highest FROM pages WHERE fk_site = '$this->site_id' ")->current();			
			
				# Add to pages table
				$data = array(
					'fk_site'	=> $this->site_id,
					'page_name'	=> $page_name,
					'label'		=> $_POST['label'],
					'position'	=> ++$max->highest,
				);
				$db->insert('pages', $data);

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

	function delete($page_id=NULL)
	{
		tool_ui::validate_id($page_id);
		$db = new Database;		
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

		$containers = array(
			'1'	=> 'Container 1',
			'2'	=> 'Container 2',
			'3'	=> 'Container 3',
			'4'	=> 'Container 4',
			'5'	=> 'Container 5',
		);
		
		if($_POST)
		{	
			#echo '<PRE>';print_r($_POST);echo '</PRE>'; die();
			$output = rtrim($_POST['output'], '#');	
			$output = explode('#', $output);
			
			if(! empty($output['0']) )
			{
				# hash format "scope.guid.container.position"
				foreach($output as $hash)
				{
					$pieces	= explode('.', $hash);
					
					# Update the rows
					$data['position']	= $pieces['3'];			
					$data['page_id']	= $page_id;
					$data['container']	= $pieces['2'];	
					if( 'global' == $pieces['0'] )
					{
						$data['page_id']	= $pieces['2'];
						$data['container']	= $pieces['2'];
					}
					$db->update('pages_tools', $data, "guid = '{$pieces['1']}' AND fk_site = '$this->site_id'");								
				}	
				echo 'Order Updated!';
			}
			else
				echo 'No Tools Sent...';
			
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
				WHERE (page_id BETWEEN 1 AND 5 OR page_id = '$page_id')
				AND fk_site = '$this->site_id'
				ORDER BY container, position
			");			
			$primary->page_id		= $page_id;
			$primary->tools			= $tools;
			$primary->containers	= $containers;

			$embed_js ='
			// Make Sortable
			$(".sortable").sortable({
				axis : "y",
				connectWith: ".sortable",
				placeholder: "placeholder",
				forcePlaceholderSize: true
			});
						
			$(".facebox #page_tools").click($.delegate({
				".toggle_scope": function(e){
					var new_scope = $(e.target).attr("rel");	
					var toggle = "local";
					if("local" == new_scope) toggle = "global";
					var new_link = "<a href=\"#\" class=\"toggle_scope\" rel=\"" + toggle + "\">Make " + toggle + "</a>";
					
					$(e.target).parents("li").removeClass().addClass(new_scope);
					$(e.target).replaceWith(new_link);
					return false;				
				}
			}));
									
			var output = "";	
			$(".facebox #link_save_sort").click(function(){
				page_id = $(this).attr("rel");
				
				$(".sortable").each(function(){
					var container = this.id;
					var kids = $(this).children("li");
					
					$(kids).each(function(i){
						var scope = $(this).attr("class");
						output += scope + "." + this.id + "." + container + "." + i + "#";
					});
				});
				//alert(output); return false;					
							
				$.facebox(function() {
						$.post("/get/page/tools/"+page_id, {output: output}, function(data){
							$.facebox(data, "ajax_status", "facebox_response");
							location.reload();
						})
					}, 
					"ajax_status", 
					"facebox_response"
				);
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
			$db->update('pages', array('position' => "$position"), "id = '$id'"); 	
			
		echo '<div class="center">Sort Order Saved!</div>'; # status response	
		die();
	}


# Configure page settings	
	function settings($page_id=NULL)
	{
		tool_ui::validate_id($page_id);
		$db = new Database;

		if($_POST)
		{
			if(! empty($_POST['label']) )
			{
				# Sanitize display/link names
				$page_name = trim($_POST['page_name']);
				if( empty($page_name) )
					$page_name = $_POST['label'];
				
				# Make URL friendly
				$pattern = "(\W)";					
				$page_name = preg_replace($pattern, '_', $page_name);

				# Update pages
				$data = array(
					'page_name'	=> $page_name,
					'title'		=> $_POST['title'],
					'meta'		=> $_POST['meta'],
					'label'		=> $_POST['label'],
					'menu'		=> $_POST['menu'],
					'enable'	=> $_POST['enable'],
				);
				$db->update('pages', $data, "id = '$page_id' AND fk_site = '$this->site_id' "); 			

				#status message
				echo 'Changes Saved!<br>Updating...';
			}
			else
				echo 'Label is required';	
		
		
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
			# Grab the page row
			$page = $db->query("SELECT * FROM pages WHERE id = '$page_id' AND fk_site = '$this->site_id' ")->current();		
			
			if( is_object($page) )
			{
				$primary = new View("page/page_settings");	
				$primary->page = $page;	

				echo $primary;
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