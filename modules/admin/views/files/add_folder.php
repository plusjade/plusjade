
<?php
$slash		= (('' == $directory)) ? '' : '/';
$url_dir	= str_replace('/', ':', $directory);
?>

<?php echo form::open("files/add_folder/$url_dir", array('class' => 'custom_ajaxForm') )?>
	<div id="common_tool_header" class="buttons">
		<button type="submit" id="add_page_submit" name="add_folder" class="jade_positive">Add Folder</button>
		<div id="common_title">Add New Folder</div>
	</div>	

	<div id="new_page_wrapper" class="fieldsets big">

		<div class="pane_left">
			<b>Folder Name</b>
			<br><input type="text" name="folder_name" rel="text_req" maxlength="50" style="width:330px">
			<div id="folder_exists" class="aligncenter error_msg"></div>
		</div>
		
		<div class="pane_right">		

		</div>
		
	</div>

	<div id="new_page_url">
		Path to Folder:
		<br><b><?php echo url::site()."files/$directory$slash"?><span id="link_example">...</span></b>
	</div>	
</form>	


<script type="text/javascript">

	$("input[name='folder_name']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
		$(this).val(input);
		$('span#link_example').html(input);
	});
	
	/* 
	 * custom validation to check for unique folder_names
	 */
	Array.prototype.in_array = function(p_val) {
		for(var i = 0, l = this.length; i < l; i++) {
			if(this[i] == p_val)
				return true;
		}
		return false;
	}
	
	// load the folder_name filter
	var filter = [<?php echo $filter?>];
		
	/* 
	 * custom ajax form, validates inputs and unique folder_names
	 */
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			sent_folder = $("input[name='folder_name']").val();				
			filter_duplicates = filter.in_array(sent_folder);
			
			if(filter_duplicates) {
				$('#folder_exists').html('Page name already exists');
				$("input[name='folder_name']").addClass('input_error');
				return false;
			}
			$('.facebox .show_submit').show();
		},
		success: function(data) {
			$('.facebox .show_submit').hide();
			$.facebox.close('facebox_2');
			$('#directory_window').html('<div>Loading...</div>');
			$('#directory_window').load('/get/files/contents/<?php echo $url_dir?>');
			$('#show_response_beta').html(data);
		}
	});
	
	
</script>