
<div id="common_tool_header" class="buttons">
	<div id="common_title">Add Files</div>
</div>	

<div class="common_left_panel aligncenter">
	<span id="spanButtonPlaceHolder"></span>
	<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />

	
	<br/><br/>
	<b>Create Image Thumbnails.</b>
	
	100x100 always made.<br/>
	<input type="checkbox" name="200" value="200"> 200x200<br/>
	<input type="checkbox" name="300" value="300"> 300x300<br/>
	<input type="checkbox" name="400" value="400"> 400x400<br/>
	<input type="checkbox" name="500" value="500"> 500x500<br/>
</div>

<div id="swf_wrapper" class="common_main_panel">
	Use the <b>browse</b> button to select up to 50 files at a time for uploading.
	<br>Files can be any type and 20mb or less per file.
	<br><br>
	<span class="legend">Upload Queue</span>
	<div class="fieldset flash" id="fsUploadProgress"></div>
</div>
	


<script type="text/javascript">
	var swfu = new SWFUpload({
		upload_url: "<?php echo url::site("get/files/upload?dir=$short_url_dir")?>",
		post_params: {"PHPSESSID" : "<?php echo session_id()?>"},
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,
		upload_start_handler :  function(){
			$('.facebox .show_submit').show();
			
			$('.common_left_panel input:checked').each(function(i){
				swfu.addPostParam('thumb['+i+']', $(this).val());
			});
		},
		queue_complete_handler : function(){
			swfu.destroy();
			$('.facebox .show_submit').hide();
			$.facebox.close('facebox_2');
			$('#directory_window').html('<div>Loading...</div>');
			$('#directory_window').load('/get/files/contents?dir=<?php echo $short_url_dir?>');
		}		
	});
	
	$(document).bind('close.facebox', function() {
		swfu.destroy();
	});
</script>