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
 * 
 */
	function index()
	{		
		$themes	= Jdirectory::contents($this->assets->themes_dir(), 'root', 'list_dir', 'safe_mode');
		
		$primary = new View('theme/index');
		$primary->themes = $themes;
		
		if('safe_mode' != $this->theme)
		{
			$files	= self::folder_contents($this->assets->themes_dir($this->theme), 'tools');	
			$primary->files = $files;
		}
		
		die($primary);
	}


/*
 * Show contents of a particular theme. Used for ajax calls.
 * $folder comes in data format: folder:sub-folder:sub-sub-folder
 */
	public function contents($folder=NULL)
	{
		$dirs = explode(':', $folder);
		if('safe_mode' == $dirs['0'])
			die('cannot load safe-mode files for editing.');
			
		$folder	= str_replace(':', '/', $folder);
		$files	= self::folder_contents($this->assets->themes_dir($folder), 'tools');
		
		$primary = new View('theme/folder');
		$primary->files = $files;
		die($primary);
	}
	
/*
 * display the view to add files to a theme
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
		$theme_path = $this->assets->themes_dir($theme);
		if(!is_dir($theme_path))
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
		
		
		# place the file.
		
		if('.css' == $ext)
			die(self::parse_and_save(
				$theme,
				'css',
				$filename,
				file_get_contents($_FILES['Filedata']['tmp_name'])
			));
		elseif('.html' == $ext)
			die(self::parse_and_save(
				$theme,
				'templates',
				$filename,
				file_get_contents($_FILES['Filedata']['tmp_name'])
			));
		else
			if(move_uploaded_file($_FILES['Filedata']['tmp_name'], "$theme_path/images/$filename"))
				die('Image uploaded');
	
		die('Error: File not uploaded.');
	}
	
/*
 * add a new blank theme folder structure to theme repo.
 */
	function add_theme()
	{
		if(empty($_POST['theme']) OR 'safe_mode' == trim($_POST['theme']))
			die('No theme sent');
			
		$theme		= valid::filter_php_url($_POST['theme']);
		$full_path	= $this->assets->themes_dir($theme);	
		
		if(is_dir($full_path))
			die('Theme already exists');
		
		if(is_dir(DOCROOT . '_assets/themes/_clone'))
			if(Jdirectory::copy(DOCROOT . '_assets/themes/_clone', $full_path))
				die($theme); # need this to update the DOM

		die('Could not add theme.');
	}
	
	
	
/*
 * edit active theme's global templates/stylesheets
 */
	function edit($type=NULL)
	{
		if('safe_mode' == $this->theme)
			die('You are in safe-mode. Cannot edit this theme.');
			
		$allowed = array('stylesheets', 'templates');
		if(! in_array($type, $allowed) )
			die('invalid type');
		
		$primary	= new View("theme/$type");
		$theme_path	= $this->assets->themes_dir($this->theme);
		
		switch($type)
		{
			case 'templates':
				$primary->templates = Jdirectory::contents("$theme_path/templates", 'root');
				$primary->contents	= (file_exists("$theme_path/templates/master.html")) ?
					file_get_contents("$theme_path/templates/master.html") : 'master.html not found' ;
				break;
				
			case 'stylesheets':
				$primary->css_files = Jdirectory::contents("$theme_path/css");
				$primary->contents	= (file_exists("$theme_path/css/global.css")) ?
					file_get_contents("$theme_path/css/global.css") : 'global.css not found' ;			
				break;
				
			default:
				die('Invalid type');
		}
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
			
		$path = $this->assets->themes_dir("$this->theme/$folder/$filename");
		
		if(file_exists($path))
			die(readfile($path));
			
		die('invalid file');
	}
	
	
/*
 * save a file to the active theme repo
 * only used @ edit views so should only be active theme.
 */
	function save($folder=NULL, $file=NULL)
	{
		if('templates' != $folder AND 'css' != $folder)
			die('invalid folder');
			
		$ext	= ('templates' == $folder) ? '.html' : '.css';
		$file	= valid::filter_php_filename($file) . '%';
		$file	= str_ireplace("$ext%", '', $file) . $ext;
		
		if(! file_exists($this->assets->themes_dir("$this->theme/$folder").$file) AND empty($_POST['contents']) )
			die('Invalid File');	

		if($_POST)
			die(self::parse_and_save($this->theme, $folder, $file, $_POST['contents']));
	}

	
/*
 * delete a file or folder from the theme repo
 */
	function delete($path=NULL)
	{
		if(NULL == $path)
			die('No path sent');

		$dirs = explode(':', $path);
		if('safe_mode' == $dirs['0'])
			die('cannot delete safe-mode files.');
			
		$path		= str_replace(':','/', $path, $count);
		$full_path	= $this->assets->themes_dir($path);
		
		# if trying to delete a theme folder
		if(0 == $count)
		{
			if($path == $this->theme)
				die('Cannot delete active theme.');
				
			if(is_dir($full_path))				
				if(Jdirectory::remove($full_path))
					die("'$path' deleted");
			
			die('Unable to delete this theme.');
		}
		
		if(is_dir($full_path))
			die('cannot delete directories');
			
		if(! file_exists($full_path) )
			die('Invalid File');	

		if(unlink($full_path))
			die('File deleted.'); # Success	
			
		die('Unable to delete file.');
	}	

	
