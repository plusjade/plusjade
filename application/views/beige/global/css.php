/* CSS Document */
/* CSS Document */
body{
	background:url(<?php echo $root_images;?>/bg.gif) repeat-x 0 0 #FDF9EE; color:#4E4628;
	font:normal 14px/19px Arial, Helvetica, sans-serif;
	margin:0; padding:0;
}
div, h1, h2, h3, h4, h5, h6, form, label, input, span, ul, li, p, a{
	margin:0; padding:0;
}
ul{
	list-style:none;
}

/* --- header_wrapper --- */

#header_wrapper{
	width:728px; 
	position:relative;
	margin:0 auto; 
	padding:8px 0 0 50px;
	overflow:auto;
}
#header{
	float:left;
}
#header_wrapper ul{
	background:url(<?php echo $root_images;?>/top_ul_bg.gif) no-repeat 0 8px;
	height:23px; 
	padding:8px 0 0 8px; 
	margin-left:217px;
}
#header_wrapper ul li{
	float:left;
	text-align:center;
	background-color:#E1DBC7; 
	color:#0B0B0B; 
	font:bold 11px/23px "Trebuchet MS", Arial, Helvetica, sans-serif; 
	text-transform:uppercase;
	padding:0 5px;
}
#header_wrapper ul li a{
	background-color:#E1DBC7; 
	color:#0B0B0B;
	font:bold 11px/23px "Trebuchet MS", Arial, Helvetica, sans-serif;
	text-transform:uppercase; 
	text-align:center; 
	text-decoration:none;
	height:23px; 
	display:block;
}
	
#header_wrapper ul li a.selected{
	background:url(<?php echo $root_images;?>/top_btn_h.gif) no-repeat 0 0 #E1DBC7; 
	color:#FFFFFF;
	font:bold 11px/23px "Trebuchet MS", Arial, Helvetica, sans-serif;
	text-transform:uppercase; 
	text-align:center; 
	text-decoration:none;
	width:65px; 
	height:23px; 
	display:block;
}
#header_wrapper ul li a:hover{
	background:url(<?php echo $root_images;?>/top_btn_h.gif) no-repeat 0 0 #E1DBC7;
	color:#FFFFFF;
	font:bold 11px/23px "Trebuchet MS", Arial, Helvetica, sans-serif;
	text-transform:uppercase; 
	text-align:center; 
	text-decoration:none;
	height:23px; 
	display:block;
}




/* --- body_wrapper --- */
	
#body_wrapper{
	width:778px; 
	margin:0 auto; 
}
#topShadow{
	background:url(<?php echo $root_images;?>/top_shadow.gif) no-repeat 0 0 #FDF9EE; color:#4E4628;
	width:778px; 
	height:34px; 
	margin:9px 0 0;
}
#bottomShadow{
	background:url(<?php echo $root_images;?>/bottom_shadow.gif) no-repeat 0 0 #FDF9EE; color:#4E4628;
	width:778px; 
	height:24px; 
}

/* --- content_wrapper --- */

#content_wrapper{
	background:url(<?php echo $root_images;?>/midle_bg.gif) repeat-y 0 0 #FDF9EE; color:#4E4628;
	padding:0 25px; 
	min-height:500px;
	overflow:hidden;
}
#content_wrapper form.search{
	background-color:#FFFFFF; color:#000000; border:#ECE8DB 1px solid;
	width:248px; padding:7px 17px 27px 17px; float:left;}
#content_wrapper form.search h2{
	background:url(<?php echo $root_images;?>/search_h2_bg.gif) no-repeat 0 0 #FFFFFF; color:#786E4E;
	width:197px; padding:0 0 10px 50px; float:left;
	font:normal 24px/42px Georgia, "Times New Roman", Times, serif;}
#content_wrapper form.search h2 span{
	background-color:#FFFFFF; color:#0B0B0B;
	font:normal 24px/42px Georgia, "Times New Roman", Times, serif;}
#content_wrapper form.search label{
	background-color:#FFFFFF; color:#0B0B0B; margin:0 0 8px 0; float:left;
	font:bold 10px/28px Arial, Helvetica, sans-serif; text-transform:uppercase;}
