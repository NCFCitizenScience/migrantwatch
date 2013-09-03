<?

$ll = $_GET['ll'];
$ll = explode(',', $ll);
$lat = $ll[0];
$lng = $ll[1];

include("google_maps_api.php");

?>
<style>
.error { color: red; }
</style>


<body onload="load()" onunload="GUnload()" style="overflow:hidden">
<table style="margin-left:auto:margin-right:auto">
<tr><td><div id="map" style="width:600px; height: 350px"></div></td></tr>
</table>


<script type="text/javascript">
               //<![CDATA[
                       function load(){
				   var map = new GMap2(document.getElementById("map"),  {size:new GSize(600,350)});			    
				   var point = new GLatLng(parseFloat(<?print $lat?>), parseFloat(<?print $lng?>)); 
				   <? print " map.setCenter(point, 3,G_NORMAL_MAP);"; ?>
		                   var marker = new GMarker(point);
		                   map.addOverlay(marker);
				    map.setUIToDefault();
                                   map.setCenter();   
			}
                         //]]>
    </script>
</body>
