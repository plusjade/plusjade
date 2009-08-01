
<style type="text/css">
#sentence{
	padding:10px;
	text-align:center;
	font-size:2.35em;
	margin-bottom:10px;
	font-weight:bold;
}
#sentence .text_logo{
	
}
#sentence .text_logo b{
	color:red;
}


.pitch{
	width:710px;
	margin:0 auto;
	clear:both;
	font-size:1em;
	line-height:1.2em;
	order:1px solid red;
}
.pitch #message{
	width:67%;
	float:left; 
	padding:10px;
	order:1px solid red;
}

.pitch #features{
	width:26%;
	float:right;
	padding:10px;
	order:1px solid red;
}
.pitch #features ul{
	line-height:1.5em;
}


#domain_name{
	padding:10px;
	text-align:right;
	font-size:1.6em;
	font-weight:bold;
	background:#ffffcc;   /* #e2f3fd; */
	color:#000;
}
#domain_name b{
	float:left;
}
#domain_name span{
	color:#4fad2e;
}
#errors{
	padding:9px;
	text-align:center;
	font-weight:bold;
	background:#d52020 url('/_assets/images/admin/gradients.jpg') repeat-x 0px -465px; /*red*/
	color:#fff;
}

#create_website{
	margin:20px 0;
	overflow:auto;
	background:skyblue url('/ _assets/images/admin/create.png') repeat-x 0 0;
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
	border-radius: 15px;
	color:#fff !important;
}
.fieldsets b {
	color:#fff !important;
}
#choose_theme{
	width:450px;
	float:left;
	padding:10px;
	text-align:center;
	order:1px solid red;
}
#choose_theme img{
	width:440px;
	height:300px;
	padding:5px;
	margin-bottom:5px;
	background:#fff;
	border:2px solid #000;
}

/* auth create (clone from admin_global.css) */

#auth_form{
	width:300px;
	float:right;
	padding:10px;
	order:1px solid red;
}
#auth_form div.fieldsets{
	background:#fff;
	padding:10px;
	margin-bottom:10px;
}
#tagline{
	padding:10px;
	background:#fff;
	text-align:center;
	font-size:2em;
}
#tagline .text_logo{
	
}
#tagline .text_logo b{
	color:red;
}
#auth_form input{
	font-size:1.8em;
	background:#fff;
	border:2px solid #000;
	width:99%;
	margin-top:7px;
}
#auth_form button{
	width:100%;
}
#auth_form .buttons{
	margin:0 auto;
	width:200px;
}

#explain_more{
	float:right;
	width:300px;
	font-size:0.9em;
}

</style>

<div id="create_website">

	<div id="domain_name">
		<b>Start Now &#8594;</b> http://beta-<span id="link_example"><?php echo $values['site_name']?></span>.plusjade.com
	</div>
	
	<div id="choose_theme">
		<img src="/_assets/images/themes/nonzero.gif" alt="">
		<br><b>Choose a Theme</b>
	</div>
	
	<div id="auth_form" class="create_form">
		<?php if(!empty($errors)):?>
			<div id="errors"><?php echo $errors?></div>
		<?php endif;?>
	
		<form action="" method="POST">
			<div class="fieldsets" style="background:transparent">
				<b>Website Name</b><br>
				<input type="text" name="site_name" value="<?php echo $values['site_name']?>" class="full auto_filename" rel="text_req" size="20" maxlength="25">
			</div>

			<div class="fieldsets" style="background:transparent">
				<b>Beta Code</b><br>
				<input type="text" name="beta" value="<?php echo $values['beta']?>" class="full" rel="text_req" maxlength="50">
			</div>
			
			<div class="buttons">
				<button type="submit" class="jade_positive">Create My Website</button>
			</div>
		</form>
		
		<div style="margin-top:10px; font-size:0.9em; text-align:center">
			<b>This is your real website!</b>
			<br>Any changes you make will be saved.
			<br><br><a href="#explain_more" class="explain_more">explain this ...</a>
		</div>
	</div>
</div>



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



	<div id="explain_more" style="display:none">
		+Jade does not require registration,
		<br>but your website starts as <b>unclaimed</b>
		<br>and cannot be password protected.
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
		
		Create your account any time at http://plusjade.com
		<br><b>Or</b> within your website admin bar labeled "claim website".
	</div>	


<script type="text/javascript">

$(document).ready(function()
{

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

	$('form').submit(function(){
		if(! $("form input").jade_validate() ) return false;
		$('button').attr('disabled', 'disabled').html('Submitting...');
	});
});

</script>




