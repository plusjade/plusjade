<?php
class Page_Controller extends Admin_Controller {

	/**
	 *	Provides CRUD for pages 
	 *	
	 */
	
	function __construct()
	{
		parent::__construct();
		if(! $this->client->logged_in()
			OR $this->client->get_user()->client_site_id != $this->site_id )
				die();
	}
	

# Manage all site pages 
	function index()
	{		
		$db			= new Database;				
		$primary	= new View("page/all_pages");
		$pages = $db->query("SELECT * FROM pages 
			WHERE fk_site = '$this->site_id' 
			ORDER BY position
		");			
		$primary->pages = $pages;
		echo $primary;	
		die();
	}

	
# ADD page
	function add()
	{
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
						$page_name = strtolower($_POST['label']);
					
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
				echo 'Page Created!!<br>Updating...'; # success			
			}
			else
				echo 'Name is required'; #error	
		}
		else
		{		
			$primary = new View("page/new_page");
			echo $primary;		
		}
		die();

	}
	
# DELETE single page from pages table
# Note: does not delete any tools owned by this page.

	function delete($page_id=NULL)
	{
		valid::id_key($page_id);
		$db = new Database;		
		$data = array(
			'id'		=> $page_id,
			'fk_site'	=> $this->site_id,		
		);
		$db->delete('pages', $data);
		echo 'Page deleted!!'; # success			
		die();
	}


# Save the tool positions/containers, and local/global scope on the page
# the posts happens via ajax in the public/assets/js/admin/init.js file
# invoked via id="get_tool_sort" link
	function tools($page_id=NULL)
	{
		valid::id_key($page_id);		
		
		if($_POST)
		{
			#echo '<PRE>';print_r($_POST);echo '</PRE>'; die();
			$db = new Database;
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
				echo 'There are no tools to sort';
		}
		die();
	}
	
		
# Save Page position order 
	public function save_sort()
	{
		$db = new Database;
		foreach($_GET['page'] as $position => $id)
			$db->update('pages', array('position' => "$position"), "id = '$id'"); 	
			
		echo 'Sort Order Saved!'; # status response	
		die();
	}


# Configure page settings	
	function settings($page_id=NULL)
	{
		valid::id_key($page_id);
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
		}
		else
		{
			$page = $db->query("SELECT * FROM pages
				WHERE id = '$page_id' 
				AND fk_site = '$this->site_id'
			")->current();		
			if( is_object($page) )
			{
				$primary = new View("page/page_settings");	
				$primary->page = $page;	
				echo $primary;
			}
			else
				echo 'Page not found';
		}
		die();
	}
	
}
/* End of file page.php */