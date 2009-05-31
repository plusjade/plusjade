<div id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $tool_id?>">
		<img src="/images/check.png" alt=""/> Save Changes
	</button>
	<div id="common_title">Edit <b>Navigation</b> Tool.</div>	
</div>

<div id="left_wrapper">
	Click on an element to select it.
	<br>Once selected you can add, edit, or delete items.
	
	<ul id="actions_list">	
		<li><a href="/get/edit_navigation/add/<?php echo $tool_id?>" id="add_node">Add Child Element</a></li>
		<li><a href="/get/edit_navigation/edit/" id="edit_node">Edit Element</a></li>
		<li><a href="#" id="delete_node">Delete Element</a></li>
	</ul>
</div>

<div id="admin_navigation_wrapper">
	<?php echo $tree?>
</div>

<script type="text/javascript">
	function get_active_node(){
		active = false;
		$('li span.active').each(function(){
			active = $(this).parent().attr('rel');
		});	
		if(!active)
			return false;

		return active;
	};
	
	// initiliaze simpleTree
	$simpleTreeCollection = $(".facebox .simpleTree").simpleTree({
		autoclose: true,
		animate:true
	});

	/*
		delegate element click actions
	*/
	$('#actions_list').click($.delegate({
		// add element
		"a#add_node": function(e){
			if( !get_active_node() ){
				alert('Select an item to add element to.');
				return false;
			}
			
			url = $(e.target).attr('href');
			local_parent = get_active_node();	
			$.facebox(function() {
					$.get(url, {local_parent: local_parent}, 
						function(data){
							$.facebox(data, false, "facebox_2");
					})
				}, 
				false, 
				'facebox_2'
			);
			return false;			
		},
		
		// edit active element
		"a#edit_node": function(e){
			if( !get_active_node() ){
				alert('Select an item to edit.');
				return false;
			}
			
			url = $(e.target).attr('href');
			el_id = get_active_node();	
			$.facebox(function() {
					$.get(url+el_id, function(data){
						$.facebox(data, false, "facebox_2");
					})
				}, 
				false, 
				'facebox_2'
			);
			return false;			
		
		
		},
		// delete active element
		"a#delete_node": function(e){
			if(!get_active_node()){
				alert('Select an item to delete.');
				return false;
			}
			else{
				if(confirm("Remove element from the list? \n NOTE: Elements are not deleted until you click \"Save Changes\""))
					$simpleTreeCollection.get(0).delNode();
			}			
			return false;
		}
		
	}));
	
	
	// Gather and send nest data.
	// ------------------------
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