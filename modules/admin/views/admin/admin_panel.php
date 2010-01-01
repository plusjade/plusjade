<!-- START admin elements -->    
<div id="admin_bar_wrapper" class="admin_reset">
  <ul id="admin_bar">
  
    <li class="jade" style="width:70px !important">
      <a href="/get/help" rel="facebox" class="block_mode"><span class="icon help">&#160; &#160;</span> Help</a>
    </li>
    
    <li class="dropdown">
      <div><span class="icon global">&#160; &#160; </span> Site</div>
      <ul>
        <li><a href="/get/admin/settings" rel="facebox"><span class="icon wrench">&#160; &#160; </span> Settings</a></li>
        <li><a href="/get/page/navigation" rel="facebox"><span class="icon sitemap">&#160; &#160; </span>Navigation</a></li>
        <li><a href="/get/theme/logo" rel="facebox"><span class="icon asterisk">&#160; &#160; </span>Logo</a></li>        
        <li><a href="/get/import/tool" rel="facebox"><span class="icon heart">&#160; &#160; </span>Import Tool</a></li>        
        <li><a href="/get/admin/logout"><span class="icon cross">&#160; &#160; </span> Logout</a></li>
      </ul>    
    </li>  
    
    <li class="dropdown">
      <div><span class="icon palette">&#160; &#160; </span> Theme</div>
      <ul> 
        <li><a href="/get/theme" rel="facebox"><span class="icon manage">&#160; &#160; </span>Manage</a></li>
        <li><a href="/get/theme/edit/stylesheets" rel="css_styler"><span class="icon css">&#160; &#160; </span>Stylesheets</a></li>
        <li><a href="/get/theme/edit/templates" rel="facebox"><span class="icon template">&#160; &#160; </span>Templates</a></li>
        <li><a href="/get/theme/change" rel="facebox" style="border-top:1px dashed #ccc"><span class="icon palette">&#160; &#160; </span>New Theme</a></li>
      </ul>    
    </li>
    
    <li class="direct">
      <a href="/get/page/index/add" class="block_mode" rel="facebox"><span class="icon page">&#160; &#160;</span> Pages</a>
    </li>
    
    <li class="direct">
      <a href="/get/tool?page_id=<?php echo $page_id?>" rel="css_styler" class="block_mode"><span class="icon tools">&#160; &#160; </span>Tools</a>
    </li>
    
    <li class="direct">
      <a href="/get/files" rel="facebox" class="block_mode"><span class="icon local">&#160; &#160; </span>Files</a>
    </li>
    
    <li class="this_page" style="width:135px">
      <a href="/get/page/settings/<?php echo $page_id?>" class="block_mode" rel="facebox"><span class="icon wrench">&#160; &#160; </span> Page Settings</a>
    </li>

    <li class="this_page" style="width:175px">
      <a href="/get/tool/create_to_page?page_id=<?php echo $page_id?>" class="block_mode" rel="facebox"><span class="icon plus">&#160; &#160; </span> ADD PAGE CONTENT</a>
    </li>
    
    <li class="floatright" style="width:65px !important;">
      <a href="#" class="toggle_admin_bar block_mode">Hide</a>
    </li>
  </ul>
  
  
  <?php if('true' != $this->claimed):?>
    <div id="unclaimed_panel">
      <a href="/get/site/claim" class="block_mode" rel="facebox">Password Protect My Website.</a>
      ** You website is not password protected!
    </div>
  <?php endif;?>

  <div id="center_response" style="display:none">Server Response</div>

    <style type="text/css">
    #new_tool_wrapper {display:none; min-width:925px; width:87%;  margin:auto; overflow:auto; background:#def1fe; border:2px solid #a3d7f9; text-align:left;}
    #tool_selector {height:90px; width:200px; float:left; margin:3px 10px 3px 3px; padding-left:10px; overflow:auto;}
    .tool-jade-blah {margin:0;padding:0;line-height:10px !important;}
    .tool-jade-blah li{margin:0;padding:0;}
    #tool_selection_panel{height:90px; width:600px; margin:3px; float:left; overflow:auto;}
    #tool_selection_panel div {height:80px; width:80px; float:left; margin:3px; border:1px solid #a3d7f9; background:#fff;}
    #tool_description {clear:both; padding:2px; text-align:center; background:#fff;}
    </style>
  <div id="new_tool_wrapper">
    <div id="tool_description">Manage blocks of textual and image based content using helpful templates.</div>  
    <div id="tool_selector">
      Content Tools
      <ul class="tool-jade-blah">
        <li>text</li>
        <li>album</li>
        <li>format</li>
        <li>navigation</li>
      </ul>
    </div>
    
    <div id="tool_selection_panel">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
    
  </div>
  
  
  <div style="display:none">
    <span id="global_css_path"><?php echo $global_css_path?></span>
    <span id="be-HaPpy_My-FriEnds" rel="<?php echo $page_id?>" title="<?php echo $this->theme?>" style="display:none"></span>
    <span id="click_hook" rel="<?php echo $page_id?>" style="display:none"></span>
    <?php            
    if('0' < count($tools_array))
    {
      // THIS IS HIDDEN: Exists so JS can grab html.
      // $tools_array is sent from build_page controller.
      $i=1;
      foreach($tools_array as $data_array)
      {
        $data_array['page_id']    = $page_id;
        $data_array['layer']    = ++$i;
        $data_array['protected']  =
          (in_array($data_array['name_id'], $protected_array))
          ? TRUE 
          : FALSE;
        echo View::factory('tool/toolkit_html', array('data_array'=> $data_array));
      }
    }
    ?>
  </div>
</div>
<div id="shadow"><div></div></div>

<noscript>Editing Your website requires Javascript to be enabled.</noscript>

<div id="hide_link">
  <a href="#">Show</a>
</div>
<!-- END admin elements -->  