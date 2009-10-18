


<div id="blog_item_<?php echo $blog_post->id?>" class="blog_item" rel="<?php echo $blog_post->id?>">

	<div class="post_title">
		<a href="<?php echo url::site("$blog_page_name/entry/$blog_post->url")?>"><?php echo $blog_post->title?></a>	
	</div>
	
	<div class="post_created">
		<?php echo $blog_post->created_on?>
	</div>
			
	<div class="post_tags">
		Tags: 
		<?php
			if(! empty($blog_post->tag_string) )
			{
				$tags = explode(',', $blog_post->tag_string);
				foreach($tags as $tag)
					echo '<a href="'.url::site("$blog_page_name/tag/$tag").'">'."$tag</a> ";
			}
		?>
	</div>
	
	<div class="post_share">
		Permalink: <input type="text" value="<?php echo url::site("$blog_page_name/entry/$blog_post->url")?>" style="width:300px">	
	</div>
	
	<div class="post_body">
		<?php echo $blog_post->body?>	
	</div>
	
	<?php if(empty($comments)):?>
		<div id="show_comments_<?php echo $blog_post->id?>" class="show_comments">
			<a href="<?php echo url::site("$blog_page_name/entry/$blog_post->url#comments")?>" class="get_comments" rel="<?php echo "/$blog_page_name/comment/$blog_post->id"?>" id="<?php echo $blog_post->id?>"><?php echo $blog_post->comments?> comments</a>
		</div>
	<?php else:	
		echo $comments;
	endif;
	?>
	
</div>
