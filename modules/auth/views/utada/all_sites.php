
<h3>Listing all websites with admin users.</h3>

<p>Total Sites: <b><?php echo $sites->count()?></b></p>
<ul style="line-height:1.6em">
	<?php

	foreach($sites as $site)
	{
		echo "<li><div><a href=\"/get/utada/get_site/$site->site_id\">$site->subdomain</a></div>";
		if(!empty($site->users_string))
		{
			$users_array = explode(',', $site->users_string);
			echo '<ul style="font-size:0.9em">';
			foreach($users_array as $user)
			{
				echo "<li>$user</li>";
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	?>
</ul>