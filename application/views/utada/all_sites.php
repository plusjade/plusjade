
<p>Total Sites: <b><?php echo $sites->count()?></b></p>

<ul class="sorters">
	<li><a href="/get/utada/all_sites?sort=alpha">Alphabetical</a></li>
	<li><a href="/get/utada/all_sites?sort=new">Newest</a></li>
</ul>

<ul class="main_list data_list" style="line-height:1.6em">
	<?php foreach($sites as $site):
		$domain = (empty($site->custom_domain)) ? '' : "http://$site->custom_domain";
	?>
		<li>
			<div>
				<a href="/get/utada/get_site/<?php echo $site->id?>"><?php echo $site->subdomain?></a>
				 <?php echo $domain?>
				  <small>(<?php echo $site->account_users->count()?>)</small>
			</div>
	</li>
	<?php endforeach;?>
</ul>