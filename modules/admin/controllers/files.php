<?php
class Files_Controller extends Controller {

/* THIS CLASS NEEDS TO BE PROTECTED 
 * sites filesystem/data directory
 *	
 */	 
	function __construct()
	{
		parent::__construct();
		if(! $this->client->can_edit($this->site_id) )
		{
			# hack for allowing swfupload to work in authenticated session...
			# leave this here to minimize this to edit_tools only.
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
		$dir	= Assets::dir_path();
		$files	= self::folder_contents($dir);
		
		$primary = new View('files/index');
		$primary->files = $files;
		die($primary);
	}
/*
 * Show contents of a particular folder. Used for ajax calls.
 * $folder comes in data format: folder:sub-folder:sub-sub-folder
 */
	public function contents($folder=NULL)
	{
		$folder	= str_replace(':', '/', $folder);
		$dir	= Assets::dir_path($folder);		
		$files	= self::folder_contents($dir);
		
		$primary = new View('files/folder');
		$primary->files = $files;
		die($primary);
	}

/*
 * Add files to a specified folder in website asset repo.
 * This outputs the view. self::upload handles the processing.
 */	
	public function add_files($directory=NULL)
	{
		$real_dir	= str_replace(':', '/', $directory);
		$full_path	= Assets::dir_path($real_dir);
		if(!is_dir($full_path))
			die('invalid directory');

		$primary = new View('files/add_files');
		$primary->directory = $directory;
		die($primary);
	}

/*
 * Upload files to specified folder. Swf uploader uses this.
 */	
	public function upload($directory=NULL)
	{
		$directory = str_replace(':', '/', $directory);
		$full_path = Assets::dir_path($directory);
		if(!is_dir($full_path))
			die('invalid directory');

		# Do we have a file and is it > 9mb
		if( empty($_FILES['Filedata']['type']) OR ( $_FILES['Filedata']['type'] > 90000 ) )
			die('Invalid File');

		# NOTE:: IS THIS SECURE??
		# Work-around maintaining the session because Flash Player doesn't send the cookies
		if(isset($_POST["PHPSESSID"]))
			session_id($_POST["PHPSESSID"]);

		# place the file.
		$new_path = "$full_path/".$_FILES['Filedata']['name'];
		if(move_uploaded_file($_FILES['Filedata']['tmp_name'], $new_path))
			die('File uploaded');
		
		die('File NOT uploaded.');
		
	}
/*
 * add new folder to a specific directory
 * default folder is root: data/site_name/assets
 */
	public function add_folder($directory=NULL)
	{
		$directory = str_replace(':', '/', $directory);
		$full_path = Assets::dir_path($directory);
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
			
		$primary = new View('files/add_folder');
		$primary->directory = $directory;
		$primary->filter = '';
		die($primary);
	}

	
/*
 * delete a folder or file from the asset repository.
 */
	function delete($path=NULL)
	{
		if(NULL == $path)
			die('No path sent');
		
		$path		= str_replace(':', '/', $path);
		$full_path	= Assets::dir_path($path);
		
		if(is_dir($full_path))
		{
			if('tools' == $path)
				die('Tools folder is required');
				
			if(Jdirectory::remove($full_path))
				die('Folder deleted');
				
			die('Could not delete the folder.');
		}
		elseif(file_exists($full_path))
		{
			if(unlink($full_path))
				die('File deleted');
				
			die('Could not delete the file.');
		}
		die('invalid path');
	}

	

/*
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
	function _output($url_array)
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
		
		$dir_path = Assets::dir_path("$dir_path$filename");
	
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
		
		$stock_dir = Assets::dir_path();
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
	
} /* End of file /modules/admin/files.php */
