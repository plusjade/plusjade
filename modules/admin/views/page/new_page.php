
<?php echo form::open('page/add', array('class' => 'custom_ajaxForm') );?>	
<?php $slash = (empty($directory)) ? '' : '/'; # add slash if not a root page.?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" id="add_page_submit" name="add_page" class="jade_positive">Add Page</button>
		<div id="common_title">Add New Page</div>
	</div>	
		
	<div id="new_page_url">
		Link to this page.
		<br><b><?php echo url::site()."$directory$slash"?><span id="link_example">...</span></b>
	</div>	
	
	<div class="common_left_panel">		

	</div>
	
	<div class="common_main_panel fieldsets big">

		<b>Page Label</b>
		<br><input type="text" name="label" rel="text_req" maxlength="50" class="send_input" style="width:330px">
		<br><br>
		<b>Page Link</b>
		<br><input type="text" name="page_name" maxlength="50" class="auto_filename receive_input" style="width:330px">
		<div id="page_exists" class="aligncenter error_msg"></div>
	
		<p style="line-height:1.6em">
			<b>Add to Main Menu?</b> <input type="checkbox" name="menu" value="yes" CHECKED> Yes!
		</p>
		
		<b>Page Template</b><br>
		<select name="template">
			<?php
			foreach($templates as $name => $desc)
				if('master' == $name)
					echo "<option selected=\"selected\">$name</option>";
				else
					echo "<option>$name</option>";
			?>
		</select>
		<div id="template_desc">
			<?php
			foreach($templates as $name => $desc)
				echo "<div class=\"$name\">$desc</div>";
			?>
		</div>
	</div>
	
	<input type="hidden" name="directory" value="<?php echo $directory?>">
</form>	

<?php if('' == $directory) $directory = 'ROOT' # for javascript?>
<script type="text/javascript">
	var filter = [<?php echo $filter?>];		
	
// custom ajax form, validates inputs and unique page_names	
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			var sent_page = $("input[name='page_name']").val();				
			var filter_duplicates = filter.in_array(sent_page);
			
			if(filter_duplicates) {
				$('#page_exists').html('Page name already exists');
				$("input[name='page_name']").addClass('input_error');
				return false;
			}
			$('.facebox .show_submit').show();
		},
		success: function(data) {
			$('.facebox .show_submit').hide();
			$.facebox.close('facebox_2');
			var directory = '<?php echo $directory?>';
			var path_for_css = directory.replace(/\//g,'_');
			$('div.'+path_for_css).append(data);
			$('#show_response_beta').html(data);				
		}
	});
	
// template select dropdown
	var selected = $("select[name='template'] option:selected").text();
	$('#template_desc div').hide();
	$('#template_desc div.'+selected).show();
	
	$("select[name='template']").change(function(){
		$('#template_desc div').hide();
		var value = $('option:selected',this).text();
		$('#template_desc div.'+value).show();
	});
	
</script>