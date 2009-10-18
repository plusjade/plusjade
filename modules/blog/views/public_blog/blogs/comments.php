
<div id="post_comments_<?php echo $blog_post->id?>" class="posts_comments">

	<div class="comment_wrapper_title">Comments</div>
	<div id="comments" class="comments_wrapper">	
		<?php
		foreach($comments as $comment)
		{
			$name = $comment->name;
			if(!empty($comment->url))
				$name = '<a href="http://'.$comment->url.'" rel="nofollow">'.$comment->name.'</a>'; 
			?>
			<div id="comment_<?php echo $comment->id?>" class="comment_item" rel="<?php echo $comment->id?>">				
				<div class="comment_name">
					<?php echo $name?> says...
				</div>
				<div class="comment_time">
					<?php echo $comment->clean_date?>
				</div>
				
				<div class="comment_body"><?php echo $comment->body?></div>
			</div>						
			<?php
		}
		?>
		<div id="supa_injector_<?php echo $blog_post->id?>" class="comment_item" style="display:none">				
			<div class="comment_name"><em class="qwz_name"></em> says...</div>
			<div class="comment_time">just now</div>
			<div class="comment_body"><em class="qwz_body"></em></div>
		</div>
			
	</div>
	
	<?php echo $comment_form?>
	
</div>

<script type="text/javascript">
	<?php if($admin_js) echo $admin_js;?>
</script>
