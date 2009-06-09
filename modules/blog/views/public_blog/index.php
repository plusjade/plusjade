

<div class="blog_navigation">

	<div class="tags_list">
		<h3>Tags</h3>
			<ul>
			<?php
				foreach($tags as $tag)
					echo '<li><a href="'.url::site("$blog_page_name/tag/$tag->value").'">'.$tag->value . '</a> <span>('.$tag->qty.')</span></li>';
			?>
			</ul>
	</div>

	<?php
		if(! empty($sticky_posts) )
		{
			?>
			<div class="notable_posts_list">
				<h3>Sticky Posts</h3>
				<ul>
					<?php
					foreach ($sticky_posts as $posts)
						echo '<li><a href="'.url::site("$blog_page_name/entry/$posts->url").'" rel="blog_ajax">'. $posts->title .'</a></li>';
					?>
				</ul>
			</div>
			<?php
		}
		?>
		
	
	<div class="archives_list">
		<h3>Archives</h3>
		<ul>
			<li><a href="<?php echo url::site("$blog_page_name/archive")?>">View All</a></li>
		</ul>
	</div>
	

	<div class="recent_comments_list">
		<h3>Recent Comments</h3>
		<ul>
			<?php
			foreach ($recent_comments as $comments)
			{
				echo '<li><span>'.$comments->name.' @</span> <a href="'.url::site("$blog_page_name/entry/$comments->url#comments").'" class="comments_post_link"  rel="blog_ajax">'. $comments->title .'</a></li>';
			}
			?>
		</ul>
	</div>
	
</div>

<div class="blog_content">
	<?php if(! empty($response) ) echo '<div class="blog_response">'.$response.'</div>'?>
	<?php if (! empty($content) ) echo $content?>
</div>
	
