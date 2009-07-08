
<span class="OFF_on_close"><?php echo $js_rel_command?></span>
<?php echo form::open('theme/change', array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Install <span></span></button>
		<div id="common_title">Install New Theme</div>
	</div>

	<div class="common_left_panel">
		<ul style="line-height:2em">
			<?php
			foreach($themes as $key => $theme)
				echo "<li><input type=\"radio\" name=\"theme\" value=\"$theme->name\" id=\"$theme->name\"> <label for=\"$theme->name\" class=\"theme_toggle\" rel=\"$theme->id\" style=\"display:inline\">$theme->name</label></li>"; 
			?>
		</ul>
		
		<br>
		<h3>Activate this theme?</h3>
		<input type="checkbox" name="activate" value="true" CHECKED> Yes!
	</div>
	
	<div id="tool_view_wrapper" class="common_main_panel">	
		<?php	
		foreach($themes as $key => $theme)
		{					
			$disabled = '';
			$class = 'jade_positive';
			if($this->theme == $theme->name)
			{
				$current_theme = $theme->id;
				$disabled = 'disabled="disabled"';
				$class = 'jade_negative';
			}
			?>			
			<div id="theme_<?php echo $theme->id?>" class="each_theme buttons aligncenter">				
				<div class="desc">
				<img src="<?php echo url::image_path("themes/$theme->name.$theme->image_ext")?>">
				</div>
			</div>
			<?php
			unset($disabled);
		}
		?>
	</div>
</form>
<script type="text/javascript">
	$('.each_theme').hide();
	$('#theme_<?php echo $current_theme?>').show();
	$('div.common_left_panel label.theme_toggle').click(function(){
		id = $(this).attr('rel');	
		$('div.each_theme').hide(); 
		$('#theme_'+id).show();
		$('#common_tool_header button span').html($(this).html());
	});
</script>