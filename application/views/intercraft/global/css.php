/* base style */
body{
	padding:0px; 
	margin:0px; 
	background:url(<?php echo $root_images?>/mainbg.gif) 0 0 repeat-x; 
	font:14px/20px Arial, Helvetica, sans-serif;
}
div, p, ul, h1, h2, h3, form, label{
	margin:0px; padding:0px;
}
ul{
	list-style-type:none;
}
a img {
	border:0;
}

/* --- header_wrapper --- */

#header_wrapper{
	width:729px; 
	height:317px; 
	position:relative;
	 margin:0 auto;
 }
#header_wrapper #logo{
	width:63px; 
	height:73px; 
	position:absolute; 
	top:108px; 
	left:46px;
}
#header_wrapper h2{
	width:300px; 
	height:40px; 
	display:block; 
	color:#B38800; 
	position:absolute; 
	top:140px; 
	left:114px; 
	font-size:12px; 
	line-height:16px; 
	padding:41px 0 0; 
	font-weight:normal;
}

#header_wrapper h1{
	width:315px; 
	height:239px; 
	background:url(<?php echo $root_images?>/header.jpg) 0 0 no-repeat; 
	position:absolute; 
	top:77px; 
	right:0px; 
}

	/* --- main_menu_wrapper --- */

#main_menu_wrapper ul{
	width:729px; 
	height:44px; 
	background:url(<?php echo $root_images?>/topmenubg.jpg) 0 0 no-repeat;
	font-size:13px; 
	position:absolute; 
	top:0px; 
	left:0px;
}
#main_menu_wrapper ul li{
	width:114px; 
	float:left;
}
#main_menu_wrapper ul li.leftpadding{
	padding:0 0 0 59px; 
	width:86px; 
	background: url(<?php echo $root_images?>/homebg.gif) 100% 0 no-repeat #fff; 
	color:#674E00; 
	text-decoration:none; 
	text-align:center; 
	line-height:44px;
}
#main_menu_wrapper ul li a{
	width:114px; 
	height:44px; 
	display:block; 
	background:url(<?php echo $root_images?>/menubg-normal.gif) 0 0 repeat-x #F2F2F2; 
	color:#674E00; 
	text-decoration:none; 
	text-align:center; 
	line-height:44px;
}
#main_menu_wrapper ul li a:hover,
#main_menu_wrapper ul li a.selected{
	width:114px; 
	background:#fff; 
	color:#674E00; 
	text-decoration:none;
}



#header_wrapper form{
	width:380px; 
	height:46px; 
	background:url(<?php echo $root_images?>/formarea.gif) 0 0 no-repeat #fff;  
	color:#6E6E6E; 
	position:absolute; 
	top:242px; 
	left:31px; 
	font:18px/46px Georgia, "Times New Roman", Times, serif;
}
#header_wrapper form label{
	float:left; 
	width:104px; 
	display:block; 
	padding:0 0 0 72px;
}
#header_wrapper form input{
	width:112px; 
	height:18px; 
	float:left; 
	display:block; 
	margin:12px 14px 0 0;
}
#header_wrapper form input.button{
	width:50px; 
	height:19px; 
	float:left; 
	display:block; 
	border:none; 
	background:url(<?php echo $root_images?>/button.gif) 0 0 no-repeat #F2F2F2; 
	font-size:14px; 
	line-height:19px; 
	font-weight:bold; 
	color:#fff; 
	text-align:center; 
	margin:12px 0 0;
}



/* --- body_wrapper --- */
#body_wrapper{
	width:750px; 
	background:url(<?php echo $root_images?>/middlebg.jpg) 0 0 no-repeat; 
	margin:0 auto; 
	padding:20px 0;
}

#middleheader_wrapper{width:682px; position:relative; top:0px; left:0px;}
#middleheader_wrapper p.captiontext{font:17px/22px Georgia, "Times New Roman", Times, serif; font-style:italic; background:#fff; color:#989898; padding:0 310px 0 0;} 
#middleheader_wrapper p.name{width:77px; background:#fff; color:#000; font-size:14px; font-style:italic; font-weight:bold; padding:0 0 0 300px;}
#middleheader_wrapper p.border{background:url(<?php echo $root_images?>/dot-line.gif) 0% 50% repeat-x; height:74px; padding:0px; margin:0px;}
#middleheader_wrapper #services{width:254px; height:101px; background:url(<?php echo $root_images?>/servicesbg.jpg) 0 0 no-repeat #DFCC97; color:#fff; font-family:"Trebuchet MS",Arial, Helvetica, sans-serif; font-weight:bold; position:absolute; top:0px; right:0px;}
#middleheader_wrapper #services p.largeone{font-size:24px; padding:18px 0 0 100px;}
#middleheader_wrapper #services p.largetwo{font-size:30px; padding:5px 0 0 100px;}
#middleheader_wrapper #services p.click{width:51px;}
#middleheader_wrapper #services p.click a{width:36px; height:18px; display:block; font-size:16px; background:url(<?php echo $root_images?>/arrow.gif) 0% 50% no-repeat #AC9248; line-height:14px; color:#fff; text-decoration:none; margin:14px 0 0 166px; padding:0 0 0 15px;}
#middleheader_wrapper #services p.click a:hover{text-decoration:underline;}




#leftPan{width:428px; float:left;}
#leftPan h2{width:200px; font:22px/28px Georgia, "Times New Roman", Times, serif; background:#fff; color:#3D3C2C;}
#leftPan h3{width:300px; height:70px; background:url(<?php echo $root_images?>/icon1.jpg) 0% 50% no-repeat #fff; color:#B38800; font:18px/70px Georgia, "Times New Roman", Times, serif; padding:0 0 0 65px;}

