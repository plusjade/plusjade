
<h3 class="aligncenter">User Data</h3>

<p>
Username: <?php echo $user->username?>
<br>Email:  <?php echo $user->email?>
<br>Logins:  <?php echo $user->logins?>
<br>Last Login:  <?php echo date("D M d, Y @ g:i A", $user->last_login)?>
</p>

<p>
	<h3>Actions</h3>

	<br>
	<br>	
	<a href="/get/utada/destroy_user/<?php echo $user->username?>">Delete this user</a>
	

</p>

