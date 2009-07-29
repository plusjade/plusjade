
<h3>Listing all websites with admin users.</h3>

<p>Total Sites: <b><?php echo $sites->count()?></b></p>
<ul style="line-height:1.6em">
	<?php

	foreach($sites as $site)
	{
		echo "<li><div><a href=\"/get/utada/get_site/$site->id\">$site->subdomain</a></div>";
		echo '<ul style="font-size:0.9em">';
		foreach($site->users as $user)
			echo "<li>$user->username</li>";
		echo '</ul></li>';
	}
	?>
</ul>