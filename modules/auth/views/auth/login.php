
<div id="tagline">Welcome Back!</div>

<div id="auth_form" class="login_form">
	
	<form action="/get/auth" method="POST">
	
		<div class="fieldsets">
			<b>Username</b><br><br>
			<input type="text" name="username" rel="text_req" style="width:270px">	
		</div>

		<div class="fieldsets">
			<b>Password</b><br><br>
			<input type="password" name="password" rel="text_req" style="width:270px">
		</div>

		<div id="login_submit" class="buttons">
			<button type="submit" name="submit" class="jade_positive" accesskey="enter">
				Log me in buddy!
			</button>
		</div>
		
	</form>
	
	<div class="error"><?php if(!empty($errors)) echo $errors?></div>
	
</div>