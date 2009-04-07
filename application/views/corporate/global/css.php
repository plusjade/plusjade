/* base styles */
body{
	padding:0px; 
	margin:0px; 
	background:url(<?php echo $root_images?>/mainbg.gif) 0 0 repeat-x #fff; 
	color:#666; font:14px/18px "Trebuchet MS", Arial, Helvetica, sans-serif;
}
div, ul, h2, img{
	padding:0px; 
	margin:0px;
}
ul{
	list-style-type:none;
}
a{
	background:#fff; 
	color:#000; 
}
a:hover{
	background:#fff; 
	color:#666; 
} 
a img{
	border:0;
}

	/* --- header_wrapper --- */

#header_wrapper{
	width:750px; 
	min-height:87px; 
	position:relative; 
	margin:0 auto;
}
#header_wrapper #logo_wrapper{
	float:left;
	margin-right:20px;
	margin-top:30px;
	padding-left:35px;
	background:#fff; 
	color:#666; 	
}
#header_wrapper #logo_wrapper img{

}

	/* --- main_menu_wrapper --- */
#main_menu_wrapper{
	float:left;
	position:relative; 
	top:44px; 
}
#main_menu_wrapper ul{
	width:475px; 
	height:32px; 
}
#main_menu_wrapper ul li{
	width:78px; 
	height:32px; 
	float:left;
}
#main_menu_wrapper ul li a{
	width:76px; 
	height:32px; 
	display:block; 
	background:url(<?php echo $root_images?>/topmenu-normal.jpg) 0 0 no-repeat #F5F4F4; 
	color:#666; 
	text-decoration:none; 
	font-size:12px; 
	font-weight:bold; 
	text-align:center; 
	line-height:32px; 
	margin-right:2px;
}
#main_menu_wrapper ul li a:hover{
	background:url(<?php echo $root_images?>/topmenu-hover.jpg) 0 0 no-repeat #F5F4F4; 
	color:#fff; 
	text-decoration:none;
}

#main_menu_wrapper ul li a.selected{
	background:url(<?php echo $root_images?>/topmenu-hover.jpg) 0 0 no-repeat #F5F4F4; 
	color:#fff; 
	text-decoration:none; 
	font-size:12px; 
	font-weight:bold; 
	text-align:center; 
	line-height:32px;
}

/* --- body_wrapper --- */

#body_wrapper{
	clear:both;
	width:750px; 
	margin:0 auto;
	padding:30px 15px;
	min-height:500px;
}
#body_wrapper p.toptextpadding{
	padding:25px 0 0;
}
#body_wrapper p span{
	background:#fff; 
	color:#D20039;
}
#body_wrapper p.more{
	width:81px; 
	height:22px; 
	display:block; margin:0 0 0 606px; 
	background:url(<?php echo $root_images?>/more-bg.gif) 0 0 no-repeat;
}
#body_wrapper p.more a{
	width:70px; 
	height:22px; 
	display:block; 
	background:url(<?php echo $root_images?>/arrow1.gif) 1% 60% no-repeat; 
	line-height:22px; 
	text-decoration:none; 
	padding:0 0 0 11px;
}
#body_wrapper p.more a:hover{
	background:url(<?php echo $root_images?>/arrow2.gif) 1% 60% no-repeat; 
	text-decoration:none;
}


/*----Body Middle Panel----*/

#bodyMiddlePan{
	width:689px; 
	position:relative; 
	margin:0 auto;
}


/*----Middle Left Panel----*/

#MiddleLeftPan{width:232px; float:left;}
#MiddleLeftPan p{padding:14px 0 0; line-height:18px;}

#MiddleLeftPan p.largegraytext{font-size:18px;}

#MiddleLeftPan p.more{width:81px; height:22px; display:block; margin:15px 0 0 145px; background:url(<?php echo $root_images?>/more-bg.gif) 0 0 no-repeat; padding:0px;}
#MiddleLeftPan p.more a{width:70px; height:22px; display:block; background:url(<?php echo $root_images?>/arrow1.gif) 1% 60% no-repeat; line-height:22px; text-decoration:none; padding:0 0 0 11px;}
#MiddleLeftPan p.more a:hover{background:url(<?php echo $root_images?>/arrow2.gif) 1% 60% no-repeat; text-decoration:none;}

