<?
include("db.php");
$limit = 'LIMIT 0,5';
$sql = "SELECT s.common_name, l.latitude,l.longitude, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
$sql.= "l1.obs_type, l1.id, l1.deleted, l1.number, u.user_name FROM migwatch_l1 l1 ";
$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
$sql.= "INNER JOIN migwatch_users u ON l1.user_id = u.user_id ";
$sql.= "WHERE l1.deleted = '0' AND valid = '1' AND l.latitude!='' AND l.longitude!=''";
//$sort = ($orderBy == 'sighting_date') ? $sort : $sortTy;
$sort = ' sighting_date DESC,number';
$sql.= $srt = " GROUP BY l.location_name ORDER BY $orderBy $sort $limit";

if ($_GET['action'] == 'listpoints') {
   //$query = "SELECT * FROM locations";
   $result = mysql_query($sql);
   $points = array();
   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
   	 array_push($points, array('name' => $row['location_name'], 'lat' => $row['latitude'], 'lng' => $row['longitude'],'state' => $row['state'],'sname' => $row['common_name'], 'user' => $row['user_name']));
	 }
	 echo json_encode(array("Locations" => $points));
	 exit;
}

?>