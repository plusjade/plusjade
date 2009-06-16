

<h3>Listing all Users + websites with admin permissions.</h3>

<p>Total Users: <b><?php echo $users->count()?></b></p>
<ul class="list_users">
	<?php
	foreach($users as $user)
	{
		echo "<li><div>User: <b><a href=\"/get/utada/get_user/$user->id\">$user->username</a></b></div>";
		
		if(!empty($user->site_string))
		{
			$site_array = explode('|', $user->site_string);
			echo '<ul style="font-size:0.9em">';
			foreach($site_array as $site)
			{
				$data = explode(':', $site);
				list ($id, $site_name) = $data;
				echo "<li><a href=\"/get/utada/get_site/$id\">$site_name</a> ($id)</li>";
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	?>
</ul>
	