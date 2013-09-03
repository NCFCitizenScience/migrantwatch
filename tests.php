<?
include("db.php");
include("functions.php");

$season = getCurrentSeason();
$season = explode('-', trim($season));

echo $seasonStart = $season[0];
?>