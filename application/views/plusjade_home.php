

<div id="create_left">	
	<form action="" method="POST">
		<input type="hidden" name="theme" value="base">	
		<div id="create_website" class="fieldsets">
		
			<div id="auth_form" class="create_form">
				<?php if(!empty($errors)):?>
					<div id="errors"><?php echo $errors?></div>
				<?php endif;?>
			

				<h3>1. Choose Your Design:</h3>		
				<div class="numbers">
					<table id="theme_selector"><tr>
						<td width="45px"><a href="#" id="prev"><img src="/_assets/images/admin/prev_sm.png" alt="prev"></a></td>
						<td><div id="title">base</div></td>
						<td width="45px"><a href="#" id="next"><img src="/_assets/images/admin/next_sm.png" alt="next"></a></td>
					</tr></table>
				</div>
				
				<h3>2. Name Your Website:</h3>
				<div class="numbers">
					<input type="text" name="site_name" value="<?php echo $values['site_name']?>" class="full auto_filename" rel="text_req" size="20" maxlength="25">
				</div>

				<h3>3. Provide Your Email Address:</h3>
				<div class="numbers">
					<input type="text" name="email" value="<?php #echo $values['email']?>" class="full" rel="text_req" size="20" maxlength="25">
				</div>
		
				<div class="numbers" style="text-align:center; background:#fff">				
					<button type="submit" class="jade_positive">Launch My Website</button>
				</div>
					
				<input type="text" name="beta" value="<?php echo $values['beta']?>" class="full" rel="text_req" maxlength="50">
			</div>			
		</div>
	</form>
</div>

<div id="create_right">
	<div id="domain_name">http://<span id="link_example"><?php echo $values['site_name']?></span>.plusjade.com</div>
	<div class="gallery">
	<?php foreach($themes as $theme):?>
		<img src="/_assets/images/themes/<?php echo "$theme->name.$theme->image_ext"?>" alt="<?php echo $theme->name?>">
	<?php endforeach;?>
	</div>
</div>

<div style="clear:both; text-align:center;padding-top:25px;">
	<h2 style="background:#ffffcc; padding:5px;">Run Your Business Website For Free With 100% No Risk.</h2> 
	Try your website out for as long as you'd like and only pay when you are ready to fully brand your website.
</div>

<h1 style="padding:0 0 20px 65px; margin:40px 0 25px 0; border-bottom:1px dashed #222;">
	<div style="float:right; margin-right:130px;">What You Get &#8595;</div>
	Fully Branded &#8595;
</h1>


<div></div>

<div id="pricing_box">
	<h1>$400.00 per year</h1>
	<div>
		Payment is required upfront but you can cancel at any time.
		<br/>We accept credit cards, checks, cash, blah blah.
	</div>
	
	<h1>Happiness Guarantee</h1>
	<div>
		If you are not happy with your website you are entitled to
		a 100% full refund of the total purchase price within 60 days
		of the purchase date.
	</div>
	
	<h1>Risk Free</h1>
	<div>
		Try us out for as long as you want. There are no long term contracts.
		Along with our happiness guarantee you are free to cancel anytime.
		Remaining full-length months will be credited back.
	</div>
</div>

<div style="width:450px; float:right;">
	<ul class="check_list">
		<li>Fully Brand Your Website.
			<br/>Change your website domain name to your own. Remove "powered by" plusjade marker.
		</li>

		<li>100% Fully Hosted.<br/>No separate bills, separate accounts, nor separate headaches.</li>
		<li>Unlimited Traffic To and From Your Site. (bandwidth)</li>
		<li>5 gigabytes Data Storage (Automatically backed up.)</li>


		<li>Unlimited Pages and Tools.</li>
		
		<li>
			Automatic Backup Scheduling of Your Entire Website. Yes <em>all</em> Data!
			<br/>Includes customer emails, reviews, images and product details, all uploaded files,
			<br/>and a complete cached version of your entire website. The data is YOURS!
		</li>

		<li>
			Consistent Development, Monitoring, and Feature Additions.
			<br/>We manage, optimize and add features to all the backend code and servers
			that power <em>your</em> website.
		</li>
		
		<li>Domain Name Registration (http://yourcompany.com)</li>
    <li>Your own email addresses (support@yourcompany.com) via Google Apps integration.</li>
    <li>Powerful Website Analytics via Google Analytics integration.</li>

		<li>
			Email and Call Back Support. 
			<br/>Something wrong with your website? Or just have a question about something internety?
			<br/>Email us and we'll be glad to help!
		</li>
	
		<li>
			Your Website is 100% Editable and Updatable Right From Your Browser.
		</li>
		
		<li>
			Truly Centralized Website Management.
			<br/>No hassles, no headaches, just one account, and proper technical management.
		</li>	
		
	</ul>

</div>



<!--
Get your professionally designed, fully brandable, fully-functional 
website <em>instantly</em>.
-->

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
	
	
	$('#create_right .gallery').cycle({ 
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




