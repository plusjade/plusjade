
<span class="on_close">update-showroom-<?php echo $tool_id?></span>

<div  id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $tool_id?>" title="showroom">Save Category Tree</button>
	<div id="common_title">Manage Showroom Categories</div>
</div>	

<div class="common_left_panel">
	Click a category to select it.
	<ul id="actions_list">	
		<li><a href="#" id="add_node">Add child category</a></li>
		<li><a href="#" id="edit_node">Edit category</a></li>
		<li><a href="#" id="delete_node">Delete category</a></li>
		<br/>
		<li><a href="/get/edit_showroom/items/<?php echo $tool_id?>/" id="show_items">List items</a></li>
	</ul>
</div>

<div id="simpletree_wrapper" class="common_main_panel" style="height:400px;overflow:auto">
	<?php echo $tree?>
</div>


<div id="add_category" class="fieldsets" style="display:none; position:absolute; background:#ffffcc; border:1px solid blue;padding:10px;">
	<span class="icon cross floatright">&#160; &#160; </span>
	<div id="common_title">Add a New Category</div>
	<b>Category Name</b>
	<br><input type="text" name="new_name" rel="text_req" class="send_input" style="width:300px">
	<br><b>Category Url</b>
	<br><input type="text" name="new_url" rel="text_req" class="auto_filename receive_input" style="width:300px">
	<br><br><button type="submit" id="add_cat" class="jade_positive">Add Category</button>
</div>

<div id="edit_category" class="fieldsets" style="display:none; position:absolute; background:#ffffcc; border:1px solid blue;padding:10px;">
	<span class="icon cross floatright">&#160; &#160; </span>
	<div id="common_title">Edit Category</div>
	<b>Category Name</b>
	<br><input type="text" name="edit_name" rel="text_req" style="width:300px">
	<br><b>Category Url</b>
	<br><input type="text" name="edit_url" rel="text_req" class="auto_filename" style="width:300px">
	<br><br><button type="submit" id="edit_cat" class="jade_positive">Save Changes</button>
</div>


<script type="text/javascript">
$(document).ready(function()
{
// a way to get the active node's data.	
	function get_active_node(){
		var active = false;
		$('li span.active').each(function(){
			active = $(this).parent().attr('rel');
		});
		return active;
	};
	
// initiliaze simpleTree
	$simpleTreeCollection = $(".facebox .simpleTree").simpleTree();
	var ROOT = $('.simpleTree > li.root').attr('rel');
	$('.facebox .simpleTree li.root > span').addClass('active');

	
// add a new category logic.
	$("button#add_cat").click(function(){
		var el_id = get_active_node();
		if(! el_id ){alert('Select an item to edit.'); return false};	
		var name = $("input[name='new_name']").val();
		var url = $("input[name='new_url']").val();
		if(!name) {alert('name is empty'); return false};
		if(!url) {alert('url is empty'); return false};
		
		$('.facebox .show_submit').show();
		$.post('/get/edit_showroom/add/<?php echo $tool_id?>/',
			{category : name, url: url, local_parent : el_id}, function(data){
			// data is the new "id"
			$simpleTreeCollection.get(0).addNode(data, name);
			$('button#add_cat').parent('div').hide();
			$(document).trigger('server_response.plusjade', data);
		});
		return false;
	});


// edit category logic.
	$("button#edit_cat").click(function(){
		var el_id = get_active_node();
		if(! el_id ){alert('Select an item to edit.'); return false};
		
		var name = $("input[name='edit_name']").val();
		var url = $("input[name='edit_url']").val();
		if(!name) {alert('name is empty'); return false};
		if(!url) {alert('url is empty'); return false};
	
		$('.facebox .show_submit').show();
		$.post('/get/edit_showroom/edit_category/'+ el_id,
			{category : name, url : url}, function(data){
			// data is the new "id"
			$('li span.active b').html(name);
			$('li span.active b').attr('rel', url);
			$('button#edit_cat').parent('div').hide();
			$(document).trigger('server_response.plusjade', data);
		});
		return false;
	});

	
//delegate element click actions
	$('#actions_list').click($.delegate({

		// add element
		"a#add_node": function(e){
			var el_id = get_active_node();
			if(!el_id) {alert('Select an item to add element to.');return false;}
			$('#add_category').show();
			return false;
		},
		
		// edit active element
		"a#edit_node": function(e){
			var el_id = get_active_node();
			if(! el_id ){alert('Select an item to edit.');return false}
			else if(ROOT == el_id){alert('You cannot edit the root node.');return false}
			var name = $('li span.active b').html();
			var url = $('li span.active b').attr('rel');
			$("input[name='edit_name']").val(name);
			$("input[name='edit_url']").val(url);
			$('#edit_category').show();
			return false;
		},
		
		// delete active element
		"a#delete_node": function(e){		
			var el_id = get_active_node();
			
			if(! el_id )
				alert('Select an item to delete.');
			else if( ROOT == el_id )
				alert('You cannot delete the root node.');
			else if( confirm("Remove node and all children? \n NOTE: Elements are not deleted until you click \"Save Changes\"") )
				$simpleTreeCollection.get(0).delNode();		
			return false;
		},
		
		// show items from category
		"a#show_items": function(e){
			var el_id = get_active_node();	
			if(! el_id ){alert('Select a category to display items from.');return false;}
			
			url = $(e.target).attr('href');
			$.facebox(function() {
				$.get(url+el_id, 
					function(data){$.facebox(data, false, "facebox_2");}
				)
			}, false, 'facebox_2');
			return false;	
		}	
	}));
		
});
</script>