<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * functions that help us determine the correct image resource to get
 */
class image_Core {
	
/*
 * determine thumbnail path of an image from the file repo
*/
	public static function thumb($path)
	{
		if(0 < substr_count($path, '/'))
		{
			$filename = strrchr($path, '/');
			return str_replace($filename, "/_sm$filename", $path);
		}

		return "/_sm/$path";
	}

	
} // end image helper