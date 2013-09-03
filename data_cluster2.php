<?php
session_start();
include("db.php");
$strSpecies='Type a species name';
$strLocation='Type a location name';
$page_title="MigrantWatch: Data";
include("main_includes.php");

$kml_text="<span class='help_text_content'><h4>KML</h4>KML is a file format used to display geographic data in an earth browser, such as Google Earth, Google Maps</span>";
?>


<? 
   include("header.php");
   include("google_maps_api.php");
$where_clause = "";
$sql = "";
$sql = "SELECT l1.id,s.common_name,l.location_name,l.city,st.state,u.user_name,l1.sighting_date,l1.obs_type,l1.frequency,l1.obs_start,l1.user_friend,";
$sql .= "l.latitude,l.longitude";
$sql .= " FROM migwatch_l1 l1 INNER JOIN migwatch_users u ON l1.user_id=u.user_id ";
$sql .= " INNER JOIN migwatch_locations l ON l.location_id=l1.location_id ";
$sql .= " INNER JOIN migwatch_species s ON s.species_id=l1.species_id ";
$sql .= " INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
                                
   
if( ( $_GET['species'] != "All" ) || $_GET['species'] != "" ) {
   $species = $_GET['species'];
} else {
   $species = "All";
}
if( $species && is_numeric($species) ) {                                  
   //$where_clause = " WHERE l1.species_id=". $species;
   $tempsql = "select common_name from migwatch_species where species_id=".$species;
   $result = mysql_query($tempsql);
   if($result) {
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $strSpecies = $row{'common_name'};
     }
   }
}

if($species){
   if($species != 'All' )
   if ($where_clause == ""){
     $where_clause = " WHERE l1.species_id = ". $species;
   } else {
     $where_clause .= " AND l1.species_id=".$species;
   }
}

             
if( ($_GET['location'] != 'All') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
   $location = $_GET['location'];
} else {	          
   $location = 'All';
}

if( $location && is_numeric($location) ) {   
   //$where_clause = " WHERE l1.location_id=". $location;
   $tempsql = "select location_name, city,district from migwatch_locations where location_id='$location'";
   $result = mysql_query($tempsql);
   if($result) {
   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
   $strLocation = $row{'location_name'};
   
   if( $row['city'] != "" ) { 
    $strLocation .= ", ". $row['city'];
   }
   if( $row['district'] != "" ) { 
    $strLocation .= ", " . $row['district'];
   }   
   
  }						   
 }
}
                               
if($location){
   if($location != 'All' )
   if ($where_clause == ""){
     $where_clause = " WHERE l1.location_id = ".$location;
   } else {
     $where_clause .= " AND l1.location_id=".$location;
   } 
}

if( $_GET['season'] != '') {
  if ( $_GET['season'] != 'All' ) {
   $season = $_GET['season'];
} 
}else {
   $season = getCurrentSeason();		
}
         
   $seasonArr = explode('-',$season);
   $seasonStart = $seasonArr[0];
   $seasonEnd = $seasonArr[1];
		
   if ($where_clause == "") {
     $where_clause = " WHERE l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";
   } else {
     $where_clause .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";
   }
			
if( ($_GET['user'] != 'All') || ($_GET['user'] != '')) {
   $user = $_GET['user'];
} else {
   $user = 'All';
}

if ($user){
   if($user != 'All')
     if ($where_clause == "") {
       $where_clause = " WHERE l1.user_id = ".$_GET['user'];
     } else {
        $where_clause .= " AND l1.user_id = ".$_GET['user'];
     }

   $tempsql = "Select user_name from migwatch_users where user_id=".$_GET['user'];
   $result = mysql_query($tempsql);
   $strUserName = "";
   if($result){
     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
       $strUserName = $row{'user_name'};
   }
}

if( is_numeric($_GET['state']) )  {
   $state = $_GET['state'];
} else {
   $state = 'All';
}

