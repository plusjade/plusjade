

<div style="padding:10px; margin-bottom:10px; background:lightblue;">
	Hello this is the forum.
</div>

	
<div id="forum_index_wrapper">
	<ul>
		<li><a href="<?php echo url::site("$page_name")?>">Home</a></li>
		<li><a href="<?php echo url::site("$page_name/submit")?>">New Post</a></li>
	</ul>

	
	<h3>Categories</h3>
	
	<ul id="forum_categories">
	<?php
	foreach($categories as $cats)
	{
		?>
		<li><a href="<?php echo url::site("$page_name/category/$cats->url")?>"><?php echo $cats->name?></a></li>
		<?php
	}
	?>
	</ul>
</div>

<div id="forum_content_wrapper">
	<?php echo $content?>
</div>
