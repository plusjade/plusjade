<?php defined('SYSPATH') or die('No direct script access.');
 
/*
 * Assets scope is relative to a websites static assets folder which is located
 * at /public/_data/<site_name>
 * This class configures paths to frequently accessed folders in said folder.

	## the base directory path is DATAPATH . "$this->site_name";
 */

class Assets_Core {
 
	public function __construct($site_name, $theme)
	{
		$this->site_name = $site_name;
		$this->theme	 = $theme;
	}	
 
/**
 * Returns a singleton instance of assets class.
 *
 * @return  object
 */
	public static function instance($site_name, $theme)
	{
		static $instance;

		if($instance == NULL)
		{
			// Initialize the assets instance
			$instance = new Assets($site_name, $theme);
		}

		return $instance;
	}

	
// -------------------------------------------------------------	
// --------------------- directory paths -----------------------
// -------------------------------------------------------------


/*
 * returns the full root path to this site's  _data folder.
 */	
	public function root_dir($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name$directory";	
	}
	
/*
 * returns the full root path to this site's assets folder folder.
 */		
	public function assets_dir($directory=NULL)
	{
		$directory = (empty($directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name/assets$directory";	
	}

/*
 * returns the full root path to this site's themes folder.
 */		
	public function themes_dir($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name/themes$directory";	
	}
	
/*
 * returns the full root path to this site's protected folder.
 */		
	public function protected_dir($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name/protected$directory";	
	}	
	

	
// -------------------------------------------------------------	
// -------------------------- urls -----------------------------
// -------------------------------------------------------------


/*
 * root_url probably will not be needed, but lets keep a note here just in case.
 */
 
 
/*
 * returns the url to site _data assets folder.
 * $use_file will use the files controller in the url to handle displaying the asset.
 */	
	public function assets_url($directory=NULL, $use_file=FALSE)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		if(TRUE == $use_file)
			return url::site("files$directory");
		else
			return url::site("_data/$this->site_name/assets$directory");		
	}
	

/*
 * returns the url to the activated theme folder
 */	
	public function theme_url($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return "/_data/$this->site_name/themes/$this->theme$directory";
	}

/*
 * returns the url to the base theme folder
 */		
	public function themes_url($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return "/_data/$this->site_name/themes$directory";
	}	

} # end
