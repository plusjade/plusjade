
<form action="/get/edit_showroom/add/<?php echo $tool_id?>" method="POST" class="ajaxForm" id="add_links_form" style="min-height:300px;">	
	
	<div  id="common_tool_header" class="buttons">
		<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $tool_id?>">
			<img src="/images/check.png" alt=""/> Add Category
		</button>
		<div id="common_title">Add a New Category</div>
	</div>	
		<div id="common_tool_info">
		<?php if(! empty($message) ) echo $message?>
		</div>
		
		
	<div class="fieldsets">
		<b>Category Name</b><br>
		<input type="text" name="category[]" class="full_width" rel="text_req">
	</div>

	<div class="fieldsets">
		<b>Category Name</b><br>
		<input type="text" name="category[]" class="full_width" rel="text_req">
	</div>
	
</form>

<script type="text/javascript">
	$("input[name='name']").keyup(function(){
		input = $(this).val().replace(/\W/g, '_').toLowerCase();
		$("input[name='url']").val(input);
	});
	$("input[name='url']").keyup(function(){
		input = $(this).val().replace(/\W/g, '_');
		$(this).val(input);
	});


</script>