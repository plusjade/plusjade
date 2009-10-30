
<div id="files_browser_wrapper" class="data_files">

	<div id="common_tool_header">
		<ul class="file_links">
			<li><span class="icon local">&nbsp; &nbsp; </span> <a href="#" class="place_selected">Place</a>	
			<li><span class="icon local">&nbsp; &nbsp; </span> <a href="#" class="thumb_selected">Thumbs</a>	
			<li><span class="icon edit_page">&nbsp; &nbsp; </span> <a href="#" class="rename_selected">Rename</a></li>
			<li><span class="icon move">&nbsp; &nbsp; </span> <a href="#" class="move_selected">Move</a></li>			
			<li><span class="icon cross">&nbsp; &nbsp; </span> <a href="#" class="delete_selected">Delete</a></li>	
			<li><span class="icon add_page">&nbsp; &nbsp; </span> <a href="/get/files/add_files" class="add_asset">Add Files</a></li>	
			<li><span class="icon add_folder">&nbsp; &nbsp; </span> <a href="/get/files/add_folder" class="add_asset">Add Folder</a></li>		
		</ul>
		<div id="common_title">Site Files Manager</div>
	</div>
	
	<div class="breadcrumb_wrapper">
		<span class="icon home">&#160; &#160;</span> <a href="/get/files/contents?mode=<?php echo $mode?>" rel="ROOT" class="get_folder">Assets</a>
		<span id="breadcrumb" rel="" class="files"></span>
	</div>
	
	<div id="directory_window" class="common_full_panel full_height" rel="ROOT" style="height:280px; background:#fff; overflow:auto">
		<?php echo View::factory('files/folder', array('files' => $files, 'mode' => $mode, 'image_types' => $image_types))?>
	</div>

	<div class="save_pane" style="display:none">
		<div class="contents">
			<span class="icon cross floatright">&#160; &#160;</span>		
			<h3><b>Create Thumbnails</b></h3>
				<input type="checkbox" name="size[]" value="100"> 100x100
				<br/><input type="checkbox" name="size[]" value="200"> 200x200
				<br/><input type="checkbox" name="size[]" value="300"> 300x300
				<br/><input type="checkbox" name="size[]" value="400"> 400x400
				<br/><input type="checkbox" name="size[]" value="500"> 500x500
				<br/><br/>
				<button class="do_thumb jade_positive">Make Thumbs</button>
			</div>
		</div>
	</div>
</div>
<div class="clearboth"></div>

