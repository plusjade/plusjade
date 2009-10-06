<?php defined('SYSPATH') or die('No direct script access.');

/* 
 * Files refer to a repository for misc. assets uploaded by the user.
 * Main Gatekeeper for Assets other than themes.
 * Location: @ /public/_data/$this->site_name/assets.
 * This class basically does CRUD relative to this folder.
 */	 
 
class Files_Controller extends Controller {

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

	
	
	public $image_types = array(
		'.jpg'	=> 'jpeg',
		'.jpeg'	=> 'jpeg',
		'.png'	=> 'png',
		'.gif'	=> 'gif',
		'.tiff'	=> 'tiff',
		'.bmp'	=> 'bmp',
		);
/*
 * index view for file browser.
 * everything is loaded within this view.
 */
	public function index()
	{
		$files	= self::folder_contents($this->assets->assets_dir(), '_tmb');
		
		$view = new View('files/index');
		$view->mode = (empty($_GET['mode'])) ? '' : $_GET['mode'] ;
		$view->files = $files;
		die($view);
	}
	
/*
 * Show contents of a particular folder. Used for ajax calls.
 * $folder comes in data format: folder:sub-folder:sub-sub-folder
 */
	public function contents($folder=NULL)
	{
		$folder	= str_replace(':', '/', $folder);	
		$files	= self::folder_contents($this->assets->assets_dir($folder), '_tmb');
		
		$view = new View('files/folder');
		$view->files = $files;
		$view->mode = (empty($_GET['mode'])) ? '' : $_GET['mode'] ;
		die($view);
	}

/*
 * View to Add files to a specified folder in website asset repo.
 * This outputs the view. self::upload handles the processing.
 */	
	public function add_files($directory=NULL)
	{
		$real_dir	= str_replace(':', '/', $directory);
		$full_path	= $this->assets->assets_dir($real_dir);
		if(!is_dir($full_path))
			die('invalid directory');

		$view = new View('files/add_files');
		$view->directory = $directory;
		die($view);
	}

/*
 * Upload files to specified folder. Swf uploader uses this.
 */	
	public function upload($directory=NULL)
	{
		$directory = str_replace(':', '/', $directory);
		$full_path = $this->assets->assets_dir($directory);
		if(!is_dir($full_path))
			die('invalid directory');

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
			if(!is_dir("$full_path/_tmb"))
				mkdir("$full_path/_tmb");

			# initiliaze image as library object.	
			$image	= new Image($_FILES['Filedata']['tmp_name']);			
			$width	= $image->__get('width');
			$height	= $image->__get('height');


			# Make square thumbnails (always need 75's for plusjade system)
				# are we instructed to make any more thumbnails?
			if(isset($_POST['thumb']))
				array_push($_POST['thumb'], 75);
			else
				$_POST['thumb'] = array(75);

			foreach($_POST['thumb'] as $size)
			{
				if(!is_dir("$full_path/_tmb/$size"))
					mkdir("$full_path/_tmb/$size");				
				if($width > $height)
					$image->resize($size, $size, Image::HEIGHT)->crop($size, $size);
				else
					$image->resize($size, $size, Image::WIDTH)->crop($size, $size);
				
				$image->save("$full_path/_tmb/$size/$filename");
			}
			
			# save an optimized original version.
			# todo. save any apsurdly huge image to a max dimension.
			# if the file is over 300kb its likely not optimized.
			if(300000 < $_FILES['Filedata']['size'])
				$image->quality(75)->save("$full_path/$filename");
			else
			{
				move_uploaded_file($_FILES['Filedata']['tmp_name'], "$full_path/$filename");			
				# $image->save("$full_path/$filename");
			}
		}
		else
		{
			# save the non image file.
			# turn php pages to text.
			str_replace('php', '', $ext, $match);
			if(0 < $match)
				move_uploaded_file($_FILES['Filedata']['tmp_name'], "$full_path/$filename.txt");	
			else
				move_uploaded_file($_FILES['Filedata']['tmp_name'], "$full_path/$filename");
		}

		die('File uploaded');
	}
	
	
/*
 * add new folder to a specific directory
 * default folder is root: data/site_name/assets
 */
	public function add_folder($directory=NULL)
	{
		$directory = str_replace(':', '/', $directory);
		$full_path = $this->assets->assets_dir($directory);
		
		if(!is_dir($full_path))
			die('invalid directory');
			
		if($_POST)
		{
			$folder_name = trim($_POST['folder_name']);
			$folder_name = valid::filter_php_url($_POST['folder_name']);
			
			if(is_dir("$full_path/$folder_name"))
				die('folder already exists');
				
			if(mkdir("$full_path/$folder_name"))
				die('Folder created.');
				
			die('Could not create folder.');
		}
			
		$view = new View('files/add_folder');
		$view->directory = $directory;
		$view->filter = '';
		die($view);
	}

	
/*
 * delete a folder or file from the asset repository.
 */
	public function delete($path_string=NULL)
	{
		if(NULL == $path_string)
			die('No path sent');
		
		$path_string = str_replace(':', '/', $path_string, $matches);
		$filename	= (0 < $matches) ? substr(strrchr($path_string, '/'), 1) : $path_string;
		$ext 		= (strrchr($filename, '.'));
		$full_path	= $this->assets->assets_dir($path_string);
		
		if(is_dir($full_path))
		{	
			if('_tmb' == $filename)
				die('Cannot delete this folder.');
			if(Jdirectory::remove($full_path))
				die('Folder deleted');	
			die('Could not delete the folder.');
		}
		elseif(file_exists($full_path))
		{
			if(unlink($full_path))
			{
				# is this an image?
				if(array_key_exists($ext, $this->image_types))
				{
					# get and recurse the _tmb directory.
					$thumb_path = str_replace($filename, '', $full_path).'_tmb/';
					$thumb_dirs = Jdirectory::contents($thumb_path, 'root', 'list_dir');
					foreach($thumb_dirs as $dir)
						if(file_exists("$thumb_path/$dir/$filename"))
							unlink("$thumb_path/$dir/$filename");				
				}
				die('File deleted');
			}	
			die('Could not delete the file.');
		}
		die('invalid path');
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
			
		$image_types = array(
			'jpg'	=> 'jpeg',
			'jpeg'	=> 'jpeg',
			'png'	=> 'png',
			'gif'	=> 'gif',
			'tiff'	=> 'tiff',
			'bmp'	=> 'bmp',
		);
		$ext = strtolower(substr(strrchr($filename, "."), 1));
		if(array_key_exists($ext, $image_types))
		{
			$type = $image_types[$ext];
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

