<style type="text/css">
#left_panel{
	width:180px;
	float:left;
}
#main_panel{
	width:600px;
	float:right;
	
}
#main_panel div.inputs{
	width:500px;
	margin:0 auto;
	text-align:right;
	margin-bottom:5px;
}
#main_panel div.inputs input{
	width:400px;
}
#main_panel div.inputs b{
	float:left;
}
</style>
<?php
	$status = array('draft'=>'','publish'=>'');
	$status[$item->status] = 'selected="selected"';
?>
<form action="/get/edit_blog/edit/<?php echo $item->id?>" method="POST" class="ajaxForm" rel="<?php echo $js_rel_command?>">	
	<input type="hidden" name="parent_id" value="<?php echo $item->parent_id?>">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Update Blog Post</div>
	</div>	

	<div id="left_panel" class="fieldsets">		
		<b>Status</b>
		<br><select name="status">
			<option <?php echo $status['draft']?>>draft</option>
			<option <?php echo $status['publish']?>>publish</option>
		</select>
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
	
	<div id="main_panel" class="fieldsets">
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