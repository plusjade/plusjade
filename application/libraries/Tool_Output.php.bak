<?php defined('SYSPATH') or die('No direct script access.');
 
class Tool_Output_Core {

	function __construct()
	{
	
	}

	
/*
 *
 * logic to save and display arbitruary attributes
 * not using because its easier to just allow/display classes
 *
 */ 
	function display_attributes()
	{
		$attributes = explode(',', $parent->attributes);
		$attr_string ='';
		if(! empty($attributes['0']) )
		{
			foreach($attributes as $attr)
			{
				$pieces	= explode(':', $attr);
				$name	= $pieces['0'];
				$value	= $pieces['1'];
				
				if ( empty($attr_array[$name]) ) 
					$attr_array[$name]='';
				
				$attr_array[$name] .= $value . ' ';
			}
			$attr_array['class'] .= 'navigation_wrapper ';
			
			
			foreach ($attr_array as $name => $value)
			{
				$attr_string .= $name .' = "' . trim($value) .'" ';
			}
			#echo $attr_string; die();
			
		}
	
	}

	
/*
 *
 * logic to save and display arbitruary attributes
 * not using because its easier to just allow/display classes
 *
 */ 
	function OLD_ALL_ATTRIBUTES()
	{
		$attributes = explode(',', $parent->attributes);
		$attr_string ='';
		if(! empty($attributes['0']) )
		{
			foreach($attributes as $attr)
			{
				$pieces	= explode(':', $attr);
				$name	= $pieces['0'];
				$value	= $pieces['1'];
				
				if ( empty($attr_array[$name]) ) 
					$attr_array[$name]='';
				
				$attr_array[$name] .= $value . ' ';
			}
			$attr_array['class'] .= 'navigation_wrapper ';
			
			
			foreach ($attr_array as $name => $value)
			{
				$attr_string .= $name .' = "' . trim($value) .'" ';
			}
			#echo $attr_string; die();
			
		}
	
	}

}