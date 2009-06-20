
<?php echo form::open_multipart("css/edit/$name_id/$tool_id", array('class' => 'ajaxForm', 'rel' => $js_rel_command))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_css" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit <?php echo $tool_name?>(<?php echo $tool_id?>) CSS.</div>	
	</div>

	<div class="common_left_panel fieldsets">
		<b>Add container class:</b>
		<br><input type="text" name="attributes" value="<?php echo $attributes?>">
	
		<ul style="line-height:1.6em">
			<li><a href="#" class="show_orig">Reset</a></li>
			<li><a href="#" class="show_stock">Show Stock</a></li>
		</ul>
	</div>
	

	<div class="common_main_panel">
		<textarea id="edit_css" name="contents" class="render_css"><?php echo $contents?></textarea>
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
</script>


