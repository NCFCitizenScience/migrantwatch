<?php
$db = new MySqli();
$db->connect('localhost','root','','migwatch');
$handle = fopen("test.csv", "r");
while ((list($name,$sciname,$population,$habitat,$core,$active) = fgetcsv($handle, 1000, ",")) !== FALSE) {
	$stmt = $db->prepare("INSERT INTO migwatch_species (common_name,scientific_name,population,habitat_type,core,Active) VALUES (?,?,?,?,?,?)");
	$stmt->bind_param('ssssii',$name,$sciname,$population,$habitat,$core,$active);
	$stmt->execute();
	$stmt->close();
}
$db->close();
?>