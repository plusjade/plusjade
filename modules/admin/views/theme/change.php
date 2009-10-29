
<div id="common_tool_header" class="buttons">
	<button id="change_theme" type="submit" class="jade_positive">Install <span></span></button>
	<div id="common_title">Install New Theme</div>
</div>

<div class="common_left_panel">
	<ul style="line-height:2em">
		<?php
		foreach($themes as $key => $theme)
			echo "<li><a href=\"#\" rel=\"$theme->id\">$theme->name</a></li>"; 
		?>
	</ul>
	
	<h3>Activate this theme?</h3>
	<input type="checkbox" name="activate" value="true" CHECKED> Yes!
</div>

<div id="tool_view_wrapper" class="common_main_panel">	
	<?php foreach($themes as $key => $theme):?>		
		<div id="theme_<?php echo $theme->id?>" class="each_theme aligncenter">				
			<div class="desc">
				<img src="<?php echo url::image_path("themes/$theme->name.$theme->image_ext")?>">
			</div>
		</div>
	<?php endforeach;?>
</div>

<script type="text/javascript">

// toggle viewing of each theme panel.	
	$('div.common_left_panel li a').click(function(){
		$('div.common_left_panel li a').removeClass();
		$(this).addClass('selected');
		var id = $(this).attr('rel');	
		$('div.each_theme').hide(); 
		$('#theme_'+id).show();
		$('#common_tool_header button span').html($(this).html());
		return false;
	});
	
// submit button to change theme	
	$('button#change_theme').click(function(){
		var theme = $('div.common_left_panel li a.selected').html();
		if('<?php echo $this->theme?>' == theme) {
			alert('Theme already active.');
			return false;
		}
		$(this).removeClass().attr('disabled','disabled');
		$('.facebox .show_submit').show();
		$.post('/get/theme/change', {theme: theme}, function(data){
			if('TRUE' == data)
				location.reload();
			else {
				alert(data);
				$.facebox.close();
			}
		});
		return false;
	});
	
// init	
	$('.each_theme').hide();
	$('div.common_left_panel li a:first').click();
</script>