/*
 * Change the site's theme
 */
	function change()
	{
		if(! empty($_POST['theme']))
		{
			$new_theme	= $_POST['theme'];
			
			$source	= DOCROOT . "_assets/themes/$new_theme";
			$dest	= $this->assets->themes_dir($new_theme);			
	
			# If particular theme directory does not yet exist, create it.
			if(! is_dir($dest) )
			{
				if(!is_dir($source))
					die('This theme does not exist.');
					
				if(! Jdirectory::copy($source, $dest) )
					die('Unable to change theme.'); # Error
				
				/*
					once theme is created, parse for tokens and save updates.
					* as of now the templates don't need to be parsed since 
					they only look for %FILES% but future functionality may warrent parsing.
				*/
				#$folders = array('css' ,'templates');
				#foreach($folders as $type)
				$type = 'css';
				if(is_dir("$dest/$type"))
				{
					$dir = dir("$dest/$type"); 
					while($file = $dir->read())
						if('.' != $file && '..' != $file)
							self::parse_and_save(
								$new_theme,
								$type,
								$file,
								file_get_contents("$dest/$type/$file")
							);
					$dir->close(); 
				}
			}

			$site = ORM::factory('site', $this->site_id);
			$site->theme = $new_theme;
			$site->save();	
			
			# on success: should clear the cache and reload the page.
			if(yaml::edit_site_value($this->site_name, 'site_config', 'theme', $new_theme ))
				die('TRUE'); # tells js to reload the page.
			
			die('There was a problem changing the theme.');
		}

		$primary = new View('theme/change');
		$primary->themes = ORM::factory('theme')->where('enabled', 'yes')->find_all();
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
		$dir_path	= $this->assets->assets_dir('banners');
		$url_path	= $this->assets->assets_url('banners');	
		
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
		$image->save( $this->assets->assets_dir("banners/$image_name") );

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
			$site = ORM::factory('site', $this->site_id);
			$site->banner = $_POST['banner'];
			$site->save();

			if(yaml::edit_site_value($this->site_name, 'site_config', 'banner', $_POST['banner']))
				die('Banner changed.'); # success		
		}
		die('Nothing sent.');
	}
	
/*
 * Delete a logo 
 * NOTE: deleting file repo assets should already having a handler for this.
 */ 
	function delete_logo()
	{
		if(empty($_POST['banner']))
			die('nothing sent');
			
		$img_path = $this->assets->assets_dir("banners/{$_POST['banner']}");
		if(file_exists($img_path))
			if(unlink($img_path))
				die('Banner deleted.');

		die('Unable to delete banner'); 
	}

/*
 * Parses the file for theme tokens and saves the result to disk.
 * setting the site name means we can parse themes relative to any site.
 * this is useful when adding themes to newly-created sites (@ plusjade).
 */ 
	private function parse_and_save($theme, $type, $filename, $contents, $site_name=FALSE)
	{
		if(!$site_name)
		{
			$images	= $this->assets->themes_url("$theme/images");
			$files	= $this->assets->assets_url();
			$dest	= $this->assets->themes_dir("$theme/$type/$filename");
		}
		else
		{
			$images	= "/_data/$site_name/themes/$theme/images";
			$files	= "_data/$site_name/assets";
			$dest	= DATAPATH . "$site_name/themes/$theme/$type/$filename";
		}
		
		if('css' == $type)
			$filtered = str_replace(
				array('../images', '%IMAGES%', '%FILES%'),
				array($images, $images, $files ),
				$contents
			);	
		else # is html
			$filtered = str_replace(
				'%FILES%',
				$files,
				$contents
			);	
		
		if(file_put_contents($dest, $filtered))
			return $filename; # name is needed for DOM update
		
		return 'Could not update file.';
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
			
		$stock_dir = $this->assets->themes_dir(). '/';
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
 * Change the site's theme
 */
	public static function _new_website_theme($site_name, $theme)
	{
		$source	= DOCROOT . "_assets/themes/$theme";
		$dest	= DATAPATH . "$site_name/themes/$theme";			

		if(!is_dir($source))
			die('This theme does not exist.');
			
		if(!Jdirectory::copy($source, $dest))
			die('Unable to create theme.'); # Error
		
		# Parse tokens.
		$type = 'css';
		if(is_dir("$dest/$type"))
		{
			$dir = dir("$dest/$type"); 
			while($file = $dir->read())
				if('.' != $file && '..' != $file)
					self::new_parse_and_save(
						$theme,
						$type,
						$file,
						file_get_contents("$dest/$type/$file"),
						$site_name
					);
			$dir->close(); 
		}

		return TRUE;
	}
	
/*
 * Parses the file for theme tokens and saves the result to disk.
 * setting the site name means we can parse themes relative to any site.
 * this is useful when adding themes to newly-created sites (@ plusjade).
 */ 
	private static function new_parse_and_save($theme, $type, $filename, $contents, $site_name)
	{
		$images	= "/_data/$site_name/themes/$theme/images";
		$files	= "_data/$site_name/assets";
		$dest	= DATAPATH . "$site_name/themes/$theme/$type/$filename";

		if('css' == $type)
			$filtered = str_replace(
				array('../images', '%IMAGES%', '%FILES%'),
				array($images, $images, $files ),
				$contents
			);	
		else # is html
			$filtered = str_replace(
				'%FILES%',
				$files,
				$contents
			);	
		
		if(file_put_contents($dest, $filtered))
			return $filename; # name is needed for DOM update
		
		return 'Could not update file.';
	}
	
	
} # end 