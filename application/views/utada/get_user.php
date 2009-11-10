
<h2>User Data</h2>
<div class="utada-box">
	Username: <?php echo $user->username?>
	<br/>Email:  <?php echo $user->email?>
	<br/>Logins:  <?php echo $user->logins?>
	<br/>Last Login:  <?php echo date("D M d, Y @ g:i A", $user->last_login)?>
</div>

<h2>Sites</h2>
<div class="utada-box">
	<ul class="data_list">
		<?php foreach($user->sites as $site):?>
			<li><a href="/get/utada/get_site/<?php echo $site->id?>"><?php echo $site->subdomain?></a></li>
		<?php endforeach;?>
	</ul>
	
	<br/><b>Add Access</b>
	<form action="/get/utada/add_access" id="add_access_form" method="post">
		Password:
		<br/><input type="password" name="password" maxlength="20">
		<br/>Site Id:
		<br/><input type="text" name="site_id" style="width:200px">
		<br/><button type="submit">Add Site</button>
		<input type="hidden" name="user_id" value="<?php echo $user->id?>">
	</form>		
	
</div>

<h2>Actions</h2>
<div class="utada-box">
	<a href="/get/utada/destroy_user?user_id=<?php echo $user->id?>">Delete this user</a>	
</div>


<script type="text/javascript">
		$('#add_access_form').ajaxForm({		 
			beforeSubmit: function(fields, form) {
				$('#add_access_form').html('Submitting...');
				//if(! $("input", form[0]).jade_validate() ) return false;
			},
			success: function(data) {
				$('#add_access_form').html(data);
			}
		});
</script>