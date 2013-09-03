<? session_start();
	include("db.php");
	
	//This is the new deletion stuff...
	if(isset($_GET['ajax'])) {
	if(isset($_GET['id'])) {

	$id = (int)$_GET['id'];
	$query= "select user_id from migwatch_photos where photo_id='$id'";
	$result = mysql_query($sql);
	while ( $data = mysql_fetch_assoc($result)) {
	      $user_id = $data['user_id'];
	}
	if( $user_id == $_SESSION['user_id']) {
            $sql = "DELETE FROM migwatch_l1 WHERE id = '$id'";
	    mysql_query($sql);
	    header("Location: uploadphotos.php?msg=success");
	}
	else {
	     header("Location: uploadphotos.php?msg=error");

	}
													}
}

?>