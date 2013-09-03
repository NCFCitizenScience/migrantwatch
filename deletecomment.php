<?php
session_start();
include("db.php");

if ($_SESSION['userid']) {
if(isset($_GET['id']))
{ 

	$user_id  = $_SESSION['userid'];
	$comment_id = $_GET['id'];
	$sql = "select user_id,photo_id from migwatch_photo_comments where comment_id='$comment_id'";
	$results = mysql_query($sql);
	while($data = mysql_fetch_assoc($results)){
		    $user_id_comment = $data['user_id'];
		    $photo_id = $data['photo_id'];
	}
	if( $user_id == $user_id_comment ) {
	$sql ="delete from migwatch_photo_comments where comment_id='$comment_id'";
	mysql_query($sql);
	
	$sql = "select comment_id from migwatch_photo_comments where photo_id='$photo_id'";
	$result1 = mysql_query($sql);
	$total_comments = mysql_num_rows($result1);

         }
$comment_no = $total_comments;

echo $comment_no . " comments";
 }
 
}
?>
