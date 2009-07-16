
<h1>All Users</h1>
<ul>
<?php
	foreach($users as $user)
	{
		?>
		<li><a href="<?php echo url::site("$page_name/profile/$user->username")?>"><?php echo $user->username?></a></li>
		
		
		<?php
	}
?>
</ul>