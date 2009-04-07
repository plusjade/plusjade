<?php
if(!empty($confirm_link))
	echo '<div style="padding:10px; text-align:center;">' , $confirm_link , '</div>';

?>
<p>
	Destory major site assets (not tools)
	associated with a site.
	<br>
	Destroys the parent user account as well (domain name)
</p>
<?php	
foreach($sites as $site)
{
	echo '<a href="/e/auth/destroy/' , $site->site_id , '/' , $site->url , '">' , $site->url , '</a><br><br>',"\n";
}