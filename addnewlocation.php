<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>MigrantWatch : Add a new Location</title>

<? 
   include("db.php"); 
   include("main_includes_thickbox.php");
   include("google_maps_api.php");

   if( !$_SESSION['userid'] ) {
       echo "<div class='notice'>You have to be logged in to access this page</div>";
       exit();
   } 

 
   $lat = 22.97;
   $lng = 77.56;

?>
<style>
.error { color: red; font-size:10px; }
.txt-box { margin-left:auto;margin-right:auto; width:700px; }
/* .txt-box td:first-child { font-weight:bold; text-align:right } */
.address_input { width:400px; }
.a_submit { margin-left:-2px;padding:5px; }
</style>
</head>
<body onload="initialize()" style="">
      <div id="add_loc_box_<? echo $type; ?>">
      	   <div id="newLocationTarget"></div>
	   	 <form action="#" onsubmit="showLocation(); return false;"> 
      <p style="text-align:center"> 
        <b>Search for an address</b>:
        <input type="text" name="q" value="" class="address_input">
	<input class="a_submit" type="submit" name="find" value="Search" /> 
      </p> 
    </form>
       	   	<form id="addNewLocation" name="editres" action="addlocation.php" method="post">
       		<table>
       		<tr>
			<td><div id="map" style="margin-left:auto;margin-right:auto"></div></td>
        	</tr>
       		<tr>
			<td style="">
       	     		    <table class='txt-box'>
	       		    <tr>
				<td><b>New Location Name<b><br><input type="text" style="width:215px" id="loc_name" name="loc_name"></td>
	       			<td><b>City</b><br><input type=text id="city"  name="city" value=""></td>
				<td><b>State</b><br>
				
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
				</td>
			</tr><tr>
				<td><b>Latitude</b><br><input type=text id="lat"  name="lat" value=""></td>
	       			<td><b>Longitude</b><br><input type=text id="lng"   name="lng" value=""></td>
				<td style="font-weight:normal;"><input type="submit" name="add_location" value="Add Location"></td>
	      		   </tr>
				<!--<input type="hidden" id="city"   name="city" value="">-->
               			<input type="hidden" id="country"   name="country" value="">
	       			<input type="hidden" name="zoom" id="zoom" value="">
               			
	       		  <!--  <tr>
				<td colspan=4 style="font-weight:normal;text-align:center"><input type="submit" name="add_location" value="Add Location"></td>
			   </tr>-->
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
      map = new GMap2(document.getElementById("map"),{size:new GSize(700,300)});
      map.setCenter(new GLatLng(<? echo $lat; ?>,<? echo $lng; ?>), 4);
      map.setUIToDefault();
      map.enableDoubleClickZoom();
        geocoder = new GClientGeocoder();
      	GEvent.addListener(map, "click", getAddress);
      
    }
    
    function getAddress(overlay, latlng) {
	map.clearOverlays();
      	if (latlng) {
        address = latlng;
	geocoder.getLocations(latlng, function(addresses) {
          if(addresses.Status.code != 200) {
            alert("reverse geocoder failed to find an address for " + latlng.toUrlValue());
          }
          else {
            address = addresses.Placemark[0];
            point = new GLatLng(latlng.y, latlng.x);
	    var final_address = address.address;
            marker = new GMarker(point);
            map.addOverlay(marker);
            if(final_address) { setLocationValues(final_address); }
              document.getElementById('lat').value = latlng.y;
             document.getElementById('lng').value = latlng.x;
          }
        });
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
        map.setCenter(new GLatLng(place.Point.coordinates[1],place.Point.coordinates[0]), 14);
         document.getElementById('lat').value = place.Point.coordinates[1];
        document.getElementById('lng').value = place.Point.coordinates[0];
        document.getElementById('country').value = place.AddressDetails.Country.CountryName;
        var final_address = place.address;

         if(final_address) {        
            setLocationValues(final_address);
         
          }
      
      }
     
    }

    function showLocation() {
            var address = document.forms[0].q.value;
            geocoder.getLocations(address,addAddressToMap);
            
    }
 
    function setLocationValues(final_address) {
         var a1 = final_address;
         a1 = a1.split(',');
         var arcount = a1.length;
      
        document.getElementById('country').value = a1[arcount - 1];
       var state_name= a1[arcount - 2];
       state_name = state_name.trim();
       $("#state").val(state_name);
       if(a1[arcount-3] != '') {
           if(a1[arcount-3] != 'undefined' ) {
             document.getElementById('city').value = a1[arcount - 3];
           }
       }
       if( a1[arcount - 4] ) {
          if( a1[arcount-4] != 'undefined') {
           document.getElementById('loc_name').value = a1[arcount - 4];
        }
         } else {
           document.getElementById('loc_name').value = '';
        }

        if( a1[arcount - 5] ) {
            document.getElementById('loc_name').value = a1[arcount - 5] + ', ' + document.getElementById('loc_name').value;
        }

        if( a1[arcount - 6] ) {
            document.getElementById('loc_name').value = a1[arcount - 6] + ', ' + document.getElementById('loc_name').value;
        }

        document.getElementById('zoom').value = map.getZoom();
    }

$(document).ready(function() {
   $("#addNewLocation").validate({
    rules: {
       loc_name: { required: true },
       lat: { required: true, latlng: true },
       lng: { required:true, latlng: true },
       state: { required: true},
       city: { required: true }
    },
   messages: {
       loc_name: { required: "<br>please enter a name for the location" },
       lat: { required: "<br>please enter a latitude", latlng: "<br>invalid latitude" },
       lng: { required: "<br>please enter a longitude", latlng: "<br>invalid longitude" },
       state: { required: "<br>please enter a state" },
       city: { required: "<br>please enter a city" }
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
<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery/jquery.form.js"></script>
</body>
</html>
