

<div style="text-align:center; padding:10px; margin-bottom:10px; background:lightblue;">
	Hello this is the forum.
</div>

	
<div id="forum_navigation_wrapper">

	<a href="<?php echo url::site("$page_name/submit")?>" id="submit_new">Submit New</a>	
	
	<h3>My Stuff</h3>
	<ul>
		<li><a href="<?php echo url::site("$page_name/my/posts")?>">Posts</a></li>
		<li><a href="<?php echo url::site("$page_name/my/comments")?>">Comments</a></li>
		<!--<li><a href="<?php echo url::site("$page_name/my/starred")?>">Starred</a></li> -->
	</ul>

	<h3>Categories</h3>	
	<ul id="forum_categories">
		<li><a href="<?php echo url::site("$page_name/category/all")?>">All</a></li>
	<?php if(FALSE !== $categories) foreach($categories as $cats):?>
		<li><a href="<?php echo url::site("$page_name/category/$cats->url")?>"><?php echo $cats->name?></a></li>
	<?php endforeach;?>
	</ul>
</div>

<div id="forum_content_wrapper">
	<?php echo $content?>
</div>
