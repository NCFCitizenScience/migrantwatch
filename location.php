<? include("checklogin.php");
   $location_id = $_GET['id'];

   if(!is_numeric($location_id)) { exit(); }

   $page_title="MigrantWatch: Locations";
   include("main_includes.php");
?>
<body onload="load()" onunload="GUnload()">
<? 
   include("header.php");
?>
<div class="container first_image">
      <div id='tab-set'>   
             <ul class='tabs'>
                 <li><a href='#x' class='selected'>location&nbsp;info</a></li>
             </ul>
         </div>
<?
$sql1= "SELECT  l.location_id,l.latitude,l.longitude,l.location_name,l.city,l.district, s.state  FROM migwatch_locations l INNER JOIN migwatch_states s ON s.state_id = l.state_id ";
$sql2 .=" WHERE l.location_id='$location_id'";
$query = $sql1 . " " . $sql2;
$result = mysql_query($query);
if(mysql_num_rows($result) > 0) {
  $data = mysql_fetch_assoc($result); 
  $location_name = addslashes($data['location_name']);
  $location_details = $data['city'] . ", " .  $data['state'];
  $lat= $data['latitude'];
  $lng= $data['longitude'];
  $location_geocodes = $data['latitude'] . "," . $data['longitude'];
  $user_name_loc = $data['user_name'];  
}

$result2 = mysql_query($sql1);
?>
<table class='page_layout'>
<tr>
	<td>
		<h3><? echo $location_name; ?>, <? echo $location_details; ?></h3>
        </td>
</tr>
<tr>	<td>
		<? if($location_geocodes != ',') { 
?>		   
		   <div id="map"></div>
		   
<? } ?>
	</td>
</tr>
<tr><td><h4>Migratory birds at <? echo $location_name ; ?></h4></td></tr>
<tr>
<td>
<table> 
<?

$sql_migbirds ="SELECT  s.species_id,s.common_name,l1.sighting_date,l1.id,l.location_id  FROM migwatch_locations as l, migwatch_species as s, migwatch_l1 as l1 where l1.location_id=l.location_id and l1.species_id = s.species_id AND l.location_id='$location_id'";

$result_bird_list = mysql_query($sql_migbirds . " GROUP by s.species_id");

?>
<table id='table1' class='tablesorter'>
<thead>
	<tr>
		<th>Species</th>
<?
		$currentMonth = date('m');
		$currentYear  = date('Y');

 		if ($currentMonth > 6) {
                        $endSeason = $currentYear;
                } else {
                        $endSeason = $currentYear - 1;
                }
		for ($i = 2007;$i <= $endSeason; $i++) {
 		 $fromTo = "$i-".($i+1);
 		 echo "<th>" . $fromTo . "</th>";
		}
?>
	</tr>
	</thead>
	<tbody>
<?
	while($data_bird_list = mysql_fetch_assoc($result_bird_list)) {
     	       $list_species_id[] = $data_bird_list['species_id'];
        }

for ($i = 2007;$i <= $endSeason; $i++) {
 $fromTo = "$i-".($i+1);
 $seasonArr = explode('-',$fromTo);
 $seasonStart = $seasonArr[0];
 $seasonEnd = $seasonArr[1];
 $sql_season = " AND l1.sighting_date BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.sighting_date <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";

 foreach( $list_species_id as $list_s) {
    $sql_s =" AND s.species_id='$list_s'";
    $sql = $sql_migbirds . " " . $sql_season . " " . $sql_s;
    $result_migbirds = mysql_query($sql);
    $num_migbirds = mysql_num_rows($result_migbirds);
    if( $num_migbirds > 0 ) {
    	$list[$list_s][] = "yes";
    } else {
        $list[$list_s][] = "no";
    }
  }
}

foreach( $list_species_id as $list_s) {
$sql="select common_name,scientific_name from migwatch_species where species_id='$list_s'";
$query=mysql_query($sql);
$result = mysql_fetch_assoc($query);
$common_name = $result['common_name'];
echo "<tr><td>" . $common_name . " (<em>" . $result['scientific_name'] . "</em>)</td>";
for( $i =0; $i < count($list[$list_s]); $i++) {
  echo "<td>" . $list[$list_s][$i] . "</td>";
}
echo "</tr>";
}
?>
</tbody>
</table>

<div id="pager" style="width:200px" >
     <form name="" action="" method="post">
          <table>
          <tr>
                <td><img src='pager/icons/first.png' class='first'/></td>
                <td><img src='pager/icons/prev.png' class='prev'/></td>
                <td><input type='text' size='8' class='pagedisplay'/></td>
                <td><img src='pager/icons/next.png' class='next'/></td>
                <td><img src='pager/icons/last.png' class='last'/></td>
                <td>
                        <select class='pagesize' style='width:50px'>
                        <option selected='selected'  value='10'>10</option>
                        <option value='20'>20</option>
                        <option value='30'>30</option>
                        <option  value='40'>40</option>
                        </select>
                </td>
          </tr>
          </table>
     </form>
</div>
</td>
</tr>
<tr>
	<td style='margin-top:-30px'><h4>Other links</h4></td>
</tr>
<tr><td><a href='data.php?location=<? echo $location_id; ?>' title='Sightings from <? echo $location_name; ?>'>Sightings from <? echo $location_name; ?></a></td></tr>
<tr><td><a href='gallery.php?location=<? echo $location_id; ?>' title='Photos from <? echo $location_name; ?>'>Photos from <? echo $location_name; ?></a></td></tr>
</table> 
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<?php 
      include("login_includes.php");
      include("footer.php");
      include("google_maps_api.php");
?>
<script>

$(function() {
             $("#table1")
                .tablesorter({  headers: { 
                   5: { sorter: false }, 6: { sorter: false }, 7 : { sorter: false }, 8: { sorter: false } },widthFixed: true, widgets: ['zebra']})
                   .tablesorterPager({container: $("#pager"), positionFixed: false});
                     
        });
</script>
 <script type="text/javascript">
               //<![CDATA[
                       function load(){
                                   var map = new GMap2(document.getElementById("map"),  {size:new GSize(920,300)});
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
</html>
