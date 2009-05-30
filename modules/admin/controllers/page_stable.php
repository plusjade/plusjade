<?php
class Page_Controller extends Admin_Controller {

	/**
	 *	Provides CRUD for pages 
	 *	
	 */
	
	function __construct()
	{
		parent::__construct();
		$this->client->can_edit($this->site_id);
	}
	

# Manage all site pages 
	function index()
	{
		$db			= new Database;				
		$primary	= new View("page/all_pages");		
		/*
			most likely will use this one in order to get more data
			into the array for viewing.
			is in menu, is disabled, page_id, etc
		*/
		$pages = $db->query("
			SELECT id,page_name, menu, enable
			FROM pages
			WHERE fk_site = '$this->site_id'
		");
		# pass the nested-directory array model...
		$primary->files_array =  self::_create_file_structure($pages);
		die($primary);
	}

	
	
	/*
		param $page_date (object) as of now
	*/
	static function _create_file_structure($page_data)
	{
		# build the page array, insert all pertinent data.
		$page_name_array = array();
		foreach($page_data as $page)
		{
			$page_name_array[] = "$page->page_name:$page->id:$page->menu:$page->enable";
		}
		
		#sort the page_name array by most sub_directories to least.
		function cmp($a, $b)
		{
			str_replace('/','_', $a, $count_a);
			str_replace('/','_', $b, $count_b);
			
			if ($count_a == $count_b)
				return 0;

			return ($count_a < $count_b) ? 1 : -1;
		}
		usort($page_name_array, 'cmp');
		
		# create an associative array to model the nested directories
		$files_array = array();	
		foreach($page_name_array as $page)
		{
			$node_array	= explode('/',$page);
			$count		= count($node_array);
			$last_node	= array_pop($node_array);
			@list($one, $two, $three, $four) = $node_array;	

			switch($count)
			{
				case '5':
					if( empty($files_array[$one]["$one/$two"]["$one/$two/$three"]["$one/$two/$three/$four"]["$one/$two/$three/$four/$last_node"]) )
						$files_array[$one]["$one/$two"]["$one/$two/$three"]["$one/$two/$three/$four"][$last_node] = $last_node;
					break;
				case '4':
					if( empty($files_array[$one]["$one/$two"]["$one/$two/$three"]["$one/$two/$three/$last_node"]) )
						$files_array[$one]["$one/$two"]["$one/$two/$three"][$last_node] = $last_node;
					break;	
				case '3':
					if( empty($files_array[$one]["$one/$two"]["$one/$two/$last_node"]) )
						$files_array[$one]["$one/$two"][$last_node] = $last_node;
					break;		
				case '2':
					if( empty($files_array[$one]["$one/$last_node"]) )
						$files_array[$one][$last_node] = $last_node;
					break;		
				case '1':
					if( empty($files_array[$last_node]) )
						$files_array[$last_node] = $last_node;
					break;
			}
		}	

		# TODO: 
			# tag protected pages ...
			# $primary->protected_pages = yaml::parse($this->site_name, 'pages_config');
			
		# troubleshooting...
		echo'<pre>';print_r($page_name_array);echo'</pre>';
		echo'<pre>';print_r($files_array);echo'</pre>';die();
		return $files_array;
	}
	
	
	
	
	
# sort the main menu navigation
	function navigation()
	{		
		$db			= new Database;				
		$primary	= new View("page/navigation");
		
		$pages = $db->query("
			SELECT * FROM pages 
			WHERE fk_site = '$this->site_id' 
			ORDER BY position
		");		
		$primary->pages = $pages;
		$primary->protected_pages = yaml::parse($this->site_name, 'pages_config');
		die($primary);
	}
	
# ADD page
	function add()
	{
		if($_POST)
		{
			$label = trim($_POST['label']);
			if( empty($label) )
				die('Name is required'); #error	

			$db = new Database;
			
			# Sanitize page_name
			$page_name = trim($_POST['page_name']);
			if( empty($page_name) )
				$page_name = strtolower($label);

			$page_name = valid::filter_php_url($page_name);

			/* Make sure page name is unique
			 * TODO: consider adding a javascript signifer that validates
			 * the javascript validation so we can bypass server validation?? 0.o
			 */
			$page_names = $db->query("
				SELECT GROUP_CONCAT( page_name separator ',') as name_string
				FROM pages
				WHERE fk_site = '$this->site_id'
			")->current();		
			$page_name_array = explode(',', $page_names->name_string);		
			
			# Is this a root or sub_page?
			if( empty($_POST['sub_page']) )
			{
				if( in_array($page_name, $page_name_array) )
					die('Page name already exists');
			}
			else
			{
				# Valide for unique sub page_name
				$sub_filter_array = array();
				foreach($page_name_array as $key => $name)
				{
					$name_array = explode('/',$name);
				
					if( 1 < count($name_array) )
					{
						$name_node	= array_pop($name_array);
						$sub_node	= array_pop($name_array);
						
						$sub_filter_array[$sub_node][] = $name_node;
					}
				}
				
				$filter_node = explode('/', $_POST['sub_page']);
				$filter_node = array_pop($filter_node);
				
				if(! empty($sub_filter_array[$filter_node]) )
					if( in_array($page_name, $sub_filter_array[$filter_node]) )
						die('Page name already exists');	
				
				$page_name = $_POST['sub_page']."/$page_name";
			}
			
			
			$max = $db->query("
				SELECT MAX(position) as highest 
				FROM pages WHERE fk_site = '$this->site_id'
			")->current();			
		
			# Add to pages table
			$data = array(
				'fk_site'	=> $this->site_id,
				'page_name'	=> $page_name,
				'label'		=> $_POST['label'],
				'position'	=> ++$max->highest,
			);
			if(! empty($_POST['menu']) )
				$data['menu'] = 'yes';
			
			$page_id = $db->insert('pages', $data)->insert_id();
			
			
			# is a page_builder submitted?
			# page builders cannot be on sub_pages
			if(empty($_POST['sub_page']) AND !empty($_POST['page_builder']) AND '0' != $_POST['page_builder'])
			{
				$tools_id = $_POST['page_builder'];
				# GET tool name
				$tool = $db->query("
					SELECT name FROM tools_list WHERE id='$tools_id'
				")->current();
				$tool_name = strtolower($tool->name);

				# INSERT row in tool parent table
				$data = array(
					'fk_site'	=> $this->site_id
				);			
				$tool_id = $db->insert("{$tool_name}s", $data)->insert_id();
				
				# INSERT pages_tools row inserting tool parent id
				$data = array(
					'page_id'	=> $page_id,
					'fk_site'	=> $this->site_id,
					'tool'		=> $tools_id,
					'tool_id'	=> $tool_id,
					'position'	=> 1
				);
				$db->insert('pages_tools', $data);
				
				Load_Tool::after_add($tool->name, $tool_id );
				
				# this tool is protected so add page to pages_config.yaml
				# and update pages row
				$newline = "\n$page_name:$tool_name:$tool_id,\n";
				yaml::add_value($this->site_name, 'pages_config', $newline);
				$db->update('pages', array('protected' => "$tool_name:$tool_id"), array('id' => $page_id));
				
				# TODO:
				# Pass output the facebox so it can load the next step page
				# echo strtolower($tool->name).'/add/'.$tool_insert->insert_id();			
			}

			echo 'Page Created!!<br>Updating...'; # success			
		}
		else
		{
			if(! isset($_GET['path_string']) )
				die('no directory selected');
				
			$primary		= new View("page/new_page");
			$db				= new Database;
			$path_string	= $_GET['path_string'];
			$path_array		= explode('/', $path_string);
			$primary->path_string = $path_string;
			
			# build the page_name array
			$pages = $db->query("
				SELECT page_name
				FROM pages
				WHERE fk_site = '$this->site_id'
			");
			$files_array = self::_create_file_structure($pages);
	
			# if the path is root...
			if( empty($path_string) )
			{
				$page_builders = $db->query("
					SELECT * FROM tools_list WHERE protected = 'yes'
				");
				$primary->page_builders = $page_builders;
				
				$root_filter = '';
				foreach($files_array as $name)
					if(! is_array($name) )
						$root_filter .= "'$name',";
				
				$root_filter = trim($root_filter, ',');
			
				$primary->filter = $root_filter;
				die($primary);
			}

			function get_sub_filter($main_array, $full_path, $pointer=0)
			{
				$path_array		= explode('/', $full_path);
				$count			= (count($path_array)-1);
				$node			= $path_array[$pointer];
				$current_path	= substr("$full_path",0,strpos($full_path,$node)+strlen($node));
				
				if( $count == $pointer )
				{
					$filter = '';
					foreach($main_array[$full_path] as $name)
						if(! is_array($name) )
							$filter .= "'$name',";
			
					$filter = trim($filter, ',');
					return $filter;
					
				}
				else
					get_sub_filter($main_array[$current_path], $full_path, ++$pointer);
			}
		
		
			$primary->filter = get_sub_filter($files_array, $path_string);
			die($primary);
		}
		die();

	}
	
# DELETE single page from pages table
# Note: does not delete any tools owned by this page.

	function delete($page_id=NULL)
	{
		valid::id_key($page_id);
		$db = new Database;		
		$page = $db->query("
			SELECT page_name FROM pages WHERE id='$page_id'
		")->current();

		# if deleting a protected page
		yaml::delete_value($this->site_name, 'pages_config', $page->page_name);
		
		$data = array(
			'id'		=> $page_id,
			'fk_site'	=> $this->site_id,		
		);
		$db->delete('pages', $data);
		
		die('Page deleted!!'); # success			
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
			
			if( empty($output['0']) )
				die('There are no tools to sort');
	
			# hash format "scope.guid_id.container.position"
			foreach($output as $hash)
			{
				$pieces	= explode('.', $hash);
				
				# Update the rows
				$guid 				= strstr($pieces['1'], '_');
				$guid 				= ltrim($guid, '_');
				$data['position']	= $pieces['3'];			
				$data['page_id']	= $page_id;
				$data['container']	= $pieces['2'];	
				if( 'global' == $pieces['0'] )
				{
					$data['page_id']	= $pieces['2'];
					$data['container']	= $pieces['2'];
				}
				$db->update('pages_tools', $data, "guid = '$guid' AND fk_site = '$this->site_id'");								
			}	
			echo 'Order Updated!';
		}
		die();
	}
	
		
# Save Page position order 
	public function save_sort()
	{
		$db = new Database;
		foreach($_GET['page'] as $position => $id)
			$db->update('pages', array('position' => "$position"), "id = '$id'"); 	
			
		die('Sort Order Saved!'); # status response	
	}


# Configure page settings	
	function settings($page_id=NULL)
	{
		valid::id_key($page_id);
		$db = new Database;

		if($_POST)
		{
			/*
			
				TODO: 
				validate page name for cases of root and subpages
			
			*/
			$label = trim($_POST['label']);
			if(empty($label) )
				die('Label is required'); #error	
			
			$page_name = trim($_POST['page_name']);
			if( empty($page_name) )
				$page_name = $label;
			
			$page_name = valid::filter_php_url($page_name);

			/* Make sure page name is unique
			 * TODO: consider adding a javascript signifer that validates
			 * the javascript validation so we can bypass server validation?? 0.o
			 */
			$page_names = $db->query("
				SELECT GROUP_CONCAT( page_name separator ',') as name_string
				FROM pages
				WHERE fk_site = '$this->site_id'
				AND id != '$page_id'
			")->current();		
			$name_array = explode(',', $page_names->name_string);		
			if( in_array($page_name, $name_array) )
				die('Page name already exists');
				
			# if new page name & page is protected update the page_config file.
			if($page_name != $_POST['old_page_name'])
			{
				yaml::edit_value($this->site_name, 'pages_config', $_POST['old_page_name'], $page_name );
			}
			
			# Update pages table
			$data = array(
				'page_name'	=> $page_name,
				'title'		=> $_POST['title'],
				'meta'		=> $_POST['meta'],
				'label'		=> $_POST['label'],
				'menu'		=> $_POST['menu'],
				'enable'	=> $_POST['enable'],
			);
			$db->update('pages', $data, "id = '$page_id' AND fk_site = '$this->site_id' "); 			

			echo 'Changes Saved!<br>Updating...'; # success				
		}
		else
		{
			$page = $db->query("
				SELECT * FROM pages
				WHERE id = '$page_id' 
				AND fk_site = '$this->site_id'
			")->current();

			if(! is_object($page) )
				die('Page not found'); # error

			$primary = new View("page/page_settings");	
			$primary->page = $page;	
			
			# Is this a subpage?
			$page_name	= $page->page_name;
			$sub_page	= '';
			$page_directories = explode('/',$page_name);
			
			if( 1 < count($page_directories) )
			{
				$page_name	=  array_pop($page_directories);
				$sub_page	= implode('/', $page_directories).'/';
			}
			$primary->page_name	= $page_name;	
			$primary->sub_page	= $sub_page;				
			
			
			/*
			 * Send all site page_names except this name, in javascript formatted array, 
			 * so the validator can check for duplicates.
			 */
			$page_names = $db->query("
				SELECT GROUP_CONCAT( CONCAT('\'',page_name,'\'') separator ',') as name_string
				FROM pages
				WHERE fk_site = '$this->site_id'
				AND id != '$page->id'
			")->current();
			$primary->page_names = $page_names->name_string;
			

			# is page protected?
			$primary->is_protected = FALSE;
			if(yaml::does_key_exist($this->site_name, 'pages_config', $page->page_name))
				$primary->is_protected = TRUE;
			
			echo $primary;
		}
		die();
	}
	
}
/* End of file page.php */