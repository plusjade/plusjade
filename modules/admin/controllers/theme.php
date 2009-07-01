<?php
class Theme_Controller extends Controller {

/**
 *	Provides CRUD for theme and theme assets 
 *	
 */	 
	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}

/*
 * edit this themes global templates
 */
	function manage()
	{
		$primary	= new View('theme/manage');
		$theme_path	= DATAPATH . "$this->site_name/themes";		
		$primary->themes = Jdirectory::contents($theme_path, 'root', 'list_dir');
		die($primary);
	}
	
/*
 * edit this themes global templates
 */
	function templates()
	{
		$primary	= new View('theme/templates');

		$custom_data_path	= Assets::data_path_theme();		
		$theme_url			= Assets::url_path_theme('images');
		$contents = '';
		if(file_exists("$custom_data_path/css/global.css"))
			$contents = str_replace('../images', $theme_url , file_get_contents("$custom_data_path/css/global.css"));
		
		
		$primary->css_files = Jdirectory::contents("$custom_data_path/css");
		$primary->contents	= $contents;
		$primary->theme_files = Jdirectory::contents($custom_data_path, 'root', TRUE);
		die($primary);
	}
	
/*
 * edit this themes global stylesheets
 */
	function stylesheets()
	{
		$primary	= new View('theme/stylesheets');
		
		$custom_data_path	= Assets::data_path_theme();		
		$theme_url			= Assets::url_path_theme('images');
		$contents = '';
		if(file_exists("$custom_data_path/css/global.css"))
			$contents = str_replace('../images', $theme_url , file_get_contents("$custom_data_path/css/global.css"));
		
		$primary->css_files = Jdirectory::contents("$custom_data_path/css");
		$primary->contents	= $contents;
		die($primary);
	}


/*
 * load a filtered css file into the textarea editor
 * works with self::stylesheets
 */	
	function load($filename=NULL)
	{
		$path = Assets::data_path_theme("css/$filename");
		if(!file_exists($path))
			die('invalid file');
		
		$url = Assets::url_path_theme('images');
		echo str_replace('../images', $url , file_get_contents($path));	
		die();
	
	}
/*
 * Edit a file from the theme repo
 * should only be css for now. 
 */
	function edit($file=NULL)
	{
		# CAUTION TODO: these names need to be filtered !!!
		$fil		= str_replace(':', '/', $file);
		$theme_path	= Assets::data_path_theme();
		
		if(! file_exists("$theme_path/$file") AND empty($_POST['contents']) )
			die('Invalid File');	

		if($_POST)
		{	
			if( file_put_contents("$theme_path/$file", $_POST['contents']) )
				die('File updated.'); # Success	
			
			die('Unable to save changes'); # Error
		}

		$primary = new View('theme/edit_file');
		$primary->file_name = $file;	
		$primary->file_contents = file_get_contents("$theme_path/$file");
		die($primary);
	}

/*
 * Change the site's theme
 */
	function change()
	{
		$db = new Database;
		
		if(! empty($_POST['theme']))
		{
			$new_theme	= $_POST['theme'];
			$source		= APPPATH . "views/$new_theme";
			$dest		= DATAPATH . "$this->site_name/themes/$new_theme";				
	
			# If theme directory does not yet exist, create it.
			if(! is_dir($dest) )				
				if(! Jdirectory::copy($source, $dest) )
					die('Unable to change theme.'); # Error

			$db->update(
				'sites',
				array('theme' => $new_theme),
				"site_id = '$this->site_id'"
			);			
			
			# on success: should clear the cache and reload the page.
			if(yaml::edit_site_value($this->site_name, 'site_config', 'theme', $new_theme ))
				die('Theme Changed');
			
			die('There was a problem changing the theme.');
		}

		$primary	= new View('theme/change');
		$themes		= $db->query("SELECT * FROM themes WHERE enabled = 'yes'");
		$primary->themes = $themes;
		$primary->js_rel_command = 'reload-home';
		die($primary);	
	}

	
/*
 * View for logo configuration.
 */
	function logo()
	{		
		$primary = new View("theme/logo");
		
		# Get all uploaded Logos
		$dir_path	= Assets::dir_path('banners');
		$url_path	= Assets::url_path_direct('banners');	
		
		if(is_dir($dir_path))
		{
			$saved_banners = array();
			$dir = opendir($dir_path);
			while (TRUE == ($file = readdir($dir))) 
			{
				$key = explode('.', $file);
				$key = $key['0'];
				if (strpos($file, '.gif', 1)||strpos($file, '.jpg', 1)||strpos($file, '.png', 1) ) 
					$saved_banners[$key] = $file;
			}
			$primary->saved_banners = $saved_banners;
			$primary->img_path = $url_path;
			die($primary);
		}
		die('Banner directory does not exist.');
	}

/*
 * Add a logo to the asset repo
 */	
	function add_logo()
	{		
		if(empty($_FILES['image']['name']))
			die('Please select an image to upload.');  #error
	
		$files = new Validation($_FILES);
		$files->add_rules('image', 'upload::valid','upload::type[gif,jpg,jpeg,png]', 'upload::size[1M]');
			
		if (! $files->validate() )
			die('Unable to upload image');  #error
			
		# Temporary file name
		$filename	= upload::save('image');
		$image		= new Image($filename);			
		$ext		= $image->__get('ext');
		$image_name = basename($filename).'.'.$ext;
		
		$image->save(DOCROOT . "data/$this->site_name/assets/banners/$image_name");
	 
		# Remove the temporary file
		unlink($filename);
		
		#success message	
		die("$image_name"); 				
			
		if(! empty($_POST['enable']) )
		{
			$db		= new Database;
			$data	= array( 'banner' => $image_name );
			$db->update('sites', $data, "site_id = $this->site_id"); 
			$_SESSION['banner'] = $image_name;
			die('Logo Saved'); # success	  		
		}			
	}
	
/*
 * Change the logo
 */
	function change_logo()
	{
		if($_POST)
		{
			$db		= new Database;
			$data	= array('banner' => $_POST['banner']);
			$db->update('sites', $data, "site_id = $this->site_id"); 
			
			if(yaml::edit_site_value($this->site_name, 'site_config', 'banner', $_POST['banner']))
				die('Banner changed.'); # success		
		}
		die('Nothing sent.');
	}
	
/*
 * Delete a logo
 */ 
	function delete_logo()
	{
		if(empty($_POST['delete_logo']))
			die('nothing sent');

		$img_path = DOCROOT."data/$this->site_name/assets/banners/{$_POST['banner']}";
		if(file_exists($img_path))
			if(unlink($img_path))
				die('Image deleted!');

		die('Unable to delete image'); 
	}
	
} # end 