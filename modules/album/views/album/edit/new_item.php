
<form action="/get/edit_album/add/<?php echo $tool_id?>" method="POST" enctype="multipart/form-data" class="ajaxForm" style="min-height:300px;">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Images
		</button>
		<div id="common_title">Add Images to Album</div>
	</div>	
	
	<div id="common_tool_info">
		You can add up to 10 files per submit. Just keep picking your images.
	</div>
	
	<div class="fieldsets">
		<input type="file" name="images[]" class="multi accept-gif|jpg|png" style="font-size:1.4em"/>
	</div>
	
	<input type="hidden" value="holder" name="holder">
</form>		