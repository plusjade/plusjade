

<div id="blog_wrapper_<?php echo $tool_id?>" class="blog_wrapper">

	<div class="blog_navigation">

		<div class="tags_list">
			<h3>Tags</h3>
				<ul>
				<?php
					foreach($tags as $tag)
						echo '<li><a href="'.url::site("blog/tag/$tag->value").'">'.$tag->value . '</a> <span>('.$tag->qty.')</span></li>';
				?>
				</ul>
		</div>

		<div class="notable_posts_list">
			<h3>Sticky Posts</h3>
			<ul>
				<?php
				foreach ($sticky_posts as $posts)
					echo '<li><a href="'.url::site("blog/entry/$posts->url").'" rel="blog_ajax">'. $posts->title .'</a></li>';
				?>
			</ul>
		</div>
		
		<div class="archives_list">
			<h3>Archives</h3>
			<ul>
				<li><a href="<?php echo url::site("blog/archive")?>">View All</a></li>
			</ul>
		</div>
		

		<div class="recent_comments_list">
			<h3>Recent Comments</h3>
			<ul>
				<?php
				foreach ($recent_comments as $comments)
				{
					echo '<li><span>'.$comments->name.' @</span> <a href="'.url::site("blog/entry/$comments->url#comments").'" class="comments_post_link"  rel="blog_ajax">'. $comments->title .'</a></li>';
				}
				?>
			</ul>
		</div>
		
	</div>
	
	<div class="blog_content">
		<?php if(! empty($response) ) echo '<div class="blog_response">'.$response.'</div>'?>
		<?php if (! empty($content) ) echo $content?>
	</div>
	
</div>