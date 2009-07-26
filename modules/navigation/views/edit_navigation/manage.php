
<span class="on_close">update-navigation-<?php echo $tool_id?></span>

<div id="common_tool_header" class="buttons">
	<button id="link_save_sort" class="jade_positive" rel="<?php echo $tool_id?>" title="navigation">Save Changes</button>
	<div id="common_title">Edit <b>Navigation</b> Tool.</div>	
</div>

<div class="common_left_panel">
	Click an element to select it.
	<ul id="actions_list">	
		<li><a href="/get/edit_navigation/add/<?php echo $tool_id?>" id="add_node">Add Child Element</a></li>
		<li><a href="/get/edit_navigation/edit/" id="edit_node">Edit Element</a></li>
		<li><a href="#" id="delete_node">Delete Element</a></li>
	</ul>
	
	<div id="element_data"></div>
</div>

<div id="simpletree_wrapper" class="common_main_panel">
	<?php echo $tree?>
</div>

<div id="add_wrapper" class="popup_dialog fieldsets">
	<span class="icon cross floatright">&#160; &#160; </span>

	<form action="/get/edit_navigation/add/<?php echo $tool_id?>" method="POST" id="add_form">	
		<input type="hidden" name="local_parent" value="">

		<div id="common_tool_header">
			<div id="common_title">Add element to Navigation</div>
		</div>	
		
		<div id="common_tool_info">
			Choose which kind of element you wish to add.
		</div>
		
		<div class="fieldsets">
			<div class="tier">
				Type:
				<select class="toggle_type" name="type" style="width:250px">
					<option value="none">Label (no link)</option>
					<option value="page">Link to +Jade Page</option>
					<option value="url">Link to external Page</option>
					<option value="email">Link to email address</option>
					<option value="file">Link to +Jade file</option>	
				</select>
			</div>
			
			<div class="tier">
				Label <input type="text" name="item" rel="text_req" style="width:250px">
			</div>
			
			<div class="tier">		
				<span id="page" style="display:none">Page:
					<select name="data" disabled="disabled">
						<?php
						foreach ($pages as $page)
							echo '<option>', $page->page_name ,'</option>';
						?>
					</select>
				</span>
				<span id="url" style="display:none">http://<input type="text" name="data" disabled="disabled" rel="text_req" style="width:250px"></span>
				<span id="email" style="display:none">mailto:<input type="text" name="data" disabled="disabled" rel="email_req" style="width:250px"></span>
			</div>
		</div>
		<br>
		<button type="submit" class="jade_positive">Create Element</button>
	</form>
</div>

<div id="edit_wrapper" class="popup_dialog fieldsets"></div>


<script type="text/javascript">	
// get the active node.	
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
	
/*
 * delegate element click actions
 */
	$('#actions_list').click($.delegate({
		
	// add element
		"a#add_node": function(e){
			var el_id = get_active_node();
			if(! el_id ){alert('Select an item to add element to.');return false}
			$('#add_wrapper input[name="local_parent"]').val(el_id);
			$('#add_wrapper').show();
			return false;			
		},
		
	// edit active element
		"a#edit_node": function(e){
			var el_id = get_active_node();
			if(! el_id ){alert('Select an item to edit.');return false}
			else if(ROOT == el_id){alert('You cannot edit the root node.');return false}
			var url = $(e.target).attr('href');
			$('#edit_wrapper').html('Loading...').load(url+el_id).show();
			return false;
		},
		
	// delete active element
		"a#delete_node": function(e){		
			var el_id = get_active_node();
			if(! el_id ) alert('Select an item to delete.');
			else if( ROOT == el_id ) alert('You cannot delete the root node.');
			else if( confirm("Remove element from the list? \n NOTE: Elements are not deleted until you click \"Save Changes\"") )
				$simpleTreeCollection.get(0).delNode();		
			return false;
		}
		
	}));
	



// for toggling the element type.
	$(".facebox .toggle_type").each(function(){
		$(this).change(function(){
			var span = "#" + $(this).val();
			// Disable all @ start
			$(".tier span").hide();
			$(".hide > :input").attr("disabled","disabled");
			// Enable single input
			$(span + " > :input").removeAttr("disabled");
			$(span).show();
		});
	});

// submit the data 
	$("#add_form").ajaxForm({
		beforeSubmit: function(){					
			if(! $("#add_form input:enabled").jade_validate() ) return false;
			$('.facebox .show_submit').show();
		},
		success: function(data) {
			var text = $("#add_form input[name='item']").val();	
			// TODO: This does not work in chrome and safari
			$simpleTreeCollection.get(0).addNode(data, text);
			$('#add_wrapper').hide();
			$('.facebox .show_submit').hide();
			$('#show_response_beta').html(data);	
		}
	});
</script>