

<h2>User: <?php echo $user->username?></h2>

  
<div class="fieldsets">
  <b>Email:</b> <?php echo $user->email?> 
  <p>
  <b>Logins:</b> <?php echo $user->logins?> 
  </p>
  <b>Last Login:</b> <?php echo date("M d Y @ g:i a", $user->last_login)?> 
</div>


<div class="fieldsets">
  <h3>Account State</h3>
  
  <b>Suspend</b>  for ___ days.
    <br><input type="text" name="reason" style="display:none">
    <br>You can site some common reasons which will be emailed to this user.
  
  <p>
  <b>Ban</b> permanently and block ip.
  </p>
  
  <b>Delete user.</b>
    <a href="/get/edit_account/delete_user?item_id=<?php echo $user->id?>" class="delete_user">Delete</a>
  <p></p>
  <br> Delete is used for inactive accounts or as per user request.
  <br>delete is different from ban in that it does not block the ip. and the email is friendly.
</div>

<div class="fieldsets">
  <h3>Account activity and badges</h3>
  
</div>


<script type="text/javascript">
  $('.delete_user').click(function(){
    if(confirm('This cannot be undone. Delete this user?')) {
      var url = $(this).attr('href');
      $('.common_main_panel')
      .html('<div class="ajax_loading">Loading...</div>')
      .load(url);    
    }
    return false;
  });

</script>