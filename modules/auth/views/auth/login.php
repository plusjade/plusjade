

<div id="jade_login_form_wrapper" class="jade_auth_form">
	
	<form id="jade_login_form" action="/get/auth" method="POST">	
		<div class="fieldsets">
			<b>Username</b><br>
			<input type="text" name="username" rel="text_req">	
		</div>

		<div class="fieldsets">
			<b>Password</b><br>
			<input type="password" name="password" rel="text_req">
		</div>

		<div class="buttons">
			<button type="submit" name="submit" class="jade_positive" accesskey="enter"> Login </button>
		</div>
	</form>
	
	<div class="error"><?php if(!empty($errors)) echo $errors?></div>
	
</div>