
<?php
function page_select($pages, $id)
{
	ob_start();
	echo '<select name="data[' , $id , ']" disabled="disabled">';
	
	foreach ($pages as $page)
	{
		echo '<option>', $page->page_name ,'</option>';
	}
	
	echo '</select>';
	
	return ob_get_clean();
}
?>

<form action="/get/edit_navigation/add/<?php echo $tool_id?>" method="POST" enctype="multipart/form-data" class="ajaxForm" id="add_links_form" style="min-height:300px;">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Links
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
			<p>
				Label <input type="text" name="item[<?php echo $x?>]" rel="text_req"> <img src="/images/admin/bullet_go.png" alt="next">  
				Type: <select class="toggle_type" rel="<?php echo $x?>" name="type[<?php echo $x?>]">
					<option value="none">Label (no link)</option>
					<option value="page">Link to +Jade Page</option>
					<option value="url">Link to external Page</option>
					<option value="email">Link to email address</option>
					<option value="file">Link to +Jade file</option>	
				</select> <img src="/images/admin/bullet_go.png" alt="next"> 
				
				<span id="page_<?php echo $x?>"  class="hide_<?php echo $x?>">Page: <?php echo page_select($pages, $x)?></span>
				<span id="url_<?php echo $x?>" class="hide_<?php echo $x?>">http://<input type="text" name="data[<?php echo $x?>]" disabled="disabled"></span>
				<span id="email_<?php echo $x?>" class="hide_<?php echo $x?>">mailto:<input type="text" name="data[<?php echo $x?>]" disabled="disabled"></span>
			</p>
			<?php
		}
	
	?>
	</div>

	
</form>		