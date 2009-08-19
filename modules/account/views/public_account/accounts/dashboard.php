

<ul class="account_user_actions">
	<li><a href="<?php echo url::site("$page_name")?>" rel="ajax">Dashboard</a></li>
	<li><a href="<?php echo url::site("$page_name/edit_profile")?>" rel="ajax">Edit Profile</a></li>
	<li><a href="<?php echo url::site("$page_name/change_password")?>" rel="ajax">Change Password</a></li>
	<li><a href="<?php echo url::site("$page_name/logout")?>">Logout</a></li>
</ul>

<div id="inject_content_wrapper">
	<?php
	if(empty($content))
	{	?>
		<p>
		Hello, <b><?php echo $account_user->username?>!</b>
		</p>

		Welcome to your user account.
		<br>
		You are currently logged in
		<br>
		here's all your cool extra options.
		<?php
	}
	else
		echo $content;
	?>
</div>