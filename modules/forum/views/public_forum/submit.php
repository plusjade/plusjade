


<form id="forum_submit_item" action="<?php echo url::site("$page_name/submit")?>" method="POST" class="fieldsets">
	
	<h3>Submit a New Post</h3>

	<b>Category</b>
	<select name="forum_cat_id">
		<?php
		foreach($categories as $cat)
			echo "<option value=\"$cat->id\">$cat->name</option>";
		?>
	</select>
	
	<br><br>
	
	<b>Title</b>
	<br><input type="text" name="title" rel="text_req">
	
	<br><br>
	
	<b>Post</b>
	<br><textarea name="body"></textarea>
	
	<br><br>
	<button type="submit">Post</button>
</form>


<script type="text/javascript">
$('#forum_submit_item').ajaxForm({
	//target: "#contact_wrapper_%VAR% #newsletter_form",
	beforeSubmit: function(fields, form) {
		if(!$("input[type=text]", form[0]).jade_validate() ) return false;
		
		$('#forum_submit_item').html('<div class="ajax_loading">loading...</div>');
	},
	success: function(data) {
		$('#forum_submit_item').html(data);
	}
});

</script>




