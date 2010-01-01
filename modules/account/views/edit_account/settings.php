
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_account/settings?pid=<?php echo $account->id?>" method="POST" class="ajaxForm">  
  
  <div id="common_tool_header" class="buttons">
    <button type="submit" class="jade_positive" accesskey="enter">Save Settings</button>
    <div id="common_title">Edit Account Settings</div>
  </div>  
  
  <div class="common_left_panel fieldsets">
  
  </div>
  
  <div class="common_main_panel fieldsets">
  
  <b>Login Header</b>
  <br><input type="text" name="login_title" value="<?php echo $account->login_title?>" style="width:400px">

  <br><br>
  <b>New Account Header</b>
  <br><input type="text" name="create_title" value="<?php echo $account->create_title?>" style="width:400px">
    
  
  </div>

</form>