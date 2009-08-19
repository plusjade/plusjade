

<?php extract($data)?>
<span class="on_close two"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("tool/css/$name_id/$tool->id", array('class' => 'ajaxForm', 'rel' => 'no_disable'))?>

	<div id="common_tool_header" class="buttons">
		<div id="common_title">Tool: <em><?php echo $toolname?></em> - Type: <em><?php echo $tool->type?></em> - View: <em><?php echo $tool->view?></em></small></div>
	</div>

	<div class="common_left_panel"  style="width:18%">	
		<b>Add container class:</b>
		<br><input type="text" name="attributes" value="<?php echo $tool->attributes?>">
		<p>
		<button type="submit" name="save_css" class="jade_positive">Save Changes</button>
		</p>
		<button type="submit" name="save_template" class="jade_positive" value="true">Save as template</button>
		<br><br>
		<b>Press TAB</b> while in the textarea to update the tool view.
	</div>
	

	<div class="common_main_panel" style="margin:0;padding:0;width:78%">
		<ul class="generic_tabs ui-tabs_nav">
			<li><a href="#" class="show_options">Options</a></li>
			<li><a href="#" class="show_css">CSS</a></li>
			<li><a href="#" class="update">Update</a></li>
			<li><a href="#" class="show_orig">Reset</a></li>
			<?php if(NULL != $template):?>
				<li><a href="#" class="show_template">Theme Template</a></li>
			<?php endif;?>
			<li><a href="#" class="show_stock">+Jade Stock</a></li>
		</ul>
		
		<div id="main_options" class="toggle">
			
			Each Image:
			<br><br>
			<div id="slider"></div>
			Border:<a href="#" class="do_option"> Red Border</a>
			<br>Background:
			<br>Padding:
			<br>Margin:
		</div>

		
		<div id="main_css" class="toggle">
			<textarea id="edit_css" name="contents" class="blah" style="height:260px"><?php echo $contents?></textarea>
		</div>
		

	</div>
	
	<div id="stock_contents" style="display:none"><?php echo $stock?></div>
	<div id="template_contents" style="display:none"><?php echo $template?></div>
</form>

<script type="text/javascript">

	$('.toggle').hide();
	$('#main_css').show();
	
	$("#slider").slider();

	
	
	$('a.show_options').click(function(){
		$('.toggle').hide();
		$('#main_options').show();
		return false;
	});
	$('a.show_css').click(function(){
		$('.toggle').hide();
		$('#main_css').show();
		return false;
	});

	/* testing */
	$('a.do_option').click(function(){
		$('.toggle').hide();
		$('#main_css').show();
		
		var css_output = 'border:2px solid red;';
		
		var new_content = $('#stock_contents').html().replace('/*img_helper*/','/*img_helper*/' + css_output);
		
		$('textarea#edit_css').val(new_content);
		$('a.update').click();
		return false;
	});

	
	
	$('a.update').click(function(){		
		var value	= $('textarea#edit_css').val();
		var css		= '<style id="<?php echo "$toolname-$tool->id-style"?>" type="text/css">'+ value +'</style>';
		$('#<?php echo "$toolname-$tool->id-style"?>').replaceWith(css);
		return false;
	});

	$('textarea#edit_css').keydown(function(e){
	// 16 = SHIFT, 9 = tab
	  if (e.keyCode == 9) {		
			var value	= $('textarea#edit_css').val();
			var css		= '<style id="<?php echo "$toolname-$tool->id-style"?>" type="text/css">'+ value +'</style>';
			$('#<?php echo "$toolname-$tool->id-style"?>').replaceWith(css);
			return false;
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


