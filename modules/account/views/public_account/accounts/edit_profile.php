

<div id="edit_profile_wrapper">

	<h1>Edit Profile</h1>

	<?php if(!empty($status)) echo "<div class=\"status aligncenter\">$status</div>"?>
	<form id="account_edit_profile" action="<?php echo url::site("$page_name/edit_profile")?>" method="POST">
		<?php
		if(FALSE == $meta)
		{
			?>
			<b>Bio</b>
			<br><textarea name="bio"></textarea>
			<?php
		}
		else
			foreach($meta as $meta)
			{
				?>
				<b><?php echo ucwords($meta->key)?></b>
				<br><textarea name="<?php echo "$meta->id"?>" style="min-height:90px"><?php echo $meta->value?></textarea>
				<?php
			}
		?>
		<br><button type="submit">Save Changes</button>
	</form>
</div>

<script type="text/javascript">
//post the change password form via ajax.

$('#account_edit_profile').ajaxForm({
	beforeSubmit: function() {
		$('#edit_profile_wrapper').html('<div class="ajax_loading">loading...</div>');
	},
	success: function(data) {
		$('#edit_profile_wrapper').html(data);
	}
});

</script>

