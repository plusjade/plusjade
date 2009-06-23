
<div id="common_tool_header" class="buttons">
	<div id="common_title">Add Files</div>
</div>	

<div id="swf_wrapper">

	<div class="aligncenter">
		<span id="spanButtonPlaceHolder"></span>
		<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
	</div>
	
	<div id="divStatus" class="aligncenter">0 Files Uploaded</div>
	
	<div class="fieldset flash" id="fsUploadProgress">
		<span class="legend">Upload Queue</span>
	</div>
	
</div>
	


<script type="text/javascript">
	swfu = new SWFUpload({
		flash_url : "/_assets/js/swfupload/Flash/swfupload.swf",
		upload_url: "<?php echo url::site("get/files/upload/$directory")?>",
		post_params: {"PHPSESSID" : "<?php echo session_id()?>"},
		file_size_limit : "50 MB",
		//file_types : "*.jpg;*.jpeg;*.gif;*.png",
		file_types_description : "All Files",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url: "/_assets/images/admin/swf_upload.png",
		button_width: "100",
		button_height: "30",
		button_placeholder_id: "spanButtonPlaceHolder",
		button_text: '<span class="theFont">Select Images</span>',
		button_text_style: ".theFont { font-size: 16; }",
		button_text_left_padding: 3,
		button_text_top_padding: 3,
		
		// The event handler functions are defined in handlers.js
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler :  function(){
			$('.facebox .show_submit').show();
		},
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler :uploadComplete,
		queue_complete_handler : function(){
			$('.facebox .show_submit').hide();
			$.facebox.close('facebox_2');
			$('#directory_window').html('<div>Loading...</div>');
			$('#directory_window').load('/get/files/contents/<?php echo $directory?>');
			//$('#show_response_beta').html(data);
		} //queueComplete	// Queue plugin event			
	});
</script>