
<p>Total Users: <b><?php echo $users->count()?></b></p>

<ul class="sorters">
	<li><a href="/get/utada/all_users?sort=alpha">Alphabetical</a></li>
	<li><a href="/get/utada/all_users?sort=new">Newest</a></li>
</ul>

<ul class="main_list data_list">
	<?php foreach($users as $user):?>
	<li>
		<div>
			<b><a href="/get/utada/get_user/<?php echo $user->id?>"><?php echo $user->username?></a> <small>(<?php echo $user->sites->count()?>)</small></b>
		</div>
	</li>
	<?php endforeach;?>
</ul>
	