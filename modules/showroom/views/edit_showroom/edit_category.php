

<form action="/get/edit_showroom/edit_category/<?php echo $cat->id?>" method="POST" class="ajaxForm" id="add_links_form" style="min-height:300px;">	
	
	<div  id="common_tool_header" class="buttons">
		<button type="submit" id="link_save_sort" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Showroom Category</div>
	</div>	
		
	<div class="fieldsets">
		<b>Category Name</b> <input type="text" name="category" value="<?php echo $cat->name?>" rel="text_req" style="width:300px">
		<input type="hidden" name="url" value="<?php echo $cat->url;?>">
		<textarea name="intro" class="render_html"><?php echo $cat->intro;?></textarea>
	</div>
	
</form>
