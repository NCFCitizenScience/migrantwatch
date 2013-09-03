<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
<? include("main_includes.php"); ?>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps JavaScript API Example: 		Reverse Geocoder</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAzr2EBOXUKnm_jVnk0OJI7xSosDVG8KKPE1-m51RBrvYughuyMxQ-i1QfUnH94QxWIa6N4U6MouMmBA" 
            type="text/javascript"></script>
    <script type="text/javascript">

    var map;
    var geocoder;
    var address;

    function initialize() {
      map = new GMap2(document.getElementById("map_canvas"));
      map.setCenter(new GLatLng(40.730885,-73.997383), 15);
      map.setUIToDefault();
      GEvent.addListener(map, "click", getAddress);
      geocoder = new GClientGeocoder();
    }
    
    function getAddress(overlay, latlng) {
      if (latlng != null) {
        address = latlng;
        geocoder.getLocations(latlng, showAddress);
      }
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
        document.getElementById('state').value = a1[arcount - 2];
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


    </script>
  </head>

  <body onload="initialize()">

   
    <div id="map_canvas" style="width: 500px; height: 400px"></div>

 <form id="addNewLocation" name="editres" action="#" method="post">

       <table style="width:940px">
       <tr>
       <td style="width:500px">
       	      <table id="loc_form_stuff">
	             <tr><td>Name</td><td><input type="text" id="new_loc_name" name="new_loc_name"></td></tr>
		            <tr><td>Latitude</td><td><input type=text id="lat"  name=lat value=""></td></tr>
			           <tr><td>Longitude</td><td><input type=text id="lng"   name=lng value=""></td></tr>
          <tr><td>Country</td><td><input type=text id="country"   name=country value=""></td></tr>
          <tr><td>Country</td><td><input type=text id="state"   name=state value=""></td></tr>
	         <tr><td><input type="text" name="zoom" id="zoom" value=""></td></tr>
		        <tr><td colspan="2"><input type="submit" name="add_location" value="Add Location"></td></tr>
			    </table>
</form>
<script>

 $(document).ready(function(){



    $("form").submit(function() {
     var latval = document.getElementById('lat').value;
     parent.new_info(latval);
     //alert(latval);
     
 });

  });

</script>
  </body>
</html>
