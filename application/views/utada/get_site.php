
<h2>Website Data</h2>
<div class="utada-box">
	Site id: <?php echo $site->id?>
	<br/>Site Subdomain: <?php echo $site->subdomain?>
	<br/>Custom Domain:  <a href="http://<?php echo $site->custom_domain?>">http://<?php echo $site->custom_domain?></a>
</div>


<h2>Admin Users</h2>
<div class="utada-box">
	<ul class="data_list">
		<?php foreach($site->account_users as $user):?>
			<li>
				<a href="/get/utada/get_user/<?php echo $user->id?>"><?php echo $user->username?></a>
				 - <a href="/get/utada/remove_access?site_id=<?php echo $site->id?>&user_id=<?php echo $user->id?>">[x]Access</a>
			</li>
		<?php endforeach;?>
	</ul>
</div>

<h2>Actions</h2>
<div class="utada-box">
	<a href="http://<?php echo "$site->subdomain." . ROOTDOMAIN?>">Go to website</a>

	<br/><br/>
	
	<b>Destory this site</b>
	<form action="/get/utada/destroy_site" id="destroy_form" method="post">
		Are you sure?
		<br/><input type="text" name="confirm">
		<br/>Password:
		<br/><input type="password" name="password" maxlength="20">
		<br/><button type="submit">Destroy!</button>
		<input type="hidden" name="site_name" value="<?php echo $site->subdomain?>">
		<input type="hidden" name="site_id" value="<?php echo $site->id?>">
	</form>	
</div>
<script type="text/javascript">


		$('#destroy_form').ajaxForm({		 
			beforeSubmit: function(fields, form) {
				$('#destroy_form').html('Submitting...');
			},
			success: function(data) {
				$('#destroy_form').html(data);
			}
		});
</script>
