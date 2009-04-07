/* CSS Document */
body{
	background:url(<?php echo $root_images;?>/bg.gif) repeat-x 0 0 #F7F7F7; 
	color:#171717;
	font:normal 13px/20px Georgia, "Times New Roman", Times, serif;
	margin:0; padding:0;
}
div, h1, h2, h3, h4, h5, h6, form, label, input, span, ul, li, a{
	margin:0; padding:0;
}
ul{
	list-style:none;
}

/* -- main_menu_wrapper -- */
#main_menu_wrapper{
	width:683px; 
	margin:0 auto; 
	height:37px;
}
#main_menu_wrapper ul{
	width:540px; 
	margin:0 auto;
	}
#main_menu_wrapper ul li{
	height:37px; 
	float:left;
	background:url(<?php echo $root_images?>/bg.gif) repeat-x 0 0 #292929; 
	color:#C1C1C1;
	font:bold 12px/37px Arial, Helvetica, sans-serif;
}
#main_menu_wrapper ul li a{
	padding:0 18px; 
	height:37px; 
	float:left; 
	text-decoration:none; 
	display:block;
	background:url(<?php echo $root_images;?>/bg.gif) repeat-x 0 0 #292929; 
	color:#C1C1C1;
	font:bold 12px/37px Arial, Helvetica, sans-serif;
}
#main_menu_wrapper ul li a:hover{
	background:url(<?php echo $root_images;?>/top_btn_h.gif) no-repeat center bottom;
}
#main_menu_wrapper ul li a.selected{
	padding:0 18px; 
	height:37px; 
	float:left; 
	text-decoration:none; 
	display:block;
	background:url(<?php echo $root_images;?>/top_btn_h.gif) no-repeat center bottom; color:#C1C1C1;
	font:bold 12px/37px Arial, Helvetica, sans-serif;
}

	/* -- body_wrapper -- */
	
#body_wrapper{
	width:750px; 
	margin:0 auto; 
	padding-bottom:50px;
	background:url(<?php echo $root_images;?>/header_bg.gif) no-repeat right top #F7F7F7; color:#171717;
}


	/* -- header_wrapper -- */
	
#header_wrapper{
	height:230px;
}
#header{
	padding-top:10px;
}
#header_tagline{
	margin-top:20px;
	padding:10px;
	font-style:italic;
	font-size:1.4em;
}

	/* -- content_wrapper -- */
	
#content_wrapper{
	min-height:500px;
	padding:10px 0;
}
#content_wrapper h2{
	font:bold italic 24px/34px Georgia, "Times New Roman", Times, serif; color:#B10000;
}
#content_wrapper h2 span{
	color:#000000; background-color:#F7F7F7;
}
#content_wrapper h3{
	font:bold 20px/24px Georgia, "Times New Roman", Times, serif; color:#526D0D;
	background:url(<?php echo $root_images;?>/folder_icon.gif) no-repeat 0 5px #F7F7F7; padding:0 2px 0 25px;
}
#content_wrapper h4{
	font:normal 20px/24px Georgia, "Times New Roman", Times, serif; color:#000000;
	background-color:#FFF7DE; padding:0 0 0 10px;
}
#content_wrapper p{
	font:normal 13px/20px Georgia, "Times New Roman", Times, serif; color:#171717;
	padding:10px 0; background-color:#F7F7F7;
}
#content_wrapper p a{
	color:#003E6A; background-color:#F7F7F7; text-decoration:underline;
	}
#content_wrapper p a:hover{
	text-decoration:none;
}
#catagory{
	padding:25px 0 0 0;
}
	
	
	
	
.pink{
	width:210px; 
	padding:15px; 
	float:left;
	background:url(<?php echo $root_images;?>/pink_bg.gif) repeat-x 0 0 #F7F7F7; color:#4B2B3E;
}
.pink h3{
	background:url(<?php echo $root_images;?>/news_icon.gif) no-repeat 2px 13px; color:#850049;
	font:bold 20px/38px Georgia, "Times New Roman", Times, serif; 
	padding:0 9px 0 22px;
}
.pink h4{
	background:url(<?php echo $root_images;?>/date_bg.gif) no-repeat 0 13px; color:#384B06;
	width:56px; 
	text-align:center;
	font:bold 12px/39px Arial, Helvetica, sans-serif;
}
.pink h5{
	color:#000000; 
	text-transform:uppercase;
	font:bold 10px/11px Georgia, "Times New Roman", Times, serif;
}
.pink p{
	color:#4B2B3E; 
	padding:3px 0 10px 0;
	font:normal 11px/17px Georgia, "Times New Roman", Times, serif;
}
.pink a.more{
	background:url(<?php echo $root_images;?>/pink_more_btn.gif) no-repeat 0 0 #FFFFFF; color:#000000;
	width:46px; 
	height:11px; 
	display:block; 
	float:right;
	text-decoration:none; 
	text-indent:-2000px;
	padding:0; 
	line-height:0;
}
.pink a.more:hover{
	background:url(<?php echo $root_images;?>/pink_more_btn_h.gif) no-repeat 0 0 #FFFFFF; color:#000000;
}


