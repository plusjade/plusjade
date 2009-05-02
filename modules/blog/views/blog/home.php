	
	<div class="blog_post_wrapper">	
		<?php
		foreach($items as $item)
		{
			?>
			<div class="each_post">
			
				<div class="post_title">
					<div class="post_created"><?php echo $item->created?></div>
					<a href="/blog/entry/<?php echo $item->url?>"><?php echo $item->title?></a>	
				</div>
				
				<div class="post_tags">
									Tags:
						<?php
							#foreach($tags as $tag)
								#echo '<a href="">'."$tag->value</a> ";
						?>
				</div>
				
				<div class="post_body">
					<?php echo $item->body?>
				
				
				
					<div id="post_comments_<?php echo $item->id?>" class="post_comments">
						<a href="/blog/entry/<?php echo $item->url?>#comments" class="get_comments" rel="<?php echo $item->id?>"><?php echo $item->comments?> comments</a>
					</div>
				
				</div>
				
				
			</div>	
			<?php
		}	
		?>	
	</div>