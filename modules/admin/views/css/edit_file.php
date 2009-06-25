
<?php echo form::open_multipart("css/edit/$name_id/$tool_id", array('class' => 'ajaxForm', 'rel' => $js_rel_command))?>



	<div class="common_left_panel fieldsets">
		
		<p>Edit <?php echo $toolname?>(<?php echo $tool_id?>) CSS.</p>
		<b>Add container class:</b>
		<br><input type="text" name="attributes" value="<?php echo $attributes?>">
	
		<button type="submit" name="save_css" class="jade_positive">Save Changes</button>
		
		<br><br>
		<button type="submit" name="save_css" class="jade_positive">Save to theme</button>
	</div>
	

	<div class="common_main_panel">
		<ul class="generic_tabs ui-tabs_nav" style="margin-bottom:0">
			<li><a href="#" class="update">Update</a></li>
			<li><a href="#" class="show_orig">Reset</a></li>
			<li><a href="#" class="show_stock">Show Stock</a></li>
		</ul>
		<textarea id="edit_css" name="contents" class="blah" style="height:275px"><?php echo $contents?></textarea>
	</div>
	
	<div id="stock_contents" style="display:none"><?php echo $stock?></div>
	
</form>

<script type="text/javascript">

	original = $('textarea#edit_css').val();
	
	$('.show_stock').click(function(){
		contents = $('#stock_contents').html();
		$('textarea#edit_css').val(contents);
		return false;
	});
	$('.show_orig').click(function(){
		$('textarea#edit_css').val(original);
		return false;
	});
	
	$('a.update').click(function(){
		value	= $('textarea#edit_css').val();
		css		= '<style id="<?php echo "$toolname-$tool_id-style"?>" type="text/css">'+ value +'</style>';
		$('#<?php echo "$toolname-$tool_id-style"?>').replaceWith(css);
		return false;
	});	
	
</script>


