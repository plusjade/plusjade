<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php
	if(! empty($title) ) echo '<title>'.$title.'</title>'."\n\t";
	if(! empty($meta_tags) ) echo $meta_tags;
	if(! empty($load_css) ) echo $load_css;
	if(! empty($load_js) ) echo $load_js;
	?>
</head>

<body>
	<?php
	if(! empty($admin_panel) ) echo view::factory('admin/admin_panel');
	
	$banner		= View::factory("_global/header");
	$menu		= View::factory("_global/menu");
	$content	= $primary;
	$footer		= View::factory("_global/footer");

	if ( file_exists("{$custom_include}global/master.html") )
		$master = file_get_contents("{$custom_include}global/master.html");	
	else
		$master = file_get_contents(APPPATH."/views/$theme_name/global/master.html");

	$keys = array(
		'%BANNER%',
		'%MENU%',
		'%CONTENT%',
		'%FOOTER%'
	);
	$replacements = array(
		$banner,
		$menu,
		$content,
		$footer
	);
	
	$master = str_replace($keys, $replacements , $master);
	
	echo $master;	
		
	?>
	<script type="text/javascript"> 
	  //<![CDATA[
		$(document).ready(function(){				
			<?php if(! empty($readyJS) ) echo $readyJS?> 
		});	
			<?php if(! empty($rootJS) ) echo $rootJS?>  
	  //]]>
	</script> 
	<?php
	$tracker_path = DOCROOT."data/$site_name/tracker.html";	
	if ( file_exists("$tracker_path") )
		echo readfile("$tracker_path");
	
		#<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>	
	?>
</body>
</html>
<!-- panda<3! -->