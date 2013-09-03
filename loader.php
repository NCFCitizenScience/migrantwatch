<? 
include("db.php");
include("functions.php");

$photo_id = $_GET['id'];

$query= "SELECT distinct p.photo_filename,p.photo_caption,l1.id, u.user_id, l.latitude,l.longitude,l.location_id, l.location_name,l.city,l.district, u.user_name, s.state, c.scientific_name,c.common_name,c.species_id FROM migwatch_l1 l1 INNER JOIN migwatch_species c ON l1.species_id = c.species_id INNER JOIN migwatch_locations l ON l1.location_id = l.location_id INNER JOIN migwatch_states s ON s.state_id = l.state_id INNER JOIN migwatch_users u ON u.user_id = l1.user_id INNER JOIN migwatch_photos p ON p.sighting_id = l1.id where p.photo_id = '$photo_id'";


if(isset($_GET['season']) && $_GET['season'] != 'All') {
        $season = $_GET['season'];
}

if($season) {
	$seasonArr = explode('-',$season);
	$seasonStart = $seasonArr[0];
	$seasonEnd = $seasonArr[1];
	$where_clause = " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";
}

if( ( $_GET['species'] != "All" ) || $_GET['species'] != "" ) {
    $species = $_GET['species'];
} else {
    $species = "All";
}
                                                              
if( $species && is_numeric($species) ) {                                 
	$where_clause .= " AND l1.species_id=". $species;
}
                                        
if( (strtolower($_GET['location']) != 'all') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
	$location = $_GET['location'];
} else {	          
	$location = 'All';
}

if( is_numeric($location) ) {   
	$where_clause .= " AND  l1.location_id=". $location;
}

if( is_numeric($_GET['user'])) {
	$user = $_GET['user'];
} else {
   $user = 'All';
}

if ( is_numeric($user)) {
	$where_clause .= " AND l1.user_id = ".$_GET['user'];
}
				
if($_GET['type'] != 'All' || $_GET['type'] != '') {
   $type =  $_GET['type'];    
} else {
	$type = 'All';	
}

if($type) {
     if( strtolower($type) != 'all') {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
 		} else {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0'";
      }
}

if ($_SESSION['dev_entries'] == 1) {
	// Show all entries : including the ones from developer
} else {
	$where_clause .= " AND u.user_name != 'Developer'";
}

//$where_clause .= " AND c.Active = '1'";
$query .= $where_clause;


$result = mysql_query($query);
if(mysql_num_rows($result) > 0) {
   $data = mysql_fetch_assoc($result);
   $photo_caption =  stripslashes($data['photo_caption']);
   $photo_filename =  $data['photo_filename'];
   $location_id = $data['location_id'];
   $species_id = $data['species_id'];
   $location_name = $data['location_name'] . ", " .  $data['state'];
   $location_geocodes = $data['latitude'] . "," . $data['longitude'];
   $species_name = $data['common_name'] . " ( <i> " . $data['scientific_name'] . "</i> )";
   $user_name = $data['user_name'];
   $photo_user_id = $data['user_id'];
   $sighting_id = $data['id'];

   list($width, $height, $type, $attr) = getimagesize("image_uploads/f_$photo_filename");
  	      
   $query = "SELECT distinct p.photo_id, p.photo_filename,p.photo_caption,l.location_id, l.location_name,l.city,l.district, u.user_name, s.state, c.common_name FROM migwatch_l1 l1 INNER JOIN migwatch_species c ON l1.species_id = c.species_id INNER JOIN migwatch_locations l ON l1.location_id = l.location_id INNER JOIN migwatch_states s ON s.state_id = l.state_id INNER JOIN migwatch_users u ON u.user_id = l1.user_id INNER JOIN migwatch_photos p ON p.sighting_id = l1.id";

if(isset($_GET['season']) && $_GET['season'] != 'All') {
  $season = $_GET['season'];                	        
}

if($season) {    
 $seasonArr = explode('-',$season);
 $seasonStart = $seasonArr[0];
 $seasonEnd = $seasonArr[1];
 $where_clause = '';
 $where_clause .= " WHERE l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";
}

if( ( $_GET['species'] != "All" ) || $_GET['species'] != "" ) {
    $species = $_GET['species'];
} else {
    $species = "All";
}
                                                              
if( $species && is_numeric($species) ) {                                 
	$where_clause .= " AND l1.species_id=". $species;
}
                                        
if( (strtolower($_GET['location']) != 'all') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
	$location = $_GET['location'];
} else {	          
	$location = 'All';
}

if( is_numeric($location) ) {   
	$where_clause .= " AND l1.location_id=". $location;
}
              			


if( is_numeric($_GET['user'])) {
	$user = $_GET['user'];
} else {
   $user = 'All';
}

if ( is_numeric($user)) {
	$where_clause .= " AND l1.user_id = ".$_GET['user'];
}
				
if($_GET['type'] != 'All' || $_GET['type'] != '') {
   $type =  $_GET['type'];    
} else {
	$type = 'All';	
}

if($type) {
     if( strtolower($type) != 'all') {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
 		} else {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0'";
      }
}

if ($_SESSION['dev_entries'] == 1) {
	// Show all entries : including the ones from developer
} else {
	$where_clause .= " AND u.user_name != 'Developer'";
}

$query .= $where_clause;

$query  .=" ORDER BY p.photo_id DESC";

$result = mysql_query($query);

$n =0;
$pos;
$photos;
while ($line = mysql_fetch_array($result)) {
	$photos[$n] = $line["photo_id"];	
	if ($photos[$n] == $photo_id) { $pos=$n;}
        if ($n == 0 ) {
          $photo_first = $photos[$n];
        }
        $photo_last = $photos[$n];
	$n++;
}

$prev_photo_id = $photos[$pos-1];
$next_photo_id = $photos[$pos+1];
if( $prev_photo_id ) {
$prev = $prev_photo_id;
} else {

 $prev = $photo_last;
}

if( $next_photo_id ) {
      $next = $next_photo_id ;
} else {
      $next = $photo_first;
}

	$get_url = '';
	foreach	($_GET as $key=>$value) {
	  if( $key != 'id' && $key != '_' ) {   
	      $get_url .= '&' . $key . '=' . $value;
	  }
	}


$query_comments = "select comment_id from migwatch_photo_comments where photo_id='$photo_id'";
$result = mysql_query($query_comments);
$comments_count  = mysql_num_rows($result);
$prev .= $get_url;
$next .= $get_url;
$page = '';
$page =  $prev . "|" . $next ."|" . $user_name . "|" . $location_name . "|";
$page .= $location_geocodes . "|" . $species_name . "|"; 
$page .= "<img title='' src='http://migrantwatch.in/image_uploads/f_" . $photo_filename . "'>|";
$page .= $photo_id . "|" . $comments_count . "|";
$page .= $species_id . "|" . $location_id . "|" . $photo_user_id . "|" . $sighting_id . "|" . $photo_caption;
echo $page;
} 
?>