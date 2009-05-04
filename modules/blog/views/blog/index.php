

<div id="blog_wrapper_<?php echo $tool_id?>" class="blog_wrapper">

	<div class="blog_navigation">

		<h3>Tags</h3>
			<ul>
			<?php
				foreach($tags as $tag)
				{
					echo '<li><a href="'.url::site("blog/tag/$tag->value").'">'.$tag->value . '</a></li>';
				}
			?>
			</ul>
		
		<h3>Archives</h3>
		
		<a href="/blog/archive/2009">2009</a>
		
		<ul>
			<li><a href="/blog/archive/2009/03">March</a></li>
			<li><a href="/blog/archive/2009/04">April</a></li>
			<li><a href="/blog/archive/2009/05">May</a></li>
		</ul>
		
		<a href="/blog/archive">View All</a>
		

		<h3>Spotlight</h3>

		<h3>Recent Comments</h3>
			
	
	
	</div>
	
	<div class="blog_content">
		<?php if (! empty($content) ) echo $content?>
		<?php if(! empty($response) ) echo '<div class="blog_response">'.$response.'</div>'?>
	</div>
	
</div>