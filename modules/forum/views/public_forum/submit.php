


<form id="forum_submit_item" action="<?php echo url::site("$page_name/submit")?>" method="POST" class="fieldsets">
	
	<h3>Submit a New Post</h3>

	<b>Category</b>
	<select name="forum_cat_id">
		<?php
		foreach($categories as $cat)
			echo "<option value=\"$cat->id\" rel=\"$cat->url\">$cat->name</option>";
		?>
	</select>
	
	<br><br>
	
	<b>Title</b>
	<br><input type="text" name="title" rel="text_req" style="width:500px">
	
	<br><br>
	
	<b>Post</b>
	<br><textarea name="body" style="height:150px;width:100%"></textarea>
	<br>
	<button type="submit">Submit</button>
</form>


<script type="text/javascript">
$('#forum_submit_item').ajaxForm({
	//target: "#contact_wrapper_%VAR% #newsletter_form",
	beforeSubmit: function(fields, form) {
		if(!$("input[type=text]", form[0]).jade_validate() ) return false;
	},
	success: function(data) {
		// todo: output a success message man.
		var category = $('select[name="forum_cat_id"] > option:selected').attr('rel');
		$('#forum_content_wrapper')
		.html('<div class="ajax_loading">loading...</div>')
		.load('<?php echo url::site("$page_name/category")?>/'+ category);
	}
});

</script>




