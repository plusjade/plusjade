

<style type="text/css">
.benefit_wrapper{
width:50%;
float:left;
padding:10px;
}
.create_wrapper{
width:44%;
float:left;
padding:10px;
}
#login_claim_wrapper{
	padding:10px;
	margin:15px 0;
	background:#ffffcc;
	text-align:center;
}
.login_response{
	text-align:center;
}
.login_response div.success{
	padding:7px;
	background:lightgreen;
}
.login_response div.error{
	padding:7px;
	background:lightpink;
}
#why_claim{
	margin-bottom:15px;
	padding:10px;
	border:1px dashed #ddd;
	background:#eee;
	line-height:1.4em
}

</style>

<div id="claim_wrapper">

	<div id="common_tool_header" class="buttons">
		<div id="common_title">Claim Your Website by Registering or Logging in.</div>
	</div>	

	<div id="login_claim_wrapper">
		<form action="/get/auth/claim_login" method="POST">
			<b>Have an account?</b> 
			Username <input type="text" name="username" rel="text_req" maxlength="50"> 
			Password <input type="password" name="password" rel="text_req" maxlength="50"> 
			<button type="submit" name="blah" class="jade_positive">Claim Website</button>
		</form>
	</div>
	<div class="login_response"></div>


	<div class="benefit_wrapper">

		<div id="why_claim">
			<b>Why Should I claim my website?</b>
			<br>Unclaimed websites are deleted 7 days after creation.
			<br>A cookie is used to establish editing privileges.
			This means only the computer and browser you used to create the site, can edit the site.
			If the cookie is removed, you can no longer edit your site.
		</div>
		
		<h2>Registration Benefits.</h2>
		
		<ul style="line-height:1.7em; font-size:1.2em;">
			<li>Website expiration date is lifted.</li>
			<li>Remove the "beta-" from your website name.</li>
			<li>Add password protection to your website.</li>
			<li>Edit your website anywhere.</li>
			<li>Easily manage multiple website from one account.</li>
			<li>Get credit for helping us during our beta period.</li>
			<li>Contribute to +Jade community sections.</li>
			<li>+Jade user accounts are global.</li>
			
		</ul>
	</div>

	<div class="create_wrapper">
		<form id="claim_form" action="/get/auth/claim" method="POST">

			<div class="aligncenter">
				http://<?php echo ROOTDOMAIN ."/users/profile"?>/<span id="link_example">...</span>
			</div>

			<div id="auth_form" class="create_form" style="width:330px">
				<?php
				if(! empty($errors))
					if(is_array($errors))
						foreach($errors as $error)
							echo "<p>$error</p>";
					else
						echo "<b>$errors</b>";
				?>		
				<div class="fieldsets">
					<b>Username</b>
					<br><input type="text" name="username" rel="text_req" class="auto_filename" maxlength="25"/>
					<br>
					<b>Email</b>
					<br><input type="text" name="email" class="full"  rel="email_req" maxlength="50"/>
					<br>
					<b>Password</b>
					<br><input type="password" name="password" class="full" rel="text_req" maxlength="50" />
					<br>
					<b>Confirm Password</b>
					<br><input type="password" name="password2" class="full" rel="text_req" maxlength="50"/>
					<br>
					<div class="buttons">
						<button type="submit" class="jade_positive">Create Account</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	
</div>

<script type="text/javascript">

	
	
// validate create new form.
	$('#claim_form').ajaxForm({
		beforeSubmit: function(fields, form){
			if(! $("input", form[0]).jade_validate() ) return false;
			if($("#claim_form input[name='password']").val() != $("#claim_form input[name='password2']").val()) {
				alert('Password does not match confirmation');return false;
			}
			$('.admin_reset .show_submit').show();
			$('#show_response_beta').html('waiting for response...');
		},
		success: function(data) {
			$('.admin_reset .show_submit').hide();
			$('#claim_wrapper').html(data);
			
		}
		
	});
	

// validate login form.
	$('#login_claim_wrapper form').ajaxForm({
		beforeSubmit: function(fields, form){
			if(! $("input", form[0]).jade_validate() ) return false;
			$('.admin_reset .show_submit').show();
			$('#show_response_beta').html('waiting for response...');
		},
		success: function(data) {
			$('.admin_reset .show_submit').hide();
			$('.login_response').html(data);
		}
	});
	

</script>
