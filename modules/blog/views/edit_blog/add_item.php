
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_blog/add/<?php echo $tool_id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Add New Post</button>
		<div id="common_title">Add New Blog Post</div>
	</div>	


	<div class="common_left_panel">	
		<ul id="common_view_toggle" class="ui-tabs-nav">
			<li><a href="#blog_body" class="selected"><b>Body</b></span></a><li>
			<li><a href="#blog_params"><b>Title & Attributes</b></span></a><li>
		</ul>
	</div>


	
	<div class="common_main_panel fieldsets">
	

		<div id="blog_body" class="toggle fieldsets">
			<textarea name="body" class="render_html"></textarea>
		</div>

		
		<div id="blog_params" class="toggle fieldsets">
		
			<b>Title</b>
			<br><input type="text" name="title" class="send_input" rel="text_req" style="width:400px">
			<br>
			<br>
			<b>Url</b>
			<br><input type="text" name="url" class="auto_filename receive_input" rel="text_req" style="width:400px">
			<br>
			<br>			
			<b>Status</b>
			<br><select name="status">
				<option>draft</option>
				<option selected="selected">publish</option>
			</select>

			
			<br><br>
			<input type="checkbox" name="sticky" value="stick"> <b>Make sticky</b>
			<div style="margin:5px 7px;">
				A <em>sticky</em> blog post will show on the main blog navigation under "sticky posts". 
			</div>
			
			<br>
			<b>Tags</b>
			<div style="margin:5px 7px;">
				Separate tags with a space. Multi-word tags should be combined.
				<br>ex: business web-development marketing
			</div>
			
			<div class="blog_post_tag_pane">
				<b>Add Tags</b>
				<br><input type="text" name="tags" style="width:200px">
			</div>
			
			<div class="blog_post_tag_pane">
				<ul id="tag_output" class="common_tag_list">
				</ul>
			</div>
			
		</div>	
		
		

		
	</div>
</form>

<script type="text/javascript">

	$('div.toggle').hide();
	$('div#blog_body').show();
		

// filter and update new tags view.		
	$('input[name="tags"]').keyup(function(){
		var input = $(this).val().replace(/[^-a-z0-9_ ]/ig, '-');
		$(this).val(input);
		
		var list = '';
		$.each($.trim(input).split(' '), function(){
			list += '<li><span>'+ this +'</span></li>';
		});
		$('ul#tag_output').empty().append(list);	
		
	});
	
	
</script>