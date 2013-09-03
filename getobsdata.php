<?
session_start();
include("db.php");
include("functions.php");
$user_id = $_SESSION['userid'];
$season = getCurrentSeason();
$season = explode('-', trim($season));
$seasonStart = $season[0];
$seasonEnd = $season[1];

$location = $_GET['loc'];

 $sql1 = "SELECT obs_start,frequency FROM migwatch_l1 WHERE location_id='$location' AND user_id = '$user_id'  AND deleted = '0' AND obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' ORDER BY sighting_date DESC LIMIT 0,1";

    $data1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($data1);    
        if($row1{'obs_start'}) { $obs_start = date("d-m-Y",strtotime($row1{'obs_start'})); }
         $frequency = $row1['frequency'];
         echo $obs_start . "___" . $frequency;

?>