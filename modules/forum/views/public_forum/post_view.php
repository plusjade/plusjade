
<div id="main_post">
	
	<div class="title">
		<a href="<?php echo url::site("$page_name/view/$post->id")?>" class="post_link">
			<?php echo $post->title?>
		</a>
	</div>

	
	<div class="votes">
		<?php if($is_logged_in AND empty($post->has_voted) AND $account_user != $post->forum_cat_post_comment->account_user->id):?>
			<a href="<?php echo url::site("$page_name/vote/{$post->forum_cat_post_comment->id}/up")?>" class="cast_vote" rel="1">Up</a>
			<br><span><?php echo $post->forum_cat_post_comment->vote_count?></span>
			<br><a href="<?php echo url::site("$page_name/vote/{$post->forum_cat_post_comment->id}/down")?>" class="cast_vote">down</a> 
		<?php else:?>
			<span><?php echo $post->forum_cat_post_comment->vote_count?></span>
		<?php endif;?>
	</div>
	
	<div class="comment_body"><?php echo $post->forum_cat_post_comment->body?></div>

	<div class="comment_meta">
		<?if($account_user == $post->forum_cat_post_comment->account_user->id):?>
			<div class="owner_actions"><a href="<?php echo url::site("$page_name/edit/post/$post->id")?>">edit</a> - <a href="">delete</a></div>
		<?endif;?>
		<div class="meta">
			by:<a href="/users/profile/<?php echo $post->forum_cat_post_comment->account_user->username?>"><?php echo $post->forum_cat_post_comment->account_user->username?></a>
			- at <?php echo date("M d y @ g:i a", $post->forum_cat_post_comment->created)?>
		</div>
	</div>	
</div>


	
<h1 id="replies_wrapper"><?php echo --$post->comment_count?> Replies</h1>
	
<div class="forum_comments_wrapper">
	<?php
	foreach($comments as $comment)
	{
		?>
		<div class="thread_item">
			<div class="votes">
				<?php if($is_logged_in AND empty($comment->has_voted) AND $account_user != $comment->account_user->id):?>
					<a href="<?php echo url::site("$page_name/vote/$comment->id/up")?>" class="cast_vote" rel="1">Up</a>
					<br><span><?php echo $comment->vote_count?></span>
					<br><a href="<?php echo url::site("$page_name/vote/$comment->id/down")?>" class="cast_vote">down</a> 
				<?php else:?>
					<span><?php echo $comment->vote_count?></span>
				<?php endif;?>
			
			</div>
			
			<div id="comment_<?php echo $comment->id?>" class="comment_body">
				<?php echo $comment->body?>
			</div>
			
			<div class="comment_meta">
				<?if($account_user == $comment->account_user->id):?>
					<div class="owner_actions"><a href="<?php echo url::site("$page_name/edit/comment/$comment->id")?>">edit</a> - <a href="">delete</a></div>
				<?endif;?>
				<div class="meta">
					 by:<a href="/users/profile/<?php echo $comment->account_user->username?>"><?php echo $comment->account_user->username?></a>
					 - on: <?php echo date("M d y @ g:i a", $comment->created)?>
				</div>
			</div>
			<div class="clearboth"></div>
		</div>			
		<?php
	}
	?>
</div>

<?php if($is_logged_in): ?>
	<div id="add_comment_wrapper">
		<form id="submit_comment" action="<?php echo url::site("$page_name/view/$post->id")?>" method="POST">
			<input type="hidden" name="post_id" value="<?php echo $post->id?>">
			<b>Add Reply</b>
			<br><br>
			<textarea name="body" style="width:100%;height:200px"></textarea>
			<p><button type="submit">Add Reply</button></p>
		</form>
	</div>
<?php else:?>
	<a href="/<?php echo $this->account_page?>">Login</a> to post a reply.
<?php endif;?>




