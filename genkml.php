<?php
require('db.php');
include("functions.php");

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
} 


				if ($_SESSION['dev_entries'] == 1) {
					// Show all entries : including the ones from developer
				} else {
					$where_clause .= " AND u.user_name != 'Developer'";
				}

				$where_clause .= " AND s.Active = '1'";

				$sql .= $where_clause;

$result = mysql_query($sql);

if (!$result) 
{
  die('Invalid query: ' . mysql_error());
}

// Creates the Document.
$dom = new DOMDocument('1.0', 'UTF-8');

// Creates the root KML element and appends it to the root document.
$node = $dom->createElementNS('http://earth.google.com/kml/2.1', 'kml');
$parNode = $dom->appendChild($node);

// Creates a KML Document element and append it to the KML element.
$dnode = $dom->createElement('Document');
$docNode = $parNode->appendChild($dnode);

// Creates the two Style elements, one for restaurant and one for bar, and append the elements to the Document element.
$restStyleNode = $dom->createElement('Style');
$restStyleNode->setAttribute('id', 'restaurantStyle');
$restIconstyleNode = $dom->createElement('IconStyle');
$restIconstyleNode->setAttribute('id', 'restaurantIcon');
$restIconNode = $dom->createElement('Icon');
$restHref = $dom->createElement('href', 'http://maps.google.com/mapfiles/kml/pal2/icon63.png');
$restIconNode->appendChild($restHref);
$restIconstyleNode->appendChild($restIconNode);
$restStyleNode->appendChild($restIconstyleNode);
$docNode->appendChild($restStyleNode);

$barStyleNode = $dom->createElement('Style');
$barStyleNode->setAttribute('id', 'barStyle');
$barIconstyleNode = $dom->createElement('IconStyle');
$barIconstyleNode->setAttribute('id', 'barIcon');
$barIconNode = $dom->createElement('Icon');
$barHref = $dom->createElement('href', 'http://maps.google.com/mapfiles/kml/pal2/icon27.png');
$barIconNode->appendChild($barHref);
$barIconstyleNode->appendChild($barIconNode);
$barStyleNode->appendChild($barIconstyleNode);
$docNode->appendChild($barStyleNode);

// Iterates through the MySQL results, creating one Placemark for each row.
while ($row = @mysql_fetch_assoc($result))
{
  // Creates a Placemark and append it to the Document.

  $node = $dom->createElement('Placemark');
  $placeNode = $docNode->appendChild($node);

  // Creates an id attribute and assign it the value of id column.
  $placeNode->setAttribute('id', 'placemark' . $row['id']);

  // Create name, and description elements and assigns them the values of the name and address columns from the results.
  $nameNode = $dom->createElement('name',htmlentities($row['user_name']));
  $placeNode->appendChild($nameNode);
  $descNode = $dom->createElement('description', $row['location_name']);
  $placeNode->appendChild($descNode);
  $styleUrl = $dom->createElement('styleUrl', '#' . $row['sighting_date'] . 'Style');
  $placeNode->appendChild($styleUrl);

  // Creates a Point element.
  $pointNode = $dom->createElement('Point');
  $placeNode->appendChild($pointNode);

  // Creates a coordinates element and gives it the value of the lng and lat columns from the results.
  $coorStr = $row['latitude'] . ','  . $row['longitude'];
  $coorNode = $dom->createElement('coordinates', $coorStr);
  $pointNode->appendChild($coorNode);
}

$kmlOutput = $dom->saveXML();
header('Content-type: application/vnd.google-earth.kml+xml');
header("Content-Disposition: attachment; filename=search_results.kml");
echo $kmlOutput;
?>
