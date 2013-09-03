<?
$where_clause = "";
$sql = "";
$sql = "SELECT l1.id,s.common_name,l.location_name,l.city,st.state,u.user_name,l1.sighting_date,l1.number,l1.obs_type,l1.frequency,l1.obs_start,l1.user_friend,";
$sql .= "l.latitude,l.longitude,l.location_id";
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
  if ( strtolower($_GET['season']) != 'all' ) {
     $season = $_GET['season'];
  } 
} else {
  $season = getCurrentSeason();

}


if($season != '' && strtolower($season) !='all' ) {         
   $seasonArr = explode('-',$season);
   $seasonStart = $seasonArr[0];
   $seasonEnd = $seasonArr[1];
		
   if ($where_clause == "") {
     $where_clause = " WHERE l1.sighting_date BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.sighting_date <> '1999-11-30' AND l1.sighting_date <> '0000-00-00' ";
   } else {
     $where_clause .= " AND l1.sighting_date BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.sighting_date <> '1999-11-30' AND l1.sighting_date <> '0000-00-00' ";
   }
		
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
	
$type=$_GET['type'];			
if( strtolower($type) != 'all' && $type != '') {
    if ($where_clause == "") {
           $where_clause = " WHERE l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type' ";
    } else {
           $where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type' ";
    }

} else {
   $type = '';	
   if ($where_clause == "") {
      $where_clause = "  WHERE l1.valid = 1 AND deleted = '0'";
   } else {
      $where_clause .= " AND l1.valid = 1 AND deleted = '0'";
   }
}
				
if ($_SESSION['dev_entries'] == 1) {
	// Show all entries : including the ones from developer
} else {
       $where_clause .= " AND u.user_name != 'Developer'";
}


$sql .= $where_clause;


?>
