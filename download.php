<?php
include("checklogin.php");

$current_url = $_SERVER['REQUEST_URI'];
require('db.php');
include("functions.php");

include("query_reports.php");

$sql .= " AND s.Active = '1' order by l1.id DESC";
$result = mysql_query($sql);

if(!$_SESSION['userid']) {

header("Location: login.php?done=" . $current_url);
}

if (!$result) 
{
  die('Invalid query: ' . mysql_error());
}

if($_GET['output'] == 'kml' ) {

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
  if($row['latitude'] && $row['longitude']) {
  // Creates a Placemark and append it to the Document.

  $node = $dom->createElement('Placemark');
  $placeNode = $docNode->appendChild($node);

  // Creates an id attribute and assign it the value of id column.
  $placeNode->setAttribute('id', 'placemark' . $row['id']);

  // Create name, and description elements and assigns them the values of the name and address columns from the results.
  $nameNode = $dom->createElement('name',htmlentities($row['user_name']));
  $placeNode->appendChild($nameNode);
 
  $final_description = "Species: " . $row['common_name'] . "<br>Reported by: " .  $row['user_name'] . "<br>Location: " . $row['location_name'];

  $descNode = $dom->createElement('description', htmlentities($final_description));
  $placeNode->appendChild($descNode);
  $styleUrl = $dom->createElement('styleUrl', '#' . htmlentities($row['sighting_date']) . 'Style');
  $placeNode->appendChild($styleUrl);

  // Creates a Point element.
  $pointNode = $dom->createElement('Point');
  $placeNode->appendChild($pointNode);

  // Creates a coordinates element and gives it the value of the lng and lat columns from the results.
  $coorStr = $row['longitude'] . ','  . $row['latitude'];
  $coorNode = $dom->createElement('coordinates', $coorStr);
  $pointNode->appendChild($coorNode);
  }
}

$kmlOutput = $dom->saveXML();
header('Content-type: application/vnd.google-earth.kml+xml');
header("Content-Disposition: attachment; filename=search_results.kml");
echo $kmlOutput;

} else if($_GET['output'] == 'csv' ) {
  //$out = '';
  $columns = mysql_num_fields($result);
  $out .= "Species , Location name, City , State, Reporter, Date, Sighting type, Observation frequency, Start date, On behalf of, Latitude,Longitude";
  $out .="\n";
  while ($row = mysql_fetch_array($result)) {
     $out .='"'.$row["common_name"].'",';
     $out .='"'.$row["location_name"].'",';
     $out .='"'.$row["city"].'",';
     $out .='"'.$row["state"].'",';
     $out .='"'.$row["user_name"].'",';
     $out .='"'.$row["sighting_date"].'",';
     $out .='"'.$row["obs_type"].'",';
     $out .='"'.$row["frequency"].'",';
     $out .='"'.$row["obs_start"].'",';
     $out .='"'.$row["user_friend"].'",';
     $out .='"'.$row["latitude"].'",';
     $out .='"'.$row["longitude"].'",';
 
  $out .="\n";
  }
  // Output to browser with appropriate mime type, you choose ;)
  header("Content-type: text/x-csv");
  header("Content-Disposition: attachment; filename=migrantwatch_reports.csv");
  echo $out;

}
?>
