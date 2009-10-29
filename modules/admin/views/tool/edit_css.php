

<?php extract($data)?>
<span class="on_close two"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("tool/css/$name_id/$tool->id", array('class' => 'ajaxForm', 'rel' => 'no_disable'))?>

	<div style="padding:5px;">		
		<div style="padding-top:10px; float:right;">			
			Add Class: <input type="text" name="attributes" value="<?php echo $tool->attributes?>">
			 <button type="submit" name="save_css" class="jade_positive">Save</button>
			 <button type="submit" name="save_template" class="jade_positive" value="true">Save as Template</button>
		</div>	
		Tool: <em><?php echo $toolname?></em> - Type: <em><?php echo $tool->type?></em> - View: <em><?php echo $tool->view?></em></small>	
	</div>

	<div class="common_full_panel" style="clear:both; margin:0;padding:0;">	
		<ul class="common_tabs_x ui-tabs_nav">
			<li><a href="#" class="show_orig">Reset</a></li>
			<?php if(NULL != $template):?>
				<li><a href="#" class="show_template">Theme Template</a></li>
			<?php endif;?>
			<li><a href="#" class="show_stock">+Jade Stock</a></li>
		</ul>
				
		<div id="main_css" class="toggle">
			<textarea id="edit_css" name="contents" style="height:250px"><?php echo $contents?></textarea>
		</div>
	</div>
	
	<div id="stock_contents" style="display:none"><?php echo $stock?></div>
	<div id="template_contents" style="display:none"><?php echo $template?></div>
</form>

<script type="text/javascript">

	$('textarea#edit_css').keydown(function(e) {
		if(e.keyCode == 9) {  // 9 = tab	
			$('#<?php echo "$toolname-$tool->id-style"?>')
				.html($('textarea#edit_css').val());
		}
	});
	var original = $('textarea#edit_css').val();
	$('.show_orig').click(function(){
		$('textarea#edit_css').val(original);
		return false;
	});
	$('.show_template').click(function(){
		var contents = $('#template_contents').html();
		$('textarea#edit_css').val(contents);
		return false;
	});
	$('.show_stock').click(function(){
		var contents = $('#stock_contents').html();
		$('textarea#edit_css').val(contents);
		return false;
	});
</script>


