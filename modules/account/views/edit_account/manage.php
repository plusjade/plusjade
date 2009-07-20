

<div id="common_tool_header">
	<div id="common_title">Manage Users</div>
</div>	

<div class="common_left_panel">	
	<ul class="acount_users_list">
	<?php
		foreach($users as $user)
		{
			?>
			<li><a href="/get/edit_account/user/<?php echo $user->id?>"><?php echo $user->username?></a></li>
			<?php
		}
	?>
	</ul>
</div>

<div class="common_main_panel">	
<p><a href="" id="question" name="question">Question</a></p>
<div id="answer"  name="answer">Answer</div>
</p>

</div>

<script type="text/javascript">
$('.acount_users_list li a').click(function(){
	var url = $(this).attr('href');
	$('.common_main_panel')
	.html('<div class="ajax_loading">Loading...</div>')
	.load(url);
	return false;
});


        $('div.showhide,#answer').hide();
        $('#question').click(function(){
        $('div.showhide,#answer').toggle();
		  return false;
       });

  
</script>


</script>