if( is_numeric($state)) {
   if($where_clause == '') {
   $where_clause = " WHERE st.state_id = ".$_GET['state'];
   } else {
         $where_clause .= " AND st.state_id = ".$_GET['state'];
   }
}

				if ($_GET['obsfrq'] != "1"){
					if($where_clause == ""){
						switch($_GET['obsfrq']){
							case 2:
								$where_clause = " WHERE l1.frequency = 'Daily' ";
								$strFrequency = "Daily only";
								break;
							case 3:
								$where_clause = " WHERE l1.frequency in ('Weekly','Daily','Twice a week') ";
								$strFrequency = "Weekly or more frequent";
								break;
							case 4:
								$where_clause = " WHERE l1.frequency in ('Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Fortnightly or more frequent";
								break;
							case 5:
								$where_clause = " WHERE l1.frequency in ('Monthly','Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Monthly or more frequent";
								break;
							default:
								$where_clause = "";
								break;
						}
					} else {
						switch($_GET['obsfrq']){
							case 2:
								$where_clause .= " AND l1.frequency = 'Daily' ";
								$strFrequency = "Daily only";
								break;
							case 3:
								$where_clause .= " AND l1.frequency in ('Weekly','Daily','Twice a week') ";
								$strFrequency = "Weekly or more frequent";
								break;
							case 4:
								$where_clause .= " AND l1.frequency in ('Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Fortnightly or more frequent";
								break;
							case 5:
								$where_clause .= " AND l1.frequency in ('Monthly','Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Monthly or more frequent";
								break;
							default:
								$where_clause .= "";
								break;
						}
					}

				}
				else
					$strFrequency = "Any";
				
if($_GET['type'] != 'All' || $_GET['type'] != '') {
   $type =  $_GET['type'];    
} else {
   $type = 'All';	
}

if($type) {
   if($type != 'All') {
   if ($where_clause == "")
        $where_clause = " WHERE l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
   else
	$where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
   } 
} /*else {
   if ($where_clause == "")
      $where_clause = "  WHERE l1.valid = 1 AND deleted = '0'";
   else
      $where_clause .= " AND l1.valid = 1 AND deleted = '0'";
}*/
				if ($_SESSION['dev_entries'] == 1) {
					// Show all entries : including the ones from developer
				} else {
					$where_clause .= " AND u.user_name != 'Developer'";
				}

				$where_clause .= " AND s.Active = '1'";

				$sql .= $where_clause;

	?>

<script type="text/javascript">

	function createMarker(point, user, friend, location, city, state, species, sDate, obsFreq, start, icon) {
  		var marker = new GMarker(point,icon);
  		GEvent.addListener(marker, "click", function() {
    			marker.openInfoWindowHtml("<font face='Arial' size='1'><b>" + species + "</b><br>Reported by <b>" + user + "</b><br>On behalf of <b>" + friend + "</b><br>At " + location + ", " + city + ", " + state + "<br>On " + sDate + "<br>Observation frequency: " + obsFreq + "<br>Start date: " + start + "</font>");
  		});
  		return marker;
                alert(marker);
	}

	var map=null;

        
        var markers = [];
        var markerClusterer = null;


    function load() {
      if (GBrowserIsCompatible()) {
         map = new GMap2(document.getElementById('map'));
          map.setCenter(new GLatLng(20.21,77.86), 4);
          map.addControl(new GLargeMapControl());
          map.addControl(new GMapTypeControl());
           var icon1 = new GIcon(G_DEFAULT_ICON);
           icon1.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|1e8a94";

           var icon2 = new GIcon(G_DEFAULT_ICON);
           icon2.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|e86b1f";

           var icon3 = new GIcon(G_DEFAULT_ICON);
           icon3.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|2aa212";

           var icon4 = new GIcon(G_DEFAULT_ICON);
           icon4.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|634f00";

           var icon5 = new GIcon(G_DEFAULT_ICON);
           icon5.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|941e23";

           var icon6 = new GIcon(G_DEFAULT_ICON);
           icon6.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|7a941e";

           var icon7 = new GIcon(G_DEFAULT_ICON);
           icon7.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|ffe400";

           var icon8 = new GIcon(G_DEFAULT_ICON);
           icon8.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|768066";
           
           var icon9 = new GIcon(G_DEFAULT_ICON);
           icon9.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|8f627a";

           var icon10 = new GIcon(G_DEFAULT_ICON);
           icon10.image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|6a1744";
<?	
	
        $table_sql = $sql;
	$result = mysql_query($table_sql);
	$i = 1;
	list($startSeason,$endSeason) =  explode('-',$season);
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                if($line['latitude']) {
		$line['count'] = $i;
        
		print "var point = new GLatLng(" . $line['latitude'] . "," . $line['longitude'] . ");\n";
		if (strtotime($line['sighting_date']) >= strtotime("$startSeason-07-01") && strtotime($line['sighting_date']) <= strtotime("$startSeason-07-31")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon10));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-08-01") && strtotime($line['sighting_date']) <= strtotime("$startSeason-08-15")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon9));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-08-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-08-31")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon8));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-08-31") && strtotime($line['sighting_date']) <= strtotime("$startSeason-09-15")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon7));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-09-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-09-30")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon6));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-09-30") && strtotime($line['sighting_date']) <= strtotime("$startSeason-10-15")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon5));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-10-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-10-31")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon4));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-10-31") && strtotime($line['sighting_date']) <= strtotime("$startSeason-11-15")) {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon3));\n\n"; }
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-11-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-11-30")) {
		print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon2));\n\n"; 
       }
		/* elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-11-30")) { */
                else {
			print "markers.push(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon1));\n\n";
                }
			}
	
        }
