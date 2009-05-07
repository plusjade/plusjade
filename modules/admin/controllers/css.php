<?php
class Css_Controller extends Controller {

	/**
	 * Compile the tools css for each page
	 * edit css files for each tool
	 * This controller shoudl be renamed to a more specific "tool" css controller
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * Build css for the tools on the page
 * $generic_tools = The different tools on the page (non-repeats)
 * $all_tools = every tool on the page
 * (string) $all_tools = "5.5" = "tools_list_id.tool_id"
 */
	function tools($all_tools=NULL)
	{
		$db				= New Database;
		$all_tools		= explode('-', $all_tools);
		$tools			= $db->query('SELECT * FROM tools_list');		
		$tools_list 	= array();
		$unique_tools	= array();
		$all_tool_instances = array();
		
		# Build assoc array for all tools
		foreach ($tools as $tool)
			$tools_list[$tool->id] = $tool->name;
		
		# get all unique tools
		foreach ($all_tools as $tool)
		{
			$pieces		= explode('.', $tool);
			$name_id	= (int) $pieces['0'];
			$tool_id	= ( empty($pieces['1']) ) ? '0' : (int)$pieces['1'];
			$name 		= ( empty($tools_list[$name_id]) ) ? '0' : strtolower($tools_list[$name_id]);
	
			$unique_tools[$name_id]	= $name;
			$all_tool_instances[]	= $name . '.' . $tool_id;
		}		

		ob_start();

		foreach($unique_tools as $tool)
		{
			$user_images	= url::site() . "data/$this->site_name/themes/$this->theme/modules/$tool/";	
			$user_css		= DATAPATH . "$this->site_name/themes/$this->theme/modules/$tool/stock.css";	
			$theme_tool_css	= APPPATH . "views/$this->theme/$tool/stock.css";
			$stock_tool_css	= MODPATH . "$tool/views/public_$tool/stock.css";
			$admin_css		= MODPATH . "$tool/views/edit_$tool/admin.css";

			#  Load user custom css if available.
			if ( file_exists($user_css) )
				readfile($user_css);
			else
			{	
				# Load root css for tool.
				if ( file_exists($theme_tool_css) )
					readfile($theme_tool_css);
				elseif( file_exists($stock_tool_css) )
					readfile($stock_tool_css);
			}

			# Load admin backend css if logged in
			# This might hurt the cache if pages don't change though ??
			# Disable for now ...
			if( $this->client->logged_in() )
			{
				if ( file_exists($admin_css) )
				{
					echo "\n /* --- BACKEND - $tool --- */ \n";
					readfile($admin_css);
				}
			}		
		}

		/* 
		 * Load custom uniqe tool css if exists
		 * array $all_tool_instances = "tools_list_id.tool_id"
		 *
		 */
		foreach($all_tool_instances as $tool)
		{
			$pieces		= explode('.', $tool);
			$name		= $pieces['0'];
			$tool_id	= $pieces['1'];
			$css_path	= DATAPATH . "$this->site_name/tools_css/$name/$tool_id.css";	

			if ( file_exists($css_path) )
				readfile($css_path);
		}
		
		# This is wrong FIX IT
		$image_path = "THIS_IS_WRONG/application/views/$this->theme/global/images";
		
		$contents		=  ob_get_clean();
		$keys			= '%PATH%';
		$replacements	= $image_path;

		header("Content-type: text/css");
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		echo str_replace($keys, $replacements , $contents);
		die();
	}

/*
 * Edit a custom css file associated with a tool.
 * Custom files are auto created if none exists.
 * Stored in /data/tools_css
 */
	function edit($name_id=NULL, $tool_id=NULL)
	{
		$this->client->can_edit($this->site_id);

		valid::id_key($name_id);	
		valid::id_key($tool_id);		
		
		$css_file_path = DOCROOT."data/$this->this->site_name/tools_css";
		$db = new Database;
		$tool		= $db->query("SELECT name FROM tools_list WHERE id='$name_id'")->current();
		$tool_name	= strtolower($tool->name);
		$table		= $tool_name.'s';
		
		# Overwrite old file with new file contents;
		if($_POST)
		{
			$attributes = $_POST['attributes'];	
			$db->update($table, array('attributes' => $attributes ), "id='$tool_id' AND fk_site = '$this->site_id'");
			
			echo Css::save_custom_css($tool_name, $tool_id, $_POST['contents'] );
		}
		else
		{
			$primary = new View('css/edit_single');			
			$primary->contents	= Css::get_css_file($tool_name, $tool_id);
			$primary->tool_id	= $tool_id;
			$primary->name_id	= $name_id;
			$primary->tool_name	= $tool_name;
			
			$parent = $db->query("SELECT attributes FROM $table
				WHERE id='$tool_id'
			")->current();
			$primary->attributes = $parent->attributes;
			
			echo $primary;
		}		
		die();

	}
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */