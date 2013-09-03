<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>MigrantWatch : Add a new Location</title>

<? 
   include("db.php"); 
   include("main_includes.php");
   include("google_maps_api.php");

   if( !$_SESSION['userid'] ) {
       echo "<div class='notice'>You have to be logged in to access this page</div>";
   }

 
   $lat = 22.97;
   $lng = 77.56;

?>
<style>
.error { color: red; }
</style>
<script type="text/javascript" src="jquery.validate.js"></script>
<script type="text/javascript" src="jquery.form.js"></script>
</head>
<body onload="initialize()" style="">
      <div id="add_loc_box_<? echo $type; ?>">
      	   <div id="newLocationTarget"></div>
	   	 <form action="#" onsubmit="showLocation(); return false;"> 
      <p style="text-align:center"> 
        <b>Search for an address</b>:
        <input type="text" name="q" value="" class="address_input" style="width:400px"><input style="width:80px;background-color:#333; color:#fff; margin-left:-2px" type="submit" name="find" value="Search" class="submit" /> 
      </p> 
    </form>
       	   	<form id="addNewLocation" name="editres" action="addlocation.php" method="post">
       		<table>
       		<tr>
			<td><div id="map" style="margin-left:auto;margin-right:auto"></div></td>
        	
			<td style="">
       	     		    <table style="maring-left:auto;margin-right:auto">    
	       		    <tr>
				<td style='text-align:right'>New Location Name<br><input type="text" style="width:215px" id="loc_name" name="loc_name"></td>
			    </tr><tr>
	       			<td style='text-align:right'>Latitude<br><input type=text id="lat"  name="lat" value=""></td>
	      		     </tr><tr>
				<td style='text-align:right'>State<!--<td><input type=text id="state" name="state" value=""></td>-->
				<br>
					<SELECT name=state id="state">
					<option value="">Select a State</option>
<?php
					$result = mysql_query("SELECT state_id, state FROM migwatch_states ORDER BY state");
        		        	if($result){
        			            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                                        	if($row['state'] != 'Not In India') {
                                                		 print "<option value=".$row{'state_id'};
                                                		 if ($row{'state_id'} == $state_id)
                                                       		    print " selected ";
                                                		    print ">".$row{'state'}."</option>\n";
        					} else {
                                                		    $other_id = $row['state_id'];
                                                		    $other = $row['state'];
                                        	}
                                	    }
				        }
?>
					</select>
				</td></tr><tr>
	       			<td style='text-align:right'>Longitude<br><input type=text id="lng"   name="lng" value=""></td>
	      		   </tr>
				<input type="hidden" id="city"   name="city" value="">	 
               			<input type="hidden" id="country"   name="country" value="">
	       			<input type="hidden" name="zoom" id="zoom" value="">
               			
	       		   <tr>
				<td colspan=4 style="text-align:center"><input type="submit" name="add_location" value="Add Location"></td>
			   </tr>
	    		   </table>
	    		</td>
         	</tr>
       		</table>
       		</form>
         </div>
