<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * functions that help us determine the correct image resource to get
 */
 
class image_Core {
	
/*
 * determine thumbnail path of an image from the file repo
 * the size parameter fetches the size you want. it may or may not exist.
*/
	public static function thumb($path, $size='75')
	{
		# a way to send back full size image.
		if(empty($size))
			return $path;
			
		if(0 < substr_count($path, '/'))
		{
			$filename = strrchr($path, '/');
			return str_replace($filename, "/_tmb/$size$filename", $path);
		}

		return "_tmb/$size/$path";
	}

	
} // end image helper