.green{
	width:182px; 
	padding:7px 15px 50px; 
	float:left; 
	margin:0 23px;
	background:url(<?php echo $root_images;?>/green_bg.gif) no-repeat 0 0 #F7F7F7; color:#4B2B3E;
	}
.green h3{
	background:url(<?php echo $root_images;?>/solution_icon.gif) no-repeat 2px 13px; color:#516D0A;
	font:bold 20px/38px Georgia, "Times New Roman", Times, serif; 
	padding:0 9px 0 22px;
}
.green h5{
	color:#000000; text-transform:uppercase;
	font:bold 10px/11px Georgia, "Times New Roman", Times, serif;
}
.green p{
	color:#3D5C32; padding:3px 0 27px 0;
	font:normal 11px/17px Georgia, "Times New Roman", Times, serif;
}
.green p a.line{
	color:#3D5C32; padding:0;  text-decoration:underline;
	font:normal 11px/17px Georgia, "Times New Roman", Times, serif;
}
.green p a.line:hover{
	color:#3D5C32; padding:0;  text-decoration:none;
	font:normal 11px/17px Georgia, "Times New Roman", Times, serif;
}
.green a.more{
	background:url(<?php echo $root_images;?>/green_more_btn.gif) no-repeat 0 0 #FFFFFF; color:#000000;
	width:46px;
	height:11px; 
	display:block; 
	float:right;
	text-decoration:none; 
	text-indent:-2000px;
	padding:0; line-height:0;
}
.green a.more:hover{
	background:url(<?php echo $root_images;?>/green_more_btn_h.gif) no-repeat 0 0 #FFFFFF; color:#000000;}

.blue{
	width:182px; padding:7px 15px 50px; float:left;
	background:url(<?php echo $root_images;?>/blue_bg.gif) no-repeat 0 0 #F7F7F7; color:#4B2B3E;}
.blue h3{
	background:url(<?php echo $root_images;?>/support_icon.gif) no-repeat 2px 11px; color:#364A5B;
	font:bold 20px/38px Georgia, "Times New Roman", Times, serif; padding:0 9px 0 22px;}
.blue h5{
	color:#000000; text-transform:uppercase;
	font:bold 10px/11px Georgia, "Times New Roman", Times, serif;}
.blue p{
	color:#364A5B; padding:3px 0 10px 0;
	font:normal 11px/17px Georgia, "Times New Roman", Times, serif;}
.blue a.more{
	background:url(<?php echo $root_images;?>/blue_more_btn.gif) no-repeat 0 0 #FFFFFF; color:#000000;
	width:46px; height:11px; display:block; float:right;
	text-decoration:none; text-indent:-2000px;
	padding:0; line-height:0;}
.blue a.more:hover{
	background:url(<?php echo $root_images;?>/blue_more_btn_h.gif) no-repeat 0 0 #FFFFFF; color:#000000;}
.goal{
	width:448px; float:left; padding:0 22px 0 0;}
.goal h2{
	font:bold italic 24px/34px Georgia, "Times New Roman", Times, serif;
	color:#B10000; background-color:#F7F7F7;}
.goal h2 span{
	color:#000000; background-color:#F7F7F7;}
.goal p{
	font:normal 13px/20px Georgia, "Times New Roman", Times, serif; color:#171717;
	padding:10px 0; background-color:#F7F7F7;}
.goal p.greenText{
	font:normal 13px/20px Georgia, "Times New Roman", Times, serif; color:#526D0D;
	padding:6px 0; background-color:#F7F7F7;}
.goal ul li{
	font:normal 13px/20px Georgia, "Times New Roman", Times, serif; color:#171717;
	padding:2px 2px 2px 23px; margin:0 0 5px 0;
	background:url(<?php echo $root_images;?>/bullet_no.gif) no-repeat 2px 5px #FFFBED;}

.floatLeft{
	float:left;
}

	/* -- footer_wrapper -- */

#footer_wrapper{
	clear:both;
	background-color:#292929; 
	color:#fff;
}
#footer_wrapper #footer{
	width:750px; 
	min-height:115px; 
	margin:0 auto; 
	padding-top:20px;
	text-align:center;
}
#footer_wrapper #footer ul{
	margin:0 auto;
	text-align:center;
}
#footer_wrapper #footer ul li{
	color:#FAFAFA; 
	background-color:#292929; 
	display:inline;
	font:bold 12px/20px Arial, Helvetica, sans-serif;
}
#footer_wrapper #footer ul li a{
	color:#FAFAFA; 
	background-color:#292929; 
	padding:0 8px;
}
#footer_wrapper #footer ul li a:hover{
	text-decoration:none;
	color:#FAFAFA;
	background-color:#484848; 
	padding:0 8px;
}
#footer_wrapper #footer #site_credits{
	text-align:center;
	color:#EDE3C0; 
	font-size:0.9em;
	background-color:#292929;
}
#site_credits a{
	color:#fff;
}

