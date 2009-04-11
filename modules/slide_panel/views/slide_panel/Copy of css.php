
/*  --- index  --- */
	
#slide_panel_wrapper #slider {
	width: 100%;
	margin: 0 auto;
	margin-top:20px;
	position: relative;
}
#slide_panel_wrapper .scroll {
	float:left;
	width:600px;
	overflow:auto;
	position:relative; /* fix for IE to respect overflow */
}
#slide_panel_wrapper .scrollContainer div.panel {
	padding:10px;
	width:580px;
	_width: 99%; /* change to 560px if not using JS to remove rh.scroll */
 }
#slide_panel_wrapper li a.selected{
	background:#7ebd40 !important;
	color:#fff !important;
}
#slide_panel_links{
	float:left;
	width:18%;
	margin-right:10px;
}
#slide_panel_links ul{
	list-style: none;
	margin: 0;
	padding: 0;
	text-indent: 1em;
}

#slide_panel_links li a{
	display:block;
	margin:10px 0;
	font-size:1.2em;
	font-weight:bold;
	text-decoration:none;
	color:#669b31;
	background:#ffffcc;
	border-bottom:1px dashed #7ebd40;
	/* green: 7ebd40 ; */
	padding:10px 5px;
}

#slide_panel_links a:hover{
	color:#fff;
	background:#7ebd40;
}