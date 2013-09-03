<?php
session_start();
include("db.php");

if ($_SESSION['userid']) {
if(isset($_GET['id']))
{ 

	$user_id  = $_SESSION['userid'];
	$watch_id = $_GET['id'];
	$sql = "select user_id from migwatch_location_watchlist where id='$watch_id'";
	$results = mysql_query($sql);
	while($data = mysql_fetch_assoc($results)){
		    $user_id_w = $data['user_id'];
	}
	if( $user_id == $user_id_w ) {
	$sql ="delete from migwatch_location_watchlist where id='$watch_id'";
	mysql_query($sql);
	
         }

 }
 
}
?>
