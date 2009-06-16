
<h3 class="aligncenter">Website Data</h3>

<p>
Site Subdomain: <?php echo $site->subdomain?>
<br>Custom Domain:  <a href="http://<?php echo $site->custom_domain?>">http://<?php echo $site->custom_domain?></a>
</p>


<h3>Users with admin access:</h3>
<?php
	foreach($users as $user)
	{
		echo "$user->username<br>";
	}
?>

<p>
	<h3>Actions</h3>
	
	<a href="http://<?php echo "$site->subdomain." . ROOTDOMAIN?>">Go to website</a>
	<br>
	<br>	
	<form action="/get/utada/grant_access" method="post">
		Grant Temp Access
		<input type="hidden" name="site_id" value="<?php echo $site->site_id?>">
		<br>Password: <input type="password" name="password" maxlength="20">
		<button type="submit">Submit</button>
	</form>	
	<br>
	<br>	
	<a href="/get/utada/destroy_site/<?php echo "$site->site_id/$site->subdomain"?>">Delete <?php echo $site->subdomain?></a>
	

</p>

