<?php
session_start();
include("db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch : Explore data</title>
	<? 	$src_base="http://wildindia.org/mwtest/";
		include("header.php"); 
		include("google_maps_api.php");
	?>
 <script src="http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/src/markerclusterer.js" type="text/javascript"></script>
<?
$where_clause = "";
$sql = "";
$sql = "SELECT s.common_name,l.location_name,l.city,st.state,u.user_name,l1.sighting_date,l1.obs_type,l1.frequency,l1.obs_start,l1.user_friend,";
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
   $where_clause = " WHERE l1.species_id=". $species;
   $tempsql = "select common_name from migwatch_species where species_id=".$species;
   $result = mysql_query($tempsql);
   if($result) {
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $strSpecies = $row{'common_name'};
     }
   }
}
             
if( ($_GET['location'] != 'All') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
   $location = $_GET['location'];
} else {	          
   $location = 'All';
}

if( $location && is_numeric($location) ) {   
   $where_clause = " WHERE l1.location_id=". $location;
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
   $where_clause = " WHERE st.state_id = ".$_GET['state'];
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

     var styles = [[{
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples//images/people35.png',
        height: 35,
        width: 35,
        opt_anchor: [16, 0],
        opt_textColor: '#FF00FF'
      },
      {
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/people45.png',
        height: 45,
        width: 45,
        opt_anchor: [24, 0],
        opt_textColor: '#FF0000'
      },
      {
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/people55.png',
        height: 55,
        width: 55,
        opt_anchor: [32, 0]
      }],
      [{
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/conv30.png',
        height: 27,
        width: 30,
        anchor: [3, 0],
        textColor: '#FF00FF'
      },
      {
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/conv40.png', 
        height: 36,
        width: 40,
        opt_anchor: [6, 0],
        opt_textColor: '#FF0000'
      },
      {
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/conv50.png',
        width: 50,
        height: 45,
        opt_anchor: [8, 0]
      }],
      [{
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/heart30.png',
        height: 26,
        width: 30,
        opt_anchor: [4, 0],
        opt_textColor: '#FF00FF'
      },
      {
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/heart40.png', 
        height: 35,
        width: 40,
        opt_anchor: [8, 0],
        opt_textColor: '#FF0000'
      },
      {
        url: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/examples/images/heart50.png',
        width: 50,
        height: 44,
        opt_anchor: [12, 0]
      }]];
     var map = null;
      var markers = [];
      var markerClusterer = null;

    function initialize() {
        if(GBrowserIsCompatible()) {

          map = new GMap2(document.getElementById('map'));
          map.setCenter(new GLatLng(20.21,77.86), 4);
          map.addControl(new GLargeMapControl());
          map.addControl(new GMapTypeControl());
          
      <?
	 
          $table_sql = $sql;
	 $result = mysql_query($sql);
         while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $loc_name = $line['location_name'];
            if( $line['latitude'] ) {
            print "var latlng = new GLatLng(" . $line['latitude'] . "+(Math.random()*0.2)-0.1," . $line['longitude'] . "+(Math.random()*0.2)-0.1);\n";
            //var marker = new GMarker(latlng, {icon: icon});
            print "var markerOnMap = createMarker(latlng, '" . addslashes( $loc_name ) ."');\n"; 
            print "markers.push(markerOnMap);\n";
	    }
          }
      ?>
       refreshMap();
          
        }
      }

   function createMarker(point, title) { 
               var icon = new GIcon(G_DEFAULT_ICON);
          icon.image = "http://chart.apis.google.com/chart?cht=mm&chs=20x20&chco=FFFFFF,008CFF,000000&ext=.png";
		var markerOnMap = new GMarker(point,icon);
                var html2= title;
                GEvent.addListener(markerOnMap, 'click', function() { 
			      markerOnMap.openInfoWindowHtml(html2, {maxWidth: '260'});
			      });      
			return markerOnMap;
			      }


   
      function refreshMap() {
        if (markerClusterer != null) {
          markerClusterer.clearMarkers();
        }
        var zoom = parseInt(document.getElementById("zoom").value, 10);
        var size = parseInt(document.getElementById("size").value, 10);
        var style = document.getElementById("style").value;
        zoom = zoom == -1 ? null : zoom;
        size = size == -1 ? null : size;
        style = style == "-1" ? null: parseInt(style, 10);
        markerClusterer = new MarkerClusterer(map, markers, {maxZoom: zoom, gridSize: size, styles: styles[style]});
      }

    
    </script>
