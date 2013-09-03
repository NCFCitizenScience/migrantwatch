<?php
session_start();
include("db.php");

 // again if we are on php4
if (!function_exists("stripos")) {
	function stripos($str,$needle,$offset=0)
  	{
    	return strpos(strtolower($str),strtolower($needle),$offset);
  	}
}

$q = strtolower($_GET["q"]);
if (!$q) return;


$sql = "SELECT s.species_id,s.common_name,s.scientific_name,s.alternative_english_names FROM migwatch_species as s, migwatch_species_watchlist as w where s.species_id != w.species_id AND w.user_id=" . $_SESSION['userid'];

$res = mysql_query($sql,$connect);

while($row = mysql_fetch_assoc($res)) {

	$key = $row['common_name'];

	if (!empty($row['alternative_english_names'])) {
		$row['alternative_english_names'] = str_replace(',',' or ',$row['alternative_english_names']);
		$key .= ' or '.$row['alternative_english_names'];
	}

	if (!empty($row['scientific_name'])) {
		$key .= ' ('.$row['scientific_name'].')';
	}

	//$items[trim($key)] = $row['show_hint'];
        $items[trim($key)] = $row['species_id'];
}

foreach ($items as $key=>$value) {
	if (stripos(strtolower($key), $q) !== false) {
		echo "$key|$value\n";
	}
}
?>