<span id="on_close">close-2</span>

<form action="/get/edit_album/edit_item/<?php echo $item->id?>" method="POST"  class="ajaxForm"> 
	<input type="hidden" name="parent_id" value="<?php echo $item->parent_id?>">
	<input type="hidden" name="path" value="<?php echo $item->path?>">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_item" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Image</div>
	</div>	
	
	<div class="fieldsets">
		<b>Filename:</b> <input type="text" value="<?php echo $item->path?>" size="20" DISABLED>
		
		<b>Caption:</b> <input type="text" name="caption" value="<?php echo $item->caption?>" size="35" maxlength="30">
	</div>
</form>
<?php
	#hack, take this out later
	$url_path = Assets::url_path_direct("tools/albums/$item->parent_id");
?>
<img src="<?php echo "$url_path/_sm/$item->path"?>">