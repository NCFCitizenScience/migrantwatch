<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
 Copyright 2008 Google Inc. 
 Licensed under the Apache License, Version 2.0: 
 http://www.apache.org/licenses/LICENSE-2.0 
 -->
<html xmlns="http://www.w3.org/1999/xhtml" 
xmlns:v="urn:schemas-microsoft-com:vml">
  <head>

    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAz2GdKUVPA5WYpd4RF7AQxTWp0bGQXViaMnWtK231_SaDUi5iRQAAX3W7fYMprLUwEDnlzJcSr5Lcw" type="text/javascript"></script>

    <style type="text/css">
    v\:* {
      behavior:url(#default#VML);
    }
    </style>
    <script language="JavaScript" type="text/javascript">

  
 //************** DEFINE THE GPX DATA SOURCE + TITLE FOR THE ROUTE ***************************
  		var gpxUrl = "http://localhost/Muth.gpx";
  		var gpxTitle = "muth, bhadra"
 //*******************************************************************************************
  		var map;
		var marker;
		function showMap(flag) {
			if(flag)
				document.getElementById("googlemap").style.visibility = "visible";
			else
				document.getElementById("googlemap").style.visibility = "hidden";
		}
		
		function centerMap(lat, lng) {
			if (marker != null) {
				map.removeOverlay(marker);
			}
			var point = new GLatLng(lat, lng);
	        map.setCenter(point);
	        marker = new GMarker(point);
	        map.addOverlay(marker);
		}
		
		function resizeMap(width, height) {
			document.getElementById("googlemap").style.width = width;
			document.getElementById("googlemap").style.height = height;
		}
		
		function dimMap() {
			
		}
				
		function gup(name) {
		  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
		  var regexS = "[\\?&]"+name+"=([^&#]*)";
		  var regex = new RegExp(regexS);
		  var results = regex.exec(window.location.href);
		  if (results == null)
		    return "";
		  else
		    return results[1];
		}

		function initMap(width, height) {
			map = new GMap2(document.getElementById("googlemap"));
			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
	        map.setCenter(new GLatLng(37.225334784, -121.987610897), 12);
			// display the map
			resizeMap(width, height);
			showMap(true);			
		}
		
		function getGpxUrl() {
			flexRuutr.setDataSource(gpxUrl, gpxTitle);
		}
		
		function load() {
				   	var gpx = gup("gpx")
		   			gpxUrl = (gpx == "") ? gpxUrl : gpx;
		   			//alert(gpxUrl);
		   			var title = gup("title");
		   			gpxTitle = (title == "") ? gpxTitle : title;
		   			//alert(title);
		
		   if (GBrowserIsCompatible()) {
		   		initMap(800, 800);
		   		try {		   			
				    var request = GXmlHttp.create();
					request.open("GET", gpxUrl, true);
					request.onreadystatechange = function() {
						if (request.readyState == 4) {
							var xmlDoc = null;
							var textLength = parseFloat(request.responseText.length);
							if (textLength > 0) {
								xmlDoc = GXml.parse(request.responseText);
							} else {
								xmlDoc = request.responseXML;
							}
							var points = xmlDoc.documentElement.getElementsByTagName("trkpt");
							var track = new Array();
							for (var i = 0; i < points.length; i++) {
						  		var point = new GPoint(parseFloat(points[i].getAttribute("lon")), parseFloat(points[i].getAttribute("lat")));
				  				track.push(point);
							}
							var p = new GPolyline(track);
							map.addOverlay(p);
							
							var bnd = xmlDoc.documentElement.getElementsByTagName("bounds");
							var sw = new GLatLng(bnd[0].getAttribute("minlat"), bnd[0].getAttribute("minlon"));
						    var ne = new GLatLng(bnd[0].getAttribute("maxlat"), bnd[0].getAttribute("maxlon"));
						    var bounds = new GLatLngBounds(sw, ne);		
						    map.setCenter(bounds.getCenter(), 12);
						} 
					}
				
					// Send the request for data			
					request.send(null);
				} catch (exception) {
					//alert("Unable to use GXmlHttp: " + exception);
				}
		   }
	    }
	    
	    
// -->
</script>
  </head>

  <body onload="load()" onunload="GUnload()" >

    <div id="googlemap" style="width: 800px; height: 700px;">
</div>

  </body>
</html>
