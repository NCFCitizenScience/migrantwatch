<?

$lat = 12.97;
$lng = 77.56;

include("google_maps_api.php");
include("main_includes.php");
?>
<script src="http://code.jquery.com/jquery-latest.js"></script>

  <script type="text/javascript" src="http://dev.jquery.com/view/trunk/plugins/validate/jquery.validate.js"></script>
<script>
$(document).ready(function() { 
    // validate signup form on keyup and submit 
    $("#addNewLocation").validate({ 
        rules: { 
            new_loc_name: "required", 
            
            lat: { 
                required: true, 
                digit: true
            }, 
           
            lng: {
                required: true,
                digit: true
            }
        },
        messages: {
	   new_loc_name: "please enter a location name",
	   lat: {
	   	required: "please enter a valid latitude"
           },
	   
	   lng: {
	   	required: "please enter	a valid	longitude"
	   }
        }
    });
});
</script>
<?

if( $_REQUEST['add_location'] ) {


     $loc_name = $_REQUEST['name'];
     $loc_lat = trim($_REQUEST['lat']);
     $loc_lng = trim($_REQUEST['lng']);

     $ch = curl_init();

     $address= $loc_lat . ',' . $loc_lng;
     $api_key = "ABQIAAAAz2GdKU-VPA5WYpd4RF7AQxR8V3_DHC_9Wl-h2nmqtOsQ0ZNDjRQuUJS68TsGkKlJyvPXyyJG3LL7Lg";
     $url = "http://maps.google.com/maps/geo?q=" . urlencode($address) . "&output=csv&key=" . $api_key;
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

     $output = curl_exec($ch);
     $output = str_replace('"','',$output);
     $output = str_replace('State','',$output);
     $location_info = explode(',',$output);
     
     //print_r($location_info);
     $k = count($location_info);
     
     
     	  $loc_country = trim($location_info[$k-1]);
          $loc_state = trim($location_info[$k-2]);
         if(!is_numeric($location_info[$k-3])) {  
	  	$loc_area = trim($location_info[$k-3]);
         }
         
     ?>
     
     <table><tr><td><a href="#" onclick="enableEdit()">edit</a></td></tr>

    
	<form action="#" name="update_loc" method="POST">
		<? if ($loc_area) { ?><tr><td>Area</td><td><input type="text"  id="loc_area" disabled="true" style="background-color:#fff; border:none; color: #000" name="loc_area" value="<? echo $loc_area; ?>"></td></tr><? } ?>
		<tr><td>State</td><td><input type="text" id="loc_state" disabled="true" style="background-color:#fff; border:none; color: #000" name="loc_state" value="<? echo $loc_state; ?>"></td></tr>
        	<tr><td>Country</td><td><input type="text" name="loc_country" style="background-color:#fff;border:none" value="<? echo $loc_country; ?>"></td></tr>
                <tr><td><input type="submit" value="Update location" id="save_location"></td>
<td><input type="button" value="cancel" id="cancel" onclick="disableEdit()"></td></tr>
    	</form>
       <script>

       document.getElementById("save_location").style.visibility = "hidden";
       document.getElementById("cancel").style.visibility = "hidden";
       function enableEdit() {
       document.getElementById("loc_state").disabled = false;
       document.getElementById("loc_state").style.border = "solid 1px";
       document.getElementById("loc_area").disabled = false;
       document.getElementById("loc_area").style.border = "solid 1px";

       document.getElementById("save_location").style.visibility = "visible";
       document.getElementById("cancel").style.visibility = "visible";
       }

       function disableEdit() {
       document.getElementById("loc_state").disabled = true;
       document.getElementById("loc_state").style.border = "none";
       document.getElementById("loc_area").disabled = true;
       document.getElementById("loc_area").style.border = "none";

       document.getElementById("save_location").style.visibility = "hidden";
       document.getElementById("cancel").style.visibility = "hidden";
       }

     </script>

     </table>
     <?
     exit();
}
?> 



<body onload="load()" onunload="GUnload()" style="">

<table style=""><tr><td style="width:200px">
<table>
<form id="addNewLocation" name="editres" action="maps.php" method="post">
<tr><td>Name</td><td><input type="text" id="new_loc_name" name="new_loc_name"></td></tr>
<tr><td>Latitude</td><td><input type=text id="lat"  name=lat value=""></td></tr>
<tr><td>Longitude</td><td><input type=text id="lng"   name=lng value=""></td></tr>
<tr><td><input type="hidden" name="zoom" id="zoom" value=""></td></tr>
<tr><td colspan="2"><input type="submit" name="add_location" value="Add Location"></td></tr>
</form>
</table></td><td><table>
<tr><td style="width:500px"><div id="map" style="width: 450px; height: 350px"></div></td></tr>
</table>
</td></tr></table>
<script>
  $(document).ready(function(){
    $("#addNewLocation").validate();
  });
  </script>

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
