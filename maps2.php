<?

$lat = 12.97;
$lng = 77.56;

?> 
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAz2GdKUVPA5WYpd4RF7AQxTWp0bGQXViaMnWtK231_SaDUi5iRQAAX3W7fYMprLUwEDnlzJcSr5Lcw" type="text/javascript"></script>


<body onload="load()" onunload="GUnload()" style="">
<form   name=editres action="html-echo.php" method="post"> 
<table style=""><tr><td style="width:200px">
<table><tr><td>Name</td><td><input type="text" name="new_loc_name"></td></tr>
<tr><td>Latitude</td><td><input type=text name=lat value=""></td></tr><tr><td>Longitude</td><td><input type=text name=lng value=""></td></tr></table></td><td><table>
<tr><td style="width:500px"><div id="map" style="width: 400px; height: 300px"></div></td></tr>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
               //<![CDATA[
                       function load(){
				   
				   var map = new GMap2(document.getElementById("map"));
			    
				   var point = new GLatLng(<?print $lat?>,<?print $lng?>);

				   map.addControl(new GLargeMapControl());
				   map.addControl(new GMapTypeControl());

				  <? print " map.setCenter(new GLatLng($lat,$lng), 2,G_NORMAL_MAP);"; ?>
				  
		                   var marker = new GMarker(point);
		                   map.addOverlay(marker);
				   //var gx = new GGeoXml("http://base.rtns.org/~ncf/mammals/pa-map.kml");

				   //map.addOverlay(gx);
				   map.enableScrollWheelZoom();
                                   map.setCenter();
				   GEvent.addListener(map, "click", function(marker, point) {
					if (!marker) {
						var clicked_lat = point.lat();
						var clicked_lng = point.lng();
					   	map.clearOverlays();
						map.addOverlay(new GMarker(point));
						<?php 
							$closest_location = 1;
						?>
						document.editres.lng.value = clicked_lng.toString();
						document.editres.lat.value = clicked_lat.toString();
					}
					else {
 	                            		<?php
							if($closest_location) {
		                             print 'marker.openInfoWindowHtml("<b>Actual Co-Ordinates unknown, plotting closest known co-ordinates</b>");';
					      }  else {
					      print 'marker.openInfoWindowHtml("<b>';
						  echo $lat;
						  print '</b>");';
						 }
					 	?>
					}	 
				 });
			}
                         //]]>
    </script>
</body>
