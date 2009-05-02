
<form action="/blog/comment/<?php echo $item_id?>" method="POST" class="public_ajaxForm">
	
	<div class="comments_wrapper">	
		<div class="comment_title">Comments</div>
			<?php
			foreach($comments as $comment)
			{
				?>
				<div class="comment_item">
					<?php echo $comment->author?><br>
					<?php echo $comment->body?>
				</div>						
				<?php
			}
			?>				
	</div>
	
	<div class="add_comment">
			Name: 
			<br><input type="text" name="author" style="width:300px" rel="text_req">
			<br>Email: 
			<br><input type="text" name="email" style="width:300px" rel="email_req">
			<br>Website:
			<br><input type="text" name="url" style="width:300px" rel="url_req">
			<br>Comment:<br>
			<textarea name="body" style="width:300px"></textarea>
			<div class="buttons">
				<button class="jade_positive">Add Comment</button>
			</div>
	</div>

</form>
	