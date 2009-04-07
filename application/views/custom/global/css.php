html {
	font-size: 80%;
	<?=$background['html'];?>;
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
  <?=$background['header_wrapper'];?>;
  min-height:80px;
  padding:10px 0;
  margin-bottom:10px;
}
	#header{
		width:100%;
		_background: url(<?php echo $user_images;?>line_down.png) repeat-x 17px 40px;
	}
#header_banner{
	float:right;
	margin-top:5px;
}
#header_address{
	padding-top:12px;
	_border:1px solid red;
	color:white;
	width:40%;
	text-align:center;
}
#header_address b{
	font-size:1.6em;
	color:#fff;
}
#header_address p{
	font-style:italic;
	color:#fff;
	font-size:0.9em;
}
	/* ------------ body_wrapper  ------------*/
	
#body_wrapper{
  _width:800px;
  margin:auto;
  padding:0 20px;
}	

	/* -----------  main_menu_wrapper ------------ */

#main_menu_wrapper{
	float:left;
	width:150px;
	background:transparent;
	padding:7px 0;
	margin-right:25px;
	text-align:center;
	_border:1px solid red;
}
#main_menu_wrapper a{
	display:block;
	padding:7px 15px; 
	margin-bottom:10px;
	<?=$background['main_menu_wrapper'];?>;
	color:#fff;
	text-decoration:none;
}
#main_menu_wrapper a:hover {
	background:#fff;
	text-decoration:underline;
	color:#000;	
}
#tab_selected{
	background:#fff !important;
	color:#333 !important;
}


	/*  ---------------- primary_wrapper ---------------- */
	
#primary_wrapper{
   <?=$background['primary_wrapper'];?>;
  min-height:400px;
  padding:0px 12px 12px 12px;
  _border:2px solid #fff;
  overflow:auto;
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