<div id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $tool_id?>">
		<img src="/images/check.png" alt=""/> Save Changes
	</button>
	<div id="common_title">Edit <b>Navigation</b> Tool.</div>	
</div>

<div id="left_wrapper">
	Click an element to select it.
	<ul id="actions_list">	
		<li><a href="/get/edit_navigation/add/<?php echo $tool_id?>" id="add_node">Add Child Element</a></li>
		<li><a href="/get/edit_navigation/edit/" id="edit_node">Edit Element</a></li>
		<li><a href="#" id="delete_node">Delete Element</a></li>
	</ul>
	
	<div id="element_data"></div>
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
		return active;
	};
	
	// initiliaze simpleTree
	$simpleTreeCollection = $(".facebox .simpleTree").simpleTree();

	//Make root the default active node.
	$('.facebox .simpleTree li.root > span').addClass('active');
	
	$("li.root > span").click(function(){
		$('span.active').removeClass('active').addClass('text');
		$(this).addClass('active');
		
	});
	
	/*
	 * delegate element click actions
	 */
	ROOT = $('.simpleTree > li.root').attr('rel');
	$('#actions_list').click($.delegate({
		
		// add element
		"a#add_node": function(e){
			el_id = get_active_node();
			
			if(! el_id ){
				alert('Select an item to add element to.');
				return false;
			}
			
			url = $(e.target).attr('href');
			$.facebox(function() {
					$.get(url, {local_parent: el_id}, 
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
			el_id = get_active_node();
			
			if(! el_id ){
				alert('Select an item to edit.');
				return false;
			}
			else if(ROOT == el_id){
				alert('You cannot edit the root node.');
				return false;
			}
			
			url = $(e.target).attr('href');
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
			el_id = get_active_node();
			
			if(! el_id )
				alert('Select an item to delete.');
			else if( ROOT == el_id )
				alert('You cannot delete the root node.');
			else if( confirm("Remove element from the list? \n NOTE: Elements are not deleted until you click \"Save Changes\"") )
				$simpleTreeCollection.get(0).delNode();		
			return false;
		}
		
	}));
	
	
	// Gather and save nest data.
	// ------------------------
	$(".facebox #link_save_sort").click(function() {
		$('.facebox .show_submit').show();	
		var output = '';
		var tool_id = $(this).attr("rel");
		
		$(".facebox #admin_navigation_wrapper ul").each(function(){
			var parentId = $(this).parent().attr("rel");
			if(!parentId) parentId = 0;
			var $kids = $(this).children("li:not(.root, .line,.line-last)");
			
			// Data set format: "id:local_parent_id:position#"
			$kids.each(function(i){
				output += $(this).attr('rel') + ':' + parentId + ':' + i + "|";
			});
		});
		//alert (output); return false;
		
		$.post('/get/edit_navigation/save_tree/'+tool_id,
			{output: output},
			function(data){
				$.facebox.close();		
				$().jade_update_tool_html('update', 'navigation', tool_id, data);
				$('.facebox .show_submit').hide();					
			}
		)		
	});		
</script>