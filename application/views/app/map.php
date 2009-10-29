<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAATl3ouz6mDWUryXLiBg_56hTaRle_T4ihdSUpL4p8Tw_T5cVK1RQVO3SKhybAmz1Hb7JecRXQiqnSUA&sensor=false" type="text/javascript"></script>
	<style type="text/css">
		body {padding:0;margin:0; width:<?php echo $width?>px; font-size:10px; font-family:verdana, serif;}
		#map_canvas{border:1px solid #333;}
		form {text-align:left;}
		form div{margin: 3px 0;}
		#directions {width:<?php echo $width?>px; font-size:10px;}
	</style>
  </head>
  <body onload="initialize()" onunload="GUnload()">
    <div id="map_canvas" style="width: <?php echo $width?>px; height: <?php echo $height?>px"></div>
	
	<form action="#" id="direction_form" onsubmit="overlayDirections();return false;" method="post">
		<b>Get Directions To Here</b>
		<div>
			Street &amp; City
			<br/><input type="text" id="street" name="street" style="width:200px">
		</div>
		<div>
			State <input type="text" id="state" name="state" size="2" maxlength="2">
			Zip <input type="text" id="zip" name="zip" size="5" maxlength="5">
			<br/>
			<button type="submit">Get Directions</button>			
		</div>
	</form>
	<a href="#" onClick="toggle_window();return false;">Get Directions</a>
      
	  
	<div id="directions"></div>

<script type="text/javascript">
function initialize() {
  if (GBrowserIsCompatible()) {
  
	//setup elements
	map		= new GMap2(document.getElementById("map_canvas"));
	gdir = new GDirections(map, document.getElementById("directions"));
	var point	= new GLatLng(<?php echo $coordinates?>);
		
	map.setCenter(point, 13); // default zoom level
	map.addOverlay(new GMarker(point));
	
	map.openInfoWindow(map.getCenter(), document.getElementById("direction_form"));
	map.setUIToDefault();
	
	// map errors
	GEvent.addListener(gdir, "error", function() {
	   if (gdir.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
		 alert("No corresponding geographic location could be found for one of the specified addresses. This may be due to the fact that the address is relatively new, or it may be incorrect.\nError code: " + gdir.getStatus().code);
	   else if (gdir.getStatus().code == G_GEO_SERVER_ERROR)
		 alert("A geocoding or directions request could not be successfully processed, yet the exact reason for the failure is not known.\n Error code: " + gdir.getStatus().code);
	   else if (gdir.getStatus().code == G_GEO_MISSING_QUERY)
		 alert("The HTTP q parameter was either missing or had no value. For geocoder requests, this means that an empty address was specified as input. For directions requests, this means that no query was specified in the input.\n Error code: " + gdir.getStatus().code);
	   else if (gdir.getStatus().code == G_GEO_BAD_KEY)
		 alert("The given key is either invalid or does not match the domain for which it was given. \n Error code: " + gdir.getStatus().code);
	   else if (gdir.getStatus().code == G_GEO_BAD_REQUEST)
		 alert("A directions request could not be successfully parsed.\n Error code: " + gdir.getStatus().code);
	   else alert("An unknown error occurred.");	
	});
	// directions error
	GEvent.addListener(gdir, "error", function() {
		 alert('The given address could not be calculated.');	
	});	
	
	GEvent.addListener(gdir, "load", function() {
		//console.log(gdir.getDistance());
		//console.log(gdir.getDuration());
		
	});

	// directions error
	GEvent.addListener(map, "closeclick", function() {
		 alert('The given address could not be calculated.');	
	});		
  }
}

	function toggle_window()
	{
		if(map.getInfoWindow().isHidden())
			map.getInfoWindow().show();
		else
			map.getInfoWindow().hide();		

	}
	
	/*
	**
	* Looks up the directions, overlays route on map,
	* and prints turn-by-turn to #directions.
	*/

	function overlayDirections()
	{
		toAddress = "<?php echo $address?>"; 
		
		fromAddress =
		  document.getElementById("street").value
		  + " " + document.getElementById("state").value
		  + " " + document.getElementById("zip").value;
		gdir.clear();	
		gdir.load("from: " + fromAddress + " to: " + toAddress);
		return false;
	}

</script>	
  </body>
</html>