
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
	var swfu = new SWFUpload({
		upload_url: "<?php echo url::site("get/theme/upload/$theme")?>",
		post_params: {"PHPSESSID" : "<?php echo session_id()?>"},
		file_types : "*.html;*.css;*.jpg;*.jpeg;*.gif;*.png",
		file_types_description : "images, css, html",
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
		}		
	});
	
	$(document).bind('close.facebox', function() {
		swfu.destroy();
	});
	
</script>