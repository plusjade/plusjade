
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
	 $('.common_tool_wrapper').each(function(i){
		++i;
		var temp	= new Array();
		temp		= $(this).attr('id').split('_');
		var toolkit = $('#toolkit_' + temp[1]).html();
		var toolbar = '<div id="toolbar_' + temp[1]  + '" class="jade_toolbar_wrapper">' + toolkit + '</div>';
		$(this).prepend(toolbar);
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

	/* add blue tool-item toolkits to DOM ajax requests
	 * enabled for calendar, showroom, (blog)
	 * -----------------------------------
	*/
	jQuery.fn.add_toolkit_items = function(toolname){
		toolname = toolname.toLowerCase();
		$('.'+ toolname +'_item', this).each(function(i){		
			var id		= $(this).attr('rel');
			var edit	= '<img src="/assets/images/admin/cog_edit.png" alt=""> <a href="/get/edit_' + toolname + '/edit/' + id + '" rel="facebox">edit</a>';
			var del		= '<img src="/assets/images/admin/delete.png" alt=""> <a href="/get/edit_' + toolname + '/delete/' + id + '" class="js_admin_delete" rel="'+ toolname +'_item_'+ id +'">delete</a>';
			var toolbar	= '<div class="jade_admin_item_edit"><span>'+ toolname +' item</span>'+ edit + ' ' + del + '</div>';
			$(this).prepend(toolbar);			
		});
	};	

	/*
	 * updates the tool container <#tool_wrapper_id> 
	 * with the updated output from that tool.
	*/
	jQuery.fn.jade_update_tool_html = function(action, toolname, tool_id, response){
		
		if(!response) response = 'Updating...';
		
		// Set loading status...
		if('add' == action) {
			// default add to container_1
			$('div.container_1').prepend('<div id="new_tool_placeholder" class="load_tool_html">Adding Tool...</div>');
		} else if('update' == action){
			$('#'+ toolname +'_wrapper_'+ tool_id).html('<div class="load_tool_html">Updating...</div>');
		}
		
		// Get the tool html output...
		$.get('/get/tool/html/'+ toolname +'/'+ tool_id, function(data){					
			/* possible actions:
				1. add
				2. update
			 */
			if('add' == action) {
				// response = guid
				// get the toolkit to insert red toolbar via ajax
				$.get('/get/tool/toolkit/'+ response, function(toolkit){
					toolbar = '<div id="toolbar_'+ response +'" class="jade_toolbar_wrapper">' + toolkit + '</div>';
					
					// replace the placeholder with toolbar + html output
					$('div.container_1 #new_tool_placeholder')
					.replaceWith('<span id="guid_'+ response +'" class="common_tool_wrapper" rel="local">' + toolbar + data + '</span>');	
				
					// add blue per-item toolbars
					$('#'+ toolname +'_wrapper_'+ tool_id).add_toolkit_items(toolname);
				});
			}
			else if('update' == action) {
				// replace old tool html with new html
				$('#'+ toolname +'_wrapper_'+ tool_id).replaceWith(data);
				// reapply blue per-item toolbars
				$('#'+ toolname +'_wrapper_'+ tool_id).add_toolkit_items(toolname);			
			}
		});
	};	


	/*
	 * DELEGATE admin link functionality
	 * -----------------------------------
	 */
	$("body").click($.delegate({
	
		// facebox links
		"a[rel=facebox]": function(e){
			var pane = "base"; // loads in "base" unless otherwise noted via id
			if(e.target.id) var pane = "2";			
			$.facebox(function(){
					$.get(e.target.href, function(data){
						$.facebox(data, false, "facebox_"+pane);
					});
			}, false, "facebox_"+pane);
			return false;
		},
		
		// facebox load div content
		"a[rel=facebox_div]": function(e){
			var pane = "base"; // loads in "base" unless otherwise noted via id
			if(e.target.id) var pane = "2";
			var url    = e.target.href.split('#')[0]
			var target = e.target.href.replace(url,'')				
			$.facebox($(target).clone().show(), false, "facebox_"+pane);
			return false;
		},
		
		// DELETE tool or tool item link		
		"a.js_admin_delete": function(e) {
			var url = $(e.target).attr("href");
			var rel	= $(e.target).attr('rel');
			var data = '<div class="buttons confirm_facebox">This can not be undone.<br><p><a href="#" class="cancel_delete"><img src="/assets/images/admin/asterisk_yellow.png">Cancel</a></p><a href="' + url +'"  class="jade_confirm_delete_common jade_negative" rel="'+ rel +'"><img src="/assets/images/admin/cross.png">Delete Item</a></div>';	
			$.facebox(data, 'confirm_facebox', 'facebox_2');
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
			$.facebox('deleting ...', "status_close", "facebox_2");
			$.get(url, function() {
				$.facebox.close();	
				$('#' + el).remove();		
			});
			return false;
		},
		
		"a.update_tool_html": function(e) {
			values		= e.target.href.split('/');
			tool_id		= values.pop();
			toolname	= values.pop();
			$.facebox.close();
			$().jade_update_tool_html('update', toolname, tool_id);
			return false;
		},
		
		// ACTIVATE action Tool toolkit menus	
		".actions_link": function(e) {
			$(e.target).next('ul').toggle();
			return false;
		}
	}));



	
	/* AJAX FORMS
	 * ACTIVATE default ajax forms in all facebox windows
	 * Can delegate on the submit event but we'll keep it as is for now.
	 * ---------------------------------------------------------------------
	 */
	$(document).bind('reveal.facebox', function(){
		$('body').addClass('disable_body').attr('scroll','no');
		$('.facebox .show_submit').hide();
		$(".ajaxForm").ajaxForm({
			beforeSubmit: function(){
				if(! $(".ajaxForm input").jade_validate() )
					return false;
					
				$('.facebox .show_submit').show();
			},
			success: function(data) {
				$('.facebox .show_submit').hide();
				var action = $('form.ajaxForm').attr('rel');

				if( 'undefined' == typeof(action) ) {
					// TODO: find something good to put here
					// if no rel attribute specified ...
					$.facebox(data, "status_reload", "facebox_2");
					alert('this form had no rel attribute');
				} else {
					action = action.split('-');
					//action	= action[0];
					//toolname	= action[1];
					//tool_id	= action[2];					
					if('close' == action[0] ) {
						// TODO: Sanitize this?
						// useful for facebox_2 requests
						$.facebox.close('facebox_'+action[1]);
					} else if('scope' == action[0] ) {
						$('span#guid_'+ action[2]).removeClass('local global');
						$('span#guid_'+ action[2]).addClass(data);
						$.facebox.close();
					} else {
						// in case of update or add
						// update tool html output to DOM via ajax
						
						$.facebox.close();
						$().jade_update_tool_html(action[0], action[1], action[2], data);	
					}
				}
				return true;
			}
		});
		
		$('textarea.render_html').wysiwyg();
		// Activate wysiwyg editor.
		/*
		$('textarea').fck({
			path: '/assets/js/fckeditor/'
			, toolbar:'Plusjade'
		});
		*/
	});

	$(document).bind('close.facebox', function(){
		$('body').removeClass('disable_body').removeAttr('scroll');
	});

	

	// ACTIVATE sortable containers
	// -------------------------------------------------
	for(i=1;i<=5;i++){
		$('.container_'+i).addClass("CONTAINER_WRAPPER").attr('rel', i);
	}

	$('.CONTAINER_WRAPPER').sortable({
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
			page_id = $('#click_hook').attr('rel');
			
			$(".CONTAINER_WRAPPER").each(function(){
				var container = $(this).attr("rel");
				var kids = $(this).children("span.common_tool_wrapper");
				
				$(kids).each(function(i){
					output += this.id + '|' + container + '|' + i + '#';
				});
			});
			$.facebox('Saving...', "status_close", "facebox_2");
			$.post("/get/tool/save_positions/"+page_id, {output: output}, function(data){
				$.facebox(data, "status_close", "facebox_2");
				setTimeout('$.facebox.close()', 1000);
			});
		}
		//revert: true
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