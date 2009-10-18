
<a href="<?php echo url::site("$page_name")?>">Login</a>


<?php if(!empty($account->create_title)):?>
	<div id="tagline"><?php echo $account->create_title?></div>
<?php endif;?>

<div id="url_sample" class="aligncenter">
	<?php echo url::site("$page_name/profile")?>/<span>...</span>
</div>

<form id="create_new_form" action="<?php echo url::site("$page_name/create")?>" method="POST">	
	<div id="auth_form" class="create_form">
		
		<?php
		if(isset($errors)) echo val_form::show_error_box($errors);		
		if(!isset($values)) $values = array();
		if(!isset($errors) OR !is_array($errors)) $errors = array();
		echo val_form::generate_fields($fields, $values, $errors);
		?>		
		<button type="submit">Create Account</button>
	</div>
</form>
<script type="text/javascript">

//sanitize and populate page_name fields
	$("input[name='username']").keyup(function(){
		var input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
		$(this).val(input);
		$('#url_sample span').html(input);
	});
	
// validate create new form.
	$('#create_new_form').submit(function(){
		if(!$("#create_new_form input").jade_validate()) return false;
		if($("#create_new_form input[name='password']").val() != $("#create_new_form input[name='password2']").val()) {
			alert('Password does not match confirmation');return false;
		}
	});
</script>
