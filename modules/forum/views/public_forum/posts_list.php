

<?php foreach($posts as $post):?>

	<div class="each_post_wrapper">
		<div class="votes">
			<span><?php echo $post->forum_cat_post_comment->vote_count?></span>
			votes
		</div>
		
		<div class="comments">
			<span><?php echo --$post->comment_count?></span>
			replies
		</div>
		
		<div class="summary">
			<div class="title">
				<a href="<?php echo url::site("$page_name/view/$post->id").'/'.valid::filter_php_url($post->title)?>" class="forum_load_main"><?php echo $post->title?></a>
			</div>
			<div>
				<a href="#" class="preview" rel="<?php echo $post->id?>">preview</a>
				created by <a href="/users/profile/<?php echo $post->forum_cat_post_comment->account_user->username?>"><?php echo $post->forum_cat_post_comment->account_user->username?></a>
				in <a href="<?php echo url::site("$page_name/category/$post->url")?>" class="forum_load_main"><?php echo $post->name?></a>
				 <abbr class="timeago" title="<?php echo date("c", $post->forum_cat_post_comment->created)?>"><?php echo date("M d y @ g:i a", $post->forum_cat_post_comment->created)?></abbr>
				<span>
				<em>last active</em> <abbr class="timeago" title="<?php echo date("c", $post->last_active)?>"><?php echo date("M d y @ g:i a", $post->last_active)?></abbr>
				</span>
			</div>
		</div>
		
	</div>
	
	<div class="post_comment" id="preview_<?php echo $post->id?>"><?php echo $post->forum_cat_post_comment->body?></div>
<?php endforeach;?>

<script type="text/javascript">
$(document).ready(function()
{
	$('div.post_comment').hide();
	$('abbr[class*=timeago]').timeago();
});
</script>
