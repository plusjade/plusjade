

<h3>Listing all Users + websites with admin permissions.</h3>

<p>Total Users: <b><?php echo $users->count()?></b></p>
<ul class="list_users">
	<?php
	foreach($users as $user)
	{
		echo "<li><div>User: <b><a href=\"/get/utada/get_user/$user->id\">$user->username</a></b></div>";
		
		echo '<ul style="font-size:0.9em">';
		foreach($user->sites as $site)
			echo "<li><a href=\"/get/utada/get_site/$site->id\">$site->subdomain</a> ($site->id)</li>";
		
		echo '</ul></li>';
	}
	?>
</ul>
	