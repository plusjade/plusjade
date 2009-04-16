
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

	// ACTIVATE Sitewide admin bar dropdowns
	// -----------------------------------
	$('#admin_bar li.dropdown ul').hide();
	$('#admin_bar li.dropdown div').hover(
		function(){
			$('#admin_bar li.dropdown ul').hide();
			$(this).next('ul').show();
		},
		function(){
			$(this).next('ul').hover(
				function(){},
				function(){$(this).hide()}
			);
		}
	);
	
	// ADD Tool toolkit to all tools on page
	// -----------------------------------
	 $(".common_tool_wrapper").each(function(i){
		++i;
		var guid = this.id;
		var toolkit = $("#toolkit_" + guid).html();
		var toolbar = '<div id="toolbar_' + guid  + '" class="jade_toolbar_wrapper" style="z-index:200">' + toolkit + '</div>';
		$(this).prepend(toolbar);
	
	 });
	 
	 
	// ADD tool-item toolkits
	// -----------------------------------
	var tools = ["contact", "showroom", "slide_panel"];	
	$.each(tools, function(){	
		var tool = this;
		$("." + tool + "_wrapper ." + tool + "_item").each(function(i){					
			var id		= $(this).attr("rel");
			var edit	= '<a href="/get/edit_' + tool + '/edit/' + id + '" rel="facebox">edit</a>';
			var del		= '<a href="/get/edit_' + tool + '/delete/' + id + '" class="jade_delete_item" rel="item_'+id+'">delete</a>';
			var toolbar	= '<div class="jade_admin_item_edit">' + edit + ' ' + del + '</div>';
					
			$(this).prepend(toolbar);			
		});
	});
	// CALENDAR ITEM TOOLBAR
	// its enabled inside the calendar controller
	// because i dont want to use livequery
		

	// DELEGATE main link functionality
	// -----------------------------------
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
		
		// DELETE single item link		
		"a.jade_delete_item": function(e) {
			var url = $(e.target).attr("href");
			var rel	= $(e.target).attr('rel');
			var data = '<div class="buttons confirm_facebox">This can not be undone.<br><br><a href="#" class="cancel_delete"><img src="/images/admin/asterisk_yellow.png">Cancel</a><br><br><a href="' + url +'"  class="jade_confirm_delete_common jade_negative" rel="'+ rel +'"><img src="/images/admin/cross.png">Delete Item</a></div>';	
			$.facebox(data, "confirm_facebox", "confirm_dialog");
			return false;		
		},
		
		// DELETE single tool link	
		"a.jade_delete_tool": function(e) {
			var url		= e.target.href;
			var rel	= $(e.target).attr('rel');
			var data	= '<div class="buttons confirm_facebox">This will delete this entire tool.<br>All content will be lost forever!<br><br><a href="#" class="cancel_delete"><img src="/images/admin/asterisk_yellow.png">Cancel</a><br><br><br><a href="'+ url +'" class="jade_confirm_delete_common jade_negative" rel="'+ rel +'"><img src="/images/admin/cross.png">Delete Tool</a></div>';	
			$.facebox(data, "confirm_facebox", "confirm_dialog");
			return false;		
		},

		// CANCEL delete button
		"a.cancel_delete": function() {
			$.facebox.close();
			return false;	
		},
		
		// DELETE CONFIRMED common button in facebox
		"a.jade_confirm_delete_common": function(e) {
			var url		= $(e.target).attr("href");
			var temp	= new Array();
			temp		= $(e.target).attr('rel').split('_');
			// # type/id
			
			// FIX THIS so it works with all tool items
			if('tool' == temp[0]) selector = 'span.guid_';
			else selector = '#showroom_item_'; 
	
			$.get(url, function(data) {
				$(selector + temp[1]).remove();
				$.facebox(data, "status_close", "confirm_dialog")
				setTimeout('$.facebox.close()', 1000);					
			});
			return false;
		}

	}));

	// ACTIVATE DEFAULT AJAX FORMS in all facebox windows
	// Cannot delegate this since theres no event.
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
					$.facebox(data, "status_reload", "facebox_2");
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

	
	
/*
	test area 
*/	

	// ACTIVATE sortable containers
	for(i=1;i<=5;i++){
		$(".container_"+i).addClass("CONTAINER_WRAPPER").attr("rel",i);
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
		handle: '.jade_toolbar_wrapper span.name',
		scrollSensitivity: 40,
		tolerance: 'pointer',
		//helper: 'clone',

		start: function(event, ui) {
			$('.CONTAINER_WRAPPER').toggleClass('highlight_containers');
			$(ui.item).toggleClass('sort_active').children('div:last').hide();
		},
		stop: function(event, ui) {
			$('.CONTAINER_WRAPPER').toggleClass('highlight_containers');
			$(ui.item).toggleClass('sort_active').children('div:last').show();
		}
		//revert: true
	});	
	
	// ADD local/global toggle to action lists
	// NOTE: consider doing this on the server.
	$("span.common_tool_wrapper").each(function(){
		var scope = $(this).attr("rel");
		var toggle = 'local';
		if("local" == scope) toggle = 'global';
		var scope_toggle = '<li><a href="#" class="toggle_scope" rel="'+ toggle +'"><img src="/images/admin/'+ toggle +'.png" alt=""> Make '+ toggle +'</a></li>';
		$("ul.toolkit_dropdown", this).append(scope_toggle);
	});	

	// ACTIVATE local/global scope toggle
	$("span.common_tool_wrapper").click($.delegate({
		".toggle_scope": function(e){
			var new_scope = $(e.target).attr("rel");	
			var toggle = "local";
			if("local" == new_scope) toggle = "global";
			var new_link = '<a href="#" class="toggle_scope" rel="' + toggle + '"><img src="/images/admin/'+ toggle +'.png" alt=""> Make ' + toggle + '</a>';
			
			$(e.target).parents("span").removeAttr("rel").attr("rel",new_scope);
			$(e.target).replaceWith(new_link);
			return false;				
		}
	}));
	
	
	// SAVE container tool-position results
	var output = "";	
	$("#get_tool_sort").click(function(){
		page_id = $(this).attr("rel");
		
		$(".CONTAINER_WRAPPER").each(function(){
			var container = $(this).attr("rel");
			var kids = $(this).children("span.common_tool_wrapper");
			
			$(kids).each(function(i){
				var scope = $(this).attr("rel");
				output += scope + "." + this.id + "." + container + "." + i + "#";
			});
		});
		//alert(output); return false;					
					
		$.facebox(function() {
				$.post("/get/page/tools/"+page_id, {output: output}, function(data){
					$.facebox(data, "status_close", "facebox_2");
					setTimeout('$.facebox.close()', 1000);
				})
			}, 
			"status_close", 
			"facebox_2"
		);
	});		

	// ACTIVATE hover action Tool toolkit menus	
	$('.actions_link').hover(
		function(){
			$(this).next('ul').show();
		},
		function(){
			$(this).next('ul').hover(
				function(){},
				function(){$(this).hide();}
			);
		}
	);
	
		/*
	var zIndexNumber = 1000;
	$('.CONTAINER_WRAPPER div, .CONTAINER_WRAPPER ul ').each(function() {
		$(this).css('zIndex', zIndexNumber);
		zIndexNumber -= 10;
		//alert('blah');
	});
	*/
	
});