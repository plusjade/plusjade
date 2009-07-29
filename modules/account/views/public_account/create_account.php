
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
		if(! empty($errors))
			if(is_array($errors))
				foreach($errors as $error)
					echo "<p>$error</p>";
			else
				echo "<b>$errors</b>";
		?>		
		<div class="fieldsets">
			<b>Username</b><br>
			<input type="text" name="username" value="" rel="text_req" maxlength="25"/>
		</div>

		<div class="fieldsets">
			<b>Email</b><br>
			<input type="text" name="email" value="" class="full"  rel="email_req" maxlength="50"/>
		</div>
		
		<div class="fieldsets">
			<b>Password</b><br>
			<input type="password" name="password" class="full" rel="text_req" maxlength="50" />
		</div>
		
		<div class="fieldsets">
			<b>Confirm Password</b><br>
			<input type="password" name="password2" class="full" rel="text_req" maxlength="50"/>
		</div>
		
		<div class="buttons">
			<button type="submit" class="jade_positive">Create Account</button>
		</div>
		
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
