﻿
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
	
	$('#admin_bar li.dropdown div').click(function(){
		$('#admin_bar li.dropdown ul').hide();
		$(this).next('ul').show();
		return false;		
	});

	// Click away hides open toolbars
	$('body:not(.jade_toolbar_wrapper)').click(function(){
		$('li.dropdown ul').hide();
		$('.actions_wrapper ul').hide();
	});


	// ADD redbar Tool toolkit to all tools
	// -----------------------------------
	 $(".common_tool_wrapper").each(function(i){
		++i;
		var temp	= new Array();
		temp		= $(this).attr('id').split('_');
		var toolkit = $("#toolkit_" + temp[1]).html();
		var toolbar = '<div id="toolbar_' + temp[1]  + '" class="jade_toolbar_wrapper">' + toolkit + '</div>';
		$(this).prepend(toolbar);
	 });
	 
	// ACTIVATE action Tool toolkit menus	
	$('.actions_link').click(function(){
		$(this).next('ul').toggle();
		return false;
	});
	
	/* ADD blue tool-item toolkits
	 * selector format: .tool_wrapper .tool_item
	 * -----------------------------------
	 */
	var tools = ['contact', 'showroom', 'slide_panel', 'faq', 'blog'];
	$.each(tools, function(){	
		var tool = this;
		$("." + tool + "_wrapper ." + tool + "_item").each(function(i){					
			var id		= $(this).attr("rel");
			var edit	= '<img src="/assets/images/admin/cog_edit.png" alt=""> <a href="/get/edit_' + tool + '/edit/' + id + '" rel="facebox">edit</a>';
			var del		= '<img src="/assets/images/admin/delete.png" alt=""> <a href="/get/edit_' + tool + '/delete/' + id + '" class="js_admin_delete" rel="'+tool+'_item_'+id+'">delete</a>';
			var toolbar	= '<div class="jade_admin_item_edit"><span>'+ tool +' item</span>'+ edit +' ' + del + '</div>';
					
			$(this).prepend(toolbar);			
		});
	});

	/* add tool-item toolkits to DOM ajax requests
	 * enabled for calendar, showroom, (blog)
	 * -----------------------------------
	*/
	jQuery.fn.add_toolkit_items = function(toolname){
		$("." + toolname + "_wrapper ." + toolname + "_item").each(function(i){					
			var id		= $(this).attr("rel");
			var edit	= '<a href="/get/edit_' + toolname + '/edit/' + id + '" rel="facebox">edit</a>';
			var del		= '<a href="/get/edit_' + toolname + '/delete/' + id + '" class="js_admin_delete" rel="item_'+id+'">delete</a>';
			var toolbar	= '<div class="jade_admin_item_edit">' + edit + ' ' + del + '</div>';
			$(this).prepend(toolbar);			
		});
	};	
	
	/*
	 * DELEGATE admin link functionality
	 * -----------------------------------
	 */
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

		// DELETE tool or tool item link		
		"a.js_admin_delete": function(e) {
			var url = $(e.target).attr("href");
			var rel	= $(e.target).attr('rel');
			var data = '<div class="buttons confirm_facebox">This can not be undone.<br><p><a href="#" class="cancel_delete"><img src="/assets/images/admin/asterisk_yellow.png">Cancel</a></p><a href="' + url +'"  class="jade_confirm_delete_common jade_negative" rel="'+ rel +'"><img src="/assets/images/admin/cross.png">Delete Item</a></div>';	
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
			var url	= $(e.target).attr("href");
			var el	= $(e.target).attr('rel');
			$.get(url, function(data) {
				$('#' + el).remove();
				$.facebox(data, "status_close", "confirm_dialog")
				setTimeout('$.facebox.close()', 1000);					
			});
			return false;
		}

	}));
	
	/*
	 * ACTIVATE default ajax forms in all facebox windows
	 * Can delegate on the submit event but we'll keep it as is for now.
	 * ---------------------------------------------------------------------
	 */
	$(document).bind('reveal.facebox', function(){		
		// Activate wysiwyg editor.
		/*
		$('textarea').fck({
			path: '/assets/js/fckeditor/'
			, toolbar:'Plusjade'
		});
		*/
		$('textarea.render_html').wysiwyg();
		var options = {
			beforeSubmit: function(){
				if(! $(".ajaxForm input").jade_validate() )
					return false;
			},
			success: function(data) {
				$.facebox(data, "status_reload", "facebox_2");
				location.reload();							
			}					
		};
		$(".ajaxForm").ajaxForm(options);
		
		
		// Focus for input fields
		$("form input, form select").focus(function(){
			$("form input, form select").removeClass("input_focus");
			$(this).addClass("input_focus");
		});
	});

	

	// ACTIVATE sortable containers
	// -------------------------------------------------
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
		},
		update: function(event, ui){			
			var output = '';
			page_id = $('#click_hook').attr("rel");
			
			$(".CONTAINER_WRAPPER").each(function(){
				var container = $(this).attr("rel");
				var kids = $(this).children("span.common_tool_wrapper");
				
				$(kids).each(function(i){
					var scope = $(this).attr("rel");
					output += scope + "." + this.id + "." + container + "." + i + "#";
				});
			});
			$.facebox(function() {
					$.post("/get/page/tools/"+page_id, {output: output}, function(data){
						$.facebox(data, "status_close", "facebox_2");
						setTimeout('$.facebox.close()', 1000);
					})
				}, 
				"status_close", 
				"facebox_2"
			);
			
		}
		//revert: true
	});	
	
	// ADD local/global toggle to action lists
	// NOTE: consider doing this on the server.
	$("span.common_tool_wrapper").each(function(){
		var scope = $(this).attr("rel");
		var toggle = 'local';
		if("local" == scope) toggle = 'global';
		var scope_toggle = '<li><a href="#" class="toggle_scope" rel="'+ toggle +'"><img src="/assets/images/admin/'+ toggle +'.png" alt=""> Make '+ toggle +'</a></li>';
		$("ul.toolkit_dropdown", this).append(scope_toggle);
	});	

	// ACTIVATE local/global scope toggle
	$("span.common_tool_wrapper").click($.delegate({
		".toggle_scope": function(e){
			var new_scope = $(e.target).attr("rel");	
			var toggle = "local";
			if("local" == new_scope) toggle = "global";
			var new_link = '<a href="#" class="toggle_scope" rel="' + toggle + '"><img src="/assets/images/admin/'+ toggle +'.png" alt=""> Make ' + toggle + '</a>';
			
			$(e.target).parents("span").removeAttr("rel").attr("rel",new_scope);
			$(e.target).replaceWith(new_link);
			return false;				
		}
	}));
	
	
	// SAVE container tool-position results
	var output = '';	
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
	
	/*
	var zIndexNumber = 1000;
	$('.CONTAINER_WRAPPER div, .CONTAINER_WRAPPER ul ').each(function() {
		$(this).css('zIndex', zIndexNumber);
		zIndexNumber -= 10;
		//alert('blah');
	});
	*/



	
});