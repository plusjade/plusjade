

<?php extract($data)?>
<span class="on_close two"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("tool/css/$name_id/$tool_id", array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<div id="common_title"><?php echo $toolname?> - <?php echo $tool_id?> CSS.</div>
	</div>

	<div class="common_left_panel"  style="width:18%">	
		<b>Add container class:</b>
		<br><input type="text" name="attributes" value="<?php echo $attributes?>">
		<p>
		<button type="submit" name="save_css" class="jade_positive">Save Changes</button>
		</p>
		<button type="submit" name="save_template" class="jade_positive" value="true">Save as template</button>
		<br><br>
		<b>Press TAB</b> while in the textarea to update the tool view.
	</div>
	

	<div class="common_main_panel" style="margin:0;padding:0;width:78%">
		<ul class="generic_tabs ui-tabs_nav">
			<li><a href="#" class="update">Update</a></li>
			<li><a href="#" class="show_orig">Reset</a></li>
			<?php if(NULL != $template) echo '<li><a href="#" class="show_template">Theme Template</a></li>'?>
			<li><a href="#" class="show_stock">+Jade Stock</a></li>
		</ul>
		<textarea id="edit_css" name="contents" class="blah" style="height:275px"><?php echo $contents?></textarea>
	</div>
	
	<div id="stock_contents" style="display:none"><?php echo $stock?></div>
	<div id="template_contents" style="display:none"><?php echo $template?></div>
</form>

<script type="text/javascript">

	$('a.update').click(function(){
		value	= $('textarea#edit_css').val();
		css		= '<style id="<?php echo "$toolname-$tool_id-style"?>" type="text/css">'+ value +'</style>';
		$('#<?php echo "$toolname-$tool_id-style"?>').replaceWith(css);
		return false;
	});

	$('textarea#edit_css').keydown(function(e){
	// 16 = SHIFT, 9 = tab
	  if (e.keyCode == 9) {		
			value	= $('textarea#edit_css').val();
			css		= '<style id="<?php echo "$toolname-$tool_id-style"?>" type="text/css">'+ value +'</style>';
			$('#<?php echo "$toolname-$tool_id-style"?>').replaceWith(css);
			return false;
		}	
	});

	
	original = $('textarea#edit_css').val();
	$('.show_orig').click(function(){
		$('textarea#edit_css').val(original);
		return false;
	});
	
	$('.show_template').click(function(){
		contents = $('#template_contents').html();
		$('textarea#edit_css').val(contents);
		return false;
	});
	
	$('.show_stock').click(function(){
		contents = $('#stock_contents').html();
		$('textarea#edit_css').val(contents);
		return false;
	});
</script>


