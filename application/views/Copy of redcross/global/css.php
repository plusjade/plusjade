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
  border-bottom:1px solid #bae0e9;
  min-height:80px;
  padding:10px 0;
  margin-bottom:20px;
}
#header{
  width:80%;
  min-width:800px;
  margin:auto;
  _border:1px solid red;
}
#header_banner{
	float:left;
}
#header_address{
	float:right;
	margin-right:10px;
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
  width:800px;
  margin:auto;
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
	<?=$background['main_menu_wrapper'];?>;
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
  min-height:600px;
  padding:20px 12px 12px 12px;
  border:2px solid #bae0e9;
  border-top:0;
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