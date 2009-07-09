
<?php echo form::open('page/add', array('class' => 'custom_ajaxForm') );?>	
<?php $slash = (empty($directory)) ? '' : '/'; # add slash if not a root page.?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" id="add_page_submit" name="add_page" class="jade_positive">Add Page</button>
		<div id="common_title">Add New Page</div>
	</div>	
		
	<div id="new_page_url">
		Your new page URL:
		<br><b><?php echo url::site()."$directory$slash"?><span id="link_example">...</span></b>
	</div>	
	
	<div class="common_left_panel">		

	</div>
	
	<div class="common_main_panel fieldsets big">

		<b>Page Label</b>
		<br><input id="" type="text" name="label" value="" rel="text_req" maxlength="50" style="width:330px">
		<br><br>
		<b>Page Link</b>
		<br><input type="text" name="page_name" maxlength="50" style="width:330px">
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

	
// if page_builder, update the name/label/url views
	$("select[name='page_builder']").change(function(){	
		value = '';
		num = $("option:selected", this).val();
		if(0 != num)
			value = $("option:selected", this).text();
			
		$("input[name='label']").val(value);
		$("input[name='page_name']").val(value.toLowerCase());
		$('span#link_example').html(value.toLowerCase());
		
	});
	
	
//sanitize and populate page_name fields
	$("input[name='label']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
		$("input[name='page_name']").val(input);
		$('span#link_example').html(input);
	});
	$("input[name='page_name']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
		$(this).val(input);
		$('span#link_example').html(input);
	});
	
/* 
 * custom validation to check for unique page_names
 */
	Array.prototype.in_array = function(p_val) {
		for(var i = 0, l = this.length; i < l; i++) {
			if(this[i] == p_val)
				return true;
		}
		return false;
	}
	
// load the page_name filter
	var filter = [<?php echo $filter?>];
		
/* 
 * custom ajax form, validates inputs and unique page_names
 */		
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			sent_page = $("input[name='page_name']").val();				
			filter_duplicates = filter.in_array(sent_page);
			
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
			directory = '<?php echo $directory?>';
			path_for_css = directory.replace(/\//g,'_');
			$('div.'+path_for_css).append(data);
			$('#show_response_beta').html(data);				
		}
	});
	
	// template select dropdown
	selected = $("select[name='template'] option:selected").text();
	$('#template_desc div').hide();
	$('#template_desc div.'+selected).show();
	
	$("select[name='template']").change(function(){
		$('#template_desc div').hide();
		value = $('option:selected',this).text();
		$('#template_desc div.'+value).show();
	});	
</script>