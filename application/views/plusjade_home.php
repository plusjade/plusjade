
<!--
<div id="shadow-container">
		<div class="shadow1">
			<div class="shadow2">
				<div class="shadow3">
					<div class="container">
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
					</div>
				</div>
			</div>
		</div>
	</div>
 -->

 
 
<div id="left_home">

	<div id="start_now">Start Now &#8594;</div>

	<div class="pitch">
		<div id="message">
			+Jade is a website publishing and management system,
			that runs completely in your web browser.
			<br>We provide fully hosted, full-featured, 100% brandable websites as a service.
			<p>
				<h3>Convenient</h3>
				<ul>
					<li>No Coding</li>
					<li>No Configuration</li>
					<li>No Installation</li>
					<li>No Setup Manuals</li>
					<li>No Headaches</li>
					<li>No Wasted Time</li>
				</ul>
			</p>
			
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
		
			+Jade is specifically for professionals and project leads
			who need a professional, fully branded, web presence,
			with powerful functionality to help you get actual results from
			your website.
			<br><br>
			
			<h3>+Jade is in private Beta</h3>
			Feedback allows us to build our service to specifcally meet <em>your</em> needs.
			<br>
			<br><b>Easy ways to leave feedback:</b>
			
			<ul>
				<li>Post in the forum</li>
				<li>Comment on the blog</li>
				<li>email me: plusjade at gmail dot com</li>
				<li>Post in: <a href="https://plusjade.fogbugz.com">PlusJade Fogbugz</a>.</li>
			</ul>

			<p>
				<b>Disclaimer:</b>
				<br>Plusjade offers no warranties, guarantees, or promises of any kind.
				Please use at your own risk.
			</p>
			The beta code is <b>DOTHEDEW</b>.
			
		
		</div>

	</div>

</div>

<div id="right_home">

	<div id="domain_name">
		http://beta-<span id="link_example"><?php echo $values['site_name']?></span>.plusjade.com
	</div>
			
	<form action="" method="POST">
		<div id="create_website" class="fieldsets">
		
			<div id="auth_form" class="create_form">
				<?php if(!empty($errors)):?>
					<div id="errors"><?php echo $errors?></div>
				<?php endif;?>
			
				
				<div style="margin:5px 10px">
					<b>Website Name</b><br>
					<input type="text" name="site_name" value="<?php echo $values['site_name']?>" class="full auto_filename" rel="text_req" size="20" maxlength="25">
				</div>
				
				<div style="margin:5px 10px">
					<b>Beta Code</b><br>
					<input type="text" name="beta" value="<?php echo $values['beta']?>" class="full" rel="text_req" maxlength="50">
				</div>


				<div id="choose_theme">
					<b>Choose a Theme</b>
					<div class="gallery">
					<?php foreach($themes as $theme):?>
						<img src="/_assets/images/themes/<?php echo "$theme->name.$theme->image_ext"?>" alt="<?php echo $theme->name?>">
					<?php endforeach;?>
					</div>
					
					<input type="hidden" name="theme" value="base">	
					
					<table style="width:100%"><tr>
						<td width="45px"><a href="#" id="prev"><img src="/_assets/images/admin/prev_sm.png" alt="prev"></a></td>
						<td><div id="title">base</div></td>
						<td width="45px"><a href="#" id="next"><img src="/_assets/images/admin/next_sm.png" alt="next"></a></td>
					</tr></table>
				</div>

			
				<div class="buttons">
					<button type="submit" class="jade_positive">Create My Website</button>
				</div>
				
				<div style="margin-top:10px; font-size:0.9em; text-align:center">
					<b>This is your real website!</b>
					<br>Any changes you make will be saved.
					<br><br><a href="#explain_more" class="explain_more">explain this ...</a>
				</div>	
			</div>			
		</div>
	</form>
	
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
		
		Create your account any time at
		<br><a href="/users/create">http://plusjade.com/users/create</a>
		<br><b>Or</b> within your website admin bar labeled "claim website".
	</div>	
			
</div>
<div style="clear:both;margin-top:10px"></div>


<div id="yelp">

</div>


<script type="text/javascript">

$(document).ready(function()
{

	/*
	function loadYelp() 
	{ 
		$('#yelp').html('<span>yahboi</span>'); 
		$.ajax({ 
			type:'GET', 
			url:"http://api.yelp.com/phone_search?phone=9497279900&ywsid=BVx1xyAjyEGPDL6XQIVcKQ", 
			success:function(feed) { 
				alert(feed);
			}, 
			dataType:'jsonp' 
		}); 
	}
	loadYelp();
	*/
	
	
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
	
	
	$('form').submit(function(){
		if(! $("form input").jade_validate() ) return false;
		$('button').attr('disabled', 'disabled').html('Submitting...');
	});
});
</script>




