<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php
	if(!empty($load_css)) echo $load_css;
	if(!empty($load_js))  echo $load_js; 
	?>
	<style type="text/css">
		body {margin:0; padding:0;}
	</style>
</head>

<body>
	<?php echo $primary;?>
	
	<script type="text/javascript"> 
	  //<![CDATA[
		$(document).ready(function(){				
			<?php if(!empty($readyJS)) echo $readyJS; ?> 
		});	
			<?php if(!empty($rootJS)) echo $rootJS; ?>  
	  //]]>
	</script> 
<!-- firebug for IE
	remove when in production 

	<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>	
-->
</body>
</html>