?>
        refreshMap();
	}
   }


   function refreshMap() {
        if (markerClusterer != null) {
          markerClusterer.clearMarkers();
        }
        var zoom = parseInt(-1, 10);
        var size = parseInt(-1, 10);
        var style = -1;
        zoom = zoom == -1 ? null : zoom;
        size = size == -1 ? null : size;
        style = style == "-1" ? null: parseInt(style, 10);
        markerClusterer = new MarkerClusterer(map, markers, {maxZoom: zoom, gridSize: size});
      }

    </script>
<body onload="load()" onunload="GUnload()">
<script src='http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/src/markerclusterer.js' type='text/javascript'></script>
<div class="container first_image">
<FORM name="reports" action="data.php" method="GET">
<table class="filter">    
<tr>
         <td style=''>season</td>
         <td>sighting&nbsp;type</td>
	 <td>species</td>
         <td>participant</td>
         
       </tr>      
         <td style="width:190px;">
                    <select style="width:85%;font-size:12px;" name='season'>
                    <option value='All'>All seasons</option>
                    <?php
                    /**
                     * Use the current month and year to find out the last season to be
                     * displayed in the drop down. (Season : 1st July - 31st Aug)
                     */
                    $currentMonth = date('m');
                    $currentYear  = date('Y');

                    /**
                     * If the current month is greater than June i.e July and onwords only then display
                     * the current year in the season
                     */
                    if ($currentMonth > 6) {
                        $endSeason = $currentYear;
                    } else {
                        $endSeason = $currentYear - 1;
                    }

                    // The sighting started in 2007-2008 so start from this season
                    for ($i = 2007;$i <= $endSeason; $i++) {
                        $fromTo = "$i-".($i+1);
                        echo '<option value="' . $fromTo  . '"';
                        if (empty($_GET)) {
                            echo ' selected>';
                        } else {
                            echo ($_GET['season'] == $fromTo) ? ' selected>' : '>';
                        }
                        echo $fromTo;
                        echo '</option>';
                    }
                    ?>
           
                    </select>
		    <? $current_season = getCurrentSeason();
                   
                       if( $_GET['season'] != '' && $_GET['season']  != $current_season  ) {		       
		    ?>
                     <a title="remove season" href="#" onClick="get_remove('season');">X</a>
                    
                       <? } ?>

                   </td> <td  style="width:190px;">
                    
		    
                    <select name="type" style="width:85%">
                            <option value="All">All</option>
                            <option value="first"<?php if($type == 'first') print("selected"); ?>>First</option>
                            <option value="general"<?php if($type == 'general') print("selected"); ?>>General</option>
                            <option value="last"<?php if($type == 'last') print("selected"); ?>>Last</option>
                     </select>
                      <? if( $_GET['type'] != "All" && $_GET['type'] != '') {  ?>
                           <a title="remove sighting type" href="#" onclick="get_remove('type');">X</a>
                      <? } ?>
		</td>
	  
                <td style="width:300px;">
                    
                    
                    <input type='text' id='species' size="25" style="width:85%;border:solid 1px #666;padding:2px" value="<? echo $strSpecies; ?>">
		               <input type='hidden' id='species_hidden' name='species' value="<? echo $species; ?>">
                             
                             <? if( is_numeric($_GET['species'] )) {  ?>
                                  <a title="remove species" href="#" onclick="get_remove('species');">X</a>
                             <? } ?>
		</td>



		<td style="width:240px;">
           	   
                    <select name=user style="width:85%">
                    <option value='All'>-- Select --</option>
                    <?php

                    //  The current season..
                    $today = getdate();
                    $currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;
                    $season = (isset($_POST['season'])) ? substr($_POST['season'], 0, 4) : $currentSeason;
                    $seasonEnd = (int)$season + 1;

                    $sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
                    $sql .= "l1.user_id=u.user_id WHERE l1.valid=1 ";
                    //$sql .= "AND l1.obs_start BETWEEN '$season-07-01' AND '$seasonEnd-06-30'  ";
                    $sql .= "ORDER BY u.user_name";
                    $result = mysql_query($sql);
                    if(mysql_num_rows($result) <= 0) {
                        $sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
                        $sql .= "l1.user_id=u.user_id WHERE l1.valid=1 ORDER BY u.user_name";
                        $result = mysql_query($sql);
                    }
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                        print "<option value=".$row{'user_id'};
                        if (($_GET['user'] != "") && ($_GET['user'] == $row{'user_id'}))
                            print " selected ";
                        print ">".$row{'user_name'}."</option>";
                    }
                    ?>
                    </select>
                </div>
                <? if( is_numeric( $_GET['user'] )) {  ?>
                     <a href="#" onclick="get_remove('user');">X</a>
                <? } ?>
		</td>
		</tr>
                
		<tr>
			<td colspan="2">state</td>
         <td colspan="2">location</td>
	 	 
       </tr>
        <tr>
          
          <td colspan="2" style="">
             <select style="width:93%;"  id="state" name=state >

                        <option value="all">All the States</option>
                    <?php

                            $result = mysql_query("SELECT state_id, state FROM migwatch_states order by state");
                            if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                                    if($row['state'] != 'Not In India') {
                                        print "<option value=".$row{'state_id'};
                                        if (($_GET['state'] != "") && ($_GET['state'] == $row{'state_id'}))
                                            print " selected ";
                                        print ">".$row{'state'}."</option>";
					                          } else {
                                        $other_id = $row['state_id'];
                                        $other = $row['state'];
                                    }
                                }
                                print("<option value=".$other_id);
							if($other_id == $state_id)
                                    print " selected ";
				    	                  print ">".$other."</option>\n";
                            }

                    ?></select>
                   <? if( is_numeric($_GET['state'] )) {  ?>
                     <a title="remove state" href="#" onclick="get_remove('state');">X</a>
                   <? } ?>

          </td>

			<td style="width:400px;">
               
               <input type='text' id='location' value="<? echo $strLocation; ?>" style="padding:2px;width:85%;border:solid 1px #666">
               <input type='hidden' id='location_hidden' name='location' value="<? echo $location; ?>">
               <? if( is_numeric($_GET['location'] )) {  ?>
                  &nbsp;<a title="remove location" href="#" onclick="get_remove('location');">X</a>
               <? } ?>
              
         </td>
        
         <td style='width:200px;text-align:right;'><a href='data.php' title='unselect all the filters' class='submit unselect'>unselect&nbsp;all</a>&nbsp;<input type='submit' class='submit' value='go'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </form>
        </tr>
	</table>
