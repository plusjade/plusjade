
<a href="<?php echo url::site("$page_name/create")?>">Create Account</a>

<?php if(!empty($account->login_title)):?>
	<div id="tagline"><?php echo $account->login_title?></div>
<?php endif;?>

<div id="auth_form" class="login_form">
	
	<form id="login_form" action="<?php echo url::site("$page_name")?>" method="POST">
		<div class="fieldsets">
			<b>Username</b>
			<br><input type="text" name="username" rel="text_req">	
		</div>

		<div class="fieldsets">
			<b>Password</b>
			<br><input type="password" name="password" rel="text_req">
		</div>

		<div class="buttons">
			<button type="submit" class="jade_positive" accesskey="enter">Login</button>
		</div>
	</form>
	
	<div class="error"><?php if(!empty($errors)) echo $errors?></div>

	<div class="login_help_toggle"><a href="#login_help">Trouble logging in?</a></div>

	<div id="login_help">
		<form id="reset_form" action="<?php echo url::site("$page_name/reset_password")?>" method="POST">
			<b>I do not remember my username/password:</b>
			<p>
				Enter the email address you used to create your account.
				<br>Your password will be reset. Further instructions will follow.
			</p>
			
			<div class="fieldsets buttons">
				<b>Email</b>
				<br><input type="text" name="email" rel="email_req" maxlength="50">
				<p><button type="submit" class="jade_positive" accesskey="enter">Submit</button></p>
			</div>

			<p><b>I do not remember my password nor my email address.</b></p>
			Please contact support(at)<?php echo str_replace('http://', '', url::site())?>
		</form>
	</div>

</div>


<script type="text/javascript">
	$('#login_help').hide();
	$('.login_help_toggle a').click(function(){
		$('#login_help').slideToggle('fast');
		return false;
	});

// validate login form.
	$('#login_form').submit(function(){
		if(!$("#login_form input").jade_validate()) return false;
	});
	
// ajaxify pw reset form.
	$('#reset_form').ajaxForm({
		//target: "#contact_wrapper_%VAR% #newsletter_form",
		beforeSubmit: function(fields, form) {
			if(!$("input", form[0]).jade_validate() ) return false;
			$('#login_help').html('<div class="ajax_loading">loading...</div>');
		},
		success: function(data) {
			$('#login_help').html(data);
		}	
	});
</script>


