<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php
	if(!empty($title)) echo '<title>'.$title.'</title>'."\n\t";
	if(!empty($meta_tags)) echo $meta_tags;
	if(!empty($load_css)) echo $load_css;
	if(!empty($load_js)) echo $load_js;
	?>
</head>

<body>
	<?php
	
	if(!empty($admin_panel)) echo view::factory('admin/admin_panel');
	
	if ( file_exists("{$custom_include}global/master.php") )
		include_once("{$custom_include}global/master.php");	
	else
		include_once("{$theme_name}/global/master.php");
	
	?>
	<script type="text/javascript"> 
	  //<![CDATA[
		$(document).ready(function(){				
			<?php if(!empty($readyJS)) echo $readyJS; ?> 
		});	
			<?php if(!empty($rootJS)) echo $rootJS; ?>  
	  //]]>
	</script> 
	<?php
	$tracker_path = DOCROOT."data/$site_name/tracker.php";	
	if ( file_exists("$tracker_path") )
		include_once("$tracker_path");
	
		#<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>	
	?>
	
	<!-- panda<3! -->
</body>
</html>