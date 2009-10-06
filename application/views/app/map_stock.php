<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
   <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAATl3ouz6mDWUryXLiBg_56hTaRle_T4ihdSUpL4p8Tw_T5cVK1RQVO3SKhybAmz1Hb7JecRXQiqnSUA&sensor=false" type="text/javascript"></script>
			
			
    <script type="text/javascript">
		
    function initialize() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        map.setUIToDefault();
      }
    }

		
    </script>
  </head>
  <body onload="initialize()" onunload="GUnload()">
    <div id="map_canvas" style="width: 500px; height: 300px"></div>
  </body>
</html>