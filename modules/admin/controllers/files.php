<?php defined('SYSPATH') or die('No direct script access.');

/* 
 * Files refer to a repository for misc. assets uploaded by the user.
 * Main Gatekeeper for Assets other than themes.
 * Location: @ /public/_data/$this->site_name/assets.
 * This class basically does CRUD relative to this folder.
 */	 
 
class Files_Controller extends Controller {

	public $image_types = array(
		'.jpg'	=> 'jpeg',
		'.jpeg'	=> 'jpeg',
		'.png'	=> 'png',
		'.gif'	=> 'gif',
		'.tiff'	=> 'tiff',
		'.bmp'	=> 'bmp',
		);		
		
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
 * index view for file browser.
 * everything is loaded within this view.
 */
	public function index()
	{
		$view = new View('files/index');
		$view->image_types = $this->image_types;
		$view->mode = (empty($_GET['mode'])) ? '' : $_GET['mode'] ;
		$view->files = self::folder_contents($this->assets->assets_dir(), '_tmb');
		die($view);
	}
	
/*
 * Show contents of a particular folder. Used for ajax calls.
 * $folder comes in data format: folder:sub-folder:sub-sub-folder
 */
	public function contents()
	{
		if(!isset($_GET['dir']))
			$_GET['dir'] = '';
		$dir = self::validate_dir($_GET['dir']);
		
		#die($dir);
		$view = new View('files/folder');
		$view->image_types = $this->image_types;
		$view->files = self::folder_contents($dir, '_tmb');
		$view->mode = (empty($_GET['mode'])) ? '' : $_GET['mode'] ;
		die($view);
	}

/*
 * View to Add files to a specified folder in website asset repo.
 * This outputs the view. self::upload handles the processing.
 */	
	public function add_files()
	{
		if(!isset($_GET['dir']))
			$_GET['dir'] = '';
		$short_dir = str_replace(':', '/', $_GET['dir']);
		$dir = self::validate_dir($_GET['dir']);
		
		$view = new View('files/add_files');
		$view->short_url_dir = $_GET['dir'];
		die($view);
	}

/*
 * Upload files to specified folder. Swf uploader uses this.
 */	
	public function upload()
	{	
		if(!isset($_GET['dir']))
			$_GET['dir'] = '';
		$dir = self::validate_dir($_GET['dir']);
		
		# Do we have a file
		if(!is_uploaded_file($_FILES['Filedata']['tmp_name']))
			die('Invalid File');
			
		# test for size restrictions?
		# ( $_FILES['Filedata']['size'] > 90000 )
		
		# NOTE:: IS THIS SECURE??
		# Work-around maintaining the session because Flash Player doesn't send the cookies
		if(isset($_POST["PHPSESSID"]))
			session_id($_POST["PHPSESSID"]);

		# sanitize the filename.
		$ext		= strrchr($_FILES['Filedata']['name'], '.');
		$ext		= strtolower($ext);
		$filename	= str_replace($ext, '', $_FILES['Filedata']['name']);
		$filename	= valid::filter_php_filename($filename).$ext;
		

		# create thumbnails for images.
		if(array_key_exists($ext, $this->image_types))
		{
			# does the thumb dir exist?
			if(!is_dir("$dir/_tmb"))
				mkdir("$dir/_tmb");

			# initiliaze image as library object.	
			$image	= new Image($_FILES['Filedata']['tmp_name']);			
			$width	= $image->__get('width');
			$height	= $image->__get('height');


			# Make square thumbnails (always need 100's for plusjade system)
				# are we instructed to make any more thumbnails?
			if(isset($_POST['thumb']))
				array_push($_POST['thumb'], 100);
			else
				$_POST['thumb'] = array(100);

			foreach($_POST['thumb'] as $size)
			{
				if(!is_dir("$dir/_tmb/$size"))
					mkdir("$dir/_tmb/$size");				
				if($width > $height)
					$image->resize($size, $size, Image::HEIGHT)->crop($size, $size);
				else
					$image->resize($size, $size, Image::WIDTH)->crop($size, $size);
				
				$image->save("$dir/_tmb/$size/$filename");
			}
			
			# save an optimized original version.
			# todo. save any apsurdly huge image to a max dimension.
			# if the file is over 300kb its likely not optimized.
			if(300000 < $_FILES['Filedata']['size'])
				$image->quality(75)->save("$dir/$filename");
			else
			{
				move_uploaded_file($_FILES['Filedata']['tmp_name'], "$dir/$filename");			
				# $image->save("$dir/$filename");
			}
		}
		else
		{
			# save the non image file.
			# turn php pages to text.
			str_replace('php', '', $ext, $match);
			if(0 < $match)
				move_uploaded_file($_FILES['Filedata']['tmp_name'], "$dir/$filename.txt");	
			else
				move_uploaded_file($_FILES['Filedata']['tmp_name'], "$dir/$filename");
		}

		die('File uploaded');
	}
	
	
/*
 * add new folder to a specific directory
 * default folder is root: data/site_name/assets
 */
	public function add_folder()
	{
		if(!isset($_GET['dir']))
			$_GET['dir'] = '';
		$short_dir = str_replace(':', '/', $_GET['dir']);
		$dir = self::validate_dir($_GET['dir']);

		if($_POST)
		{
			$folder_name = trim($_POST['folder_name']);
			$folder_name = valid::filter_php_url($folder_name);
			
			if(is_dir("$dir/$folder_name"))
				die('folder already exists');
				
			if(mkdir("$dir/$folder_name"))
				die('Folder created.');
				
			die('Could not create folder.');
		}
			
		$view = new View('files/add_folder');
		$view->short_dir = $short_dir;
		$view->short_url_dir = $_GET['dir'];
		$view->filter = '';
		die($view);
	}

/*
 * move assets within the asset repository.
 */
	public function move()
	{
		if(!isset($_GET['dir']))
			$_GET['dir'] = '';
		$new_dir = self::validate_dir($_GET['dir']);
		
		# old dir
		if(!isset($_POST['path']))
			die('no old dir');				
		$old_dir = self::validate_dir($_POST['path']);
		
		# json assets
		if(empty($_POST['json']))
			die('nothing sent');			
		$json = self::validate_json($_POST['json']);

		# loop through the assets	
		foreach($json as $asset)
		{		
			rename("$old_dir/$asset->name", "$new_dir/$asset->name");
			
			# only if this is an image do we try to move the thumbs.
			if(!is_dir("$old_dir/$asset->name"))
			{	
				# is this an image?
				$ext = (strrchr($asset->name, '.'));
				
				if(!empty($ext) AND array_key_exists($ext, $this->image_types))
				{
					if(!is_dir("$new_dir/_tmb"))
						mkdir("$new_dir/_tmb");
						
					# get and recurse the old _tmb directory.
					$thumb_dirs = Jdirectory::contents("$old_dir/_tmb/", 'root', 'list_dir');
					foreach($thumb_dirs as $dir)	
						if(file_exists("$old_dir/_tmb/$dir/$asset->name"))
						{
							if(!is_dir("$new_dir/_tmb/$dir"))
								mkdir("$new_dir/_tmb/$dir");	
							
							rename("$old_dir/_tmb/$dir/$asset->name", "$new_dir/_tmb/$dir/$asset->name");				
						}
				
				}
			}
		}
		die('Assets Moved.');
	}


/*
 * delete assets from the asset repository.
 */
	public function delete()
	{
		if(!isset($_GET['dir']))
			$_GET['dir'] = '';
		$dir = self::validate_dir($_GET['dir']);
		
		if(empty($_POST['json']))
			die('nothing sent');			
		$json = self::validate_json($_POST['json']);
			
		foreach($json as $asset)
		{
 			$ext = (strrchr($asset->name, '.'));
			$full_path = "$dir/$asset->name";
			if(is_dir($full_path))
			{	
				if('_tmb' == $asset->name)
					die('Cannot delete this folder.');
				
				Jdirectory::remove($full_path);
			}
			elseif(file_exists($full_path))
			{
				if(unlink($full_path))
					if(array_key_exists($ext, $this->image_types))
					{			
						# get and recurse the _tmb directory.
						$thumb_dirs = Jdirectory::contents("$dir/_tmb/", 'root', 'list_dir');
						foreach($thumb_dirs as $tmb_dir)
							if(file_exists("$dir/_tmb/$tmb_dir/$asset->name"))
								unlink("$dir/_tmb/$tmb_dir/$asset->name");				
					}
			}
		}
		die('Assets deleted');
	}


	
/*
 * create thumbs for images from the asset repository.
 */
	public function thumbs()
	{
		if(!isset($_GET['dir']))
			$_GET['dir'] = '';
		$dir = self::validate_dir($_GET['dir']);

		# sizes array
		if(empty($_POST['sizes']) OR !is_array($_POST['sizes']))
			die('invalid sizes');
		
		# json assets array
		if(empty($_POST['json']))
			die('nothing sent');
		$json = self::validate_json($_POST['json']);

		
		# run through the sent assets and generate thumbs.
		foreach($json as $asset)
		{
 			$ext = (strrchr($asset->name, '.'));

			if(is_dir("$dir/$asset->name") AND '_tmb' != $asset->name)
			{	
				self::recurse_dir_thumbs("$dir/$asset->name", 'root', true, $_POST['sizes']);
			}
			elseif(file_exists("$dir/$asset->name") AND array_key_exists($ext, $this->image_types))
			{	
				self::generate_thumbs($dir, $asset->name, $_POST['sizes']);
			}
		}
		die('Thumbnails generated');
	}

/*
 *
 */
	private function recurse_dir_thumbs($dir, $parent = 'root', $recurse=false, $sizes, $omit = '_tmb') 
	{ 
		if(substr($dir, -1) != "/")
			$dir .= "/"; # add trailing slash if missing 	 	
		
		# open pointer to directory and read list of files 
		$d = @dir($dir) or die("Jdirectory::contents: Failed opening directory $dir for reading");
		
		while(false !== ($entry = $d->read())) 
		{ 
			# skip hidden files and any omissions
			if( ($entry[0] == "." ) OR (! empty($omit) AND $entry == "$omit" ) )
				continue;
				
			if(is_dir("$dir$entry")) 
			{
				if(TRUE == $recurse && is_readable("$dir$entry/"))
					self::recurse_dir_thumbs("$dir$entry/", $entry, true, $sizes, '_tmb');
			} 
			elseif(is_readable("$dir$entry") && $entry != 'Thumbs.db')
			{	
				# is this file is an image
				$ext = (strrchr($entry, '.'));
				if(array_key_exists($ext, $this->image_types))
					self::generate_thumbs($dir, $entry, $sizes);
			}
		} 
		$d->close(); 
		return 'done';
	}

	
/*
 * actual handler for generating thumbnails based on given sizes array
	$dir = directory to file
	$filename = name of the file
	$sizes = array of sizes to be generated.
 */
	private function generate_thumbs($dir, $filename, $sizes)
	{
		# does the thumb dir exist?
		if(!is_dir("$dir/_tmb"))
			mkdir("$dir/_tmb");

		# initiliaze image as library object.	
		$image	= new Image("$dir/$filename");			
		$width	= $image->__get('width');
		$height	= $image->__get('height');
		
		foreach($sizes as $size)
		{
			#if($size != 100 AND $size > $width AND $size > $height)
				#continue; 
			if(file_exists("$dir/_tmb/$size/$filename"))
				continue;
			if(!is_dir("$dir/_tmb/$size"))
				mkdir("$dir/_tmb/$size");
			
			if($width > $height)
				$image->resize($size, $size, Image::HEIGHT)->crop($size, $size)->quality(90);
			else
				$image->resize($size, $size, Image::WIDTH)->crop($size, $size)->quality(90);
			
			$image->save("$dir/_tmb/$size/$filename");
		}	
		return 'success';
	}
		
	
/*
 * validates the GET url being sent from most of the ajax interaction urls
 */	
	private function validate_dir($dir=NULL)
	{
		$dir = str_replace(':', '/', $dir);
		valid::file_dir($dir);
		$dir = $this->assets->assets_dir($dir);	
		
		if(!is_dir($dir))
			die('invalid dir');
		
		return $dir;	
	}

/*
 * validate that the json string is properly formed for file hanling.
 */
	private static function validate_json($json)
	{		
		$json = json_decode($json);		
		if(NULL === $json OR !is_array($json))
			die('invalid json');
		
		return $json;
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
		
		$stock_dir = $this->assets->assets_dir();
		$short_dir = str_replace("$stock_dir/", '', $full_dir);
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


/* CURRENTLY NOT USING --- 
 * Used to give cleaner urls to a website's asset folder.
 * This method can be accessed via site.com/files/<path>
 * I'm thinking we should try to avoid displaying images associated with tools
 * in this way because of the increased overhead.
 * 2 Main reasons why we should use this:
		1. Custom Handle the displaying/downloading actions of various types of files.
		2. Create nicer looking urls to asset folder.
		
	if we don't mind these 2 reasons, it is much easier to access the file directy
	especially for images!
	
	To change url and directory paths always use ASSETS library.
 */
	public function _output($url_array)
	{
		$filename = array_pop($url_array);
		if(empty($filename) OR 'files' == $filename)
			die('remember to 404 not found');

		$parsed_url = $url_array;
		foreach($url_array as $key => $segment)
			if(empty($segment) OR 'files' == $segment)
				unset($parsed_url[$key]);
	
		$dir_path = '';
		if (0 < count($parsed_url))
			$dir_path = implode('/', $parsed_url) . '/';
		
		$dir_path = $this->assets->assets_dir("$dir_path$filename");
	
		if(!file_exists($dir_path))
			die('remember to do 404 not found');

		$ext = strtolower(substr(strrchr($filename, "."), 1));
		if(array_key_exists($ext, $this->image_types))
		{
			$type = $this->image_types[$ext];
			header("Content-Type: image/$type");
			readfile($dir_path);
		}
		else
		{
			# TODO: intelligently parse the extension so we can do type-specific actions.
			header("Content-Type: application/$ext");
			header("Content-Disposition: attachment; filename=$filename");
			readfile($dir_path);				
		}
	}
	
} /* End of file /modules/admin/files.php */

