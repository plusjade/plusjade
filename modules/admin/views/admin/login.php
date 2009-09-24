


<h2>Hello, Login Please!</h2>

<div id="auth_form" class="form-box">
	
	<form id="login_form" action="" method="POST">
		<div class="fieldsets">
			<div>Username</div>
			<input type="text" name="username" rel="text_req" value="<?php echo $values['username']?>">	
		</div>

		<div class="fieldsets">
			<div>Password</div>
			<input type="password" name="password" rel="text_req">
		</div>

		<div class="buttons">
			<button type="submit" class="jade_positive" accesskey="enter" style="padding:6px 30px;">Login</button>
		</div>
	</form>
	
	
</div>

<h2>Trouble Loggin in?</h2>
<div class="form-box">
	
	
	<div id="login_help">
		
			<div class="indent">I forgot my password:</div>			
			<br/>
			<form id="reset_form" action="" method="POST" style="width:400px">
				<div class="fieldsets buttons">
					<div>Username or Email</div>
					<input type="text" name="email" rel="text_req" maxlength="50">
					<button type="submit" class="jade_positive" accesskey="enter">Submit</button>
				</div>
			</form>
			<br/>
			<div class="indent">
				Enter your username OR the email address you used to create your account.
				We will email you a NEW password with instructions.
				<p><b>I do not remember any of this information.</b></p>
			</div>
	</div>

</div>


<script type="text/javascript">

$(document).ready(function(){				

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
});
</script>


