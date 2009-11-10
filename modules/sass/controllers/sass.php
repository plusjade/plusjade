<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohaml library to parse haml files
 *
 * @package        Kohaml
 * @author         Justin Hernandez <justin@transphorm.com>
 * @license        http://www.opensource.org/licenses/isc-license.txt
 */
class Sass_Controller extends Controller
{

	public function __construct()
	{
		parent::__construct(); // necessary
	}

	public function index($file)
	{
    if(empty($file)) die('no file');
		header("Content-type: text/css");
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		

		$file = kohana::find_file('views',"sass/$file", false, 'sass');
		# public_blog/blogs/stock
		if(!file_exists($file))
			die('invalid file');
		
		# echo kohana::debug(file($file)); die();
		#$files = 'advanced';
		#if (!is_array($files)) $files = array($files);
		echo Kosass::factory('compact')->compile(file($file));
	
		


		
		# output directly to battle-test this sucka
		# echo sass::stylesheet('advanced', 'compact');
		die();
	}


	public function sass()
	{		

	}
	

}
