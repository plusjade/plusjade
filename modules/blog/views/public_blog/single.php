


<div id="blog_item_<?php echo $item->id?>" class="blog_item" rel="<?php echo $item->id?>">

	<div class="post_title">
		<a href="<?php echo url::site("blog/entry/$item->url")?>"><?php echo $item->title?></a>	
	</div>
	
	<div class="post_created">
		<?php echo $item->created_on?>
	</div>
			
	<div class="post_tags">
		Tags: 
		<?php
			if(! empty($item->tag_string) )
			{
				$tags = explode(',', $item->tag_string);
				foreach($tags as $tag)
					echo '<a href="'.url::site("blog/tag/$tag").'">'."$tag</a> ";
				
			}
		?>
	</div>
	
	<div class="post_body">
		<?php echo $item->body?>	
	</div>

	<div class="post_share">
		<div class="share_title">Share</div>
		Permalink: <input type="text" value="<?php echo url::site("blog/entry/$item->url")?>" style="width:300px">	
	</div>
	
	<?php 
	if( empty($comments) )
	{
		?>
		<div id="show_comments_<?php echo $item->id?>" class="show_comments">
			<a href="<?php echo url::site("blog/entry/$item->url#comments")?>" class="get_comments" rel="<?php echo $item->id?>"><?php echo $item->comments?> comments</a>
		</div>
		<?
	}
	else
		echo $comments;
	?>
	
</div>
