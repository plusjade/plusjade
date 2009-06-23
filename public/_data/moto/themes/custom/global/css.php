html {
	font-size: 80%;
	_<?php echo $background['html'];?>;
	background:#000 url(<?php echo $user_images;?>moto.gif) repeat-x 17px 40px;
}
html, body {
	margin:0;
	font-family:verdana, arial;
}

	/* ------------ reset base styles  ------------*/
	
a {border:0;}
img {border:0;}
h1,h2,h3,h4,h5,h6{font-size:1em; padding:0; margin:0; margin-bottom:10px;}
h1{font-size:1.6em;}
h2{font-size:1.4em;}
h3{font-size:1.2em;}
h4{font-size:1.1em;}
h5{font-size:1em;}
/* ul , ol, dl{line-height:1.4em;}  */
dt{font-weight:bold;}

	/* ------------ header_wrapper  ------------*/
	
#header_wrapper{
	margin-top:70px;
	float:left;
	width:450px;
	height:225px;
	padding:20px;
	text-align:center;
	_border:1px solid red;
}
#header_address{
	text-align:center;
}
#header_address b{
	font-size:1.8em;
}
#header_address a{
	text-decoration:none;
	font-weight:bold;
	font-style:italic;
	font-size:1.4em;
}
#header_address a:hover{
	color:#ca0420;
}
	/* ------------ body_wrapper  ------------*/

#body_wrapper{
	_border:1px solid red;
	width:900px;
	margin:auto;
	margin-top:30px;
}	
	/* -----------  main_menu_wrapper ------------ */

#main_menu_wrapper{
	float:right;
	width:350px;
	_background:#fff;
	padding:20px;
	text-align:center;
	_border:2px solid #dedede;
	_background:#fff url(<?php echo $user_images;?>gray.png) repeat 0 0;
}
#main_menu_wrapper a{
	display:block;
	padding:8px 10px; 
	margin-bottom:10px;
	<?=$background['main_menu_wrapper'];?>;
	color:#ca0420;
	font-weight:bold;
	text-decoration:none;
	border-bottom:2px solid #ccc;
}
#main_menu_wrapper a:hover {
	background:#ca0420;
	text-decoration:underline;
	color:#fff;	
}
#tab_selected{
	background:#ca0420 !important;
	color:#fff !important;
}

	/*  ---------------- primary_wrapper ---------------- */
	
#primary_wrapper{
	clear:both;
	<?=$background['primary_wrapper'];?>;
	min-height:300px;
	padding:20px 50px;
	_border:1px dashed #fff;
	color:#fff; 
}
#primary_tagline{
	font-weight:bold;
	color:#0c7ced;
	font-size:1.6em;
	text-align:center;
}
#primary_left_panel{
	padding:0 10px;
	width:46%;
	_border:1px solid red;
}
#primary_right_panel{
  float:right;
  width:50%;
  _border:1px solid red;
  text-align:center;
}
#primary_full_panel{
	padding:0 10px;
	_border:1px solid green;
}
#primary_right_panel img{
  padding:6px;
  background:#fff;
  border:1px solid #fff;
}


	/*  ---------------- footer_wrapper ---------------- */

#footer_wrapper{
	height:100px;
	color:#fff;
	text-align:center;
}


	/*  ---------------- misc ---------------- */
	
#shell{
	text-align:center;
	margin:auto;
}
#frontpic{
	padding:5px;
	background:#fff;
	border:5px solid #b50f03;
	
}
#headline{
	font-size:1.3em;
	padding-bottom:10px;
	color:#fff;
	font-weight:bold;
}