#content_wrapper form.search input{
	background-color:#EFEBDE; color:#0B0B0B; border:#C3BCA4 1px solid;
	width:158px; height:22px; padding:2px; margin:0 0 8px 0; float:right;
	font:normal 14px/20px Arial, Helvetica, sans-serif;}
#content_wrapper form.search p{
	background-color:#FFFFFF; color:#CC0000; float:left; margin:6px 0 0 0;
	font:normal 13px/15px Arial, Helvetica, sans-serif;}
#content_wrapper form.search input.check{
	background-color:#EFEBDE; color:#0B0B0B; border:#C3BCA4 1px solid;
	width:15px; height:15px; float:left; margin:6px 0 0 9px;}
#content_wrapper form.search input.submit{
	background:url(<?php echo $root_images;?>/submit_bg.gif) no-repeat 37px 0 #FFFFFF; color:#0B0B0B; border:none;
	width:53px; height:13px; float:right; margin:7px 0 0 0; padding:0 23px 0 0; cursor:pointer;
	font:normal 10px/13px Arial, Helvetica, sans-serif; text-transform:uppercase;}
#eventLink{
	width:345px; padding:0 0 0 70px; float:left;}
#eventLink h2{
	background:url(<?php echo $root_images;?>/event_link_bg.gif) no-repeat 0 7px #FFFFFF; color:#786E4E;
	padding:6px 0 10px 48px;
	font:normal 28px/42px Georgia, "Times New Roman", Times, serif;}
#eventLink h2 span{
	background-color:#FFFFFF; color:#0B0B0B;
	font:normal 28px/42px Georgia, "Times New Roman", Times, serif;}
#eventLink ul{
	float:left; padding:0 0 0 5px;}
#eventLink ul li{
	font:normal 13px/19px Arial, Helvetica, sans-serif; 
	background:url(<?php echo $root_images;?>/red_arrow.gif) no-repeat 0 7px #FFFFFF; color:#4E4628;
	padding:0 0 0 6px;}
#eventLink ul li a{
	font:normal 13px/19px Arial, Helvetica, sans-serif; text-decoration:none; 
	background-color:#FFFFFF; color:#4E4628;
	padding:0 4px; display:block;}
#eventLink ul li a:hover{
	font:normal 13px/19px Arial, Helvetica, sans-serif; text-decoration:none; 
	background-color:#F4EFDF; color:#4E4628;
	padding:0 4px; display:block;}
#eventLink a.more{
	background:url(<?php echo $root_images;?>/more_bg.gif) no-repeat 66px 0 #FFFFFF; color:#0B0B0B; float:right;
	font:bold 10px/13px Arial, Helvetica, sans-serif; text-decoration:none; text-transform:uppercase;
	padding:0 20px 0 0; margin:5px 10px 0 0;}
#eventLink a.more:hover{
	background:url(<?php echo $root_images;?>/more_bg_h.gif) no-repeat 66px 0 #FFFFFF; color:#0B0B0B; float:right;
	font:bold 10px/13px Arial, Helvetica, sans-serif; text-decoration:none; text-transform:uppercase;
	padding:0 20px 0 0; margin:5px 10px 0 0;}
#midle{
	background:url(<?php echo $root_images;?>/picture.gif) no-repeat 0 0 #FFFFFF; color:#4E4628;
	padding:270px 0 0 0;}
#midle h2{
	background-color:#FFFFFF; color:#0B0B0B;
	font:normal 28px/46px Georgia, "Times New Roman", Times, serif;}
#midle h2 span{
	background-color:#FFFFFF; color:#A60101;
	font:normal 28px/46px Georgia, "Times New Roman", Times, serif;}
#midle p{
	font:normal 14px/19px Arial, Helvetica, sans-serif; background-color:#FFFFFF; color:#4E4628;}
#midle a.more{
	background:url(<?php echo $root_images;?>/more_bg.gif) no-repeat 66px 0 #FFFFFF; color:#0B0B0B; float:right;
	font:bold 10px/13px Arial, Helvetica, sans-serif; text-decoration:none; text-transform:uppercase;
	padding:0 20px 0 0; margin:5px 35px 0 0;}
#midle a.more:hover{
	background:url(<?php echo $root_images;?>/more_bg_h.gif) no-repeat 66px 0 #FFFFFF; color:#0B0B0B; float:right;
	font:bold 10px/13px Arial, Helvetica, sans-serif; text-decoration:none; text-transform:uppercase;
	padding:0 20px 0 0; margin:5px 35px 0 0;}
