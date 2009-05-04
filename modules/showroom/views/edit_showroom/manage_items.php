
<div  id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Item Order
	</button>
	<strong>Manage <b>Showroom</b> tool.</strong>
</div>	

<b>Category</b> 
<select id="admin_cat_dropdown" name="category">
	<?php
	foreach($categories as $category)
	{
		echo '<option value="'.$category->id.'">'.$category->name.'</option>'."\n";
	}
	?>
</select>

	<div id="load_box" style="min-height:400px"></div>

<script type="text/javascript">
	$("#admin_cat_dropdown").change(function(){
		val = $("option:selected", this).val();
		$("#load_box").load("get/edit_showroom/list_items/"+val);
	})
</script>