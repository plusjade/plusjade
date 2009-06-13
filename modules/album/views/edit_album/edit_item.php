
<form action="/get/edit_album/edit_item/<?php echo $item->id?>" method="POST"  class="ajaxForm" rel="close-2"> 
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

<?php /*	
<img src="<?php echo $data_path.'/assets/images/albums/'.$item->parent_id .'/'.$item->path?>">
*/