</head> 

<body onload="initialize()" onunload="GUnload()">




<div class="container first_image"  style="padding-bottom:10px;width:950px;margin-left:auto;margin-right:auto">

<FORM name="reports" action="data.php" method="GET">
<table class="filter">          
         
<tr>
         <td style=''>Season</td>
         <td>Sighting type</td>
	 <td>Species</td>
         <td>Participant</td>
         <td></td>
       </tr>
       <tr>
         <td style="width:160px;">
                    <select style="width:85%;font-size:12px;" name='season'>
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
           
                    </select>&nbsp;&nbsp;
		    <? $current_season = getCurrentSeason();
                   
                       if( $_GET['season'] != '' && $_GET['season']  != $current_season  ) {		       
		    ?>
                     <a title="remove season" href="#" onClick="get_remove('season');">X</a>
                    
                       <? } ?>

                   </td> <td  style="width:160px;">
                    

                    <select name="type" style="width:85%">
                            <option value="All">All</option>
                            <option value="first"<?php if($type == 'first') print("selected"); ?>>First Sighting</option>
                            <option value="general"<?php if($type == 'general') print("selected"); ?>>General Sighting</option>
                            <option value="last"<?php if($type == 'last') print("selected"); ?>>Last Sighting</option>
                     </select>&nbsp;&nbsp;
                      <? if( $_GET['type'] != "All" && $_GET['type'] != '') {  ?>
                           <a title="remove sighting type" href="#" onclick="get_remove('type');">X</a>
                      <? } ?>
		</td>
	  
                <td style="width:400px;">
                    
                  
                    <input type='text' id='species' size="25" style="width:90%;border:solid 1px #666" value="<? echo $strSpecies; ?>">
		               <input type='hidden' id='species_hidden' name='species' value="<? echo $species; ?>">
                              &nbsp;
                             <? if( is_numeric($_GET['species'] )) {  ?>
                                  <a title="remove species" href="#" onclick="get_remove('species');">X</a>
                             <? } ?>
		</td>



		<td style="width:300px;">
           
                    <select name=user style="width:91%">
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
                </div>&nbsp;
                <? if( is_numeric( $_GET['user'] )) {  ?>
                     <a href="#" onclick="get_remove('user');">X</a>
                <? } ?>
		</td><td></td>
		</tr>
                <tr><td colspan='4' style='height:15px'></tr>
		<tr>
			<td colspan="2">State</td>
         <td colspan="2">Location</td>
	 
	 <td>
	 <? if( count($_GET) > 0 ) { ?>
	   <a href="data.php">Remove&nbsp;All</a>
	 <? } ?>
	 </td>
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

                    ?></select>&nbsp;&nbsp;
                   <? if( is_numeric($_GET['state'] )) {  ?>
                     <a title="remove state" href="#" onclick="get_remove('state');">X</a>
                   <? } ?>

          </td>

			<td colspan="2" style="width:500px;">
               
               <input type='text' id='location'  size='25' value="<? echo $strLocation; ?>" style="width:95%;border:solid 1px #666">
               <input type='hidden' id='location_hidden' name='location' value="<? echo $location; ?>">
               <? if( is_numeric($_GET['location'] )) {  ?>
                  &nbsp;<a title="remove location" href="#" onclick="get_remove('location');">X</a>
               <? } ?>
              
         </td>
        
         <td style='width:110px'><input type='submit' style='padding:2px' value='go'></td>
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

?>
<div class='container' style="width:930px;margin-left:auto;margin-right:auto;text-align:right">
<a href='download.php?output=kml<? echo $url_add; ?>'>KML</a>&nbsp;&nbsp;<a href='download.php?output=csv<? echo $url_add; ?>'>CSV</a>
</div>
<?

