/*
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License
*/

* {
	margin: 0;
	padding: 0;
}

body {
	background: #A9B3BB url(<?php echo $root_images;?>img01.gif) repeat-x;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #555555;
}

h1, h2, h3 {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
}

h1 {
	font-size: 3em;
}

h2 {
	font-size: 2em;
}

h3 {
	font-size: 1em;
}

p, ul, ol {
	margin-bottom: 1em;
	line-height: 140%;
}

a {
	color: #333333;
}

a:hover {
	text-decoration: none;
	color: #FC3E1A;
}

img.left {
	float: left;
	margin: 3px 20px 0 0;
	border: 3px solid #848C8F;
}

img.right {
	float: right;
	margin: 3px 0 0 20px;
	border: 3px solid #848C8F;
}

hr {
	display: none;
}

/* Header */

#logo {
	width: 670px;
	height: 130px;
	margin: 0 auto;
	padding: 70px 0 0 100px;
	background: url(<?php echo $root_images;?>img02.gif) no-repeat 0px 50px;
}

#logo h1, #logo p {
	color: #FFFFFF;
}

#logo h1 {
}

#logo p {
	margin: 0;
	padding: 0 0 0 2px;
	line-height: normal;
}

#logo a {
	text-decoration: none;
	color: #FFFFFF;
}

/* Menu */

#menu {
	width: 770px;
	height: 70px;
	margin: 0 auto;
}

#menu ul {
	margin: 0;
	padding: 0;
	list-style: none;
	line-height: normal;
}

#menu li {
	display: inline;
}

#menu a {
	display: block;
	float: left;
	height: 43px;
	padding: 12px 30px 0 30px;
	text-decoration: none;
	font: bold 1.2em "Trebuchet MS", Arial, Helvetica, sans-serif;
	color: #333333;
}

#menu a:hover {
	text-decoration: underline;
}

#menu .current_page_item a {
	background: url(<?php echo $root_images;?>img03.gif) no-repeat center bottom;
}

/* Wide Post */

.wide-post {
	width: 770px;
	margin: 0 auto;
	background: #D4D9DD url(<?php echo $root_images;?>img04.gif) repeat-y;
}

.wide-post .title {
	padding: 30px 30px 20px 30px;
	background: url(<?php echo $root_images;?>img05.gif) no-repeat;
	font-size: 2em;
}

.wide-post .title a {
	text-decoration: none;
	border-bottom: 1px solid #B4BBBE;
	color: #333333;
}

.wide-post .title a:hover {
	border: none;
}

.wide-post .entry {
	padding: 0 30px;
}

.wide-post .bottom {
	height: 20px;
	background: url(<?php echo $root_images;?>img06.gif) no-repeat left bottom;
}

.wide-post .links {
	clear: both;
	margin: 0;
	padding: 10px;
	background: #CDD4D7;
	border-top: 1px solid #B4BBBE;
	font-size: .9em;
}

.wide-post .links a {
	text-decoration: none;
}

.wide-post .links .date {
	padding-left: 20px;
	background: url(<?php echo $root_images;?>img08.gif) no-repeat left center;
}

.wide-post .links .author {
	padding-left: 20px;
	background: url(<?php echo $root_images;?>img09.gif) no-repeat left center;
}

.wide-post .links .comments {
	padding-left: 20px;
	background: url(<?php echo $root_images;?>img10.gif) no-repeat left center;
}

.wide-post .links .feeds {
	padding-left: 10px;
	background: url(<?php echo $root_images;?>img11.gif) no-repeat left center;
}

/* Two Columns */

.two-columns {
	width: 770px;
	margin: 0 auto;
	padding: 20px 0;
}

.two-columns .column-one {
	float: left;
	width: 370px;
}

.two-columns .column-two {
	float: right;
	width: 370px;
}

.two-columns .title {
	height: 28px;
	padding: 7px 0 0 10px;
	background: #DB8603 url(<?php echo $root_images;?>img12.gif) no-repeat;
	font-size: 1.4em;
	color: #333333;
}

.two-columns .entry {
	background: url(<?php echo $root_images;?>img13.gif) repeat-x;
}

/* Recent Posts */

#recent-posts {
}

#recent-posts ul {
	margin: 0;
	padding: 20px;
	list-style: none;
}

#recent-posts li {
	padding: 5px 15px;
	background: url(<?php echo $root_images;?>img14.gif) no-repeat left center;
	border-bottom: 1px solid #949EA4;
}

/* Recent Comments */

#recent-comments {
}

#recent-comments ul {
	margin: 0;
	padding: 20px;
	list-style: none;
}

#recent-comments li {
	padding: 5px 15px 5px 17px;
	background: url(<?php echo $root_images;?>img15.gif) no-repeat left center;
	border-bottom: 1px solid #949EA4;
}

/* Submenu */

#submenu {
	clear: both;
	padding: 20px 0;
	background: #535657 url(<?php echo $root_images;?>img16.gif) repeat-x left bottom;
	color: #A9B6BB;
}

#submenu ul {
	width: 770px;
	margin: 0 auto;
	padding: 0;
	list-style: none;
	line-height: normal;
}

#submenu li {
	display: block;
	float: left;
	width: 180px;
	padding: 0 12px 0 0;
}

#submenu li ul {
	width: auto;
	margin: 0;
}

#submenu li li {
	display: list-item;
	float: none;
	width: auto;
	padding: 5px 0 5px 10px;
	background: url(<?php echo $root_images;?>img17.gif) no-repeat left center;
	border-bottom: 1px solid #424546;
}

#submenu h2 {
	margin: 0 0 5px 0;
	border-bottom: 2px solid #424546;
	font-size: 1.2em;
}

#submenu a {
	text-decoration: none;
	color: #A9B6BB;
}

#submenu a:hover {
	text-decoration: underline;
}

/* Footer */

#footer {
	padding: 40px 0;
	background: #3F4244 url(<?php echo $root_images;?>img18.gif) repeat-x;
}

#footer p {
	text-align: center;
	font-size: smaller;
	color: #666666;
}

#footer a {
	color: #666666;
}