
<style type="text/css">
	.left_master{
		float:left;
		width:45%;
		padding:10px;
	}
	.right_master{
	float:right;
	width:45%;	
	padding:10px;
	}
	
	ul.list_users{
		line-height:1.6em;
		list-style:none;
		margin:0;
		padding:0;
	}
	ul.list_users div{
	background:#ffffcc;
	margin:2px;
	padding:2px 3px;
	}

</style>

<div id="master_wrapper">

	<ul class="common_tabs_x ui-tabs-nav">
		<li><a href="/get/utada/all_users">All Users</a></li>	
		<li><a href="/get/utada/all_sites">All Websites</a></li>	
		<li><a href="/get/utada/clean_db">Clean Database</a></li>	
	</ul>

	<div class="left_master">
		<ul>
		<?php 
			foreach($sites as $site)
				echo "<li>$site->subdomain<br><a href=\"/get/utada/remove_access/$site->id\">[x]Access</a></li>";
		?>
		</ul>
	
	</div>
	
	
	<div class="right_master">

	</div>
</div>


<script type="text/javascript">
	$('#master_wrapper').click($.delegate({
	
		'.common_tabs_x a, .left_master a':function(e){
			$('div.left_master').html('<div class="ajax_loading">Loading...</div>');
			$('div.left_master').load(e.target.href);
			return false;
		}
	}));
</script>