<script type="text/javascript">

    var map;
    var geocoder;
    var address;

    String.prototype.trim = function() {
    	// Strip leading and trailing white-space
	return this.replace(/^\s*|\s*$/g, "");
    }

    function initialize() {
      map = new GMap2(document.getElementById("map"),{size:new GSize(500,300)});
      map.setCenter(new GLatLng(<? echo $lat; ?>,<? echo $lng; ?>), 4);
      map.setUIToDefault();
      //map.enableGoogleBar();
      GEvent.addListener(map, "click", getAddress);
      geocoder = new GClientGeocoder();
    }
    
    function getAddress(overlay, latlng) {
      if (latlng != null) {
        address = latlng;
        geocoder.getLocations(latlng, showAddress);
      }
    }

    function addAddressToMap(response) {
      map.clearOverlays();
      if (!response || response.Status.code != 200) {
        alert("Sorry, we were unable to geocode that address");
      } else {
        place = response.Placemark[0];
        point = new GLatLng(place.Point.coordinates[1],
                            place.Point.coordinates[0]);
        marker = new GMarker(point);
        map.addOverlay(marker);
        marker.openInfoWindowHtml(place.address + '<br>' +
          '<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode);
             document.getElementById('lat').value = place.Point.coordinates[1];
        document.getElementById('lng').value = place.Point.coordinates[0];
        document.getElementById('country').value = place.AddressDetails.Country.CountryName;

	 if(place.address) {     
       	var a1 = place.address;
        a1 = a1.split(',');
      }
       var arcount = a1.length;
       //if(a1[arcount - 2] != 'undefined' ) {
            //var state_name = a1[arcount - 2];
	    var state_name= a1[arcount - 2];
	   state_name = state_name.trim();
	   
            $("#state").val(state_name);
	//}
		  if(a1[arcount-3] != '' ) {

           document.getElementById('city').value = a1[arcount - 3];
	   if( a1[arcount - 4] ) {
	     document.getElementById('loc_name').value = a1[arcount - 4];
	   } else { document.getElementById('loc_name').value = ''; }
	   if( a1[arcount - 5] ) {
	     document.getElementById('loc_name').value = a1[arcount - 5] + ', ' + document.getElementById('loc_name').value;
	   } else { document.getElementById('loc_name').value = ''; }

        }

        document.getElementById('zoom').value = map.getZoom();
      }
    }
// showLocation() is called when you click on the Search button
    // in the form.  It geocodes the address entered into the form
    // and adds a marker to the map at that location.
    function showLocation() {
      var address = document.forms[0].q.value;
      geocoder.getLocations(address, addAddressToMap);
    }
 
   // findLocation() is used to enter the sample addresses into the form.
    function findLocation(address) {
      document.forms[0].q.value = address;
 
      showLocation();
    }    

    function showAddress(response) {
      map.clearOverlays();
      if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
      } else {
        place = response.Placemark[0];
        point = new GLatLng(place.Point.coordinates[1],
        place.Point.coordinates[0]);
        document.getElementById('lat').value = place.Point.coordinates[1];
        document.getElementById('lng').value = place.Point.coordinates[0];
        document.getElementById('country').value = place.AddressDetails.Country.CountryName;
       if(place.address) {     
       	var a1 = place.address;
        a1 = a1.split(',');
      }
       var arcount = a1.length;
       //if(a1[arcount - 2] != 'undefined' ) {
            //var state_name = a1[arcount - 2];
	    var state_name= a1[arcount - 2];
	   state_name = state_name.trim();
	   
            $("#state").val(state_name);
	//}
		  if(a1[arcount-3] != '' ) {

           document.getElementById('city').value = a1[arcount - 3];
	   if( a1[arcount - 4] ) {
	     document.getElementById('loc_name').value = a1[arcount - 4];
	   } else { document.getElementById('loc_name').value = ''; }
	   if( a1[arcount - 5] ) {
	     document.getElementById('loc_name').value = a1[arcount - 5] + ', ' + document.getElementById('loc_name').value;
	   } else { document.getElementById('loc_name').value = ''; }


        }

        document.getElementById('zoom').value = map.getZoom();
        marker = new GMarker(point);
        map.addOverlay(marker);
        marker.openInfoWindowHtml(
		  '<b>orig latlng:</b>' + response.name + '<br/>' + 
        '<b>latlng:</b>' + place.Point.coordinates[1] + "," + place.Point.coordinates[0] + '<br>' +
        '<b>Status Code:</b>' + response.Status.code + '<br>' +
        '<b>Status Request:</b>' + response.Status.request + '<br>' +
        '<b>Address:</b>' + place.address + '<br>' +
        '<b>Accuracy:</b>' + place.AddressDetails.Accuracy + '<br>' +
        '<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode);
      }
    }


$(document).ready(function() {
   $("#addNewLocation").validate({
    rules: {
       loc_name: { required: true },
       lat: { required: true, latlng: true },
       lng: { required:true, latlng: true },
       state: { required: true},
    },
   messages: {
       loc_name: { required: "<br>please enter a name for the location" },
       lat: { required: "<br>please enter a latitude", latlng: "<br>invalid latitude" },
       lng: { required: "<br>please enter a longitude", latlng: "<br>invalid longitude" },
       state: { required: "<br>please enter a state" },
    }

	
  });
  
  jQuery.validator.addMethod("latlng", function(value, element) { 
	      return this.optional(element) || /-?\d+\.\d+/.test(value);
   }, "please enter a valid value");

  jQuery.validator.addMethod("lettersonly", function(value, element) {
       return this.optional(element) || /^[a-z]+$/i.test(value);
  }, ""); 

  
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

</body>
</html>
