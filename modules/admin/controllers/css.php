<?php
class Css_Controller extends Controller {

	/**
	 *	Compile the tools css for each page
	 * 
	 *
	 *
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * Build the css for the tools on the page
 * $generic_tools = The different tools on the page (non-repeats)
 * $all_tools = every tool on the page
 */
	function tools($generic_tools=NULL, $all_tools=NULL)
	{
		$primary		= new View('css/tools');
		$db				= New Database;
		$generic_tools	= explode('-', $generic_tools);
		$all_tools		= explode('-', $all_tools);
		$tools			= $db->query('SELECT * FROM tools_list');		
		$tools_list 	= array();
		
		# Build assoc array for all tools
		foreach ($tools as $tool)
		{
			$tools_list[$tool->id] = $tool->name;
		}
		
		$primary->generic_tools	= $generic_tools;
		$primary->all_tools		= $all_tools;
		$primary->tools_list	= $tools_list;

		
		# Check if client is logged in.
		/* DOESNT WORK
		if(!$this->client->logged_in())
			$logged_in = 'no';
		else
			$logged_in = 'yes';
			
		$primary->logged_in = $logged_in;
		*/
		echo $primary;
		die();
		
	}

	function edit($id_pair=NULL)
	{
		$id_pair	= explode('.', $id_pair);
		$name_id	= $id_pair['0'];
		$tool_id	= $id_pair['1'];
		
		tool_ui::validate_id($name_id);
		tool_ui::validate_id($tool_id);
		
		$css_file_path = DOCROOT."/data/$this->site_name/tools_css";
			
		# Overwrite old file with new file contents;
		if($_POST)
		{
			if( file_put_contents($css_file_path, $_POST['contents']) )
				echo 'Page updated!'; # Success message	
			else
				echo 'Unable to save changes'; # Error message		
		}
		else
		{
			$primary = new View('css/edit_single');
			
			if(! file_exists("$css_file_path/$name_id/$tool_id.css") )
			{
				if(! is_dir("$css_file_path/$name_id") )
					mkdir("$css_file_path/$name_id");
				
				$source = MODPATH . "";
				# Get the stock file helper in the tool folder
				if( copy($source, "$css_file_path/$name_id/$tool_id.css") )
					return 'Page created!!'; #Success message
				else
					return 'Unable to copy page.'; # Error message
			}
			
			$primary->contents	= file_get_contents($css_file_path);
			$primary->identifer	= "$name_id.$tool_id";
			echo $primary;
		
		}
	
	
		die();
	}
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */