<?php
session_start();
include("./db.php");
include("functions.php");
$user_id = $_SESSION['userid'];
 // again if we are on php4
if (!function_exists("stripos")) {
	function stripos($str,$needle,$offset=0)
  	{
    	return strpos(strtolower($str),strtolower($needle),$offset);
  	}
}

$q = strtolower($_GET["q"]);
if (!$q) return;

$season = getCurrentSeason();
$season	= explode('-', trim($season));

$seasonStart = $season[0];
$seasonEnd = $season[1];

$sql = "SELECT location_id, location_name, city, district,state ";
$sql.= "FROM migwatch_locations l ";
$sql.= "INNER JOIN migwatch_states s ";
$sql.= "ON l.state_id = s.state_id ORDER BY s.state";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
    $location_id = $row['location_id'];
    $sql1 = "SELECT obs_start,frequency FROM migwatch_l1 WHERE location_id='$location_id' AND user_id = '$user_id'  AND deleted = '0' AND obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' ORDER BY sighting_date DESC LIMIT 0,1";
    $data1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($data1);    
    $obs_start = $row1['obs_start']; 
    $frequency = $row1['frequency']; 
    $key1 = $row['location_name'];
    $key1.= ', '.$row['city'];
    $key1.= ', '.$row['district'];
    $key1.= ', '.$row['state'];
        
    $items[$key1] = $row['location_id']."|".$row['location_name']."|".$row['city']."|".$row['district']."|".$row['state']."|".$obs_start."|".$frequency;
}
foreach ($items as $key=>$value) {
	if (stripos(strtolower($key), $q) !== false) {
		echo "$key|$value\n";
	} else {
           $loci[] = $key;
}     }

if(count($loci)>0) { echo "Don't find your location? Add a new location|0\n"; }

?>