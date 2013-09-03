<?

$lat = 12.97;
$lng = 77.56;

include("google_maps_api.php");

?>

<style>
.error { color: red; }
</style>
  <script type="text/javascript" src="jquery.validate.js"></script>
  <script src="jquery.form.js" language="javascript"></script>

<script>
$(document).ready(function() { 
    // validate signup form on keyup and submit 
    $("#addNewLocation").validate({ 
        rules: { 
            new_loc_name: { required: true },
            
            lat: { 
                required: true, 
                digits: true
            }, 
           
            lng: {
                required: true,
                digits: true
            }
        },
   messages: {
	   new_loc_name: "please enter a location name",
	   lat: {
			required: "please enter a valid latitude",
         digits: "invalid latitude"
       },
	   
	   lng: "please enter a valid	longitude"

    },
    
    });

   $('#addNewLocation').ajaxForm({ 
        // target identifies the element(s) to update with the server response 
        target: '#newLocationTarget', 
 
        // success identifies the function to invoke when the server response 
        // has been received; here we apply a fade-in effect to the new content 
        success: function() { 
            $('#newLocationTarget').fadeIn('slow'); 
        } 
    }); 

  
});
</script>




<body onload="load()" onunload="GUnload()" style="overflow:hidden">
<div id='newLocationTarget'></div>
<form id="addNewLocation" name="editres" action="addlocation.php" method="post">

<table style=""><tr><td style="width:200px">
<table id="loc_form_stuff">

<tr><td>Name</td><td><input type="text" id="new_loc_name" name="loc_name"></td></tr>
<tr><td>Latitude</td><td><input type=text id="lat"  name="lat" value=""></td></tr>
<tr><td>Longitude</td><td><input type=text id="lng"   name=lng value=""></td></tr>
<tr><td><input type="hidden" name="zoom" id="zoom" value=""></td></tr>
<tr><td colspan="2"><input type="submit" name="add_location" value="Add Location"></td></tr>

</table></td><td><table>
<tr><td style="width:600px"><div id="map" style="width: 550px; height: 450px"></div></td></tr>
</table>
</td></tr></table>
</form>

<script type="text/javascript">
               //<![CDATA[
                       function load(){
				   
				   var map = new GMap2(document.getElementById("map"),  {size:new GSize(450,350)});
			    
				   //var point = new GLatLng(<?print $lat?>,<?print $lng?>);
				   var point = new GLatLng(parseFloat(<?print $lat?>), parseFloat(<?print $lng?>)); 

				   <? print " map.setCenter(point, 3,G_NORMAL_MAP);"; ?>
				   
				   				  
		                   var marker = new GMarker(point);
		                   map.addOverlay(marker);
				   
				    map.setUIToDefault();
                                   map.setCenter();
                                   
                                   GEvent.addListener(map, "move", function() {
        			   		checkBounds();
				   });

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
                                                document.editres.zoom.value = map.getZoom();
					}
					else {
 	                            		<?php
							if($closest_location) {
		                             print 'marker.openInfoWindowHtml("<small>1) Hover over the maps<br>2) mark your location</small>");';
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
