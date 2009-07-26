
<?php
$slash		= (('' == $directory)) ? '' : '/';
$url_dir	= str_replace('/', ':', $directory);
?>

<?php echo form::open("files/add_folder/$url_dir", array('id' => 'files_add_folder_form') )?>
	<div id="common_tool_header" class="buttons">
		<button type="submit" id="add_page_submit" name="add_folder" class="jade_positive">Add Folder</button>
		<div id="common_title">Add New Folder</div>
	</div>	

	<div id="new_page_wrapper" class="fieldsets big">

		<div class="pane_left">
			<b>Folder Name</b>
			<br><input type="text" name="folder_name" rel="text_req" class="auto_filename" maxlength="50" style="width:330px">
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
	var filter = [<?php echo $filter?>];

// custom ajax form, validates inputs and unique folder_names
	$("#files_add_folder_form").ajaxForm({
		beforeSubmit: function(){
			if(! $("#files_add_folder_form input").jade_validate() )
				return false

			var sent_folder = $("input[name='folder_name']").val();				
			var filter_duplicates = filter.in_array(sent_folder);
			
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