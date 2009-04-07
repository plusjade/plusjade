/*
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License
*/

body {
	margin: 0;
	padding: 0;
	background: #FFFFFF url(<?php echo $root_images;?>img01.gif) repeat-x;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	color: #8E959B;
}

h1, h2, h3 {
	margin: 0;
	padding: 0;
}

a {
	color: #E55D13;
}

a:hover {
	text-decoration: none;
}

/* Header */

#header {
	width: 856px;
	height: 215px;
	margin: 0 auto;
	padding: 12px 0 0 0;
}

/* Logo */

#logo {
	float: left;
	width: 208px;
	height: 208px;
	padding: 0 15px 0 0;
	background: url(<?php echo $root_images;?>img02.jpg) no-repeat;
}

#logo h1 {
	padding: 120px 0 0 0;
	text-align: center;
	letter-spacing: -3px;
	font-size: 40px;
	font-weight: normal;
}

#logo h2 {
	margin-top: -10px;
	text-align: center;
	font-size: 13px;
}

#logo a {
	text-decoration: none;
	text-transform: lowercase;
}

#logo a:hover {
	color: #990000;
}

/* Menu */

#menu {
	float: left;
	padding: 0 0 0 0;
}

#menu ul {
	height: 55px;
	margin: 0;
	padding: 23px 0;
	list-style: none;
}

#menu li {
	display: inline;
}

#menu a {
	display: block;
	float: left;
	width: 120px;
	height: 43px;
	padding-top: 12px;
	background: url(<?php echo $root_images;?>img03.jpg) no-repeat;
	text-align: center;
	text-decoration: none;
	text-transform: lowercase;
	letter-spacing: -1px;
	font-size: 26px;
}

#menu a:hover, #menu .active a {
	background-image: url(<?php echo $root_images;?>img04.jpg);
	color: #FFFFFF;
}

/* Content */

#snowflakes_content {
	width: 850px;
	margin:0 auto;
}

/* Posts */

#posts {
	float: right;
	width: 620px;
	position:relative;
	top:-80px;
}

.post {
	padding-bottom: 1em;
}

.post .title {
	padding-top: 10px;
	letter-spacing: -.1em;
	font-size: 2em;
	font-weight: normal;
	color: #8E959B;
}

.post .date {
	height: 20px;
	padding: 2px 0 0 10px;
	background: url(<?php echo $root_images;?>img12.gif) no-repeat;
	font-size: 12px;
	font-weight: normal;
}

.post .story {
	padding: 0 20px;
	line-height: 1.6em;
}

.post .meta {
	height: 40px;
	margin: 0 20px;
	padding: 0 0 0 10px;
	background: #EEEDED url(<?php echo $root_images;?>img13.gif) no-repeat;
}

.post .meta span {
	display: none;
}

.post .meta p {
	margin: 0;
	padding: 10px 0 0 0;
}

.post .meta a {
	float: left;
	height: 20px;
	padding: 4px 20px 0 37px;
	text-decoration: none;
	font-size: small;
	font-weight: bold;
	color: #777777;
}

.post .meta a:hover {
	color: #E55D13;
}

.post .category {
	background: url(<?php echo $root_images;?>img14.gif) no-repeat left bottom;
}

.post .comments {
	background: url(<?php echo $root_images;?>img15.gif) no-repeat left bottom;
}

/* Bar */

#bar {
	float: left;
	width: 200px;
}

/* Box Style One */

.boxed1 {
	margin-bottom: 20px;
}

.boxed1 h2 {
	height: 40px;
	margin: 0 0 2px 0;
	padding: 10px 0 0 50px;
	background: #AFB43C url(<?php echo $root_images;?>img06.jpg) no-repeat;
	text-transform: lowercase;
	letter-spacing: -2px;
	font-size: 26px;
	font-weight: normal;
	color: #FFFFFF;
}

.boxed1 ul {
	margin: 0;
	padding: 0;
	background: #E4E9C5 url(<?php echo $root_images;?>img07.gif) no-repeat left bottom;
	list-style: none;
}

.boxed1 li {
	padding: 10px;
	background: url(<?php echo $root_images;?>img08.gif) repeat-x;
	font-size: small;
}

.boxed1 a {
	text-decoration: none;
	font-weight: bold;
	color: #A7B83F;
}

.boxed1 a:hover {
	color: #545C20;
}

.boxed1 .active, .boxed1 .active a {
	background-color: #ABB63D;
	color: #FFFFFF;
}

/* Box Style Two */

.boxed2 {
	margin-bottom: 20px;
}

.boxed2 h2 {
	height: 40px;
	margin: 0 0 2px 0;
	padding: 10px 0 0 50px;
	background: #AFB43C url(<?php echo $root_images;?>img09.jpg) no-repeat;
	text-transform: lowercase;
	letter-spacing: -2px;
	font-size: 26px;
	font-weight: normal;
	color: #FFFFFF;
}

.boxed2 ul {
	margin: 0;
	padding: 0;
	background: #C5DFE9 url(<?php echo $root_images;?>img10.gif) no-repeat left bottom;
	list-style: none;
}

.boxed2 li {
	padding: 10px;
	background: url(<?php echo $root_images;?>img11.gif) repeat-x;
	font-size: small;
}

.boxed2 a {
	text-decoration: none;
	font-weight: bold;
	color: #3F95B8;
}

.boxed2 a:hover {
	color: #204B5C;
}

.boxed2 .active, .boxed2 .active a {
	background-color: #204B5C;
	color: #FFFFFF;
}

/* Box Style Three */

.boxed3 {
	text-align: justify;
	font-size: small;
}

.boxed3 h2 {
	height: 40px;
	margin: 0 0 10px 0;
	border-bottom: 2px solid #CCCCCC;
	text-transform: lowercase;
	letter-spacing: -2px;
	font-size: 26px;
	font-weight: normal;
}

.boxed3 a {
	text-decoration: none;
	color: #8E959B;
}

.boxed3 a:hover {
	color: #E55D13;
}

/* Footer */

#footer {
	clear: both;
	width: 700px;
	height: 60px;
	margin: 2em auto;
	background: #EB850C url(<?php echo $root_images;?>img16.gif) no-repeat;
	font-size: small;
	font-weight: bold;
	color: #FFFFFF;
}

#footer p {
	margin: 0;
}

#footer a {
	color: #FFFFFF;
}

#copy {
	float: left;
	padding: 20px 15px;
}

#feed {
	float: right;
	height: 30px;
	padding: 20px 15px 0 35px;
	background: url(<?php echo $root_images;?>img18.gif) no-repeat 0 15px;
}

