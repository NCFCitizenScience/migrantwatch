<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<!--
 Copyright 2008 Google Inc. 
 Licensed under the Apache License, Version 2.0: 
 http://www.apache.org/licenses/LICENSE-2.0 
 --> 
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"> 
  <head> 
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
    <title>Google Maps API Example: Reverse Geocoding</title> 
    <? include("google_maps_api.php"); ?>
   
    <script type="text/javascript"> 
 
    var map = null;
    var geocoder = null;
 
    function initialize() {
      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(new GLatLng(22.97, 77.56), 13);
        map.addControl(new GSmallZoomControl());
        geocoder = new GClientGeocoder();
        GEvent.addListener(map, "click", clicked);
        map.openInfoWindow(map.getCenter(), "Click the map!");
      }
    }
 
    function clicked(overlay, latlng) {
      if (latlng) {
        geocoder.getLocations(latlng, function(addresses) {
          if(addresses.Status.code != 200) {
            alert("reverse geocoder failed to find an address for " + latlng.toUrlValue());
          }
          else {
            address = addresses.Placemark[0];
            point = new GLatLng(address.Point.coordinates[1],
                            address.Point.coordinates[0]);
        marker = new GMarker(point);
        map.addOverlay(marker);

            var myHtml = address.address;
            map.openInfoWindow(latlng, myHtml);
          }
        });
      }
    }
    </script> 
  </head> 
  <body onload="initialize()" onunload="GUnload()"> 
      <div id="map_canvas" style="width: 500px; height: 300px"></div> 
    </form> 
  </body> 
</html> 
