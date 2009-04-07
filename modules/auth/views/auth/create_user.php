
<?php
$errors = '';
	
if(! empty($error) )
	if( is_array($error) )
		foreach($error as $error)
			$errors .= $error.'<br>';
	else
		$errors .= $error;
?>
	
<div id="auth_register_form">
	<b><?php echo $errors?></b>
	
	<form action="" method="POST">
		<div class="fieldsets">
			<b>Email</b><br>
			<input type="text" name="email" class="full" maxlength="50">
		</div>
		
		<div class="fieldsets">
			<b>http://</b><input type="text" name="username" size="20" maxlength="25"/>.plusjade.com
		</div>
		
		<div class="fieldsets">
			<b>Password</b><br />
			<input type="password" name="password" class="full" maxlength="50" />
		</div>
		
		<div class="fieldsets">
			<b>Confirm Password</b><br />
			<input type="password" name="password2" class="full" maxlength="50"/>
		</div>
		
		<div class="buttons">
			<button type="submit" class="jade_positive">Create Account</button>
		</div>
		
	</form>
</div>
