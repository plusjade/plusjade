

<h2>Update email address</h2>
<br><input type="text" name="email" value="<?php echo $user->email?>"> <button type="submit"> Save Email</button>

<br>
<br>

<h2>Change Password</h2>
<form id="jade_login_form" action="/get/auth/account" method="POST">
	
	<div id="auth_form" class="create_form">
	
		<div class="aligncenter">
			New Password must be 5+ characters.
			<br><?php if(!empty($error)) echo $error?>
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

