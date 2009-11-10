
<span class="on_close two"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("tool/css/$name_id/$tool->id", array('class' => 'ajaxForm', 'rel' => 'no_disable'))?>
	
	<div style="padding:5px;">		
		<div style="padding-top:10px; float:right;">			
			Add Class: <input type="text" name="attributes" value="<?php echo $tool->attributes?>">
			 <button type="submit" name="save_css" class="jade_positive">Save</button>
			 <button type="submit" name="save_template" class="jade_positive" value="true">Save as Template</button>
		</div>	
		Tool: <em><?php echo $toolname?></em> - Type: <em><?php echo $tool->type?></em> - View: <em><?php echo $tool->view?></em></small>	
		<?php if(!empty($theme_sass)):?>
			This tool is using a global theme file.
		<?php endif;?>	
	</div>

	<ul class="common_tabs_x ui-tabs-nav">
		<li><a href="#theme_sass"><b>Theme Sass</b></span></a><li>
		<li><a href="#custom_sass"><b>Custom Sass</b></span></a><li>
	</ul>
	
	<div class="common_full_panel" style="clear:both; margin:0;padding:0;">	
		
		<div id="theme_sass" class="toggle">
			<textarea id="edit_sass" name="output" style="height:250px"><?php echo $theme_sass?></textarea>
		</div>
		
		<div id="custom_sass" class="toggle">
			<textarea id="edit_css" name="output" style="height:250px"><?php echo $custom_sass?></textarea>
		</div>
	</div>
</form>

<script type="text/javascript">

  // setup common tabs functionality
	$(".common_tabs_x li a").click(function(){
		$('.common_tabs_x li a').removeClass('active');
		var pane = $(this).attr('href');
		$('.common_full_panel div.toggle').hide();
		$('.common_full_panel div'+ pane).show();
		return false;
	});
	$('.common_tabs_x li a:first').click();
	
	$('textarea#edit_css').keydown(function(e) {
		if(e.keyCode == 9) {  // 9 = tab	
			$('#<?php echo "$toolname-$tool->id-style"?>')
				.html($('textarea#edit_css').val());
		}
	});
</script>


