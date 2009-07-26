
<div id="tagline">Welcome Back!</div>

<div id="auth_form" class="login_form">
	
	<form id="login_form" action="/get/auth" method="POST">
	
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

	
	<div class="login_help_toggle"><a href="#login_help">Trouble logging in?</a></div>

	<div id="login_help">
		<form id="reset_form" action="/get/auth/reset_password" method="POST">
			<b>I do not remember my username/password:</b>
			<p>
				Enter your account username below.
				<br>Your password will be reset and emailed to the address on file. Further instructions will follow.
			</p>
			
			<div class="fieldsets buttons">
				<b>Username</b>
				<br><input type="text" name="username" rel="text_req" maxlength="50">
				<p><button type="submit" class="jade_positive">Reset Password</button></p>
			</div>

			<p><b>I do not remember my password nor my username.</b></p>
			Please contact support(at)<?echo ROOTDOMAIN?>
		</form>
	</div>

</div>


<script type="text/javascript">
	$('#login_help').hide();
	$('.login_help_toggle a').click(function(){
		$('#login_help').slideToggle('fast');
		return false;
	});
	
	$('#login_form').submit(function(){
		if(!$("#login_form input").jade_validate())
			return false;
	});
	$('#reset_form').submit(function(){
		if(!$("#reset_form input").jade_validate())
			return false;
	});
	
/*	
	$('form').ajaxForm({
		beforeSubmit: function(fields, form){
			if(! $("input", form[0]).jade_validate() ) return false;
		},
		success: function(data) {
		}
	});
*/
</script>


