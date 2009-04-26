<style type="text/css">

/* -- Form Styles ------------------------------- */
#form1{	
	margin: 0;
	padding: 0;
}
#form_wrapper{
	width:450px;
	margin:0 auto;
}

#form1 div.fieldset {
	border:  1px solid #afe14c;
	margin: 10px 0;
	padding: 20px 10px;
}
#form1 div.fieldset span.legend {
	position: relative;
	background-color: #FFF;
	padding: 3px;
	top: -30px;
	font: 700 14px Arial, Helvetica, sans-serif;
	color: #73b304;
}


div.flash {
	width: 375px;
	margin: 10px 5px;
	border-color: #D9E4FF;
	-moz-border-radius-topleft : 5px;
	-webkit-border-top-left-radius : 5px;
    -moz-border-radius-topright : 5px;
    -webkit-border-top-right-radius : 5px;
    -moz-border-radius-bottomleft : 5px;
    -webkit-border-bottom-left-radius : 5px;
    -moz-border-radius-bottomright : 5px;
    -webkit-border-bottom-right-radius : 5px;
}

button,
input,
select,
textarea { 
	border-width: 1px; 
	margin-bottom: 10px;
	padding: 2px 3px;
}



input[disabled]{ border: 1px solid #ccc } /* FF 2 Fix */


label { 
	width: 150px; 
	text-align: right; 
	display:block;
	margin-right: 5px;
}

#btnSubmit { margin: 0 0 0 155px ; }

/* -- Table Styles ------------------------------- */
td {
	font: 10pt Helvetica, Arial, sans-serif;
	vertical-align: top;
}

.progressWrapper {
	width: 357px;
	overflow: hidden;
}

.progressContainer {
	margin: 5px;
	padding: 4px;
	border: solid 1px #E8E8E8;
	background-color: #F7F7F7;
	overflow: hidden;
}
/* Message */
.message {
	margin: 1em 0;
	padding: 10px 20px;
	border: solid 1px #FFDD99;
	background-color: #FFFFCC;
	overflow: hidden;
}
/* Error */
.red {
	border: solid 1px #B50000;
	background-color: #FFEBEB;
}

/* Current */
.green {
	border: solid 1px #DDF0DD;
	background-color: #EBFFEB;
}

/* Complete */
.blue {
	border: solid 1px #CEE2F2;
	background-color: #F0F5FF;
}

.progressName {
	font-size: 8pt;
	font-weight: 700;
	color: #555;
	width: 323px;
	height: 14px;
	text-align: left;
	white-space: nowrap;
	overflow: hidden;
}

.progressBarInProgress,
.progressBarComplete,
.progressBarError {
	font-size: 0;
	width: 0%;
	height: 2px;
	background-color: blue;
	margin-top: 2px;
}

.progressBarComplete {
	width: 100%;
	background-color: green;
	visibility: hidden;
}

.progressBarError {
	width: 100%;
	background-color: red;
	visibility: hidden;
}

.progressBarStatus {
	margin-top: 2px;
	width: 337px;
	font-size: 7pt;
	font-family: Arial;
	text-align: left;
	white-space: nowrap;
}

a.progressCancel {
	font-size: 0;
	display: block;
	height: 14px;
	width: 14px;
	background-image: url(../images/cancelbutton.gif);
	background-repeat: no-repeat;
	background-position: -14px 0px;
	float: right;
}

a.progressCancel:hover {
	background-position: 0px 0px;
}


/* -- SWFUpload Object Styles ------------------------------- */
.swfupload {
	vertical-align: top;
}

</style>

<div id="common_tool_header" class="buttons">
	<div id="common_title">Add Images to Album</div>
</div>	

<form id="form1" action="/get/edit_album/add/<?php echo $tool_id?>" method="POST" enctype="multipart/form-data" ass="ajaxForm" style="min-height:300px;">	
	
<div id="form_wrapper">

	<div class="aligncenter">
		<span id="spanButtonPlaceHolder"></span>
		<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
	</div>
	
	<div id="divStatus" class="aligncenter">0 Files Uploaded</div>
	
	<div class="fieldset flash" id="fsUploadProgress">
		<span class="legend">Upload Queue</span>
	</div>
</div>
</form>	
	
<script type="text/javascript">
	swfu = new SWFUpload({
		flash_url : "/assets/js/swfupload/Flash/swfupload.swf",
		upload_url: "/get/edit_album/add_image/<?php echo $tool_id?>",
		post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
		file_size_limit : "50 MB",
		file_types : "*.jpg;*.jpeg;*.gif;*.png",
		file_types_description : "Image Files",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url: "/assets/images/admin/swf_upload.png",
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
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueComplete	// Queue plugin event			
	});
</script>