
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/settings?pid=<?php echo $format->id?>" method="POST" class="ajaxForm">  
  
  <div id="common_tool_header" class="buttons">
    <button type="submit" name="save_settings" class="jade_positive" accesskey="enter">Save Settings</button>
    <div id="common_title">Edit Format Settings</div>
  </div>  
  
  <div class="common_left_panel">
  
  </div>
  <div class="common_main_panel fieldsets">
    <b>Format Name</b>
    <br/><input type="text" name="name" value="<?php echo $format->name?>" style="width:300px">
  
    <br/><br/>
    <b>Format Type</b>
      <input type="text" value="<?php echo $format->type?>" READONLY>
      
    <br/><br/>

    <b>Format Params</b>
    <br/><input type="text" name="params" value="<?php echo $format->params?>" style="width:300px">

    <br/><br/>

    <b>Format <?php echo $format->type?> View</b>
    <br/><select name="view">
      <?php
        foreach($type_views as $view)
          if($view == $format->view)
            echo "<option selected=\"selected\">$view</option>";
          else
            echo "<option>$view</option>";
      ?>
    </select>
  </div>

</form>