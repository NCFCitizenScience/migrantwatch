<?
	include("db.php");
	//This is the new deletion stuff...
	if(isset($_POST['ajax'])) {
	if(isset($_POST['id'])) {
	$id = (int)$_POST['id'];
        $sql = "DELETE FROM migwatch_l1 WHERE id = '$id'";
	mysql_query($sql);
        }
}

?>