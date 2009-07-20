
<h2>Newest</h2>

<div class="category_posts">
	<?php
	foreach($posts as $post)
	{
		?>
		<div class="thread_item">
			<div class="votes">
				<span><?php echo $post->forum_cat_post_comment->vote_count?></span>
				<br>votes
			</div>
			
			<div class="comments">
				<span><?php echo --$post->comment_count?></span>
				<br>replies
			</div>
			
			<div class="summary">
				<div>
				<a href="<?php echo url::site("$page_name/view/$post->id")?>" class="load_post_view"><?php echo $post->title?></a></div>
				<br>
				<div>
					<a href="#" class="preview" rel="<?php echo $post->id?>">preview</a>
					 - by : <a href="/users/profile/<?php echo $post->forum_cat_post_comment->account_user->username?>"><?php echo $post->forum_cat_post_comment->account_user->username?></a>
					 - in : <a href="<?php echo url::site("$page_name/category/$post->url")?>"><?php echo $post->name?></a>					
					 - at : <?php echo date("M d y @ g:i a", $post->forum_cat_post_comment->created)?>
					<?if($account_user == $post->forum_cat_post_comment->account_user->id):?>
					<div class="owner_actions"><a href="">edit</a> - <a href="">delete</a></div>
					<?endif;?>
				</div>
			</div>
			
		</div>
		
		<blockquote id="preview_<?php echo $post->id?>"><?php echo $post->forum_cat_post_comment->body?></blockquote>
		<?php
	}
	?>
</div>


<script type="text/javascript">
$(document).ready(function()
{
	$('blockquote').hide();
});
</script>
