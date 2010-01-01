
$(document).ready(function()
{
/* TOGGLE ADMIN BAR  */
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

/* ACTIVATE Sitewide admin bar dropdowns */
  $('#admin_bar li.dropdown ul').hide();  
  $('#admin_bar li.dropdown div').click(function(){
    $('#admin_bar li.dropdown ul').hide();
    $(this).next('ul').show();
    $('#admin_bar li.dropdown div').removeClass('dropdown_selected');
    $(this).addClass('dropdown_selected');
    return false;    
  });

/* Click away hides open toolbars */
  $('body:not(.jade_toolbar_wrapper)').click(function(){
    $('li.dropdown ul').hide();
    $('.actions_wrapper .toolkit_wrapper').hide();
    $('#admin_bar li.dropdown div').removeClass('dropdown_selected');
  });

/* ADD redbar Tool toolkit to all tool instances on the page.*/
   $('.common_tool_wrapper').each(function(i){
    var instanceId = $(this).attr('id').split('_')[1];
    var toolkit = $('#toolkit_' + instanceId).html();    
    $('<div id="toolbar_' + instanceId  + '"></div>')
    .addClass('jade_toolbar_wrapper')
    .prepend(toolkit)
    .prependTo(this);
   });
 
/* add blue tool-item toolkits to DOM ajax requests */
  jQuery.fn.add_toolkit_items = function(toolname) {
    var toolname = toolname.toLowerCase();
    $('.'+ toolname +'_item', this).each(function(i){    
      var id    = $(this).attr('rel');
      var edit  = '<span class="icon cog">&nbsp; &nbsp; </span> <a href="/get/edit_' + toolname + '/edit?item_id=' + id + '" rel="facebox">edit</a>  ';
      var del    = '<span class="icon cross">&nbsp; &nbsp; </span> <a href="/get/edit_' + toolname + '/delete?item_id=' + id + '" class="js_admin_delete" rel="'+ toolname +'_item_'+ id +'">del</a>';
      $('<div></div>')
      .addClass('jade_admin_item_edit')
      .prepend(edit)
      .append(del)
      .prependTo(this);  
    });
  };  
  
/* 
 * ADD blue tool-item toolkits
 * selector format: .tool_wrapper .tool_item
 */  
  var tools = ['showroom', 'format', 'blog', 'review'];
  $.each(tools, function(){
    $().add_toolkit_items(this);
  });
  
/*
 * injects tool output into the DOM.
 * can add tool output Into DOM or update tools already in DOM via #tool_wrapper_<id>
  action  = (string)
  tool  = (object)
*/
  jQuery.fn.jade_inject_tool = function(action, tool) {  
    var include_js = 'yes'; // Set loading status...
    
    if('add' == action) {
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
      else { // replace old tool html with new html
        $('#'+ tool.toolname +'_wrapper_'+ tool.parent_id).replaceWith(data);
      }
      // apply blue per-item toolbars
      $('#'+ tool.toolname +'_wrapper_'+ tool.parent_id).add_toolkit_items(tool.toolname);
    });
  };  


/* DELEGATE admin link functionality */
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
      var rel  = $(e.target).attr('rel');
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
      var url  = $(e.target).attr("href");
      var el  = $(e.target).attr('rel');
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
      var url  = $(e.target).attr("href");
      var el  = $(e.target).attr('rel');
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
      var url  = $(e.target).attr("href");
      var el  = $(e.target).attr('rel');
      $(document).trigger('show_submit.plusjade');
      $.get(url, function(data) {
        $('#' + el).remove();
        $('#center_response').html(data).show();
        setTimeout('$("#center_response").fadeOut(4000)', 1500);  
      });
      return false;
    },
    // ##THIS DOES NOT WORK FOR SOME  ##
    //toggle the tab interfaces for edit_tool views.
    ".common_tabs_x li a": function(e) {
      $('.common_tabs_x li a').removeClass('active');
      var pane = $(this).attr('href');
      $('.common_full_panel div.toggle').hide();
      $('.common_full_panel div'+ pane).show();
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
    // ?? deletes a sortable row_wrapper list element?
    'ul.row_wrapper .delete_item a' :function(e){
      if(confirm('This cannot be undone. Delete this item?')){
        var id = $(e.target).attr('rel');
        $.get(e.target.href, function(){
          $('#item_'+ id).remove();
        });
      }
      return false;
    },  
  /* Click actions for css styler ----- */  
    // load the styler contents (css)
    "a[rel=css_styler]": function(e) {
      $.facebox.close();
      $('div.styler_wrapper').show();
      $('div.styler_wrapper .styler_dialog')
      .html('<div class="loading">Loading...</div>')
      .load(e.target.href, function(){
        $('.show_submit').hide();
        $(document).trigger('ajaxify.form');        
      });
      return false;
    },  
    // hide the styler dialog
    "div.styler_wrapper a.hide": function(e) {
      $('div.dialog_wrapper').toggle();      
      if('hide'== $(e.target).html()) {
        $(e.target).html('show');
        $('div.styler_wrapper').css('top', $.getPageHeight()- 32);
      }
      else {
        $('div.styler_wrapper').css('top', $.getPageHeight()- 405);
        $(e.target).html('hide')
      }
      return false;
    },    
    // close the styler dialog
    "div.styler_wrapper a.close": function(e) {
      $(document).trigger('on_close.execute');
      $('div.styler_wrapper').hide();
      $('div.styler_wrapper div.styler_dialog').empty();
      return false;
    },
    // close any generic save pane
    '.save_pane .icon.cross':function(e) {
      $(e.target).parent('.contents').parent('.save_pane').remove();
    }
    
    /*
    // cross tag hides the pop up dialog
    'span.icon.cross.floatright' : function(e) {
      $(e.target).parent('div').hide();
    }
    */  
  }));

