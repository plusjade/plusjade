

<div id="add-comment-<?php echo $blog_post->id?>">

	<form action="<?php echo url::site("$page_name/entry/$blog_post->url#add-comment-$blog_post->id")?>" method="POST">
		<input type="hidden" name="blog_post_id" value="<?php echo $blog_post->id?>">	
		<div class="add_comment_title">Add Comment</div>	
		
		<?php
		# make the form
		if(isset($errors)) echo val_form::show_error_box($errors);
		if(!isset($values)) $values = array();
		if(!isset($errors)) $errors = array();
		echo val_form::generate_fields($fields, $values, $errors);
		?>
		<button type="submit">Add Comment</button>
	</form>

</div>

<script type="text/javascript">
	var add_wrapper = '#add-comment-<?php echo $blog_post->id?>';
	$(add_wrapper + ' form').ajaxForm({
		beforeSubmit: function(fields, form) {
			if(!$("input, textarea", form[0]).jade_validate())return false;
			$(fields).each(function() {
				$('#supa_injector_<?php echo $blog_post->id?> em.qwz_' + this.name).replaceWith(this.value);
			});			
			$(add_wrapper + ' form').html('<div class="ajax_loading">Submitting...</div>');
		},
		success: function(data) {
			$('#supa_injector_<?php echo $blog_post->id?>').show();
			$(add_wrapper + ' form').replaceWith(data);
		}
	});
</script>
