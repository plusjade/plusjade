<?php defined('SYSPATH') OR die('No direct access allowed.');
	/* 
	 * Case A: Is Public Ajax Request.
	 * 	Parse to appropriate public tool controller
	 * 	Optimize ajax queries. 
	 * 	Bypass as much code as possible..
	 * 
	*/
class Ajax_Core {

	/* 
		is showroom tool
	*/
	public function showroom($url_array, $tool_id)
	{
		$category	= @$url_array['2'];
		$item		= @$url_array['3'];	
		$showroom	= new Showroom_Controller();
		
		if(! empty($category) AND empty($item) )
			return $showroom->_items_category($tool_id, $category);
		elseif(! empty($category) AND !empty($item) )
			return $showroom->_item($category, $item);		
	}

	/* 
		is blog tool
	*/
	public function blog($url_array, $tool_id)
	{
		$action	= @$url_array['2'];
		$value	= @$url_array['3'];	
		$blog	= new Blog_Controller();
		switch($action)
		{
			case 'entry':
				return $blog->_single_post($value);
				break;
			
			case 'tag':
				break;	
				
			case 'archive':
				break;

			case 'comment':
				# this is an ajaxForm comment post request
				# OR ajax request to view comments
				valid::id_key($value);
				if($_POST)
					return $blog->_post_comment($value);
				else
					return $blog->_get_comments($value);
				break;
				
			default:
	
				break;
		}
	}

	/* 
		is calendar tool
	*/	
	public function calendar($url_array, $tool_id)
	{
		$action	= @$url_array['2'];
		$year	= @$url_array['3'];
		$month	= @$url_array['4'];
		$day	= @$url_array['5'];
		$calendar	= new Calendar_Controller();
		
		if('month' == $action)
		{
			return $calendar->month($tool_id, $year, $month);
		}
		elseif('day' == $action)
		{
			return $calendar->day($tool_id, $year, $month, $day);
		}
	}
	
	/* 
		Fail Silently
	*/
	public function __call($method, $args)
	{
		die("undefined Method: '$method'"); #delete in production
	}
} # End