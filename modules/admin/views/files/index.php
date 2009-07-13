
<div id="files_browser_wrapper">

	<div id="common_tool_header">
		<div id="common_title">Files Browser</div>
	</div>
	
<div id="files_browser_wrapper">
		
	<div class="common_left_panel" style="width:150px">
		<?php if($mode) echo '<div class="editor_info"><b><i>Double Click</i></b> the file you want to place into the editor.</div>';?>
		
		<br><br>
		<span class="icon add_page">&nbsp; &nbsp; </span> <a href="/get/files/add_files" class="add_asset">Add Files</a>
		<br><br>		
		<span class="icon add_folder">&nbsp; &nbsp; </span> <a href="/get/files/add_folder" class="add_asset">Add Folder</a>	
	</div>
	
	<div class="breadcrumb_wrapper" style="float:right; width:620px">
		<a href="/get/files/contents?mode=<?php echo $mode?>" rel="ROOT" class="get_folder">Assets</a><span id="breadcrumb" rel=""></span>
	</div>	
	<div id="directory_window" class="common_main_panel full_height" rel="ROOT" style="width:620px; height:350px; overflow:auto">
		<?php echo View::factory('files/folder', array('files'=> $files, 'mode'=> $mode))?>
	</div>
	
</div>
<div class="clearboth"></div>