#MiddleLeftPan p.chat{width:232px; height:70px; padding:14px 0 36px;}
#MiddleLeftPan p.chat a{width:232px; height:70px; display:block; background:url(<?php echo $root_images?>/24hours.gif) 0 0 no-repeat; text-indent:-20000px;}
#MiddleLeftPan p.chat a:hover{background:url(<?php echo $root_images?>/24hours.gif) 0 0 no-repeat; text-indent:-20000px;}

#MiddleLeftPan h2{width:210px; height:50px; display:block; background:url(<?php echo $root_images?>/icon1.jpg) 100% 0 no-repeat  #fff; color:#D20039; border-bottom:1px dashed #BDB9B9; font-size:24px; line-height:22px; padding:0 20px 0 0;}
#MiddleLeftPan h2 span{background:#fff; color:#545454; font-weight:bold; font-size:14px; text-transform:uppercase;}

/*----/Middle Left Panel----*/
/*----Middle Right Panel----*/


#MiddleRightPan{width:384px; float:left; padding:0 0 0 73px;}
#MiddleRightheader_wrapper{width:384px; height:203px; background:url(<?php echo $root_images?>/image1.jpg) 0 100% no-repeat; padding:29px 0 0; margin:0 0 40px 0;}

#MiddleRightheader_wrapper p.fresh{width:108px; height:24px; display:block; margin:0 0 0 208px; font-size:20px; text-transform:uppercase;}
#MiddleRightheader_wrapper p.business{width:170px; height:20px; display:block; margin:0 0 0 210px; background:url(<?php echo $root_images?>/dotline.gif) 0 100% repeat-x #fff; color:#6BB600; font-size:27px; text-transform:uppercase; text-align:right;}

#MiddleRightPan h2{width:380px; height:20px; background:url(<?php echo $root_images?>/dotline.gif) 0 100% repeat-x #fff; color:#6BB600; font-size:24px;}
#MiddleRightPan p{padding:8px 0 0;}

#RightListfastPan{width:200px; float:left; padding:10px 0 0;}
#RightListfastPan ul{width:200px;}
#RightListfastPan ul li{width:200px; height:20px; float:left;}
#RightListfastPan ul li a{width:190px; line-height:20px; background:url(<?php echo $root_images?>/bullet.gif) 0 6px no-repeat #fff; color:#666; text-decoration:underline; padding:0 0 0 18px;}
#RightListfastPan ul li a:hover{background:url(<?php echo $root_images?>/bullet-hover.gif) 0 6px no-repeat #fff; color:#000; text-decoration:underline;}

#RightListnextPan{width:180px; float:left; padding:10px 0 0;}
#RightListnextPan ul{width:180px;}
#RightListnextPan ul li{width:180px; height:20px; float:left;}
#RightListnextPan ul li a{width:160px; line-height:20px; background:url(<?php echo $root_images?>/bullet.gif) 0 6px no-repeat #fff; color:#666; text-decoration:underline; padding:0 0 0 18px;}
#RightListnextPan ul li a:hover{background:url(<?php echo $root_images?>/bullet-hover.gif) 0 6px no-repeat #fff; color:#000; text-decoration:underline;}



/* --- footer_wrapper --- */

#footer_wrapper{
	background:url(<?php echo $root_images?>/footerbg.gif) 0 0 repeat-x #F4F4F4; 
	color:#212121;
	position:relative; 
	margin:0 auto; 
	height:133px; 
	clear:both;
}
#footer{
	width:689px; 
	position:relative;
	margin:0 auto; 
	font:12px/15px "Trebuchet MS",Arial, Helvetica, sans-serif; 
	font-weight:normal; 
	padding:15px 0 0;
}

#footer ul{
	width:450px; 
	height:20px; 
	position:relative; 
	margin:0 auto;
	text-align:center;
}
#footer li{
	display:inline;
}
#footer ul li a{
	padding:0 10px 0;
	color:#212121; 
	background:#F4F4F4; 
	text-decoration:none;
}
#footer ul li a:hover{
	text-decoration:underline;
}

#footer .credits{
	width:190px; 
	background:#F4F4F4; 
	color:#212121; 
	margin:0 auto; 
}
#footer .credits a{
	background:#F4F4F4; 
	color:#212121; 
}

#footer p.copyright{
	width:250px; 
	background:#F4F4F4; 
	color:#212121;
	position:relative; 
	margin:0 auto;
}
