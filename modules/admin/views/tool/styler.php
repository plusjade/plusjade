


<textarea id="show_css" class="view_group" style="width:100%;height:230px"><?php echo $stock_css?></textarea>
<ul class="generic_tabs ui-tabs-nav">
	<li><a href="#" class="update" rel="html">Update</a></li>
</ul>
<iframe src="<?php echo $iframe_url?>" id="show_html" class="view_group" width="100%" height="370px" frameborder="0" style="border:1px solid #ccc">
your browser does not support iframes
</iframe>


<script type="text/javascript">
	$('a.update').click(function(){
		value	= $('textarea').val();
		css		= '<style id="<?php echo $style_id?>" type="text/css">'+ value +'</style>';
		
		$('#<?php echo $style_id?>').replaceWith(css);	
		//$('iframe').contents().find('#<?php echo $style_id?>').replaceWith(css);		
		return false;
	});	

/*
	$('a.update').click(function(){
		value	= $('textarea').val();
		css		= '<style id="<?php echo $style_id?>" type="text/css">'+ value +'</style>';
		$('iframe').contents().find('#<?php echo $style_id?>').replaceWith(css);		
		return false;
	});	
*/	
	$('a.OFFLINE_toggle_show').click(function(){
		view	= $(this).attr('rel');
		$('.view_group').hide();
		$('#show_'+view).show();
		if('html' == view){
			value	= $('textarea').val();
			css		= '<style id="blog-68-style" type="text/css">'+ value +'</style>';
			$('iframe').contents().find('#blog-68-style').replaceWith(css);		
		}
		return false;
	});		
</script>

