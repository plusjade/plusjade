
$(document).ready(function()
{

/*
 * TOGGLE ADMIN BAR	
 */
	$(".toggle_admin_bar").click(function(){
		$('#shadow').slideUp('slow');
		$("#admin_bar_wrapper").slideUp("slow", function(){
			$(".jade_toolbar_wrapper").slideUp("slow");
			$(".jade_admin_item_edit").slideUp("slow");
			$("#hide_link").slideDown("slow");
		});
	});
	$("#hide_link a").click(function(){
		$('#shadow').slideDown("slow");
		$("#admin_bar_wrapper").slideDown("slow", function(){
			$(".jade_toolbar_wrapper").slideDown("slow");
			$(".jade_admin_item_edit").slideDown("slow");
			$("#hide_link").slideUp("slow");
		});
	});	
	
	$('#shadow div').css({background: '#000', opacity: 0.2});

/*
 * ACTIVATE Sitewide admin bar dropdowns
 */
	$('#admin_bar li.dropdown ul').hide();
	
	$('#admin_bar li.dropdown div').click(function(){
		$('#admin_bar li.dropdown ul').hide();
		$(this).next('ul').show();
		$('#admin_bar li.dropdown div').removeClass('dropdown_selected');
		$(this).addClass('dropdown_selected');
		return false;		
	});

/*
 * Click away hides open toolbars
 */
	$('body:not(.jade_toolbar_wrapper)').click(function(){
		$('li.dropdown ul').hide();
		$('.actions_wrapper .toolkit_wrapper').hide();
		$('#admin_bar li.dropdown div').removeClass('dropdown_selected');
	});

/*
 * ADD redbar Tool toolkit to all tool instances on the page.
 */
	 $('.common_tool_wrapper').each(function(){
		var instanceId = $(this).attr('id').split('_')[1];
		var toolkit = $('#toolkit_' + instanceId).html();
		var toolbar = '<div id="toolbar_' + instanceId  + '" class="jade_toolbar_wrapper">' + toolkit + '</div>';
		$(this).prepend(toolbar);
	 });

	 
/* 
 * add blue tool-item toolkits to DOM ajax requests
 */
	jQuery.fn.add_toolkit_items = function(toolname){
		var toolname = toolname.toLowerCase();
		$('.'+ toolname +'_item', this).each(function(i){		
			var id		= $(this).attr('rel');
			var edit	= '<span class="icon cog">&nbsp; &nbsp; </span> <a href="/get/edit_' + toolname + '/edit/' + id + '" rel="facebox">edit</a>';
			var del		= '<span class="icon cross">&nbsp; &nbsp; </span> <a href="/get/edit_' + toolname + '/delete/' + id + '" class="js_admin_delete" rel="'+ toolname +'_item_'+ id +'">delete</a>';
			var toolbar	= '<div class="jade_admin_item_edit"><span class="item_name">'+ toolname +' item</span>'+ edit + ' ' + del + '</div>';
			$(this).prepend(toolbar);			
		});
	};	
	
/* 
 * ADD blue tool-item toolkits
 * selector format: .tool_wrapper .tool_item
 */	
	var tools = ['showroom', 'format', 'blog', 'reviews'];
	$.each(tools, function(){
		$().add_toolkit_items(this);
	});
	
/*
 * injects tool output into the DOM.
 * can add tool output Into DOM or update tools already in DOM via #tool_wrapper_<id>
	action	= (string)
	tool	= (object)
*/
	jQuery.fn.jade_inject_tool = function(action, tool){
	
		var include_js = 'yes'; // Set loading status...
		
		if('add' == action) {
			// default add to container_1
			$('div.container_1').prepend('<div id="new_tool_placeholder" class="load_tool_html">Adding Tool...</div>');
		} else {
			include_js = 'no';
			$('#'+ tool.toolname +'_wrapper_'+ tool.parent_id).html('<div class="load_tool_html">Updating...</div>');
		}

		// Get the tool output from the server...
		$.get('/get/tool/html/'+ tool.toolname +'/'+ tool.parent_id, {js: include_js}, function(data){	
			if('add' == action) {
				// TODO: finalize a proper way to get the page_id
				var page_id = $('#click_hook').attr('rel');
				
				// get the toolkit to insert red toolbar via ajax
				$.get('/get/tool/toolkit/'+ tool.instance +'/' + tool.tool_id + '/' + page_id, function(toolkit){
					var toolbar = '<div id="toolbar_'+ tool.instance +'" class="jade_toolbar_wrapper">' + toolkit + '</div>';
					// replace the placeholder with toolbar + html output
					$('div.container_1 #new_tool_placeholder')
					.replaceWith('<span id="instance_'+ tool.instance +'" class="common_tool_wrapper local" rel="guid_' + tool.tool_id + '">' + toolbar + data + '</span>');	
				});
			}
			else {
				// replace old tool html with new html
				$('#'+ tool.toolname +'_wrapper_'+ tool.parent_id).replaceWith(data);
			}
			
			// apply blue per-item toolbars
			$('#'+ tool.toolname +'_wrapper_'+ tool.parent_id).add_toolkit_items(tool.toolname);
		});
	};	


/*
 * DELEGATE admin link functionality
 */
	$("body").click($.delegate({
	
	  // facebox load ajax
		"a[rel=facebox]": function(e){
			var pane = ((e.target.id)) ? '2' : 'base';	
			$.facebox(function(){
					$.get(e.target.href, function(data){
						$.facebox(data, false, "facebox_"+pane);
					});
			}, false, "facebox_"+ pane);
			return false;
		},
		
	  // facebox for icon spans since delegation does not bubble.
		"a[rel=facebox] span.icon": function(e){
			$parent = $(e.target).parent();
			var url = $parent.attr('href');
			var pane = ((e.target.id)) ? '2' : 'base';		
			$.facebox(function(){
					$.get(url, function(data){
						$.facebox(data, false, "facebox_"+pane);
					});
			}, false, "facebox_"+pane);
			return false;
		},
		
	  // facebox load div content
		"a[rel=facebox_div]": function(e){
			var pane = ((e.target.id)) ? '2' : 'base';
			var url    = e.target.href.split('#')[0];
			var target = e.target.href.replace(url,'');			
			$.facebox($(target).clone().show(), false, "facebox_"+pane);
			return false;
		},
		
	  // DELETE tool or tool item		
		"a.js_admin_delete": function(e) {
			var url = $(e.target).attr("href");
			var rel	= $(e.target).attr('rel');
			var data = '<div class="buttons confirm_facebox">This can not be undone.<br>\
				<p><a href="#" class="cancel_delete">\
					<span class="icon asterisk">&#160; &#160; &#160;</span> Cancel\
				</a></p>\
				<a href="' + url +'"  class="jade_confirm_delete_common jade_negative" rel="'+ rel +'">\
					<span class="icon cross">&#160; &#160; &#160;</span> Delete Item\
				</a>\
			</div>';	
			$.facebox(data, 'confirm_facebox', 'facebox_2');
			return false;		
		},
		
	  // CANCEL delete button
		"a.cancel_delete": function() {
			$.facebox.close();
			return false;	
		},

	  // DELETE CONFIRMED tool object. (not instance but an actual tool)
		"a.jade_confirm_delete_common": function(e) {
			var url	= $(e.target).attr("href");
			var el	= $(e.target).attr('rel');
			$(document).trigger('show_submit.plusjade');
			$.get(url, function(data) {
				$.facebox.close();
				// a tool can be on a page multiple times, so we query by rel.
				$("span[rel='" + el + "']").remove();
				$('#center_response').html(data).show();
				setTimeout('$("#center_response").fadeOut(4000)', 1500);	
			});
			return false;
		},

		
	  // OFF*** DELETE CONFIRMED common button in facebox
		"a.OFF_jade_confirm_delete_common": function(e) {
			var url	= $(e.target).attr("href");
			var el	= $(e.target).attr('rel');
			$(document).trigger('show_submit.plusjade');
			$.get(url, function(data) {
				$.facebox.close();
				$('#' + el).remove();
				$('#center_response').html(data).show();
				setTimeout('$("#center_response").fadeOut(4000)', 1500);	
			});
			return false;
		},

	  // remove a tool instance from the page and DOM.
		"a.jade_tool_remove": function(e) {
			var url	= $(e.target).attr("href");
			var el	= $(e.target).attr('rel');
			$(document).trigger('show_submit.plusjade');
			$.get(url, function(data) {
				$('#' + el).remove();
				$('#center_response').html(data).show();
				setTimeout('$("#center_response").fadeOut(4000)', 1500);	
			});
			return false;
		},


	  // ?? delete a file asset maybe?
		'ul.row_wrapper .delete_item a' :function(e){
			if(confirm('This cannot be undone. Delete this item?')){
				var id = $(e.target).attr('rel');
				$.get(e.target.href, function(){
					$('#item_'+ id).remove();
				});
			}
			return false;
		},
		
	  // ACTIVATE action Tool toolkit menus	
		".actions_link": function(e) {
			$(e.target).next('div').toggle();
			return false;
		},

	  // ACTIVATE action Tool toolkit menus for span icons	
		".actions_link span.icon": function(e) {
			$(e.target).parent('a').next('div').toggle();
			return false;
		},		

	  // toggle edit tool view panes
		"#common_view_toggle li a": function(e){
			$('.common_main_panel div.toggle').hide();
			$('#common_view_toggle li a').removeClass('selected');
			var div = $(e.target).addClass('selected').attr('href');
			$('.common_main_panel div'+ div).show();
			return false;
		},	



		
	/* Click actions for css styler ----- */
	/* ------------------------------------------------------------  */
		
	// load the styler contents (css)
		"a[rel=css_styler]": function(e) {
			$.facebox.close();
			$('div.styler_wrapper').show();
			$('div.styler_wrapper .styler_dialog')
			.html('<div class="loading">Loading...</div>')
			.load(e.target.href, function(){
				$('.facebox .show_submit').hide();
				$(document).trigger('ajaxify.form');				
			});
			return false;
		},
		
	  // hide the styler dialog
		"div.styler_wrapper a.hide": function(e) {
			$('div.dialog_wrapper').toggle('fast');			
			var state = $(e.target).html();
			
			if('hide'== state){
				$(e.target).html('show');
				$('div.styler_wrapper').css('top', $.getPageHeight()- 32);
			}
			else{
				$('div.styler_wrapper').css('top', $.getPageHeight()- 435);
				$(e.target).html('hide')
			}
			return false;
		},
		
	  // close the styler dialog
		"div.styler_wrapper a.close": function(e) {
			$(document).trigger('on_close.execute');
			$('div.styler_wrapper').hide();
			$('div.styler_wrapper div.styler_dialog').html('');
			$('#files_browser_wrapper img').unbind('dblclick');
			return false;
		},
		
	  // cross button hides the pop up dialog
		'span.icon.cross.floatright' : function(e) {
			$(e.target).parent('div').hide();
		}
	}));
	
/* 
 * auto-filter form fields delegation
 */
$('body').keyup($.delegate({

	"input.send_input": function(e){
		var input = $(e.target).val().replace(/[^-a-z0-9_]/ig, '-').toLowerCase();
		$(e.target).siblings('input.receive_input').val(input);
		$('span#link_example').html(input);
	},
	
	"input.auto_filename": function(e){
		var input = $(e.target).val().replace(/[^-a-z0-9_]/ig, '').toLowerCase();
		$(e.target).val(input);
		$('span#link_example').html(input);
	}
}));


/* 
 * Add the css styler wrapper into the DOM.
 */
	$('body')
	.append('<div class="styler_wrapper admin_reset" style="display:none"> \
		<div class="actions"> \
			<a href="#" class="close">close</a> \
			<a href="#" class="hide">hide</a> \
			<div id="lower_response" class="server_response" style="display:none;">Server Response</div>\
			<div class="show_submit" style="display:none"><div>...Submitting</div></div> \
		</div> \
		<div class="dialog_wrapper"> \
			<div class="styler_dialog">&#160;</div> \
		</div>\
	</div>');
	$('div.styler_wrapper').css('top', $.getPageHeight()- 405);

	
/*
 * ajaxify the forms 
 */
	$(document).bind('ajaxify.form', function(){
		$('.ajaxForm').ajaxForm({		 
			beforeSubmit: function(fields, form){
				if(! $("input", form[0]).jade_validate() ) return false;

				if('no_disable' != $(form[0]).attr('rel'))
					$('button', form[0])
					.attr('disabled','disabled')
					.removeClass('jade_positive');
				
				$(document).trigger('show_submit.plusjade');
			},
			success: function(data) {
				$('.facebox form button')
				.removeAttr('disabled')
				.addClass('jade_positive');
				$(document).trigger('server_response.plusjade', data);
				
				
				// if 2 fbs are active, we assume the form is submitted from 2
				// so we close only box 2, else close everything.				
				//var whichBox = (1 < $('.facebox_active').length) ? 'facebox_2' : null;
				//$.facebox.close(whichBox);
			}
		});
	});

	
/*
 * show the submit ajax loading graphic.
 */
$(document).bind('show_submit.plusjade', function(){
	$('.facebox_response.active').remove();
	$('.admin_reset .show_submit').show();
});

/*
 * show the resultant server data
 */
$(document).bind('server_response.plusjade', function(e, data){
	$('.show_submit').hide();
	$('.facebox_response.active').remove();
	$('.facebox_response')
		.clone()
		.addClass('active')
		.html(data)
		.show()
		.insertAfter('.facebox_response');
	setTimeout('$(".facebox_response.active").fadeOut(4000)', 1500);	
});


/* 
 * Bind functions to after facebox is revealed event.
 * 1. Activate rich text editor (jwysiwyg)
 * 2. trigger admin ajax forms.
 * 3. establish full height of facebox when window resizing.
 */	
	$(document).bind('reveal.facebox', function(){
		$('body').addClass('disable_body').attr('scroll','no');
		$('.facebox .show_submit').hide();
		$('.facebox_response.active').remove();
		$('textarea.render_html').wysiwyg();
		$(document).trigger('ajaxify.form');
		
		// Expand/contract box-element to fill up full-available facebox view.
		// Mainly for text editor and asset browsers windows.
		var height = (300 > ($.getPageHeight()- 300)) ? 170 : $.getPageHeight()- 250;
		$('.facebox div.wysiwyg, .facebox textarea.initiliazed, .render_css, .full_height').css('min-height', height);
		$('.facebox div.wysiwyg iframe').css('min-height', height-30);
	});

/*
 * Bind functions to the CLOSE facebox event.
 */	
	$(document).bind('close.facebox', function() {
		$('body').removeClass('disable_body').removeAttr('scroll');
		$('.facebox .show_submit').hide();
		$(document).trigger('on_close.execute');
		
		// testing, dont no if this works the way i want it to.
		// supposed to unbind delegated functionality since it has different ones
		$('#files_browser_wrapper img').unbind('dblclick');
	});

/*
 * execute an on_close command
 */
	$(document).bind('on_close.execute', function() {
		var action = ((1 == $('.on_close').length))
		? $('.on_close').html()
		: $('.on_close.two').html();

		if(null == action) {
			return false;
		} else {
			action = action.split('-'); 
			//**action = array(action, toolname, parent_id);
			var tool = { 
				"toolname" : action[1], 
				"parent_id" : action[2]
			};

			switch(action[0])
			{
				case 'update':
					$().jade_inject_tool('update', tool);	
					break;
				case 'close':
					// useful for facebox_2 requests TODO: Sanitize this?
					break;
				case 'scope':
					$('span#guid_'+ action[2]).removeClass('local global');
					$('span#guid_'+ action[2]).addClass(data);	
					break;
				case 'reload':
					$.facebox(data, 'loading_msg', 'facebox_2');
					location.reload();
					break;
				case 'save_css':
					alert('css saved =D\n');
					break;
				case 'update_menu':
					$('#MAIN_MENU').html('<b>Updating...</b>').load('/get/page/load_menu');
					break;
				default:
					alert(action[0] + 'has no action function');
			}
		}
	});

	
/*
 * ACTIVATE sortable containers
 */
	for(i=1;i<=5;i++){
		$('.container_'+i).addClass("CONTAINER_WRAPPER").attr('rel', i);
	}

	$('.CONTAINER_WRAPPER').sortable({
		items: 'span.common_tool_wrapper',
		connectWith: '.CONTAINER_WRAPPER',
		forcePlaceholderSize: true,
		placeholder: 'CONTAINER_placeholder',
		//appendTo: 'body',
		cursor: 'move',
		cursorAt: 'top',
		//forceHelperSize: true,
		handle: '.jade_toolbar_wrapper div.name_wrapper',
		scrollSensitivity: 40,
		tolerance: 'pointer',
		helper: 'original',
		appendTo: 'body',

		start: function(event, ui) {
			$('.CONTAINER_WRAPPER').toggleClass('highlight_containers');
			$(ui.item).toggleClass('sort_active').children('div:last').hide();
		},
		stop: function(event, ui) {
			$('.CONTAINER_WRAPPER').toggleClass('highlight_containers');
			$(ui.item).toggleClass('sort_active').children('div:last').show();
		},
		update: function(event, ui){			
			var page_id = $('#click_hook').attr('rel');
			var data = new Array();
			$('#center_response').html('Saving tool positions...').show();
			$(".CONTAINER_WRAPPER").each(function(){

				var container = $(this).attr("rel");
				var kids = $(this).children("span.common_tool_wrapper");
				
				$(kids).each(function(i){
					var instance = new Object();
					instance.id = this.id.split('_')[1];
					instance.container = container;
					instance.position = i;
					data.push(instance);
				});
			});
			var dataString = $.toJSON(data); // console.log(data); alert(dataString);
			
			$.post("/get/tool/save_positions/"+ page_id, {output: dataString},
				function(data){
					$('#center_response').html(data).show();
					setTimeout('$("#center_response").fadeOut(4000)', 1500);	
				}
			);
		}
		//revert: true
	});	


// 	
// ------------------------------------------------------------------------------


/* Centralize the main admin interfaces here so we can cache
 * Pages, Files and theme handling.
*/

/* 
 * Pages browser function delegation.
 */
	$('body').click($.delegate({
	// open file dropdown lists
		'#page_browser_wrapper img.file_options, #page_browser_wrapper span.icon.page': function(e){
			$('#page_browser_wrapper ul.option_list').hide();
			$(e.target).nextAll('ul').show();
		},
		
	// show a new folder directory
		'#page_browser_wrapper .open_folder': function(e){
			var path = $(e.target).attr('rel');
			var klass = path.replace(/\//g,'_');
			
			$('div.sub_folders').hide();
			$('#directory_window').attr('rel',klass);		
			$('div.'+klass).show();
			
			// add the breadcrumb
			var folder_string = '';	
			if('ROOT' == path){
				path = '';
			}
			else{
				var folder_array = path.split('/');
				var el_count = folder_array.length;

				for (i=0; i < el_count; i++){
					var result_string = $.strstr(path, folder_array[i], true) + folder_array[i];
					folder_string += ' / <a href="/'+ result_string +'" rel="'+ result_string +'" class="open_folder">'+ folder_array[i] +'</a>';
				}
			}
			$('#breadcrumb').attr('rel',path).html(folder_string);
			return false;
		},
		
	// open new page facebox
		'#page_browser_wrapper a.new_page': function(e){
			$.facebox(function(){
				var path = $('#breadcrumb').attr('rel');
				$.get(e.target.href, {directory: path}, 
					function(data){$.facebox(data, false, 'facebox_2')}
				);
			}, false, 'facebox_2');
			return false;
		},
		
	// delete a page
		'#page_browser_wrapper a.delete_page': function(e){
			if('folder' == $(e.target).attr('rel'))
			{
				alert('A page must have no sub-pages before it can be deleted.');
				return false;
			}
			if (confirm("This cannot be undone! Delete this page?")) {
				$.parent = $(e.target).parent('a');
				var id = $(e.target).attr('id');
				
				$.get(e.target.href, function(data){
					// remove from container
					$('#page_wrapper_'+ id).remove();
					$('#center_response').html(data).show();
					setTimeout('$("#center_response").fadeOut(4000)', 1500);
				});
			}
			return false;
		},

	// turn a page into a folder path
		'#page_browser_wrapper a.folderize': function(e){
			var folder_path = $(e.target).attr('rel');
			var id = $(e.target).attr('id');
			var filename = $(e.target).attr('title');
			var klass = folder_path.replace(/\//g,'_');
			var html = '<img src="/_assets/images/admin/folder.jpg" rel="'+ folder_path +'" class="open_folder"> <span class="icon page">&#160; &#160;</span> ';
			$('#page_wrapper_'+ id +' img').replaceWith(html);
			
			var container = '<div class="'+ klass +' sub_folders"></div>';
			$('#directory_window').prepend(container);
			$(e.target).parent('li').remove();
			return false;
		}	
	}));	
	
	
/* 
 * File Browser function delegation
 */
	$('body').click($.delegate({
	
	// load the file browser into the bottom pane =D
		'a.get_file_browser':function(e){
			var mode = $(e.target).attr('rel');
			$('div.styler_wrapper').show();
			$('div.styler_wrapper .styler_dialog')
			.html('<div class="loading">Loading...</div>')
			.load('/get/files?mode='+ mode, function(){
				$('.facebox .show_submit').hide();
			});
			return false;
		},

		
	// ajax load a real-directory path
		'#files_browser_wrapper .get_folder' : function(e){
			$('#directory_window').html('<div lass="ajax_loading">Loading...</div>');
			var url = $(e.target).attr('href');
			$('#directory_window').load(url);
			
			// add the breadcrumb
			var path = $(e.target).attr('rel');
			var type = $('#breadcrumb').attr('class');
			var folder_string = '';	
			if('ROOT' == path){
				path = '';
			}
			else {
				var folder_array = path.split(':');
				var folder_count = folder_array.length;
				// takes string "one/two/three" & outputs breadcrumb.
				// ex: one, one/two, one/two/three.
				for (i=0; i < folder_count; i++){
					var result_string = $.strstr(path, folder_array[i], true) + folder_array[i];
					folder_string += ' / <a href="/get/'+ type +'/contents/'+ result_string +'" rel="'+ result_string +'" class="get_folder">'+ folder_array[i] +'</a>';
				}
			}
			$('#breadcrumb').attr('rel', path).html(folder_string);			
			return false;
		},

		
	// add a file to a real directory folder
		'#files_browser_wrapper a.add_asset': function(e){
			$.facebox(function(){
				var path = $('#breadcrumb').attr('rel');
				$.get(e.target.href +'/'+ path,
					function(data){$.facebox(data, false, 'facebox_2')}
				);
			}, false, 'facebox_2');
			return false;
		},

	// delete a file from data or theme
		'#files_browser_wrapper div.file_asset span.cross': function(e){
			if(confirm('This cannot be undone. Delete this file?'))
			{
				var path	= $('#breadcrumb').attr('rel');
				var type	= $('#breadcrumb').attr('class');
				var file	= $(e.target).parent('div').attr('rel');
				var ufile	= ((path)) ? ':' : '';
				ufile	+= file;
				$.get('/get/'+ type +'/delete/'+ path + ufile,
					function(data){
						file = file.replace('.', '_')
						$('#directory_window #' + file).remove();
						$(document).trigger('server_response.plusjade', data);
			})};
			return false;
		},

	// delete are a real directory folder from _data
		'#files_browser_wrapper.data_files div.folder_asset span.cross': function(e){
			$parent	= $(e.target).parent('div');
			var path	= $parent.attr('rel');
			var folder	= $parent.attr('id');
			
			if('tools' == path){ alert('Tools folder is required.'); return false}
			if(confirm('This cannot be undone. Delete folder and all inner contents?')) {
				$.get('/get/files/delete/'+ path,
					function(data){
						$('#directory_window #' + folder).remove();
						$(document).trigger('server_response.plusjade', data);
			})};
			return false;
		},
		
	// remove images from gallery	
		'#remove_images' : function(){
			$("#sortable_images_wrapper img.ui-selected").each(function(){
				$(this).parent('div').remove();
			});
			return false;		
		}

	}));

	

// doubleclick file browser actions
	$('body').dblclick($.delegate({
	  // add selected image to gallery.
		'#files_browser_wrapper img.to_showroom':function(e) {
			$(e.target).addClass('selected');
			$(e.target).parent('div').addClass('selected');
			$(e.target).clone().prependTo('#images .gallery');
			return false;
		}
	}));
	

/*
	SimpleTree stuff
	as much as we can centralize, but is not everything.
*/
	$('body').click($.delegate({
	// Gather and save nest data.
		'.facebox #link_save_sort' : function(e) {	
			var output = '';
			var tool = $(e.target).attr("title");
			var parent_id = $(e.target).attr("rel");
			
			$(".facebox #simpletree_wrapper ul").each(function(){
				var parentId = $(this).parent().attr("rel");
				if(!parentId) parentId = 0;
				var $kids = $(this).children("li:not(.root, .line, .line-last)");
				
				// Data set format: "id:local_parent_id:position#"
				$kids.each(function(i){
					if(undefined == $(this).attr('rel')) return true;
					output += $(this).attr('rel') + ':' + parentId + ':' + i + "|";
				});
			});
			if(!output){alert('Nothing to save.'); return false;}
			// alert (output); return false;
			$(document).trigger('show_submit.plusjade');
			$.post('/get/edit_'+ tool +'/save_tree/' + parent_id,
				{output: output},
				function(data){
					$(document).trigger('server_response.plusjade', data);				
				}
			)	
		},
		
		'#simpletree_wrapper li.root > span' : function(e){
			$('#simpletree_wrapper span.active')
			.removeClass('active')
			.addClass('text');
			$(e.target).addClass('active');
			return false;
		}
}));


	$("#save_sort").click(function() {
		var order = $("#generic_sortable_list").sortable("serialize");
		var url = $(this).attr('rel');
		if(!order){
			alert("No items to sort");
			return false;
		}
		$(document).trigger('show_submit.plusjade');
		$.get('/get/'+ url +'/save_sort?'+order, function(data){
			$(".facebox .show_submit").hide();
		})				
	});
			
			
/*
 * resizing functions
 */
	$(window).resize(function(){
		// bottom styler dialog
		var state = $('div.styler_wrapper a.toggle').html();
		var height = 405;
		if('show' == state) height = 32; 
		$('div.styler_wrapper').css('top', $.getPageHeight()- height);
		
		// facebox textareas/content should hold full height.
		height = (300 > ($.getPageHeight()- 300)) ? 170 : $.getPageHeight()- 250;	
		$('.facebox div.wysiwyg, .facebox textarea.initiliazed, .render_css, .full_height')
		.css('min-height', height);
		$('.facebox div.wysiwyg iframe')
		.css('min-height', height-30);
	});
	
	// $(window).scroll(function(){});	


	
	
// Album Management Functions:

	// delegate editing image caption
	$('body').click($.delegate({
	// show save caption pane
		'.facebox #sortable_images_wrapper b': function(e){
			$('.save_pane').clone().addClass('helper').show().insertBefore('.common_left_panel');		
			var caption = $(e.target)
				.parent('span')
				.next('img')
				.addClass('editing')
				.attr('title');
			$('.save_pane input[name="caption"]').val(caption);
			return false;
		},
	// save the caption
		'.facebox .save_pane button':function(e){
			var caption = $('.save_pane input[name="caption"]').val(caption);
			$('#sortable_images_wrapper img.editing').attr('title', caption);
			$('.save_pane.helper').remove();
			$('#sortable_images_wrapper img').removeClass('editing');
			$(document).trigger('server_response.plusjade', 'Caption Saved');			
		},
	// close the save pane
		'.facebox .save_pane .icon.cross':function(e){
			$('.save_pane.helper').remove();
			$('#sortable_images_wrapper img').removeClass('editing');
		}
	}));
	

	
// --- misc funcitons ---
 
/*
 * checks if a value is in an array.
 */
	Array.prototype.in_array = function(p_val) {
		for(var i = 0, l = this.length; i < l; i++) {
			if(this[i] == p_val)
				return true;
		}
		return false;
	}		

});  // end of init.js

