
<a href="<?php echo url::site("$page_name/create")?>">Create Account</a>

<?php if(!empty($account->login_title)):?>
	<div id="tagline"><?php echo $account->login_title?></div>
<?php endif;?>

<div id="auth_form" class="login_form">

	<?php if(isset($errors)) echo val_form::show_error_box($errors);?>
	<?php if(isset($failed_login)) echo val_form::show_invalid_box('Invalid username or password');?>
	
	<form id="login_form" action="<?php echo url::site("$page_name")?>" method="POST">
		<?php
		if(!isset($values)) $values = array();
		if(!isset($errors)) $errors = array();
		echo val_form::generate_fields($fields, $values, $errors);
		?>
		<button type="submit" accesskey="enter">Login</button>
	</form>
	
	<div class="login_help_toggle"><a href="#login_help">Trouble logging in?</a></div>

	<div id="login_help">
		<form id="reset_form" action="<?php echo url::site("$page_name/reset_password")?>" method="POST">
			<b>I do not remember my username/password:</b>
			<p>
				Enter the email address you used to create your account.
				<br/>Your password will be reset. Further instructions will follow.
			</p>
			
			<fieldset>
				<label>Email</label>
				<br/><input type="text" name="email" rel="email_req" maxlength="50">
			</fieldset>
			
			<button type="submit" accesskey="enter">Submit</button>
			
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


