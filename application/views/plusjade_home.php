

<div id="alpha_message" rel="august 1st 09 !">
	<a href="#" class="show_alpha">What is private alpha?</a>
	<strong>Welcome to +Jade Private Alpha!</strong>
	<div id="show_alpha" style="display:none">
	This means users are helping me test the core system framework.
	Please do rip apart +Jade, just remember that feedback is very crucial to development!
	You can post in the forum, blog, or email me directly.
	Also, your data may be lost! +Jade cannot guarantee anything during alpha.
	If you want to be an alpha tester, please email me! Anyone is welcome, the alpha key is only meant
	as a throttle, in case something really goes wrong. 
	<br>-  Thank you! - (this domain name) at gmail dot com
	</div>
</div>


<form action="" method="POST">
	<div id="create_website">

		<div id="domain_name">
			<b>Start Now &#8594;</b> http://beta-<span id="link_example"><?php echo $values['site_name']?></span>.plusjade.com
		</div>
		
		<div id="choose_theme">
			<table style="width:100%"><tr>
				<td><a href="#" id="prev"><img src="/_assets/images/admin/prev_sm.png" alt="prev"></a></td>
				<td><b>Choose a Theme</b></td>
				<td><a href="#" id="next"><img src="/_assets/images/admin/next_sm.png" alt="next"></a></td>
			</tr></table>
			<div class="gallery">
			<?php foreach($themes as $theme):?>
				<img src="/_assets/images/themes/<?php echo "$theme->name.$theme->image_ext"?>" alt="<?php echo $theme->name?>">
			<?php endforeach;?>
			</div>
			
			<div id="title">base</div>
			<input type="hidden" name="theme" value="base">	
		</div>
		
		<div id="auth_form" class="create_form">
			<?php if(!empty($errors)):?>
				<div id="errors"><?php echo $errors?></div>
			<?php endif;?>
		
			
			<div class="fieldsets" style="background:transparent">
				<b>Website Name</b><br>
				<input type="text" name="site_name" value="<?php echo $values['site_name']?>" class="full auto_filename" rel="text_req" size="20" maxlength="25">
			</div>

			<div class="fieldsets" style="background:transparent">
				<b>Alpha Code</b><br>
				<input type="text" name="beta" value="<?php echo $values['beta']?>" class="full" rel="text_req" maxlength="50">
			</div>
			
			<div class="buttons">
				<button type="submit" class="jade_positive">Create My Website</button>
			</div>
			
			<div style="margin-top:10px; font-size:0.9em; text-align:center">
				<b>This is your real website!</b>
				<br>Any changes you make will be saved.
				<br><br><a href="#explain_more" class="explain_more">explain this ...</a>
			</div>
			
			<div id="explain_more" style="display:none">
				+Jade does not require registration,
				<br>but your website starts as <b>unclaimed</b>
				<p>
					Unclaimed websites expire after 7 days.
				</p>
				Creating an account allows you to:
				<ul>
					<li>Officially claim your website.</li>
					<li>Change your subdomain name.</li>
					<li>Password protect your website.</li>
					<li>Manage multiple websites.</li>
					<li>Upgrade.</li>
					<li>Become a part of our fantastic community!</li>
				</ul>
				
				Create your account any time at <a href="/users/create">http://plusjade.com/users/create</a>
				<br><b>Or</b> within your website admin bar labeled "claim website".
			</div>	
	
	
		</div>
	</div>
</form>


<div class="pitch">
	<div id="message">
		+Jade is an in-browser website publishing and management system.
		<br>We provide hosted, full-featured, websites as a service.
		<p>
		No advanced coding or web knowledge necessary.
		<br>No installs, setup manuals, configuration, headaches or lost time.
		
		</p>
		+Jade is a more powerful approach to "online website builders".
		<br>We focus on productivity, clarity, and convenience for the user.

	</div>
	
	<div id="features">
		<h3>Powerful Functionality</h3>
		<ul>
			<li>Ajax enabled</li>
			<li>blogs</li>
			<li>forums</li>
			<li>user accounts</li>
			<li>event calendars</li>
			<li>product galleries</li>
			<li>image galleries</li>
		 </ul>
	</div>
	
	<div style="clear:both;"></div>
	
</div>

<div style="text-align:center">
<h1>plusjade.com runs on +Jade =)</h1>
</div>


<script type="text/javascript">

$(document).ready(function()
{

	$('#choose_theme .gallery').cycle({ 
		prev:   '#prev', 
		next:   '#next', 
		timeout: 0, 
		before: function () { 
				$('input[name="theme"]').val(this.alt);
				$('#title').html(this.alt); 
			}
	}); 
	
	$('body').keyup($.delegate({

		"input.send_input": function(e){
			var input = $(e.target).val().replace(/[^-a-z0-9_]/ig, '-');
			$(e.target).siblings('input.receive_input').val(input);
			$('span#link_example').html(input);
		},
		
		"input.auto_filename": function(e){
			var input = $(e.target).val().replace(/[^-a-z0-9_]/ig, '');
			$(e.target).val(input);
			$('span#link_example').html(input);
		}
	}));


	$('a.explain_more').click(function(){
		$('#explain_more').slideToggle('fast');
		return false;
	});

	$('a.show_alpha').click(function(){
		$('#show_alpha').slideToggle('fast');
		return false;
	});
	
	
	$('form').submit(function(){
		if(! $("form input").jade_validate() ) return false;
		$('button').attr('disabled', 'disabled').html('Submitting...');
	});
});

</script>




