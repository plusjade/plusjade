<div id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $tool_id?>">
		<img src="/images/check.png" alt=""/> Save Changes
	</button>
	<div id="common_title">Edit <b>Navigation</b> Tool.</div>	
</div>

<div id="admin_navigation_wrapper">
	<?php echo $tree?>
</div>

<script type="text/javascript">
	$simpleTreeCollection = $(".facebox .simpleTree").simpleTree({
		autoclose: true,
		animate:true
	});
	
	// add delete icons
	$(".facebox li:not(.root)>span").after(' <img src="<?php echo url::image_path('navigation/cross.png')?>" class="li_delete" alt="">');
	
	// activate delete icons
	$(".facebox .li_delete").click(function(){
		$(this).parent().remove();	
	});
	
	// Gather and send nest data.
	$(".facebox #link_save_sort").click(function() {
		var output = "";
		var tool_id = $(this).attr("rel");
		
		$(".facebox #admin_navigation_wrapper ul").each(function(){
			var parentId = $(this).parent().attr("rel");
			if(!parentId) parentId = 0;
			var $kids = $(this).children("li:not(.root, .line,.line-last)");
			
			// Data set format: "id:local_parent_id:position#"
			$kids.each(function(i){
				output += $(this).attr("rel") + ":" + parentId + ":" + i + "#";
			});
		});
		//alert (output); return false;
		$.facebox(function() {
				$.post("/get/edit_navigation/save_sort/"+tool_id, {output: output}, function(data){
					$.facebox(data, "status_reload", "facebox_response");
					location.reload();
				})
			}, 
			"status_reload", 
			"facebox_response"
		);
	});		
</script>