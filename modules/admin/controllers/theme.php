<?php
class Theme_Controller extends Controller {

/**
 *	Provides CRUD for theme and theme assets 
 *	
 */	 
	function __construct()
	{
		parent::__construct();
		if(! $this->client->can_edit($this->site_id) )
		{
			# hack for allowing swfupload to work in authenticated session...
			if( empty($_POST['PHPSESSID']) )
				die('Please login');
		}
	}
	
/*
 * manage theme assets via file browser
 */
	function manage()
	{
		$themes	= DATAPATH . "$this->site_name/themes";
		$files	= self::folder_contents("$themes/$this->theme", 'tools');	
		$themes	= Jdirectory::contents($themes, 'root', 'list_dir');
		
		$primary = new View('theme/index');
		$primary->is_editor = (empty($_GET['editor'])) ? FALSE : TRUE ;
		$primary->themes = $themes;
		$primary->files = $files;
		die($primary);
	}

/*
 * manage theme assets via file browser
 */
	function add_files($theme=NULL)
	{
		$primary = new View('theme/add_files');
		$primary->theme = $theme;
		die($primary);
	}	

	
/*
 * Upload files to a specific theme. Swf uploader uses this.
 */	
	public function upload($theme=NULL)
	{
		$full_path = DATAPATH . "$this->site_name/themes/$theme";
		if(!is_dir($full_path))
			die('invalid theme');

		# Do we have a file
		if(! is_uploaded_file($_FILES['Filedata']['tmp_name']) )
			die('Invalid File');
		
		# test for size restrictions?
		# ( $_FILES['Filedata']['size'] > 90000 )
		
		# NOTE:: IS THIS SECURE??
		# Work-around maintaining the session because Flash Player doesn't send the cookies
		if(isset($_POST["PHPSESSID"]))
			session_id($_POST["PHPSESSID"]);

		# sanitize the filename.
		$ext		= strrchr($_FILES['Filedata']['name'], '.');
		$filename	= str_replace($ext, '', $_FILES['Filedata']['name']);
		$ext		= strtolower($ext);
		$filename	= valid::filter_php_filename($filename).$ext;
		
		# is file allowed?
		$allowed = array(
			'.css'	=> 'css',
			'.html'	=> 'html',
			'.jpg'	=> 'jpeg',
			'.jpeg'	=> 'jpeg',
			'.png'	=> 'png',
			'.gif'	=> 'gif',
			'.tiff'	=> 'tiff',
			'.bmp'	=> 'bmp',
		);
		if(!array_key_exists($ext, $allowed))
			die('Filetype not allowed');
		
			
		if('.css' == $ext)
			$folder = 'css';
		elseif('.html' == $ext)
			$folder = 'templates';
		else
			$folder = 'images';

		# place the file.
		if(move_uploaded_file($_FILES['Filedata']['tmp_name'], "$full_path/$folder/$filename"))
			die('File uploaded');
			
		
		die('Error: File not uploaded.');
	}
	
/*
 * Show contents of a particular theme. Used for ajax calls.
 * $folder comes in data format: folder:sub-folder:sub-sub-folder
 */
	public function contents($folder=NULL)
	{
		$folder	= str_replace(':', '/', $folder);
		$dir	= DATAPATH . "$this->site_name/themes/$folder";	
		$files	= self::folder_contents($dir, 'tools');
		
		$primary = new View('theme/folder');
		$primary->files = $files;
		die($primary);
	}
/*
 * Similar to Jdirectory::contents but only gets contents of one folder/directory at a time.
 * Builds the array differently for "file browser" specific data handling.
 */ 
	 private function folder_contents($full_dir, $omit = null) 
	 { 
		$retval = array(); 	
		# add trailing slash if missing 	
		if(substr($full_dir, -1) != "/")
			$full_dir .= "/"; 	
		# open pointer to directory and read list of files 
		$d = @dir($full_dir) or die("show_dir_contents: Failed opening directory $full_dir");
		
		$stock_dir = DATAPATH . "$this->site_name/themes/";
		$short_dir = str_replace($stock_dir, '', $full_dir);
		$short_dir = str_replace('/', ':', $short_dir);
		
		while(false !== ($entry = $d->read())) 
		{
			# skip hidden files and any omissions
			if( ($entry[0] == "." ) OR (! empty($omit) AND $entry == "$omit" ) )
				continue;
				
			if(is_dir("$full_dir$entry")) 
				$retval["$short_dir$entry"] = "folder|$entry"; 
			else if( is_readable("$full_dir$entry") && $entry != 'Thumbs.db' ) 
				$retval["$short_dir$entry"] = "file|$entry";	 
		 } 
		 $d->close(); 
		 asort($retval);
		 return $retval; 
	}
		
/*
 * delete a file from a particular theme.
 * TODO: clean this class up.
 */
	function delete_browser($path=NULL)
	{
		if(NULL == $path)
			die('No path sent');
		
		$path		= str_replace(':', '/', $path);
		$full_path	= DATAPATH . "$this->site_name/themes/$path";	
		
		if(is_dir($full_path))
			die('Cannot delete directories');
		
		if(file_exists($full_path))
			if(unlink($full_path))
				die('File deleted');

		die('Could not delete the file.');
	}
	
/*
 * delete a theme folder from themes folder
 * TODO: clean this class up.
 */
	function delete_theme($theme=NULL)
	{
		if(NULL == $theme)
			die('No path sent');
		
		$full_path	= DATAPATH . "$this->site_name/themes/$theme";	
		
		if(is_dir($full_path))
			if(Jdirectory::remove($full_path))
				die("'$theme' deleted");

		die('Could not delete theme folder');
	}

/*
 * delete a theme folder from themes folder
 * TODO: clean this class up.
 */
	function add_theme()
	{
		if(empty($_POST['theme']))
			die('No theme sent');
			
		$theme		= valid::filter_php_url($_POST['theme']);
		$full_path	= DATAPATH . "$this->site_name/themes/$theme";	
		
		if(is_dir($full_path))
			die('Theme already exists');
		
		$clone = APPPATH . 'views/_clone';
		
		if(Jdirectory::copy($clone, $full_path))
			die($theme); # need this to update the DOM

		die('Could not add theme.');
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
 * edit this themes global templates
 */
	function templates()
	{		
		$primary	= new View('theme/templates');
		$custom_data_path	= Assets::data_path_theme('templates');		
		$templates_url		= Assets::url_path_theme('templates');		
		$primary->templates = Jdirectory::contents($custom_data_path, 'root', TRUE);
		die($primary);
	}
	
/*
 * load a file from theme repo into the textarea editor
 * works with self::stylesheets and self::templates
 */	
	function load($folder=NULL, $filename=NULL)
	{
		if('templates' != $folder AND 'css' != $folder)
			die('invalid folder');
			
		$path = Assets::data_path_theme("$folder/$filename");
		if(!file_exists($path))
			die('invalid file');
		
		if('css' == $folder)
			$url = Assets::url_path_theme('images');
		else
			die(file_get_contents($path));
			
		echo str_replace('../images', $url , file_get_contents($path));	
		die();
	
	}
/*
 * save a file from the theme repo
 */
	function save($folder=NULL, $file=NULL)
	{
		if('templates' != $folder AND 'css' != $folder)
			die('invalid folder');
			
		$file		= valid::filter_php_filename($file) . $ext;
		$ext		= ('templates' == $folder) ? '.html' : '.css';
		$theme_path	= Assets::data_path_theme($folder);
		
		if(! file_exists("$theme_path/$file") AND empty($_POST['contents']) )
			die('Invalid File');	

		if($_POST)
		{
			# replace any user-tokens
			if('css' == $folder)
				$contents = str_replace('%IMAGES%', Assets::url_path_theme('images'), $_POST['contents']);	
			else
				$contents = str_replace('%IMAGES%', Assets::url_path_theme('images'), $_POST['contents']);	
				
			if(file_put_contents("$theme_path/$file", $contents))
				die('File updated.'); # Success	
			
			die('Unable to save changes'); # Error
		}
	}

	
/*
 * delete a file from the theme repo
 * should only be css for now. 
 */
	function delete($folder=NULL, $file=NULL)
	{
		if('templates' != $folder AND 'css' != $folder)
			die('invalid folder');
			
		#TODO validate the file extion to be only .css or .html.
		
		# CAUTION TODO: these names need to be filtered !!!
		$theme_path	= Assets::data_path_theme($folder);
		
		if(! file_exists("$theme_path/$file"))
			die('Invalid File');	

		if(unlink("$theme_path/$file"))
			die('File deleted.'); # Success	
			
		die('Unable to delete file.'); # Error
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
		$image->save( Assets::dir_path("banners/$image_name") );

		unlink($filename);
		die("$image_name"); # needed to add to DOM via ajax
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
		if(empty($_POST['banner']))
			die('nothing sent');
			
		$img_path = Assets::dir_path("banners/{$_POST['banner']}");
		if(file_exists($img_path))
			if(unlink($img_path))
				die('Banner deleted.');

		die('Unable to delete banner'); 
	}
	
} # end 