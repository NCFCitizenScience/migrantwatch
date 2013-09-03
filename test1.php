<? include("db.php"); ?>
<!-- Hello! I am Marc Grabanski, you can find me at -> http://marcgrabanski.com -->
<html>
	<head>
		<title>Google Maps and jQuery</title>
		<!-- http://code.google.com/apis/ajaxlibs/documentation/ -->

		<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAZBe7uHI90ESk2XAmWRL3RxR6u04U0tImA3bfwZ3-HKdEno7z2xRk2YE6OkudtBX5qy0vLrgbf1DUCg"></script>
		<script type="text/javascript">
		google.load("jquery", '1.3');
		google.load("maps", "2.x");
		</script>
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				var map = new GMap2($("#map").get(0));
				var burnsvilleMN = new GLatLng(20.920397,78.222656);
				map.setCenter(burnsvilleMN, 5);
			
<?php
$limit = 'LIMIT 0,1';
$sql = "SELECT s.common_name, l.latitude,l.longitude, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
$sql.= "l1.obs_type, l1.id, l1.deleted, l1.number, u.user_name FROM migwatch_l1 l1 ";
$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
$sql.= "INNER JOIN migwatch_users u ON l1.user_id = u.user_id ";
$sql.= "WHERE l1.deleted = '0' AND valid = '1'";
//$sort = ($orderBy == 'sighting_date') ? $sort : $sortTy;
$sort = ' sighting_date DESC,number';
$sql.= $srt = " ORDER BY $orderBy $sort $limit";
$result = mysql_query($sql,$connect);
if($result && (mysql_num_rows($result) > 0)) {

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  
   array_push($points, array('name' => $row['common_name'], 'lat' => $row['latitude'], 'lng' => $row['longitude']));
   echo json_encode(array("Locations" => $points));
    exit;
}}


?>


   
      if (json.Locations.length > 0) {
   
          for (i=0; i<json.Locations.length; i++) {
   
              var location = json.Locations[i];
   
              addLocation(location);
   
          }
   
          
  
      }
				$(markers).each(function(i,marker){
					$("<li />")
						.html("Point "+i)
						.click(function(){
							displayPoint(marker, i);
						})
						.appendTo("#list");
					
					GEvent.addListener(marker, "click", function(){
						displayPoint(marker, i);
					});
				});
				
				$("#message").appendTo(map.getPane(G_MAP_FLOAT_SHADOW_PANE));
				
				function displayPoint(marker, index){
					$("#message").hide();
					
					var moveEnd = GEvent.addListener(map, "moveend", function(){
						var markerOffset = map.fromLatLngToDivPixel(marker.getLatLng());
						$("#message")
							.fadeIn()
							.css({ top:markerOffset.y, left:markerOffset.x });
					
						GEvent.removeListener(moveEnd);
					});
					map.panTo(marker.getLatLng());
				}
			});
		</script>
		<style type="text/css" media="screen">
			#map { float:left; width:920px; height:400px; }
			#message { position:absolute; padding:10px; background:#555; color:#fff; width:75px; }
			#list { float:left; width:200px; background:#eee; list-style:none; padding:0; }
			#list li { padding:10px; }
			#list li:hover { background:#555; color:#fff; cursor:pointer; cursor:hand; }
		</style>
	</head>
	<body>
		<div id="map"></div>
		<ul id="list"></ul>
		<div id="message" style="display:none;">
			Test text.
		</div>
	</body>
</html>
