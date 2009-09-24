
<?php
	if(isset($errors) && is_array($errors))
		foreach($errors as $error)
			echo "<b class=\"error\">$error</b><br/>";
?>
<div class="newsletter_form_wrapper">
	<b>Sign up for our newsletter</b>
	<form action="<?php echo url::site($page_name)?>" method="post">
		<div>
			<label for="name">Name:</label>
			<br/><input name="name" id="name" type="text" rel="text_req" value="<?php echo $values['name']?>">
			
			<br/><label for="email">Email:</label>
			<br/><input name="email" id="email" type="text" rel="email_req" value="<?php echo $values['email']?>">
			<br/><button type="submit">Subscribe</button>
		</div>
	</form>
</div>

<script type="text/javascript">

	$('.newsletter_form_wrapper form').ajaxForm({
		beforeSubmit: function(fields, form) {
			if(!$("input", form[0]).jade_validate() ) return false;
			$(form[0]).parent('div').html('<div class="ajax_loading">loading...</div>');
		},
		success: function(data) {
			$('.newsletter_form_wrapper').html(data);
		}	
	});
</script>