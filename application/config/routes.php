<?php defined('SYSPATH') OR die('No direct access allowed.');

#$config['_default'] = 'home';

#break URI into segments
$parts = explode("/", $_SERVER['REQUEST_URI']);

if( !empty($parts['1']) AND ($parts['1'] == 'get' OR $parts['1'] == 'e') )
{
	# Delete any empty segments (allows for inclusion/exlusion of trailing slash)
    foreach($parts as $key => $value) 
		if(is_null($value) || $value=="") 
			unset($parts[$key]);
   
	$build_url = '';
	$build_route = $parts['1'];
	
	#count number of useful segments being passed in URI
	#Subtract 1 from count since  $parts[1] is our 'e' signifer || For reference, $parts[0]  will always be deleted since it will always be empty
    $count = count($parts)-1; $count2 = $count;
  
  # build dynamic route. We need to execute at least once to catch and route all URI's
    do
	{
        $build_route .= '/([^/]+)';
        --$count;
    }
    while ( $count > 0 );
	 
	# build the URI to route to
	for($x=1; $x < $count2+1; $x++)
		$build_url .= '$'."{$x}/";  
   
	$build_url = substr($build_url,0,-1);

	# generate finished route
	$config["$build_route"] = "$build_url";

	# *Troubleshoot*
    #print_r($parts);
    #echo '<br>$route["'.$build_route.'"] = "'.$build_url.'";'; die();
}



