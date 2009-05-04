<?php defined('SYSPATH') OR die('No direct access allowed.');
	/* 
	 * 
	 * Optimize ajax queries. Bypass as much code as possible..
	 * 
	 * 
	*/
class Ajax_Showroom_Core {

	function parse($url_array)
	{
		$category	= @$url_array['2'];
		$item		= @$url_array['3'];	
		$showroom	= new Showroom_Controller();
		
		if(! empty($category) AND empty($item) )
			return $showroom->_items_category($category);
		elseif(! empty($category) AND !empty($item) )
			return $showroom->_item($category, $item);		
	}

	function parse_blog($url_array)
	{
			$action	= @$url_array['2'];
			$value	= @$url_array['3'];	
			$blog	= new Blog_Controller();
			
			if( empty($action))
			{

			}
			elseif('entry' == $action)
			{
				return $this->_single_post($value);
			}
			elseif('comment' == $action AND !empty($value))
			{
				# this is an ajaxForm comment post request
				# OR ajax request to view comments
				tool_ui::validate_id($value);
				if($_POST)
					return $blog->_post_comment($value);
				else
					return $blog->_get_comments($value);
			}	
	}




} # End