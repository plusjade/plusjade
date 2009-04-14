
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
	
	// Add tool parent tool_kit to all tools on page
	// -----------------------------------
	 $(".common_tool_wrapper").each(function(i){
		++i;
		var guid = this.id;
		var toolkit = $("#toolkit_" + guid).html();
		var toolbar = "<div id=\"toolbar_identifer_" + guid  + "\" class=\"jade_toolbar_wrapper\">" + toolkit + "</div>";
		$(this).prepend(toolbar);
	
	 });
	// break the list if the width is too small
	 $(".jade_toolbar_wrapper").each(function(i){
		++i;
		var guid = this.id;
		var width = $(this).width();	
		if(width<300) $("ul", this).css("clear","both");		
		else $(this).css("height","20px");
	
	 });
	 
	 
	// Add PER ITEM TOOLKIT to ITEMS
	// -----------------------------------
	var tools = ["contact", "showroom", "slide_panel"];	
	$.each(tools, function(){	
		var tool = this;
		$("." + tool + "_wrapper ." + tool + "_item").each(function(i){					
			var id		= $(this).attr("rel");
			var edit	= "<a href=\"/get/edit_" + tool + "/edit/" + id + "\" rel=\"facebox\">edit</a>";
			var del		= "<a href=\"/get/edit_" + tool + "/delete/" + id + "\" class=\"jade_delete_item\">delete</a>";
			var toolbar	= "<div class=\"jade_admin_item_edit\">" + edit + " " + del + "</div>";
					
			$(this).prepend(toolbar);			
		});
	});
	// CALENDAR ITEM TOOLBAR
	// its enabled inside the calendar controller
	// because i dont want to use livequery
		

	// Delegate main link functionality
	$("body").click($.delegate({
	
		// facebox links
		"a[rel*=facebox]": function(e){
			var pane = "base"; // loads in "base" unless otherwise noted via id
			if(e.target.id) var pane = "2";			
			$.facebox(function(){
					$.get(e.target.href, function(data){
						$.facebox(data, false, "facebox_"+pane);
					});
			}, false, "facebox_"+pane);
			return false;
		},
		
		// img facebox links
		".imgfacebox": function(e){
			var pane = "base"; 	// loads in "base" unless otherwise noted via id
			var href = $(e.target).parent().attr("href");
			if(e.target.id) var pane = "2";		
			$.facebox(function(){
					$.get(href, function(data){
						$.facebox(data, false, "facebox_"+pane);
					});
			}, false, "facebox_"+pane);
			return false;
		},
		
		// delete an item link		
		"a.jade_delete_item": function(e) {
			url = $(e.target).attr("href");
			var data = "<div class=\"buttons confirm_facebox\">This can not be undone.<br><br><a href=\"/#\" class=\"cancel_delete\"><img src='/images/admin/asterisk_yellow.png'>Cancel</a><br><br><a href=\"" + url +"\"  class=\"jade_confirm_delete_common jade_negative\"><img src='/images/admin/cross.png'>Delete Item</a></div>";
			
			$.facebox(data, "confirm_facebox", "confirm_dialog");
			return false;		
		},
		
		// delete a tool link	
		"a.jade_delete_tool img, a.jade_delete_tool span": function(e) {
			url = $(e.target).parent().attr("href");
			var data = "<div class=\"buttons confirm_facebox\">This will delete this entire tool.<br>All content will be lost forever!<br><br><a href=\"/#\" class=\"cancel_delete\"><img src='/images/admin/asterisk_yellow.png'>Cancel</a><br><br><br><a href=\"" + url +"\"  class=\"jade_confirm_delete_common jade_negative\"><img src='/images/admin/cross.png'>Delete Tool</a></div>";	
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
				beforeSubmit: function(){
					if( $(".ajaxForm input").jade_validate() )
						return true;
					else
						return false;
				},
				success: function(data) {
					$.facebox(data, "ajax_status", "facebox_2");
					location.reload();							
				}					
			};
		$(".ajaxForm").ajaxForm(options);
		
		// wysiwyg text editor
		$("textarea.render_html").wysiwyg();
		
		// Focus for input fields
		$("form input, form select").focus(function(){
			$("form input, form select").removeClass("input_focus");
			$(this).addClass("input_focus");
		});
	});

	$("#test_content_toggle").click(function(){
		$(".jade_toolbar_wrapper").next().slideToggle("slow");
		$(".jade_toolbar_wrapper ul").toggle();

		// HIGHLITE CONTAINERS.
		for(i=1;i<=5;i++){
			$(".container_"+i).addClass("CONTAINER_WRAPPER");
		}

		
		$(".CONTAINER_WRAPPER").sortable({
			items: 'span.common_tool_wrapper',
			connectWith: '.CONTAINER_WRAPPER',
			forcePlaceholderSize: true,
			placeholder: 'CONTAINER_placeholder',
			appendTo: 'body',
			cursor: 'move',
			cursorAt: 'top',
			forceHelperSize: true,
			handle: '.jade_toolbar_wrapper',
			//helper: 'clone',
			opacity: '0.8'
			//revert: true
		});
	});
	


	
	
});