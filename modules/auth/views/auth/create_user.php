
<div id="tagline">
	Join <span class="text_logo"><b>+</b>Jade</span> &#8594; Create your website in minutes.
</div>

<form action="/get/auth/create" method="POST">	
	<div id="auth_form" class="create_form">
		<?php
			
			if(is_array($errors))
				foreach($errors as $error)
					echo "<p>$error</p>";
			else
				echo "<b>$errors</b>";
		?>

		<div class="fieldsets">
			<b>Beta Code</b><br>
			<input type="text" name="beta" value="<?php echo $values['beta']?>" class="full" rel="text_req" maxlength="50">
		</div>
		
		<div class="fieldsets">
			<b>Username</b><br>
			http://<input type="text" name="username" value="<?php echo $values['username']?>" rel="text_req" size="20" maxlength="25" style="width:230px !important">.plusjade.com
		</div>

		<div class="fieldsets">
			<b>Email</b><br>
			<input type="text" name="email" value="<?php echo $values['email']?>" class="full" rel="text_req" maxlength="50">
		</div>
		
		<div class="fieldsets">
			<b>Password</b><br>
			<input type="password" name="password" class="full" rel="text_req" maxlength="50" />
		</div>
		
		<div class="fieldsets">
			<b>Confirm Password</b><br>
			<input type="password" name="password2" class="full" rel="text_req" maxlength="50"/>
		</div>
		
		<div class="buttons">
			<button type="submit" class="jade_positive">Create My Website</button>
		</div>
		
	</div>
</form>


<script type="text/javascript">

$(document).ready(function()
{
	$('form').ajaxForm({
		beforeSubmit: function(fields, form){
			if(! $("input", form[0]).jade_validate() ) return false;
			$('button').attr('disabled', 'disabled').html('Submitting...');
		},
		success: function(data) {
		}
	});
});

</script>