<? 
   $url = '';
   foreach( $_GET as $key => $value ) {
   	    if( strtolower($value) != 'all' && $value !='' ) {
   	    $url_add .="&" . $key . "=" . $value;
	    }
   }



$result = mysql_query($table_sql);
 if (mysql_num_rows($result) > 0) {
if($_SESSION['userid']) {
?>
<div class='container page_layout' style='height:50px; font-size:14px;'>
   <b>Download data</b>&nbsp;<a title='Download data below as KML' href='download.php?output=kml<? echo $url_add; ?>'>KML</a>&nbsp;
   (<a class='help_text' title="<? echo $kml_text; ?>" href='#'>read more</a>)
&nbsp;&nbsp;|&nbsp;&nbsp;<a title='Download data below as CSV' href='download.php?output=csv<? echo $url_add; ?>'>CSV</a>
</div>
<?
}

?>

<div class='container' style="width:930px;margin-left:auto;margin-right:auto;border-top:solid 1px #d95c15" id='tab-set'>
   <ul class='tabs'>
   
           <li style='margin-left:0'><a href='#text1' class='selected'>map</a></li>
           <li style='margin-left:0'><a href='#text2'>tabular</a></li>
 
   </ul>
   <div id='text1'>
              <div id="map" style="width:930px;height:500px;text-align:center">Loading maps. This might take some time</div><br><br>
  </div>

  <div id='text2'>
     <table id="table1" class="tablesorter">
                <thead>
                        <tr>
                                <th style="width:50px">&nbsp;No</th>
                                <th style='width:200px;'>Common Name</th>           
                                <th style='width:200px'>Location</th>
                                <th style='width:100px'>Date</th>                        
				<th style='width:100px'>Sighting type</th>
                                <th style='width:50px'>Count</th>
                                <th style='width:200px'>Reported by</th>
                    
                        </tr>
                </thead>
                <tbody>
     <?
	$i=1;
	list($startSeason,$endSeason) =  explode('-',$_GET['season']);
	//if (mysql_num_rows($result) > 0) {
	   while ($row = mysql_fetch_array($result)) {
	             print "<tr>";
                        print "<td style='text-align:center'><a href='sighting.php?id=" . $row{'id'} ."'>" . $i . "</a></td>";
	                print "<td>".$row{'common_name'}."</td>";
                        print "<td>".$row{'location_name'}.", ".$row{'city'}.", ".$row{'state'}."</td>";
                        print "<td>".date("Y-m-d",strtotime($row{'sighting_date'}))."</td>";
		        print "<td>" . ucfirst($row{'obs_type'}) . "</td>";
                        ($row['number'] > 0 ) ? print "<td>".$row{'number'}."</td>" : print "<td> -- </td>";
                        print "<td style='border-right:solid 1px #ffcb1a'>".$row{'user_name'}."</td>";
                        print "</tr>";
                        $i++;
           }
?>
     </tbody>
     </table>
       <div id="pager" class="column span-7" style="" >
                        <form name="" action="" method="post">
                                <table >
                                <tr>
                                        <td><img src='pager/icons/first.png' class='first'/></td>
                                        <td><img src='pager/icons/prev.png' class='prev'/></td>
                                        <td><input type='text' size='8' class='pagedisplay'/></td>
                                        <td><img src='pager/icons/next.png' class='next'/></td>
                                        <td><img src='pager/icons/last.png' class='last'/></td>
                                        <td>
                                                <select class='pagesize'>
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

       </div>

</div>
<? } else { ?>

<div class='container notice' style="width:900px;margin-left:auto;margin-right:auto;">No results</div>
<? } 
include("credits.php"); 
?>

