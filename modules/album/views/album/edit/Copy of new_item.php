
<form action="/get/edit_album/add/<?php echo $tool_id?>" method="POST" enctype="multipart/form-data" class="ajaxForm" style="min-height:300px;">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Images
		</button>
		<div id="common_title">Add Images to Album</div>
	</div>	
	
	<div id="common_tool_info">
		You can add up to 10 files per submit. Just keep picking your images.
	</div>
	
	<div class="fieldsets aligncenter">
		<input type="file" name="images[]" class="multi accept-gif|jpg|png" style="font-size:1.4em"/>
	</div>
	
	<input type="hidden" value="holder" name="holder">
</form>		

<script type="text/javascript">
	//$.MultiFile();

			swfu = new SWFUpload({
				flash_url : "/assets/js/swfupload/swfupload.swf",
				upload_url: "upload.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "100 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "images/TestImageNoText_65x29.png",
				button_width: "65",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">Upload</span>',
				button_text_style: ".theFont { font-size: 16; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event			
			});
</script>