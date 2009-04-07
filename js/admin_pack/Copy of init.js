
$(document).ready(function()
{
	// TOGGLE ADMIN BAR
	// -----------------------------------	
	$(".toggle_admin_bar").click(function(){
		$("#admin_bar_wrapper").slideToggle("slow", function(){
			$(".jade_toolbar_wrapper").slideToggle("slow");
			$(".jade_admin_item_edit").slideToggle("slow");
			$("#hide_link").slideToggle("slow");
		});
		
	});
	
	// Add tool_kit to ALL TOOLS on page
	// -----------------------------------
	 $(".common_tool_wrapper").each(function(i){
		++i;
		var toolkit = $("#toolkit_"+ i).html();
		var toolbar = "<div id=\"toolbar_identifer_" + i  + "\" class=\"jade_toolbar_wrapper\">" + toolkit + "</div>";
		$(this).prepend(toolbar);
	 });

	 
	// Add PER ITEM TOOLKIT to ITEMS
	// -----------------------------------
	var tools = ["contact", "showroom", "slide_panel"];
	$.each(tools, function(){
		var tool = this;
		$("." + tool + "_wrapper ." + tool + "_item").each(function(i){					
			var id		= $(this).attr("rel");
			var edit	= "<a href=\"/e/edit_" + tool + "/edit/" + id + "\" rel=\"facebox\">edit</a>";
			var del		= "<a href=\"/e/edit_" + tool + "/delete/" + id + "\" class=\"jade_delete_item\">delete</a>";
			var toolbar	= "<div id=\"blah\" class=\"jade_admin_item_edit\">" + edit + " " + del + "</div>";
					
			$(this).prepend(toolbar);			
		}); 
	});
	// CALENDAR ITEM TOOLBAR
	// its enabled inside the calendar controller
	// because i dont want to use livequery
	// -----------------------------------				

	// Delegate main link functionality
	$("body").click($.delegate({
	
		// facebox links
		"a[rel*=facebox]": function(e){
			var url	= $(e.target).attr("href");
			var id	= $(e.target).attr("id");
			
			$.facebox(function(){
					$.get(url, function(data){
						$.facebox(data, false, "facebox_"+id);
					});
			}, false, "facebox_"+id);
			return false;
		},	
		
		// delete an item link		
		"a.jade_delete_item": function(e) {
			url = $(e.target).attr("href");
			var data = "<div class=\"buttons confirm_facebox\">This can not be undone.<br> <a href=\"" + url +"\"  class=\"jade_confirm_delete_common jade_negative\">Delete Item</a> <a href=\"/#\" class=\"cancel_delete\">Cancel</a></div>";
			
			$.facebox(data, "confirm_facebox", "confirm_dialog");
			return false;		
		},
		
		// delete a tool link	
		"a.jade_delete_tool": function(e) {
			url = $(e.target).attr("href");
			var data = "<div class=\"buttons confirm_facebox\">This will delete this entire tool.<br>All content will be lost forever!<br> <a href=\"" + url +"\"  class=\"jade_confirm_delete_common jade_negative\">Delete Tool</a> <a href=\"/#\" class=\"cancel_delete\">Cancel</a></div>";	
			$.facebox(data, "confirm_facebox", "confirm_dialog");
			return false;		
		},
		
		// confirm delete button
		"a.jade_confirm_delete_common": function(e) {
			url = $(e.target).attr("href");
			$.get(url, function(data) { 
				$.facebox(data, "ajax_status", "confirm_dialog")
				location.reload();						
			});
			return false;
		},
		
		// cancel delete button
		"a.cancel_delete": function() {
			$.facebox.close();
			return false;	
		}
		
	}));


	$(document).bind('reveal.facebox', function(){		
		// Ajax forms
			var options = {
				success: function(data) { 
					$.facebox(data, "ajax_status", "facebox_")
					location.reload();						
				}					
			};
		$(".ajaxForm").ajaxForm(options);
		
		// wysiwyg text editor
		$("textarea.render_html").wysiwyg();
	});
 
});