#leftPan p.redtext{font-size:18px; background:#fff; color:#D20039; padding:0 61px 0 0;}
#leftPan p.more{width:66px; height:25px;}
#leftPan p.more a{width:60px; height:25px; display:block; background:url(<?php echo $root_images?>/arrow1.gif) 85% 50% no-repeat #AC9145; color:#fff; font:14px/25px "Trebuchet MS",Arial, Helvetica, sans-serif; text-transform:uppercase; text-decoration:none; margin:0 0 0 300px; padding:0 0 0 6px;}
#leftPan p.more a:hover{background:url(<?php echo $root_images?>/arrow1.gif) 85% 50% no-repeat #D20039; color:#fff; text-decoration:none;}

#leftPan ul{padding:20px 62px 10px 0;}
#leftPan ul li{height:20px;}
#leftPan ul li.top-lipadding{padding:24px 0 0;}

#leftPan ul li a{background:url(<?php echo $root_images?>/bullet.gif) 0 6px no-repeat #fff; color:#3D3C2C; font-size:14px; line-height:20px; text-decoration:none; padding:0 0 0 20px;}
#leftPan ul li a:hover{ background:url(<?php echo $root_images?>/bullet2.gif) 0 6px no-repeat #fff; color:#7A7A77; text-decoration:none;}

#leftPan #blog{width:367px; height:167px; background:url(<?php echo $root_images?>/image1.jpg) 0 0 no-repeat #fff; color:#3D3C2C; margin:39px 0 0; font-family:"Trebuchet MS",Arial, Helvetica, sans-serif;}
#leftPan #blog p.smalltext{font-size:20px; padding:16px 0 0 137px;}
#leftPan #blog p.bigtext{font-size:24px; padding:5px 0 0 137px;}
#leftPan #blog p.link{font:14px/18px "Trebuchet MS",Arial, Helvetica, sans-serif; font-weight:bold; margin:35px 19px 0 137px;}
#leftPan #blog p.link a{display:block; width:132px; height:54px; background:url(<?php echo $root_images?>/click-normal.gif) 100% 0% no-repeat #C4C4C4; color:#fff; font-weight:bold; text-decoration:none; padding:0 77px 0 0;}
#leftPan #blog p.link a:hover{text-decoration:none; background:url(<?php echo $root_images?>/click-hover.gif) 100% 0% no-repeat #C4C4C4; color:#fff;}

/*---/Left Panel---*/
/*---Right Panel---*/
#rightPan{width:254px; float:left;}
#rightPan h2{width:200px; height:36px; font:22px/20px Georgia, "Times New Roman", Times, serif; background:#fff; color:#3D3C2C;}
#rightPan h3{width:200px; height:36px; font:22px/20px Georgia, "Times New Roman", Times, serif; background:#fff; color:#3D3C2C;}

#rightPan ul{padding:0 0 25px 0;}
#rightPan ul li{width:220px; height:25px;}
#rightPan ul li a{background:url(<?php echo $root_images?>/arrow2.gif) 0 7px no-repeat #fff; color:#3D3C2C; line-height:25px; text-decoration:underline; padding:0 0 0 20px;}
#rightPan ul li a:hover{background:url(<?php echo $root_images?>/arrow3.gif) 0 7px no-repeat #fff; color:#7A7A77; text-decoration:underline;}

#rightPan ul.nextone{padding:0 0 25px 0;}
#rightPan ul.nextone li{width:220px; height:25px;}
#rightPan ul.nextone li a{background:url(<?php echo $root_images?>/arrow2.gif) 0 7px no-repeat #fff; color:#D20039; line-height:25px; text-decoration:underline; padding:0 0 0 20px;}
#rightPan ul.nextone li a:hover{background:url(<?php echo $root_images?>/arrow3.gif) 0 7px no-repeat #fff; color:#7A7A77; text-decoration:underline;}



#footer_wrapper{
	height:117px; 
	background:url(<?php echo $root_images?>/footerbg.gif) 0 0 repeat-x #fff; 
	color:#6F5D2B; 
	font:12px/22px "Trebuchet MS",Arial, Helvetica, sans-serif; 
	clear:both; 
	padding-top:105px;
	text-align:center;
}

#footer{
	width:729px; 
	margin:0 auto;
}
#footerlogoPan{
	margin:0 auto;
	width:215px; 
	height:40px; 
}

#footer ul{
	width:546px; 
	margin:0 auto;
	text-align:center;
}
#footer li{
	display:inline;
}
#footer ul li a{
	padding:0 10px 0; 
	color:#6F5D2B; 
	background: url(<?php echo $root_images?>/footermenubg.gif) 0 0 repeat-x #FFFBEE; 
	text-decoration:none;
}
#footer ul li a:hover{
	text-decoration:underline;
}

#footer templateworld{
	width:250px; 
	background:#FFF; 
	color:#444; 
	display:block; 
}

#footer ul.templateworld a{
	background:#FFF; 
	display:block; 
	color:#444; 
	text-decoration:none;
}
#footer ul.templateworld a:hover{
	text-decoration:underline;
}

#footer p.copyright{
	margin:0 auto;
	text-align:center;
	width:204px; 
	background: url(<?php echo $root_images?>/copyrightbg.gif) 0 0 repeat-x #FFFDF6; 
	color:#D20039;
}

