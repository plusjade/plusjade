
<?php
	$status = array('draft'=>'','publish'=>'');
	$status[$item->status] = 'selected="selected"';
?>
<form action="/get/edit_blog/edit/<?php echo $item->id?>" method="POST" class="ajaxForm" style="min-height:300px;">	
	<input type="hidden" name="parent_id" value="<?php echo $item->parent_id?>">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Update Blog Post</div>
	</div>	
	
	<div class="fieldsets">
		<b>Title</b> <input type="text" name="title" value="<?php echo $item->title?>" size="50" rel="text_req">
		
		<p>
			<b>Url - Slug</b> <input type="text" name="url" value="<?php echo $item->url?>" size="50" rel="text_req">
		</p>
		
		<b>Add Tags</b> <input type="text" name="tags" size="50">
	
		<br>
		Current Tags: 
		<?php
			if(! empty($item->tag_string) )
			{
				$tags = explode(',', $item->tag_string);
				foreach($tags as $tag)
				{
					$pair = explode('_', $tag);
					echo $pair['0'] . '<a href="/get/edit_blog/delete_tag/' .$pair['1']. '" rel="facebox" id="2">[x]</a> ';
				}
			}
		?>
		<p>
			Status: 
			 <select name="status">
				<option <?php echo $status['draft']?>>draft</option>
				<option <?php echo $status['publish']?>>publish</option>
			</select>
		</p>
	</div>
	
	<b>Body</b>	
	<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
	
</form>

<script type="text/javascript">
$("input[name='title']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
	$("input[name='url']").val(input);
});
$("input[name='url']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
	$(this).val(input);
});
</script>