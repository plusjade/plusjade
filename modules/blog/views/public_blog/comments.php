
<div id="post_comments_<?php echo $blog_post_id?>" class="posts_comments">

	<form action="<?php echo url::site("$blog_page_name/comment/$blog_post_id")?>" method="POST" class="public_ajaxForm">
		<input type="hidden" name="tool_id" value="<?php echo $tool_id?>">
		
		<div class="comment_wrapper_title">Comments</div>
		<div id="comments" class="comments_wrapper">	
				<?php
				foreach($comments as $comment)
				{
					$name = $comment->name;
					if(! empty($comment->url) )
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
		</div>
		
		<div class="add_comment_title">Add Comment</div>
		<div class="add_comment">
			<p>
				<span>Name*</span>
				<input type="text" name="name" rel="text_req">
			</p>
			
			<p>
				<span>Email*</span> 
				<input type="text" name="email" rel="email_req">
			</p>
			
			<p>
				<span>Website</span>
				<input type="text" name="url">
			</p>
			
			</p>
				<span>Comment*</span>
				<textarea name="body"></textarea>
			</p>
			
			<div class="buttons">
				<button class="jade_positive">Add Comment</button>
			</div>
		</div>

	</form>
</div>

<?php if($admin_js):?>
	<script type="text/javascript">
		<?php echo $admin_js?>
	</script>
<?php endif;?>

