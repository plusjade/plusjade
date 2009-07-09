<?php defined('SYSPATH') or die('No direct script access.');
 
class Assets_Core {

/*
 * Assets scope is relative to a websites static assets folder which is located
 * at /public/_data/<site_name>
 * This class configures paths to frequently accessed folders in said folder.

	## the base directory path is DATAPATH . "$this->site_name";
 */

 
/*
 * returns the full root path to this site's  _data folder.
 */	
	function root_dir($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name$directory";	
	}
	
/*
 * returns the full root path to this site's assets folder folder.
 */		
	function assets_dir($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name/assets$directory";	
	}

/*
 * returns the full root path to this site's themes folder.
 */		
	function themes_dir($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name/themes$directory";	
	}
	
/*
 * returns the full root path to this site's protected folder.
 */		
	function protected_dir($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name/protected$directory";	
	}	
	
	
// -------------------------- urls -----------------------------

/*
 * root_url probably will not be needed, but lets keep a note here just in case.
 */
 
 
/*
 * returns the url to site _data assets folder.
 * $use_file will use the files controller in the url to handle displaying the asset.
 */	
	function assets_url($directory=NULL, $use_file=FALSE)
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
	function theme_url($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return "/_data/$this->site_name/themes/$this->theme$directory";
	}

/*
 * returns the url to the base theme folder
 */		
	function themes_url($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return "/_data/$this->site_name/themes$directory";
	}	

} # end
