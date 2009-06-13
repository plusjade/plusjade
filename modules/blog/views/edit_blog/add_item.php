
<style type="text/css">
#left_panel{
	width:180px;
	float:left;
	border:1px solid red;
}
#main_panel{
	width:600px;
	float:right;
	
}
#main_panel div.inputs{
	width:500px;
	margin:0 auto;
	text-align:right;
	margin-bottom:5px;
}
#main_panel div.inputs input{
	width:400px;
}
#main_panel div.inputs b{
	float:left;
}
</style>

<form action="/get/edit_blog/add/<?php echo $tool_id?>" method="POST" class="ajaxForm" rel="<?php echo $js_rel_command?>">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Post
		</button>
		<div id="common_title">Add New Blog Post</div>
	</div>	
	
	<div id="left_panel" class="fieldsets">		
		<b>Tags</b>
		<br><input type="text" name="tags" style="width:150px">
		
		<br>
		<br><b>Status</b>
		<br><select name="status">
			<option>draft</option>
			<option>publish</option>
		</select>
	</div>
	
	<div id="main_panel" class="fieldsets">
		<div class="inputs">
			<b>Title</b> <input type="text" name="title" rel="text_req">
		</div>
		
		<div class="inputs">
			<b>Url</b> <input type="text" name="url" rel="text_req">
		</div>
		<textarea name="body" class="render_html"></textarea>
	</div>
</form>

<script type="text/javascript">
$("input[name='title']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
	$("input[name='url']").val(input);
});
$("input[name='url']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
	$(this).val(input);
});
</script>