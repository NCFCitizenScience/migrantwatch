<?php
session_start();
include("./db.php");
include("functions.php");
$user_id = $_SESSION['userid'];

$today = getdate();
$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;

 // again if we are on php4
if (!function_exists("stripos")) {
	function stripos($str,$needle,$offset=0)
  	{
    	return strpos(strtolower($str),strtolower($needle),$offset);
  	}
}

$q = strtolower($_GET["q"]);
if (!$q) return;

$locations = getAllMyLocationData($user_id, $connect);
$mylocations = array();
$where = "";
if(!empty($locations)) {
    foreach($locations as $location) {
        $mylocations[] = $location['location_id'];
    }
    $myloc_ids = implode(", ", $mylocations);
    $where = "WHERE location_id NOT IN($myloc_ids)";
}
$sql = "SELECT location_id, location_name, city, district, latitude, longitude, state ";
$sql.= "FROM migwatch_locations l ";
$sql.= "INNER JOIN migwatch_states s ";
$sql.= "ON l.state_id = s.state_id $where ORDER BY s.state";

$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
    $locid = $row['location_id'];
    $sql1 = "SELECT obs_start,frequency FROM migwatch_l1 WHERE location_id='$locid' " .
      "AND user_id = '$user_id' AND obs_start >'$currentSeason-06-30' AND obs_type = 'first' AND deleted = '0' ORDER BY sighting_date DESC LIMIT 0,1";

    $result1 = mysql_query($sql1);
    $data1 = mysql_fetch_assoc($result1);

    $key1 = $row['location_name'];
    $key1.= ', '.$row['city'];
    $key1.= ', '.$row['district'];
    $key1.= ', '.$row['state'];
    $items[$key1] = $row['location_id']."|".$row['location_name']."|".$row['city']."|".$row['district']."|".$row['state']."|".$row['latitude']."|".$row['longitude']."|".$data1['obs_start']."|".$data1['frequency'];
}
foreach ($items as $key=>$value) {
	if (stripos(strtolower($key), $q) !== false) {
		echo "$key|$value\n";
	} 
}
//print_r($mylocations);
?>