/* auto-filter form fields delegation */
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


/* Add the css styler wrapper into the DOM. */
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

  
/* ajaxify the forms */
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

  
/* show the submit ajax loading graphic. */
$(document).bind('show_submit.plusjade', function(){
  $('.facebox_response.active').remove();
  $('.admin_reset .show_submit').show();
});

/* show the resultant server data */
$(document).bind('server_response.plusjade', function(e, data){
  $('.facebox_response.active').remove();
  $('.show_submit').hide();
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
    $('.show_submit').hide();
    $('.facebox_response.active').remove();
    $('textarea.render_html').wysiwyg();
    $(document).trigger('ajaxify.form');
    
    // Expand/contract box-element to fill up full-available facebox view.
    // Mainly for text editor and asset browsers windows.
    var height = (300 > ($.getPageHeight()- 300)) ? 170 : $.getPageHeight()- 250;
    $('.facebox div.wysiwyg, .facebox textarea.initiliazed, .render_css, .full_height').css('min-height', height);
    $('.facebox div.wysiwyg iframe').css('min-height', height-30);
  });

/* Bind functions to the CLOSE facebox event. */  
  $(document).bind('close.facebox', function() {
    $('body').removeClass('disable_body').removeAttr('scroll');
    $('.facebox_response.active').remove();
    $('.facebox .show_submit').hide();
    $(document).trigger('on_close.execute');
  });