#colorBg{
	background-color:#FCFAF3; float:left; color:#0B0B0B;
	margin:18px 0 0 0; padding:18px 40px 18px 38px; width:642px;}
#futurePlans{
	width:298px; float:left;}
#futurePlans h2.text1{
	background-color:#FCFAF3; color:#0B0B0B;
	font:normal 28px/40px Georgia, "Times New Roman", Times, serif;}
#futurePlans h2.text1 span{
	background-color:#FCFAF3; color:#A60101;
	font:normal 28px/40px Georgia, "Times New Roman", Times, serif;}
#futurePlans ul{ float:left;}
#futurePlans ul li{
	font:normal 13px/19px Arial, Helvetica, sans-serif; color:#4E4628;
	background:url(<?php echo $root_images;?>/red_bullet.gif) no-repeat 0 6px #FCFAF3; padding:0 0 0 10px;}
#futurePlans ul li a{
	font:bold 13px/19px Arial, Helvetica, sans-serif; text-decoration:none;
	background-color:#FCFAF3; color:#4E4628; display:block;}
#futurePlans ul li a:hover{
	font:bold 13px/19px Arial, Helvetica, sans-serif; text-decoration:none;
	background-color:#EAE6D9; color:#4E4628; display:block;}
#futurePlans p{
	background:url(<?php echo $root_images;?>/boeder.gif) repeat-x 0 14px #FCFAF3; color:#0B0B0B;
	height:13px; line-height:13px; padding:14px 0 19px 0;}
#futurePlans p a.more{
	background:url(<?php echo $root_images;?>/more_bg.gif) no-repeat 76px 0 #FCFAF3; color:#0B0B0B; float:right;
	font:bold 10px/13px Arial, Helvetica, sans-serif; text-decoration:none; text-transform:uppercase;
	padding:0 20px 0 10px; margin:0;}
#futurePlans p a.more:hover{
	background:url(<?php echo $root_images;?>/more_bg_h.gif) no-repeat 76px 0 #FCFAF3; color:#0B0B0B; float:right;
	font:bold 10px/13px Arial, Helvetica, sans-serif; text-decoration:none; text-transform:uppercase;
	padding:0 20px 0 10px; margin:0;}
#newsLetter{
	margin:0 auto;}
#newsLetter span.nltop{
	background:url(<?php echo $root_images;?>/newsletter_top.gif) no-repeat 0 0 #FCFAF3; color:#000000;
	line-height:0; font-size:0; height:15px; display:block;}
#newsLetter span.nlbottom{
	background:url(<?php echo $root_images;?>/newsletter_bottom.gif) no-repeat 0 0 #FCFAF3; color:#000000;
	line-height:0; font-size:0; height:14px; display:block;}
#newsLetter form.newsLetter{
	background:url(<?php echo $root_images;?>/newsletter_midle.gif) repeat-y 0 0 #FCFAF3; color:#000000;
	width:298px; padding:0 16px; float:left;}
#newsLetter form.newsLetter h2.text2{
	background:url(<?php echo $root_images;?>/newsletter_h2_bg.gif) no-repeat 0 0; color:#786E4E;
	padding:0 0 10px 55px; float:left; width:228px; height:37px;
	font:normal 24px/30px Georgia, "Times New Roman", Times, serif;}
#newsLetter form.newsLetter h2.text2 span{
	background-color:#FFFFFF; color:#0B0B0B;
	font:normal 24px/30px Georgia, "Times New Roman", Times, serif;}
#newsLetter form.newsLetter label{
	background-color:#FFFFFF; color:#0B0B0B; margin:0 0 8px 0; float:left;
	font:bold 10px/28px Arial, Helvetica, sans-serif; text-transform:uppercase;}
#newsLetter form.newsLetter input{
	background-color:#EFEBDE; color:#0B0B0B; border:#C3BCA4 1px solid;
	width:168px; height:22px; padding:2px; margin:0 0 8px 15px; float:left;
	font:normal 14px/20px Arial, Helvetica, sans-serif;}
