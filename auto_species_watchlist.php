<?php
include("./db.php");

 // again if we are on php4
if (!function_exists("stripos")) {
	function stripos($str,$needle,$offset=0)
  	{
    	return strpos(strtolower($str),strtolower($needle),$offset);
  	}
}

$q = strtolower($_GET["q"]);
if (!$q) return;


$sql = "SELECT species_id,common_name,scientific_name,alternative_english_names,show_hint FROM migwatch_species";
if (!isset($_GET['all'])) {
	$sql .= " WHERE Active = '1'";
}
$res = mysql_query($sql,$connect);

while($row = mysql_fetch_assoc($res)) {

	$key = addslashes($row['common_name']);

	if (!empty($row['alternative_english_names'])) {
		$row['alternative_english_names'] = str_replace(',',' or ',$row['alternative_english_names']);
		$key .= ' or '. addslashes($row['alternative_english_names']);
	}

	if (!empty($row['scientific_name'])) {
		$key .= ' ('.$row['scientific_name'].')';
	}

	//$items[trim($key)] = $row['show_hint'];
        $items[trim($key)] = $row['species_id'];
}

foreach ($items as $key=>$value) {
	if (stripos(strtolower($key), $q) !== false) {
		echo stripslashes($key) . "|$value\n";
	}
}
?>