$result = mysql_query($table_sql);
 if (mysql_num_rows($result) > 0) {
?>

<div class='container' style="width:930px;margin-left:auto;margin-right:auto;border-top:solid 1px #d95c15" id='tab-set'>
   <ul class='tabs'>
   
           <li><a href='#text1' class='selected'>map</a></li>
           <li><a href='#text2'>tabular</a></li>
 
   </ul>
   <div id='text1'>
   	     <input type="hidden" id="size" value="-1">
	     <input type="hidden" id="zoom" value="-1">
	     <input type="hidden" id="style" value="-1">
              <div id="map" style="width:930px;height:500px"></div>
  </div>

  <div id='text2'>
     <table id="table1" class="tablesorter" style="width:930px;margin-left:auto;margin-right:auto" cellpadding="3">
                <thead>
                        <tr>
                                <th>&nbsp;No</th>
                                <th>Common Name</th>           
                                <th>Location</th>
                                <th style='width:30px'>Date</th>                        
				<th>Sighting type</th>
                                <th>Count</th>
                                <th>Reported by</th>
                                <th>On behalf of</th>
                        </tr>
                </thead>
                <tbody>

     <?
	$i=1;
         
         //$result = mysql_query($table_sql);
         
	list($startSeason,$endSeason) =  explode('-',$_GET['season']);
	//if (mysql_num_rows($result) > 0) {
	   while ($row = mysql_fetch_array($result)) {
	             print "<tr>";
                        print "<td>$i</td>";
	            print "<td style='width:200px;'>".$row{'common_name'}."</td>";
                        print "<td style='width:200px;'>".$row{'location_name'}.", ".$row{'city'}.", ".$row{'state'}."</td>";
                        print "<td style='width:150px'>".date("d-m-Y",strtotime($row{'sighting_date'}))."</td>";
		        print "<td>" . ucfirst($row{'obs_type'}) . "</td>";
                        ($row['number'] > 0 ) ? print "<td>".$row{'number'}."</td>" : print "<td> -- </td>";
                        print "<td>".$row{'user_name'}."</td>";
                        print "<td style='border-right:0.5px solid #ffcb1a'>".$row{'user_friend'}."</td>";
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

<div class='container notice' style="width:900px;margin-left:auto;margin-right:auto;">no results</div>
<? } ?>
</div>

</div>
</div>

<div class="container bottom">

</div>
<script type='text/javascript'>

			$().ready(function() {

            $("#species").autocomplete("autocomplete_reports.php", {
                width: 260,
                selectFirst: false,
                matchSubset :0,
                mustMatch: true,
                            
           });

         $("#species").result(function(event , data, formatted) {
                if (data)
						
                  document.getElementById('species_hidden').value = data[1];
                  
						
          });


         $('#location').autocomplete("auto_miglocations.php", {
                width: 495,
                selectFirst: false,
                matchSubset :0,
                mustMatch: true,
                  //formatItem:formatItem
                 
          });

         $("#location").result(function(event , data, formatted) {
                if (data)
                  document.getElementById('location_hidden').value = data[1];
                  
                          
         });
});

</script>
<? include("tab_include.php"); ?>



</div>
</body>
</html>

<script type="text/javascript">
function get_remove(parameter) {

<? if($_GET['type']){
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
     alert(remove_season);
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
</script>

<script language="javascript">

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

        function isEmpty(s)
        {   return ((s == null) || (s.length == 0))
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
        function formReset(){
            document.rpt_l1.target = "";
            document.rpt_l1.action = "rpt_level1.php";
        }

        function showReport(){
            document.rpt_l1.target = "blank";
            document.rpt_l1.action = "rpt_level1_excel.php";
            document.rpt_l1.submit();
            document.rpt_l1.target = "";
        }


        function validate(){
            if(isWhitespace(document.frm_chpass.opwd.value))
            {
                alert('Please enter old password');
                return false;
            }


            if(document.frm_chpass.pwd.value != document.frm_chpass.pwd1.value){
                alert("The new passwords dont match. Please re-enter.");
                return false;
            }

            if(document.frm_chpass.pwd.value.length < 6){
                alert("The new password should be atleast 6 characters long.");
                return false;
            }

            return true;

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
<? include("page_includes_js.php"); ?>
