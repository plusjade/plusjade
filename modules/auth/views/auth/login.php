
<div id="tagline">Welcome Back!</div>

<div id="auth_form" class="login_form">
	
	<form action="/get/auth" method="POST">
	
		<div class="fieldsets">
			<b>Username</b><br>
			<input type="text" name="username" rel="text_req">	
		</div>

		<div class="fieldsets">
			<b>Password</b><br>
			<input type="password" name="password" rel="text_req">
		</div>

		<div class="buttons">
			<button type="submit" name="submit" class="jade_positive" accesskey="enter">
				Log me in buddy!
			</button>
		</div>
		
	</form>
	
	<div class="error"><?php if(!empty($errors)) echo $errors?></div>
	
</div>