#newsLetter form.newsLetter input.submit{
	background:url(<?php echo $root_images;?>/submit_bg.gif) no-repeat 45px 0 #FFFFFF; color:#0B0B0B; border:none;
	width:60px; height:13px; float:right; margin:7px 34px 0 0; padding:0 30px 0 0; cursor:pointer;
	font:normal 10px/13px Arial, Helvetica, sans-serif; text-transform:uppercase;}
#contact{
	width:312px; float:right;}
#contact span.ctop{
	background:url(<?php echo $root_images;?>/contact_top.gif) no-repeat 0 0 #FCFAF3; color:#000000;
	line-height:0; font-size:0; height:14px; display:block;}
#contact span.cbottom{
	background:url(<?php echo $root_images;?>/contact_bottom.gif) no-repeat 0 0 #FCFAF3; color:#000000;
	line-height:0; font-size:0; height:25px; display:block;}
#contact form.contact{
	background:url(<?php echo $root_images;?>/contact_midle.gif) repeat-y 0 0 #FCFAF3; color:#000000;
	width:272px; padding:0 20px; float:left;}
#contact form.contact h2.text3{
	background:url(<?php echo $root_images;?>/contact_h2_bg.gif) no-repeat 0 0 #FFFFFF; color:#0B0B0B;
	padding:0 0 10px 55px; float:left; width:228px; height:37px;
	font:normal 24px/30px Georgia, "Times New Roman", Times, serif;}
#contact form.contact h2.text3 span{
	background-color:#FFFFFF; color:#A60101;
	font:normal 24px/30px Georgia, "Times New Roman", Times, serif;}
#contact form.contact label{
	background-color:#FFFFFF; color:#0B0B0B; margin:0 0 8px 0; float:left;
	font:bold 10px/28px Arial, Helvetica, sans-serif; text-transform:uppercase;}
#contact form.contact input{
	background-color:#EFEBDE; color:#0B0B0B; border:#C3BCA4 1px solid;
	width:168px; height:22px; padding:2px; margin:0 0 8px 15px; float:right;
	font:normal 14px/20px Arial, Helvetica, sans-serif;}
#contact form.contact textarea{
	background-color:#EFEBDE; color:#0B0B0B; border:#C3BCA4 1px solid;
	width:168px; height:66px; padding:2px; margin:0 0 13px 15px; float:right;
	font:normal 14px/20px Arial, Helvetica, sans-serif;}
#contact form.contact input.submit{
	background:url(<?php echo $root_images;?>/submit_bg.gif) no-repeat 45px 0 #FFFFFF; color:#0B0B0B; border:none;
	width:60px; height:13px; float:right; margin:0 0 0 10px; padding:0 20px 0 0; cursor:pointer;
	font:normal 10px/13px Arial, Helvetica, sans-serif; text-transform:uppercase;}
#contact form.contact input.reset{
	background:url(<?php echo $root_images;?>/more_bg.gif) no-repeat 45px 0 #FFFFFF; color:#0B0B0B; border:none;
	width:60px; height:13px; float:right; margin:0; padding:0 15px 0 0; cursor:pointer;
	font:normal 10px/13px Arial, Helvetica, sans-serif; text-transform:uppercase;}

	

/* --- footer_wrapper --- */	
	
#footer_wrapper{
	position:relative; 
	margin:0 auto; 
	width:700px; 
	padding:12px 0 50px;
	font:normal 12px/16px "Trebuchet MS", Arial, Helvetica, sans-serif;
}
#footer_wrapper ul{
	text-align:center;
	
}
#footer_wrapper ul li{
	display:inline;
	color:#0B0B0B; 
	background-color:#FDF9EE;
}
#footer_wrapper ul li a{
	color:#0B0B0B;
	background-color:#FDF9EE; 
	padding:0 8px; 
}
#footer_wrapper ul li a:hover{
	color:#0B0B0B; 
	background-color:#EFEBDE; 
	padding:0 8px; 
}

#footer_wrapper .site_credits{
	background-color:#FDF9EE; 
	padding:10px 0;
	text-align:center;
	color:#0B0B0B; 
	background-color:#FDF9EE; 
	font:normal 12px/19px "Trebuchet MS", Arial, Helvetica, sans-serif;
}
#footer_wrapper .site_credits a{
	color:#0B0B0B; 
	background-color:#FDF9EE; 
}
#footer_wrapper .site_credits a:hover{
	color:#0B0B0B; 
	background-color:#EFEBDE; 
	
}



