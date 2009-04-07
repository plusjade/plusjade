/*
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License
*/

body {
	margin: 0;
	padding: 0;
	background: #372412 url(<?php echo $root_images;?>img01.gif) repeat-x;
	font-size: 13px;
	color: #FFFFFF;
}

body, th, td, input, textarea, select, option {
	font-family: Arial, Helvetica, sans-serif;
}

h1, h2, h3 {
	text-transform: lowercase;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-weight: normal;
	color: #FFFFFF;
}

h1 {
	letter-spacing: -2px;
	font-size: 3em;
}
h2 {
	letter-spacing: -1px;
	font-size: 2em;
}
h3 {font-size: 1em;}
p, ul, ol {
	line-height: 200%;
}
blockquote {padding-left: 1em;}

blockquote p, blockquote ul, blockquote ol {
	line-height: normal;
	font-style: italic;
}
a {color: #FFEA6F;}
a:hover {text-decoration: none;}
img { border: none; }


	/* ------------ admin_panel_wrapper  ------------*/

#admin_panel{
	text-align:center;
	background:orange;
	padding:10px 0;
	border-top:1px solid #333;
	border-bottom:1px solid #333;
	position:absolute;
	width:100%;
}
#admin_panel a{
	color:#fff;
	margin-right:10px;
}

/*  ---------------- primary_client_links ---------------- */

#primary_client_links{
	text-align:right;
	padding:5px;
}

/* Header */

#header {
	width: 830px;
	_border:1px solid red;
	height: 260px;
	margin: 0 auto;
	background: url(<?php echo $root_images;?>img02.jpg) no-repeat;
}
/* Logo */

#logo {
	height: 170px;
	background: url(<?php echo $root_images;?>img07.gif) no-repeat left 65%;
}

#logo a {
	text-decoration: none;
	color: #372412;
}

#header_banner{
	float:left;
	padding-top:10px;
}
#header_address{
	float:right;
	padding-top:10px;
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
}

/* Menu */

#menu {
	width: 830px;
	height: 70px;
	background: url(<?php echo $root_images;?>img03.jpg) no-repeat;
}

#menu ul {
	margin: 0;
	padding: 0;
	list-style: none;
}

#menu li {
	display: inline;
}

#menu a {
	display: block;
	float: left;
	width: 120px;
	height: 73px;
	padding-top: 35px;
	text-transform: lowercase;
	text-decoration: none;
	text-align: center;
	letter-spacing: -1px;
	font-size: 24px;
	color: #FFFFFF;
}

#menu a:hover {
	background: url(<?php echo $root_images;?>img09.jpg) no-repeat;
	color: #FFFFFF;
}

#menu .active a {
	background: url(<?php echo $root_images;?>img09.jpg) no-repeat;
	color: #372412;
}

/* Page */

#page {
	width: 830px;
	margin: 0 auto;
	padding: 5px 0;
	_border:1px solid green;
	overflow:auto;
}

/* Content */

#content {
	float: left;
	width: 99%;
	_border:1px solid red;
}

.post {
	padding: 0 0 20px 0;
}

.title {
	margin: 0;
	border-bottom: 2px solid #4A3903;
}

.byline {
	margin: 0;
}

.meta {
	border-top: 1px solid #4A3903;
	text-align: right;
	color: #646464;
}

.meta a {
	padding-left: 15px;
	background: url(<?php echo $root_images;?>img06.gif) no-repeat left center;
	font-weight: bold;
}

	/*  ---------------- secondary_wrapper ---------------- */
	
#secondary_wrapper{
  clear:both;
  text-align:center;
  padding:10px 20px;
  margin:0 20px;
}


/* Sidebar */

#sidebar {
	float: right;
	width: 200px;
	_border:1px solid red;
}

#sidebar ul {
	margin: 0;
	padding: 0;
	list-style: none;
}

#sidebar li {
}

#sidebar li ul {
	padding: 15px 0;
}

#sidebar li li {
	padding-left: 30px;
	border-bottom: 1px dotted #4A3903;
	background: url(<?php echo $root_images;?>img06.gif) no-repeat 15px 50%;
}

#sidebar h2 {
	margin: 0;
	padding: 20px 0 2px 30px;
	background: url(<?php echo $root_images;?>img05.gif) no-repeat left bottom;
	border-bottom: 2px solid #4A3903;
}

#sidebar a {
	text-decoration: none;
}

#sidebar a:hover {
	text-decoration: underline;
}

/* Footer */

#footer {
	clear: both;
	padding: 20px 0;
	background: #FFEA6F;
	border-top: 3px solid #E8AD35;
	text-align: center;
	font-size: smaller;
	color: #E8AD35;
}

#footer a {
	color: #C28C21;
}
