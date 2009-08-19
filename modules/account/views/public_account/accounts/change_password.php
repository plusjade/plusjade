

<div class="change_password_wrapper">
	<?php if(TRUE == $success):?>
		Your password has been changed!
		<p>An email has been sent to confirm these changes.</p>
		Thanks. =D
	<?php else:?>
		<form id="account_change_password" action="<?php echo url::site("$page_name/change_password")?>" method="POST">
			
			<div id="auth_form" class="create_form">
			
				<div class="aligncenter">
					New Password must be 5+ characters.
					<br><b style="color:red"><?php echo $error?></b>
				</div>
				
				<div class="fieldsets">
					<b>Old Password</b><br><br>
					<input type="password" name="old_password" rel="text_req" class="full_width">	
				</div>

				<div class="fieldsets">
					<b>New Password</b><br><br>
					<input type="password" name="password" rel="text_req" class="full_width">
				</div>

				<div class="fieldsets">
					<b>Confirm New Password</b><br><br>
					<input type="password" name="password_confirm" rel="text_req" class="full_width">
				</div>
				
				<div id="login_submit" class="buttons">
					<button type="submit" class="jade_positive" accesskey="enter">
						Change Password
					</button>
				</div>
			</div>
		</form>
	<?php endif;?>
</div>

<script type="text/javascript">
$('#account_change_password').ajaxForm({
	//target: "#contact_wrapper_%VAR% #newsletter_form",
	beforeSubmit: function(fields, form) {
		if(!$("input[type=password]", form[0]).jade_validate() ) return false;
		
		if(fields[1].value != fields[2].value)
		{
			alert('New Password does not match confirmation');return false;
		}
		$('.change_password_wrapper').html('<div class="ajax_loading">loading...</div>');
	},
	success: function(data) {
		$('.change_password_wrapper').html(data);
	}
});
</script>



