

<ul id="vertical_tabs" class="ui-tabs-nav">	
	<li><a href="/get/auth" class="ui-tabs-selected"><span>Dashboard</span></a></li>
	<li><a href="/get/auth/account"><span>Account</span></a></li>
</ul>


<div id="auth_content_wrapper">
	<?php if(!empty($message)):?>
		<div style="padding:10px; background:#ffffcc; font-weight:bold; text-align:center;"><?echo $message?></div>
	<?php endif;?>
	<?php echo $content?>
</div>