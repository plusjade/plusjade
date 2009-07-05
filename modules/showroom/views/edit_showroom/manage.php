
<span id="on_close">update-showroom-<?php echo $tool_id?></span>

<div  id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $tool_id?>">Save Category Tree</button>
	<div id="common_title">Manage Showroom Categories</div>
</div>	

<div id="left_wrapper">
	Click a category to select it.
	<ul id="actions_list">	
		<li><a href="/get/edit_showroom/add/<?php echo $tool_id?>" id="add_node">Add Sub-Category</a></li>
		<li><a href="/get/edit_showroom/edit_category/" id="edit_node">Edit Category</a></li>
		<li><a href="#" id="delete_node">Delete Category</a></li>
		<br>
		<li><a href="/get/edit_showroom/add_item/<?php echo $tool_id?>" id="add_item">Add item</a></li>
		<br>
		<li><a href="/get/edit_showroom/items/" id="show_items">Show items</a></li>
	</ul>
	<a href="#add_new_category" rel="facebox_div" id="2">blah</a>

	<div id="element_data"></div>
</div>

<div id="admin_showroom_wrapper">
	<?php echo $tree?>
</div>


<div id="add_new_category" style="display:none">
	<form action="/get/edit_showroom/add/<?php echo $tool_id?>" method="POST" class="ajaxForm" style="min-height:300px">	
		
		<div  id="common_tool_header" class="buttons">
			<button type="submit" class="jade_positive" rel="<?php echo $tool_id?>">Add Category</button>
			<div id="common_title">Add a New Category</div>
		</div>
		
		<div class="fieldsets">
			<b>Category Name</b>
			<br><input type="text" name="category" el="text_req" style="width:300px">
		</div>
		
	</form>
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
	
	$("li.root > span").click(function(){
		$('span.active').removeClass('active').addClass('text');
		$(this).addClass('active');
		
	});
	
	
	/*
		delegate element click actions
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
		},


		// add element
		"a#add_item": function(e){
			el_id = get_active_node();
			
			if(! el_id ){
				alert('Select category to add item to.');
				return false;
			}
			
			url = $(e.target).attr('href');
			$.facebox(function() {
					$.get(url, {category: el_id}, 
						function(data){
							$.facebox(data, false, "facebox_2");
					})
				}, 
				false, 
				'facebox_2'
			);
			return false;			
		},
		
		// show items from category
		"a#show_items": function(e){
			el_id = get_active_node();
			
			if(! el_id ){
				alert('Select a category to display items from.');
				return false;
			}
			
			url = $(e.target).attr('href');
			$.facebox(function() {
					$.get(url+el_id, 
						function(data){
							$.facebox(data, false, "facebox_2");
					})
				}, 
				false, 
				'facebox_2'
			);
			return false;			
		}
		
	}));
	
	
	// Gather and send nest data.
	// ------------------------
	$(".facebox #link_save_sort").click(function() {
		var output = "";
		var tool_id = $(this).attr("rel");
		
		$(".facebox #admin_showroom_wrapper ul").each(function(){
			var parentId = $(this).parent().attr("rel");
			if(!parentId) parentId = 0;
			var $kids = $(this).children("li:not(.root, .line,.line-last)");
			
			// Data set format: "id:local_parent_id:position#"
			$kids.each(function(i){
				output += $(this).attr('rel') + ':' + parentId + ':' + i + "|";
			});
		});
		//alert (output); return false;
		$.post('/get/edit_showroom/save_tree/'+tool_id,
			{output: output},
			function(data){
				$.facebox(data, "loading_msg", "facebox_2");
			}
		)
	});	
</script>