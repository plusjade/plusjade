
<div id="common_tool_header" class="buttons">
	<div id="common_title">Add Files to Theme: <?php echo $theme?></div>
</div>	

<div class="common_left_panel aligncenter">
	<span id="spanButtonPlaceHolder"></span>
	<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
</div>

<div id="swf_wrapper" class="common_main_panel">
	 <b>Click Browse</b> button to select up to 12 files at a time for uploading.
	<br><br>
	<b>Accepted filetypes:</b> <i>.html</i>, <i>.css</i>, <i>images(.jpg, .gif, .png)</i>
	<p>
	<b>NOTE:</b> Files will automatically be placed into appropriate folders.
	<br>All other extensions will be omitted.
	</p>
	<span class="legend">Upload Queue</span>
	<div class="fieldset flash" id="fsUploadProgress">
	</div>
</div>
	


<script type="text/javascript">
	swfu = new SWFUpload({
		flash_url : "/_assets/js/swfupload/Flash/swfupload.swf",
		upload_url: "<?php echo url::site("get/theme/upload/$theme")?>",
		post_params: {"PHPSESSID" : "<?php echo session_id()?>"},
		file_size_limit : "20 MB",
		file_types : "*.html;*.css;*.jpg;*.jpeg;*.gif;*.png",
		file_types_description : "images, css, html",
		file_upload_limit : 20,
		file_queue_limit : 12,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url: "/_assets/images/admin/browse.png",
		button_width: "87",
		button_height: "40",
		button_placeholder_id: "spanButtonPlaceHolder",		
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
			//$('#show_response_beta').html(data);
		}		
	});
</script>