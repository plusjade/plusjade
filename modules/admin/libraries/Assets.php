<?php defined('SYSPATH') or die('No direct script access.');
 
class Assets_Core {

/*
 * Gets and sets site assets
 * i.e. images/files etc. These are other than theme and tool specific assets.
 *
 */

/*
 * returns the full directory path to the data folder
 */
	function dir_path($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return  DATAPATH . "$this->site_name/assets$directory";	
	}

/*
 * returns full url path to asset folder
 * as determined by our file_controller & get_site router.
 */
	function url_path($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return url::site("files$directory");
	}	
	
/*
 * returns DIRECT full url path to asset folder
 * Its longer but faster.
 */
	function url_path_direct($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return url::site("_data/$this->site_name/assets$directory");
	}

/*
 * returns DIRECT full url path to asset folder
 * Its longer but faster.
 */
	function url_path_theme($directory=NULL)
	{
		$directory = ((NULL === $directory)) ? '' : "/$directory";
		
		return "/_data/$this->site_name/themes/$this->theme$directory";
	}
	
} # end