</div>

</div>
</div>

<div class="container bottom">

</div>
<? include("tab_include.php"); ?>
<script type='text/javascript'>
$().ready(function() {

var state_val = $('#state').val();

$('#species').emptyonclick();
$('#location').emptyonclick();

$("#species").autocomplete("autocomplete_reports.php", {
  width: 260,
  selectFirst: false,
  matchSubset :0,
  mustMatch: true,                          
});

$("#species").result(function(event , data, formatted) {
  if (data) {
	 document.getElementById('species_hidden').value = data[1];
  }
});



$('#location').autocomplete("auto_location_watchlist.php", {
   width: 400,
   selectFirst: false,
   matchSubset :0,
   cache:false,
   mustMatch: true,
   extraParams: {state: function() { return $("#state").val(); } },
});

$("#location").result(function(event , data, formatted) {
   if (data) {
	document.getElementById('location_hidden').value = data[1];
   }
});
});


function get_remove(parameter) {
<? 
if($_GET['type']){
   $remove_type = $_GET['type'];
}
  
if($_GET['species']){
   $remove_species = $_GET['species'];
}

if($_GET['user']){
   $remove_user = $_GET['user'];
}
   
if($_GET['state']){
   $remove_state = $_GET['state'];
}

if($_GET['location']){
   $remove_location = $_GET['location'];
}

if($_GET['season']){
   $remove_season = $_GET['season'];
}

?>
var remove_season = '<? echo $remove_season; ?>';
var remove_type = '<? echo $remove_type; ?>';
var remove_species = '<? echo $remove_species; ?>';
var remove_user = '<? echo $remove_user; ?>';
var remove_state = '<? echo $remove_state; ?>';
var remove_location = '<? echo $remove_location; ?>';

if ( parameter == 'season') {
   remove_season = '<? echo getCurrentSeason(); ?>';
}

if ( parameter == 'type') {
   remove_type = 'All';
}

if (parameter == 'species') {
    remove_species = 'All'; 
}

if (parameter == 'user') {
    remove_user = 'All'; 
}

if (parameter == 'location') {
    remove_location = 'All';
}

if ( parameter == 'state') {
    remove_state = 'All'; 
} 

var url = "data.php?season=" + remove_season + "&type=" + remove_type + "&species=" + remove_species + "&user=" + remove_user + "&state=" + remove_state + "&location=" + remove_location;

window.location = url;
}

