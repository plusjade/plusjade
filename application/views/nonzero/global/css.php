/*

Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Nonzero
Version    : 1.0
Released   : 20080425

*/
/* Basic Stuff */

*
{
margin: 0em;
padding: 0em;
}

body
{
background-color: #fff;
color: #585858;
font-size: 9pt;
font-family: "trebuchet ms", helvetica, sans-serif;
}

h1,h2,h3,h4,h5,h6
{
font-weight: normal;
letter-spacing: -1px;
}

h3,h4,h5,h6
{
color: #66000F;
}

h1 span
{
font-weight: bold;
}

h3 span
{
font-weight: bold;
}

h4 span
{
font-weight: bold;
}

br.clear
{
clear: both;
}


img.floatTL
{
float: left;
margin-right: 1.5em;
margin-bottom: 1.5em;
margin-top: 0.5em;
}

a
{
text-decoration: underline;
color: #D90000;
}

a:hover
{
text-decoration: none;
}

ul.links
{
list-style: none;
}

ul.links li
{
line-height: 2em;
}

ul.links li.first
{
}

p
{
line-height: 1.8em;
}

/* header_wrapper */

#header_wrapper
{
width:100%;
height:122px;
background: #440000 url('<?php echo $root_images;?>n1.gif') repeat-x;
}

#header_inner
{
position: relative;
width: 950px;
height:122px;
margin: 0 auto;
}

/* Logo */

#logo
{
position: absolute;
bottom: 0.6em;
}

#logo h1 a
{
display: inline;
text-decoration:none;
color: #fff;
font-size: 1.4em;
}

#logo h2
{
display: inline;
padding-left: 0.5em;
color: #E5CCD0;
font-size: 1.0em;
}

/* Menu */

#menu
{
position: absolute;
right: 0em;
bottom: 0em;
}

#menu ul
{
list-style: none;
}

#menu li
{
float: left;
}

#menu li a
{
margin-left: 0.5em;
display: block;
padding: 1.1em 1.4em 1.0em 1.4em;
background: #fff url('<?php echo $root_images;?>n4.gif') repeat-x;
border: solid 1px #fff;
color: #616161;
font-weight: bold;
font-size: 1.0em;
text-transform: lowercase;
text-decoration: none;
}

#menu li a.selected
{
background: #CA2F2F url('<?php echo $root_images;?>n3.gif') repeat-x;
color: #fff;
border: solid 1px #A94B4B;
}

/* Main */

#main
{
background: #fff url('<?php echo $root_images;?>n2.gif') 0px 1px repeat-x;
}

#main_inner p
{
text-align: justify;
margin-bottom: 2.0em;
}

#main_inner ul
{
margin-bottom: 2.0em;
}

#main_inner
{
position: relative;
width: 950px;
margin: 0 auto;
padding-top: 3.5em;
}

#main_inner h3,h4
{
border-bottom: dotted 1px #E1E1E1;
position: relative;
}

#main_inner h3
{
font-size: 2.1em;
padding-bottom: 0.1em;
margin-bottom: 0.8em;
}

#main_inner h4
{
font-size: 1.2em;
padding-bottom: 0.175em;
margin-bottom: 1.4em;
margin-top: 0.95em;
}

#main_inner .post
{
position: relative;
}

#main_inner .post h3
{
position: relative;
font-size: 1.7em;
padding-bottom: 1.2em;
}

#main_inner .post ul.post_info
{
list-style: none;
position: absolute;
top: 3em;
font-size: 0.8em;
}

#main_inner .post ul.post_info li
{
background-position: 0em 0.2em;
background-repeat: no-repeat;
display: inline;
padding-left: 18px;
}

#main_inner .post ul.post_info li.date
{
background-image: url('<?php echo $root_images;?>n5.gif');
}

#main_inner .post ul.post_info li.comments
{
background-image: url('<?php echo $root_images;?>n6.gif');
margin-left: 1.1em;
}

/* Footer */

#footer_wrapper
{
width: 950px;
margin: 0 auto;
text-align: center;
clear: both;
border-top: dotted 1px #E1E1E1;
margin-top: 1.0em;
margin-bottom: 1.0em;
padding-top: 1.0em;
text-transform: lowercase;
}

/* Search */

input.button
{
background: #CA2F2F url('<?php echo $root_images;?>n3.gif') repeat-x;
color: #fff;
border: solid 1px #A94B4B;
font-weight: bold;
text-transform: lowercase;
font-size: 0.8em;
height: 2.0em;
}

input.text
{
border: solid 1px #F1F1F1;
font-size: 1.0em;
padding: 0.25em 0.25em 0.25em 0.25em;
}

#search
{
position: relative;
width: 100%;
margin-bottom: 2.0em;
}

#search input.text
{
position: absolute;
top: 0em;
left: 0em;
width: 9.5em;
}

#search input.button
{
position: absolute;
top: 0em;
right: 0em;
min-width: 2.0em;
max-width: 2.5em;
}

/* LAYOUT - 3 COLUMNS */

	/* Primary content */
	
	#primaryContent_3columns
	{
	position: relative;
	margin-right: 34em;
	}
	
	#columnA_3columns
	{
	position: relative;
	float: left;
	width: 100%;
	margin-right: -34em;
	padding-right: 2em;
	}
	
	/* Secondary Content */
	
	#secondaryContent_3columns
	{
	float: right;
	}
	
	#columnB_3columns
	{
	width: 13.0em;
	float: left;
	padding: 0em 2em 0.5em 2em;
	border-left: dotted 1px #E1E1E1;
	}
	
	#columnC_3columns
	{
	width: 13.0em;
	float: left;
	padding: 0em 0em 0.5em 2em;
	border-left: dotted 1px #E1E1E1;
	}
	
/* LAYOUT - 2 COLUMNS */

	/* Primary content */
	
	#primaryContent_2columns
	{
	position: relative;
	margin-right: 17em;
	}
	
	#columnA_2columns
	{
	position: relative;
	float: left;
	width: 100%;
	margin-right: -17em;
	padding-right: 2em;
	}
	
	/* Secondary Content */
	
	#secondaryContent_2columns
	{
	float: right;
	}
	
	#columnC_2columns
	{
	width: 13.0em;
	float: left;
	padding: 0em 0em 0.5em 2em;
	border-left: dotted 1px #E1E1E1;
	}

/* LAYOUT - COLUMNLESS */

	/* Primary content */
	
	#primaryContent_columnless
	{
	position: relative;
	}
	
	#columnA_columnless
	{
	position: relative;
	width: 100%;
	}

	