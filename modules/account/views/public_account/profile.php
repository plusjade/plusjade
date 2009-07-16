

<?php
if(!$account_user)
	echo 'User does not exist';
else
{
	?>
	<h1><?php echo $account_user->username?>'s Profile</h1>

	<?php
	foreach($meta as $meta)
	{
		?>
		<h3><?php echo ucwords($meta->key)?></h3>
		<blockquote><?php echo $meta->value?></blockquote>
		<?php
	}
}