function formatItem(row) {
    var completeRow;
    completeRow = new String(row);
    var scinamepos = completeRow.lastIndexOf("(");
    var rest = substr(completeRow,0,scinamepos);
    var sciname = substr(completeRow,scinamepos);
    var commapos = sciname.lastIndexOf(",");
    sciname = substr(sciname,0,commapos);
    var newrow = rest + ' <i>' + sciname + '</i>';
    return newrow;
}

function isEmpty(s){   
    return ((s == null) || (s.length == 0))
}

        // BOI, followed by one or more whitespace characters, followed by EOI.
        var reWhitespace = /^\s+$/
        // BOI, followed by one or more characters, followed by @,
        // followed by one or more characters, followed by .,
        // followed by one or more characters, followed by EOI.
        var reEmail = /^.+\@.+\..+$/
        var defaultEmptyOK = false
        // Returns true if string s is empty or
        // whitespace characters only.

        function isWhitespace (s)

        {   // Is s empty?
            return (isEmpty(s) || reWhitespace.test(s));
        }
        

        function substr( f_string, f_start, f_length ) {
            // http://kevin.vanzonneveld.net
            // +     original by: Martijn Wieringa
            // *         example 1: substr("abcdef", 0, -1);
            // *         returns 1: "abcde"

            if(f_start < 0) {
                f_start += f_string.length;
            }

            if(f_length == undefined) {
                f_length = f_string.length;
            } else if(f_length < 0){
                f_length += f_string.length;
            } else {
                f_length += f_start;
            }

            if(f_length < f_start) {
                f_length = f_start;
            }

            return f_string.slice(f_start,f_length);
        }    
</script>
<? include("footer.php"); ?>
<script type="text/javascript">
        $(function() {
             $("#table1")
                .tablesorter({  headers: { 
                   5: { sorter: false }, 6: { sorter: false }, 7 : { sorter: false }, 8: { sorter: false } },widthFixed: true, widgets: ['zebra']})
                   .tablesorterPager({container: $("#pager"), positionFixed: false});

              $("#table2")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager2"), positionFixed: false});
                     
        });
        
</script>
</body>
</html>


