

<form action="/get/edit_blog/add/<?php echo $tool_id?>" method="POST" class="ajaxForm" style="min-height:300px;">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Post
		</button>
		<div id="common_title">Add New Blog Post</div>
	</div>	
	
	<div class="fieldsets">
		<b>Title</b> <input type="text" name="title" size="50" rel="text_req">
		
		<p>
			<b>Url - Slug</b> <input type="text" name="url" size="50" rel="text_req">
		</p>
		
		<b>Category/tags</b> <input type="text" name="category" size="50">
	</div>
	
	<b>Body</b>	
	<textarea name="body" class="render_html"></textarea>
	
</form>

<script type="text/javascript">
$("input[name='title']").keyup(function(){
	input = $(this).val().replace(/\W/g, '_').toLowerCase();
	$("input[name='url']").val(input);
});
$("input[name='url']").keyup(function(){
	input = $(this).val().replace(/\W/g, '_');
	$(this).val(input);
});
</script>