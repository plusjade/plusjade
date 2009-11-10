<style type="text/css">
ul.blah {float:right;}
ul.blah li {display:inline}
</style>

<span class="on_close two"><?php echo $js_rel_command?></span>

<div>
	Tool: <em><?php echo $toolname?></em> - Type: <em><?php echo $tool->type?></em> - View: <em><?php echo $tool->view?></em></small>	
	Add Class: <input type="text" name="attributes" value="<?php echo $tool->attributes?>"> 
	
	<div style="padding-top:5px;">	
		<ul class="blah ui-tabs_nav">
			<li><a href="#" class="show_orig">Reset</a></li>
			<?php if(isset($template) AND NULL != $template):?>
				<li><a href="#" class="show_template">Theme Template</a></li>
			<?php endif;?>
			<li><a href="#" class="show_stock">+Jade Stock</a></li>
		</ul>	
		<button id="update_test">Update</button>
		 <button type="submit" name="save_css" id="save_css" class="jade_positive">Save</button>
		 <button type="submit" name="save_template" class="jade_positive" value="true">Save as Template</button>
	</div>
</div>

<select id="css_line" size="1" style="idth:200px"> 
	<?php foreach($css as $key => $element):?>
		<option><?php echo $key?></option>
	<?php endforeach;?>
</select>

<div class="common_full_panel" style="clear:both; margin:0;padding:0;">	
			
	<div id="main_css" class="toggle" style="float:right; width:250px;">
		<textarea id="edit_css" name="contents" style="width:200px; height:250px"></textarea>
	</div>
</div>

<script type="text/javascript">

	var css = <?php echo json_encode($css)?>; //console.log(css);

	$('#update_test').click(function() {
		var output = '';
		// consolidate the css object.
		$.each(css, function(el, val) {
			output += el + '{' + $.trim(val) + "}\n";
			//console.log(el + '{' + val + '}');	
		});
		//console.log(output);
		// add to DOM
		$('#<?php echo "$toolname-$tool->id-style"?>').html(output);
		return false;
	});
	
	$('textarea#edit_css').keydown(function(e) {
		if(e.keyCode == 9) {  // 9 = tab	
			var line = $('#css_line option:selected').val();
			css[line] = $('textarea#edit_css').val();
			$('#update_test').click();
			return false;
		}
	});
	
	$('#css_line').change(function() {
		var line = $('#css_line option:selected').val();	
		$('textarea#edit_css').val(css[line]);
		return false;
	});
	$('#css_line').change();


	// save css changes 
	$('#save_css').click(function(){
		var output = '';
		// consolidate the css object.
		$.each(css, function(el, val) {
			output += el + '{' + val + '}';
		});
		$(document).trigger('show_submit.plusjade');
		$.post('/get/tool/css/<?php echo "$name_id/$tool->id"?>',{output:output}, function(data) {
			$(document).trigger('server_response.plusjade', data);
		});
	});
</script>