/* execute an on_close command */
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

  
/* ACTIVATE sortable containers */
  for(i=1;i<=5;i++) {
    var fake = $('<div></div>')
    .addClass('common_tool_wrapper fake')
    .css({height:'20px'});
    
    $('.container_'+i)
    .addClass("CONTAINER_WRAPPER")
    .append(fake)
    .attr('rel', i);
  }
  
  $('.CONTAINER_WRAPPER').sortable({
    items: 'span.common_tool_wrapper:not(.fake)',
    connectWith: '.CONTAINER_WRAPPER',
    handle: '.jade_toolbar_wrapper div.name_wrapper',
    forcePlaceholderSize: true,
    forceHelperSize: true,
    placeholder: 'CONTAINER_placeholder',
    cursor: 'move',
    cursorAt: 'top',
    scrollSensitivity: 40,
    tolerance: 'pointer',
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
      $(".CONTAINER_WRAPPER").each(function() {
        var container = $(this).attr("rel");
        $(this).children("span.common_tool_wrapper")
          .each(function(i) {
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
  });  


/* Centralize the main admin interfaces (Pages, Files and theme handling) */
/*--------------------------*/

/* Pages browser function delegation. */
  $('body').click($.delegate({
    // refresh the pages display.
    'a.refresh_pages': function(e) {
      $('#page_browser_wrapper .common_full_panel').html('<div class="ajax_loading">Loading...</div>');
      $('#page_browser_wrapper .common_full_panel').load('/get/page/list_all');
      return false;
    },    
    // open file dropdown lists page
    '#page_browser_wrapper img.file_options': function(e){
      $('#page_browser_wrapper ul.option_list').hide();
      $(e.target).nextAll('ul').show();
    },
    // open file dropdown lists folder
    '#page_browser_wrapper span.icon.page': function(e) {
      $('#page_browser_wrapper ul.option_list').hide();
      $(e.target).parent('div').siblings('ul').show();
    },    
    // show a new folder directory
    '#page_browser_wrapper .open_folder': function(e) {
      var path = $(e.target).attr('rel');
      var klass = path.replace(/\//g,'_');
      var folder_string = '';  
      
      $('div.sub_folders').hide();
      $('#directory_window').attr('rel', klass);    
      $('div.' + klass).show();
      
      // add the breadcrumb
      if('ROOT' == path) {path = '';}
      else {
        var folder_array = path.split('/');
        var el_count = folder_array.length;
        
        for (i=0; i < el_count; i++) {
          var result_string = $.strstr(path, folder_array[i], true) + folder_array[i];
          folder_string += ' / <a href="/'+ result_string +'" rel="'+ result_string +'" class="open_folder">'+ folder_array[i] +'</a>';
        }
      }
      $('#breadcrumb').attr('rel',path).html(folder_string);
      return false;
    },
    /*
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
    */
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
      var html = '<img src="/_assets/images/admin/folder.png" rel="'+ folder_path +'" class="open_folder"> <span class="icon page">&#160; &#160;</span> ';
      $('#page_wrapper_'+ id +' img').replaceWith(html);
      
      var container = '<div class="'+ klass +' sub_folders"></div>';
      $('#directory_window').prepend(container);
      $(e.target).parent('li').remove();
      return false;
    },
    // add a page builder link.
    '#add_page_builder' : function() {
      var system_tool = $('#page_builder_select option:selected').val();
      $.facebox(function(){
          $.get('/get/page/add_builder', {'system_tool': system_tool}, function(data){
            $.facebox(data, false, "facebox_2");
          });
      }, false, "facebox_2");
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
          folder_string += ' / <a href="/get/'+ type +'/contents?dir='+ result_string +'" rel="'+ result_string +'" class="get_folder">'+ folder_array[i] +'</a>';
        }
      }
      $('#breadcrumb').attr('rel', path).html(folder_string);      
      return false;
    },
    // add files or folders to a real directory folder
    '#files_browser_wrapper a.add_asset': function(e){
      $.facebox(function(){
        var path = $('#breadcrumb').attr('rel');
        $.get(e.target.href +'?dir='+ path,
          function(data){$.facebox(data, false, 'facebox_2')}
        );
      }, false, 'facebox_2');
      return false;
    },

    // move assets within the assets directory.   
    '.move_selected': function(e) {  
      var path = $('#breadcrumb').attr('rel');
      
      if('drop!' == $(e.target).html()) {
        $(e.target).html('move');    
        if('undefined' == mover) { alert('data not set'); return false;}
        if(path == mover.path) { alert('original and new destination is the same'); return false;}
        $.post('/get/files/move?dir='+ path, {path:mover.path, json:mover.json},
          function(data){
            $('#directory_window').html('<div>Loading...</div>');
            $('#directory_window').load('/get/files/contents?dir=' + path);
            $(document).trigger('server_response.plusjade', data);
        });
        delete mover;
      }
      else {
        // JSONize asset selections
        var data = new Array();    
        $("#files_browser_wrapper img.ui-selected").each(function() {
          var asset = new Object();
          asset.name = $(this).attr('title');
          data.push(asset);
        });
        if(0 == data.length) {alert('nothing selected');return false};
        
        mover = new Object();
        mover.path = path;
        mover.json = $.toJSON(data);
        $(e.target).html('drop!');
      }
      return false;
    },

    // delete assets from the assets directory.
    '.delete_selected': function(){
      if(confirm('This cannot be undone. Delete this file?')) {    
        // JSONize asset selections
        var data = new Array();    
        $("#files_browser_wrapper img.ui-selected").each(function() {
          var asset = new Object();
          asset.name = $(this).attr('title');
          data.push(asset);        
          $(this).parent('div').remove();
        });
        if(0 == data.length) {alert('nothing selected'); return false};
        var json = $.toJSON(data); // alert(dataString);      
        var path = $('#breadcrumb').attr('rel');    
        $.post('/get/files/delete?dir='+ path, {json:json},
          function(data){
            $(document).trigger('server_response.plusjade', data);
        });
        return false;
      }
    },
    
    // show the thumbnail panel
    '.thumb_selected' : function() {
      // JSONize asset selections
      var data = new Array();    
      $("#files_browser_wrapper img.ui-selected").each(function() {
        var asset = new Object();
        asset.name = $(this).attr('title');
        data.push(asset);
      });
      if(0 == data.length) {alert('nothing selected');return false};

      $('.save_pane.helper').remove();
      $('.save_pane').clone().addClass('helper').show().prependTo('.common_full_panel');
      json = $.toJSON(data);
      return false;
    },
    // execute the thumbnail generator
    'button.do_thumb': function() {
      if('undefined' == json) { alert('data not set'); return false;}
      var path = $('#breadcrumb').attr('rel');
      var sizes = new Array();  
      $('div.save_pane.helper input:checked').each(function(i) {
        sizes.push($(this).val());
      });
      if(0 == sizes.length) { alert('no thumbnails selected'); return false;}

      $.post('/get/files/thumbs?dir='+ path, {'sizes[]':sizes, 'json':json},
        function(data) {
          $(document).trigger('server_response.plusjade', data);    
          $('div.save_pane.helper .contents').html(data);
          setTimeout('$("div.save_pane.helper").remove()', 1000);
      });
    }

  }));

/* 
 * Tools browser function delegation
 */
  $('body').click($.delegate({
    // save the tool meta-name
    '#tools_browser_wrapper table td span.icon.save': function(e){
      var name = $(e.target).siblings('input').val();
      var tool_id = $(e.target).attr('rel');
      $(document).trigger('show_submit.plusjade');
      $.get('/get/tool/update/' + tool_id, {name :name}, function(data){
        $(document).trigger('server_response.plusjade', data);
        console.log('good'); // solve this.
      });
    },
    // delete the tool.
    '.jade_delete_tool': function(e) {
      if(confirm('This cannot be undone. Delete this tool?')) {
        var id = $(e.target).attr('rel');
        $.get(e.target.href, function(data){
          $('#icon_'+ id).remove();
          $(document).trigger('server_response.plusjade', data);
        });
      }
      return false;
    },    
    // show tool quick view.
    '#tools_browser_wrapper a.show_view': function(e) {  
      $('.save_pane')
        .clone()
        .prependTo('#tools_browser_wrapper')
        .addClass('helper')
        .show();
      $('.save_pane.helper .output_tool_html')
        .html('<div class="plusjade_ajax">Loading...</div>')
        .load(e.target.href);
      return false;
    },    
    // add tool to the current page:
    'a.to_page': function(e) {
      var args = $(e.target).attr('rel').split(':');
      var tool = {
        "toolname" : args[0],
        "parent_id" : args[1],
        "tool_id" : args[2],
      };    
      $.get(e.target.href, function(data) {
        // expecting an insert_id from pages_tools table
        if(isNaN(data)){alert(data); return false};
        tool.instance = data;
        $().jade_inject_tool('add', tool);
      });
      return false;
    }
  }));

/* 
 * Theme function delegation
 */
  $('body').click($.delegate({
  /* manage themes index */
    // load a theme button
    "button#load_theme" : function() {
      var theme = $("select[name='theme'] option:selected").text();    
      $('#directory_window').html('Loading file...');
      $.get('/get/theme/contents/'+ theme,
        function(data){
          $('#directory_window').html(data);
          var link = ' / <a href="/get/theme/contents/'+ theme +'" class="get_folder">'+ theme +'</a>';
          $('#breadcrumb').html(link);
          $('#upload_files').attr('href','/get/theme/add_files/'+theme);
        }
      );
      return false;
    },
    // activate a theme button
    "button#activate_theme" : function() {
      var current_theme = $('#be-HaPpy_My-FriEnds').attr('title');
      var theme = $("select[name='theme'] option:selected").text();    
      if(current_theme == theme) {alert('Theme already active.'); return false};
      if(confirm('Activate this theme: ' + theme + '?')) {  
        $('.facebox .show_submit').show();
        $.post('/get/theme/change', {theme: theme}, function(data) {
          if('TRUE' == data) location.reload();
          else { alert(data); $.facebox.close();}
        });
      }
      return false;
    },  
    // delete a theme button
    "button#delete_theme" : function() {
      var current_theme = $('#be-HaPpy_My-FriEnds').attr('title');
      var theme = $("select[name='theme'] option:selected").text();
      if(current_theme == theme) {alert('Cannot delete active theme.'); return false};  
      if(confirm('This cannot be undone. Delete this entire theme folder?')) {
        $.get('/get/theme/delete/'+ theme,
          function(data) {
            $("select[name='theme'] option:selected").remove();  
            $('#directory_window').empty();
            $('#breadcrumb').empty();
            $('#upload_files').attr('href','/get/theme/add_files/' + current_theme);
          }
        );
      }
      return false;
    },
    // add a theme button
    "button#add_theme" : function() {
      var theme = $("input[name='add_theme']").val();
      if(!theme || 'safe_mode' == theme) {
        alert('specify a theme name other than "safe_mode"');
        return false;
      }
      $.post('/get/theme/add_theme', {theme : theme},
        function(data){
          $("select[name='theme']").append('<option>'+ data +'</option>');
          $('#show_response_beta').html(data);
        }
      );
      return false;
    },
  /* common crud for templates/css interface */
    // Load file from select dropdown into textarea
    "button.load_theme_file" : function(e) {
      var type = $(e.target).attr('rel');
      var $scope = $('#theme_'+ type +'_wrapper');
      var file = $("select[name='files'] option:selected", $scope).text();      
      $('textarea', $scope).val('Loading file...');
      $.get('/get/theme/load/'+ type +'/'+ file +'?v=39840',
        function(data) {
          $('textarea', $scope).val(data);
          // DOESNT WORK! set file as selected
          $("div.save_pane select[name='update_file'] option", $scope).removeAttr('selected');
          $('.current_file', $scope).html(file);
          $("div.save_pane select[name='update_file'] option[value='"+ file +"']", $scope).attr({selected:'selected'});
        }
      );
      return false;
    },
    // delete a theme file 
    "button.delete_theme_file" : function(e) {
      var type = $(e.target).attr('rel');
      var $scope = $('#theme_'+ type +'_wrapper');
      var current_theme = $('#be-HaPpy_My-FriEnds').attr('title');
      var file = $("select[name='files'] option:selected", $scope).text();
      if(confirm('This cannot be undone. Delete file: '+ file))
        $.get('/get/theme/delete/' + current_theme + ':' + type + ':'+ file,
          function(data) {
            $("select[name='files'] option:selected", $scope).remove();
            $('#show_response_beta').html(data);
          }
        );
    },    
    // show theme save_pane
    'button.show_theme_save' : function(e) {
      var type = $(e.target).attr('rel');
      var $scope = $('#theme_'+ type +'_wrapper');  
      $('.save_pane.helper', $scope).remove();
      $('.save_pane', $scope).clone().addClass('helper').show().prependTo('#theme_'+ type +'_wrapper .common_full_panel');
      return false;
    },    
    // update a theme file
    'button.update_theme_file': function(e) {
      var type = $(e.target).attr('rel');
      var $scope = $('#theme_'+ type +'_wrapper');
      var file = $("div.save_pane.helper select[name='update_file'] option:selected", $scope).text();
      var contents = $('textarea', $scope).val();
      
      $('div.save_pane.helper .contents', $scope).html('Saving '+ file + '...');
      $.post('/get/theme/save/'+ type +'/'+ file, {contents: contents }, function(data){
        $('div.save_pane.helper .contents', $scope).html(data + ' saved!!');
        setTimeout('$("#theme_'+ type +'_wrapper div.save_pane.helper").remove()', 1000);
      });
      return false;
    },
    // save as new file
    'button.new_theme_file': function(e) {
      var type = $(e.target).attr('rel');
      var lang = ('templates'== type) ? 'html' : 'css';
      var $scope = $('#theme_'+ type +'_wrapper');
      if(! $("div.save_pane.helper input[name='new_file']").jade_validate()) return false;    
      var file = $("div.save_pane.helper input[name='new_file']").val() + '.' + lang;  
      var contents = $('textarea', $scope).val();
      
      $('div.save_pane.helper .contents', $scope).html('Creating ...' + file);
      $.post('/get/theme/save/'+ type +'/'+ file, {contents: contents }, function(data){
        $('select.files_list', $scope).append('<option value="'+ data +'">'+ data +'</option>');
        $('div.save_pane.helper .contents', $scope).html(data + ' saved!!');
        setTimeout('$("#theme_'+ type +'_wrapper div.save_pane.helper").remove()', 1000);
      });
      return false;
    }
  }));

/* css keydown functionality */  
  $('body').keydown($.delegate({  
    // TAB update the DOM with current css
    '#theme_css_wrapper textarea#edit_css' : function(e) {
      if (e.keyCode == 9) {    
        $('head link#global-sheet').remove();
        $('#global-style').remove();
        $('<style id="global-style" type="text/css"></style>')
        .html($('#theme_css_wrapper textarea#edit_css').val())
        .appendTo('head');    
        return false;
      }  
    }
  }));

  
/* SimpleTree stuff - as much as we can centralize, but is not everything. */
  $('body').click($.delegate({
    // Gather and save nest data.
    '.facebox #link_save_sort' : function(e) {  
      var tool = $(e.target).attr("title");
      var parent_id = $(e.target).attr("rel");
      var data = new Array();
      
      $(".facebox #simpletree_wrapper ul").each(function() {
        var parentId = $(this).parent().attr("rel");
        if(!parentId) parentId = 0;
        var $kids = $(this).children("li:not(.root, .line, .line-last)");

        $kids.each(function(i) {
          if(undefined == $(this).attr('rel')) return true; 
          var node = new Object();
          node.id = $(this).attr('rel');
          node.local_parent_id = parentId;
          node.position = i;
          data.push(node);
        });
      });
      var json = $.toJSON(data); // alert(dataString);
      if(!json){alert('Nothing to save.'); return false;}
      //console.log(json); return false;
      $(document).trigger('show_submit.plusjade');
      $.post('/get/edit_'+ tool +'/save_tree/' + parent_id,
        {json: json},
        function(data){
          $(document).trigger('server_response.plusjade', data);        
        }
      )  
    },
    // something  
    '#simpletree_wrapper li.root > span' : function(e){
      $('#simpletree_wrapper span.active')
      .removeClass('active')
      .addClass('text');
      $(e.target).addClass('active');
      return false;
    }
  }));

  // save the simpleTree
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
      

      
      
      
// Album Management Functions:
  $('body').click($.delegate({
  // show save caption pane
    '.facebox #sortable_images_wrapper span': function(e){
      $('.save_pane').clone().addClass('helper').show().insertBefore('.common_full_panel');    
      var caption = $(e.target)
        .parent('span')
        .next('img')
        .addClass('editing')
        .attr('title');
      $('.save_pane input[name="caption"]').val(caption);
      return false;
    },
  // save the caption
    '.facebox #sortable_images_wrapper .save_pane button':function(e){
      var caption = $('.save_pane input[name="caption"]').val(caption);
      $('#sortable_images_wrapper img.editing').attr('title', caption);
      $('.save_pane.helper').remove();
      $('#sortable_images_wrapper img').removeClass('editing');
      $(document).trigger('server_response.plusjade', 'Caption Saved');      
    },
    /*
  // close the save pane
    '.facebox .save_pane .icon.cross':function(e){
      $('.save_pane.helper').remove();
      $('#sortable_images_wrapper img').removeClass('editing');
      console.log('album/image save pane');
    },  
    */
  // remove images from gallery  
    '#remove_images' : function() {
      $("#sortable_images_wrapper img.ui-selected").each(function(){
        $(this).parent('div').remove();
      });
      return false;    
    },
    
    // editor place function handler
    '.place_selected': function() {
      $("#files_browser_wrapper img.ui-selected").each(function() {
        $('<div></div>')
        .addClass('album_images')
        .prepend('<span class="icon move">&#160; &#160;</span><span class="icon cog">&#160; &#160;</span>')
        .append($(this).clone().removeClass('ui-selected'))
        .appendTo($('#sortable_images_wrapper'));
      });
      return false;    
    }
  }));


/* resizing functions */
  $(window).resize(function() {
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

  
// --- misc funcitons ---
/* checks if a value is in an array. */
  Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++) {
      if(this[i] == p_val)
        return true;
    }
    return false;
  }    

});  // end of init.js

