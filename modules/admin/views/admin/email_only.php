


<h2>Hello, Login Please!</h2>

<div id="auth_form" class="form-box">
	
	You have not registered an account with plusjade yet.
	Creating an account lets you password protect your website for better security.
	<br/>Until then, all you need is your email address!
	<br/><br/>
	<form id="login_form" action="" method="POST">
		<div class="fieldsets">
			<div>Email</div>
			<input type="text" name="email" rel="text_req" value="<?php #echo $values['email']?>">	
		</div>

		<div class="buttons">
			<button type="submit" class="jade_positive" accesskey="enter" style="padding:6px 30px;">Login</button>
		</div>
	</form>
	
	
</div>

<h2>Don't Remember Your Email?</h2>
<div class="form-box">
	<div id="login_help">

			<div class="indent">
				If you have forgotten which email you used to sign up for this account, try
				searching for "plusjade" within your email client. There is no other way for us
				to validate you as the owner of this site without your email address.
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


