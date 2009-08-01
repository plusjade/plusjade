
<style type="text/css">


.multi_website_message,
.own_domain{
padding:10px;
margin:10px;
background:#eee;
}
.own_domain{
background:#ffffcc;
}
</style>
<div style="margin:10px 20px;">


	<h2>Hello there, <?php echo ucfirst($user->username)?>!</h2>

	<h3>Your Websites</h3>
	<div id="site_button_wrapper" style="margin:10px; padding:10px; min-height:100px; border:1px dashed #ddd;">
		<?php
		foreach($sites_array as $name => $token)
		{
			?>
			<p>
				<b><?php echo $name?></b> &#8594; <a href="<?php echo "http://$name.". ROOTDOMAIN ."/get/auth/manage?tKn=$token"?>">Edit Website</a>  -- 
				<a href="<?php echo url::site("$page_name/safe_mode/$name")?>" class="ajaxify">Activate safe-mode</a>
			</p>
			<?php
		}
		
		
		if($is_admin)
			echo '<p><b>Admin</b> &#8594; <a href="/get/utada">Go to Master</a></p>';
		?>	
	</div>
		
	<h3>I want my own domain address!</h3>
	<div class="own_domain">
		+Jade allows full domain-name mapping. 
		<br>This means your site can run @ http://mysite.com
		<br>Unfortunately, this will not be enabled during our beta period.
	</div>
	
	
	<h3>Add New Website</h3>
	<div class="multi_website_message">
		You can add up to 3 websites to this account.
		<br>Sure you can create a new account and get 3 more,
		<br>but +Jade asks that you help us by focusing your testing efforts!
	</div>
	<?php if(3 > count($sites_array) OR $this->account_user->get_user()->username == 'jade'):?>
		subdomain:
		<form id="new_website_form" action="<?php echo url::site("$page_name/new_website")?>" method="POST">
			<input type="text" name="site_name" rel="text_req" class="auto_filename" style="width:300px">
			<br>
			<br>
			<button type="submit">Create New Website</button>
		</form>
	<?php else:?>
		You already have 3 websites =D.
	<?php endif;?>

</div>


<script type="text/javascript">
	$('a.ajaxify').click(function(){
		var url = $(this).attr('href');
		$('#auth_content_wrapper')
		.html('<div class="ajax_loading">Loading...</div>')
		.load(url);
		return false;
	});
	
$('#new_website_form').ajaxForm({
	target: "#auth_content_wrapper",
	beforeSubmit: function(){
		if(!$("#new_website_form input").jade_validate()) return false;
		$('#auth_content_wrapper').html('<div class="ajax_loading">Loading...</div>');
	}		
});
</script>




