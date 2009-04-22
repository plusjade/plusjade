html {
	font-size: 80%;
	<?php echo $background['html'];?>;
}
html, body {
	margin:0;
	font-family:verdana, arial;
	background: url(<?php echo $user_images;?>graf.jpg) repeat-x 0px 0px;
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
	_<?=$background['header_wrapper'];?>;
	background:transparent;
	min-height:180px;
	padding:10px 0;
	margin-bottom:10px;
}
#header{
	margin:auto;
	width:850px;		
}
#header_banner{
	margin-top:3px;
}
#header_address{
	display:none;
	float:right;
	padding-top:5px;
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
  width:890px;
  margin:auto;
  padding:0 20px;
  _border:1px solid red;
}	

	/* -----------  main_menu_wrapper ------------ */

#main_menu_wrapper{
	float:right;
	background:transparent;
	padding:8px 0;
	margin-right:25px;
	text-align:center;
	_border:1px solid red;
}
#main_menu_wrapper a{
	padding:8px 18px; 
	margin-left:10px;
	<?=$background['main_menu_wrapper'];?>;
	color:#222;
	text-decoration:none;
	_border:1px solid #fff;
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
  min-height:300px;
  padding:20px;
  _border-bottom:1px solid #ccc;
  overflow:auto;
  color:#333; 
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

#content{
	float:left;
	width:73%;
	border-right:1px solid #ccc;
	padding-right:20px;
	min-height:400px;
}
	/*  ---------------- sidebar (right) ---------------- */
#sidebar{
	float:right;
	width:22%;
	height:400px;
	padding-left:20px;
}
	
	/*  ---------------- secondary_wrapper ---------------- */
	
#secondary_wrapper{
  text-align:center;
  padding:10px 20px;
  background:transparent;
  margin:0 20px;
}

	/*  ---------------- action_wrapper ---------------- */

#action_wrapper{
	_border:1px solid #ccc;
	margin:0 20px;
	background:#fff;
	padding:20px;
}
.action{
	font-size:1.6em;
	line-height:1.4em;
	font-weight:bold;
	text-align:center;
}
#newsletter{
	text-align:center;
	line-height:1.2em;
}
#footer_wrapper{
	padding-top:60px;
	background:#dedede;
	min-height:200px;
	color:#fff;
	text-align:center;
	_background: url(<?php echo $user_images;?>line_up.png) repeat-x 17px 0;
}

	/*   ------------- status messages  ------------- */
	
.error{
	background:pink;
	padding:5px;
}
textarea{
	background:#fff !important;
}

.panelContain{
	background:red !important;
}

#selected_banner{
	border:2px solid orange !important;
}
	/*   ------------- helpers  ------------- */
	
.align_center{text-align:center;}
.align_right{text-align:right;}
.clearboth{clear:both;}
.clearleft{clear:left;}
.clearright{clear:right;}



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