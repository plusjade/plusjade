
<?php
	$status = array('draft'=>'','publish'=>'');
	$status[$item->status] = 'selected="selected"';
?>
<span id="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_blog/edit/<?php echo $item->id?>" method="POST" class="ajaxForm">	
	<input type="hidden" name="parent_id" value="<?php echo $item->parent_id?>">
	<input type="hidden" name="sticky_posts" value="<?php echo $sticky_posts?>">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Save Changes</button>
		<div id="common_title">Update Blog Post</div>
	</div>	

	<div class="common_left_panel fieldsets">		
		<b>Status</b>
		<br><select name="status">
			<option <?php echo $status['draft']?>>draft</option>
			<option <?php echo $status['publish']?>>publish</option>
		</select>
		
		<br><br>
		<?php
		if($is_sticky)
			echo '<input type="checkbox" name="sticky" value="unstick"> <b>Remove Sticky</b>';
		else
			echo '<input type="checkbox" name="sticky" value="stick"> <b>Make sticky</b>';
		?>
		
		<p>
			<b>Add Tags</b>
			<br><input type="text" name="tags" style="width:150px">	
		</p>
		<b>Current Tags</b>
		<ul style="font-size:0.9em">
		<?php
			if(! empty($item->tag_string) )
			{
				$tags = explode(',', $item->tag_string);
				foreach($tags as $tag)
				{
					$pair = explode('_', $tag);
					echo '<li>' . $pair['0'] . ' <a href="/get/edit_blog/delete_tag/' .$pair['1']. '" rel="facebox" id="2">[x]</a></li>';
				}
			}
		?>
		</ul>
		
	</div>
	
	<div class="common_main_panel fieldsets">
		<div class="inputs">
			<b>Title</b> <input type="text" name="title" value="<?php echo $item->title?>" rel="text_req">
		</div>
		
		<div class="inputs">
			<b>Url</b> <input type="text" name="url" value="<?php echo $item->url?>" rel="text_req">
		</div>
		<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
	</div>
	
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