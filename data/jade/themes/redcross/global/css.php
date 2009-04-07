html {
	font-size: 80%;
	
}
html, body {
	margin:0;
	padding:0;
	font-family:verdana, arial;

}

	/* ------------ reset base styles  ------------*/
	
a {border:0;}
img {border:0;}
h1,h2,h3,h4,h5,h6{font-size:1em; padding:0; margin:0; margin-bottom:10px;}
h1{font-size:1.6em; color:#0c7ced;}
h2{font-size:1.4em; color:#7c78d3;}
h3{font-size:1.2em;}
h4{font-size:1.1em;}
h5{font-size:1em;}
/* ul , ol, dl{line-height:1.4em;}  */
dt{font-weight:bold;}

	/* ------------ header_wrapper  ------------*/
	
#header_wrapper{
	<?=$background['html'];?>;
	blah-<?=$background['header_wrapper'];?>;
	border-bottom:2px solid #d4e4f7;
	padding:0 15px;
	text-align:center;
}
#header{
	margin:0 auto;
	width:850px;
	overflow:hidden;
}

#headline_wrapper{
	text-align:center; 
	padding:5px; 
	width:400px;
	margin:0 auto;
	margin-top:20px;
}
#headline{
	font-size:1.8em; 
	color:#0c7ced; 
	padding:5px;
}

#header_banner{
	float:left;
}
#header_address{
	float:right;
	width:180px;
	padding:10px;
	text-align:right;
}
#header_address b{
	font-size:1.2em;
	color:#444;
}
#header_address p{
	font-style:italic;
	color:#444;
	font-size:0.9em;
}
	/* ------------ body_wrapper  ------------*/
	
#body_wrapper{
  width:850px;
  margin:0 auto;
  padding:0;
}	

	/* -----------  main_menu_wrapper ------------ */

#main_menu_wrapper{
	clear:both;
	margin-top:40px;
	min-width:850px;
}
#main_menu_wrapper ul {
	text-align:center;
	width:850px;
	list-style:none;
	margin:0 auto;
	padding:8px 0; 
	overflow:hidden;
}
#main_menu_wrapper ul li {
	display:inline;
	margin-left:10px;
}
#main_menu_wrapper li a {
	padding:8px 13px; 
	background: #7ebd40 url(/images/admin/green_bg.png) repeat-x bottom left;
	color:#fff;
	font-size:1.2em;
	text-decoration:none;
	font-family:verdana;
}
#main_menu_wrapper li a:hover {
	background:#fff;
	text-decoration:underline;
	color:#000;	
}
#main_menu_wrapper li a.selected {
	background:#d52020 !important;
	color:#fff;
	text-decoration: underline;
}

	/*  ---------------- content_wrapper ---------------- */
	
#content_wrapper{
	<?=$background['content_wrapper'];?>;
	min-height:420px;
	padding:20px;
	min-height:600px;
}
#primary_tagline{
	_font-weight:bold;
	_color:#0c7ced;
	_font-size:1.6em;
	_text-align:center;
}
#primary_left_panel{
	padding:0 10px;
	width:46%;
	blah_border:1px solid red;
}
#primary_right_panel{
  float:right;
  width:50%;
  blah_border:1px solid red;
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

/* --- footer wrapper --- */

#footer_wrapper{
	border-top:3px solid #d4e4f7;
	height:200px;
	<?=$background['html'];?>;
}
	/*  ---------------- extras ---------------- */
	

.image_placeholder{
margin:10px 15px;
height:300px;
background:#e4f3f6;
border:1px solid #ccc;
}

