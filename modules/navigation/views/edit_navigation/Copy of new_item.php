
<?php
function page_select($pages, $id)
{
	ob_start();
	echo '<select name="data[' , $id , ']" disabled="disabled">';
	
	foreach ($pages as $page)
		echo '<option>', $page->page_name ,'</option>';

	echo '</select>';
	
	return ob_get_clean();
}
?>
<style type="text/css">
	.each_link_wrapper{
		border:1px solid red;
		padding:5px;
	}
</style>

<form action="/get/edit_navigation/add/<?php echo $tool_id?>" method="POST" class="ajaxForm" id="add_links_form" style="min-height:300px;">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">
			<img src="<? echo url::image_path('check.png')?>" alt=""/> Add Links
		</button>
		<div id="common_title">Add Links to Navigation</div>
	</div>	
	
	<div id="common_tool_info">
		Add as many links as you want. You can arrange them later!
	</div>
	
	<div class="fieldsets">
		<?php
		for($x=0; $x<3; ++$x)
		{
			?>
			<p id="clone_<?php echo $x?>" class="each_link_wrapper">
				Label <input type="text" name="item[<?php echo $x?>]" rel="text_req">
				
				<img src="<?php echo url::image_path('admin/bullet_go.png')?>" alt="next">  
				Type:
				<select class="toggle_type" rel="<?php echo $x?>" name="type[<?php echo $x?>]">
					<option value="none">Label (no link)</option>
					<option value="page">Link to +Jade Page</option>
					<option value="url">Link to external Page</option>
					<option value="email">Link to email address</option>
					<option value="file">Link to +Jade file</option>	
				</select>
				
				<img src="<? echo url::image_path('admin/bullet_go.png')?>" alt="next"> 
				
				<span id="page_<?php echo $x?>"  class="hide_<?php echo $x?>">Page: <?php echo page_select($pages, $x)?></span>
				<span id="url_<?php echo $x?>" class="hide_<?php echo $x?>">http://<input type="text" name="data[<?php echo $x?>]" disabled="disabled"></span>
				<span id="email_<?php echo $x?>" class="hide_<?php echo $x?>">mailto:<input type="text" name="data[<?php echo $x?>]" disabled="disabled"></span>
			</p>
			<?php
		}
		?>
	</div>
</form>

<script type="text/javascript">

	$('#clone_1').clone().appendTo('.facebox .fieldsets');
	
	$(".facebox .toggle_type").each(function(){
		var field_id = $(this).attr("rel");
		
		$(this).change(function(){
			var span = "#" + $(this).val() + "_" + field_id;
			
			// Disable to start over
			$(".hide_" + field_id).hide();
			$(".hide_" + field_id + " > :input").attr("disabled","disabled").removeAttr("rel");
			
			// Enable selection
			$(span + " > :input").removeAttr("disabled").attr("rel","text_req");
			$(span).show();
		});
	});
</script>