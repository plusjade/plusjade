<?php
if(TRUE == $success)
{
	?>
	Your password has been changed!
	<p>An email has been sent to confirm these changes.</p>
	Thanks. =D
	<p><a href="/get/auth">My Dashboard</a></p>
	<?php
}
else
{
	?>
	<form id="jade_login_form" action="/get/auth/change_password" method="POST">
		
		<div id="auth_form" class="create_form">
		
			<div class="aligncenter">
				New Password must be 5+ characters.
				<br><?php echo $error?>
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
	<?php
}

