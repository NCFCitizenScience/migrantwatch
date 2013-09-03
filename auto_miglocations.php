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

$sql = "SELECT location_id, location_name, city, district,state ";
$sql.= "FROM migwatch_locations l ";
$sql.= "INNER JOIN migwatch_states s ";
$sql.= " ON l.state_id = s.state_id"; 
$sql .=" ORDER BY s.state";

$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
    $location_id = $row['location_id'];
    $key1 = $row['location_name'];
    $key1.= ', '.$row['city'];
    $key1.= ', '.$row['district'];
    $key1.= ', '.$row['state'];
        
    $items[$key1] = $row['location_id']."|".$row['location_name']."|".$row['city']."|".$row['district']."|".$row['state'];
}
foreach ($items as $key=>$value) {
	if (stripos(strtolower($key), $q) !== false) {
		echo "$key|$value\n";
	} else {
           $loci[] = $key;
}     }

if(count($loci)>0) { echo "Don't find your location? Add a new location|0\n"; }

?>