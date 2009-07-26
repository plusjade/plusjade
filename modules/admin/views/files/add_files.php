
<div id="common_tool_header" class="buttons">
	<div id="common_title">Add Files</div>
</div>	

<div class="common_left_panel aligncenter">
	<span id="spanButtonPlaceHolder"></span>
	<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
</div>

<div id="swf_wrapper" class="common_main_panel">
	Use the <b>browse</b> button to select up to 12 files at a time for uploading.
	<br>Files can be any type and 20mb or less per file.
	<br><br>
	<span class="legend">Upload Queue</span>
	<div class="fieldset flash" id="fsUploadProgress">
	</div>
</div>
	


<script type="text/javascript">
	var swfu = new SWFUpload({
		upload_url: "<?php echo url::site("get/files/upload/$directory")?>",
		post_params: {"PHPSESSID" : "<?php echo session_id()?>"},
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,
		upload_start_handler :  function(){
			$('.facebox .show_submit').show();
		},
		queue_complete_handler : function(){
			swfu.destroy();
			$('.facebox .show_submit').hide();
			$.facebox.close('facebox_2');
			$('#directory_window').html('<div>Loading...</div>');
			$('#directory_window').load('/get/files/contents/<?php echo $directory?>');
		}		
	});
	
	$(document).bind('close.facebox', function() {
		swfu.destroy();
	});
</script>