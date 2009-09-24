<?php
if(!empty($confirm_link))
	echo '<div style="padding:10px; text-align:center;">' , $confirm_link , '</div>';

?>
<p>
	Destory major site assets (not tools) associated with a site.
	<p>
		Destroys data directory folder.
	</p>
	
	Database tables affected:
	<ul>
		<li>Sites</li>
		<li>sites_users</li>
		<li>pages</li>
	</ul>
</p>
<ul>
	<?php	
	foreach($sites as $site)
		echo "<li><a href=\"/get/utada/destroy/$site->site_id/$site->subdomain\">$site->subdomain</a></li>";
	?>
</ul>