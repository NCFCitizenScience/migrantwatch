<?
$confirm = $_GET['val'];

include("./db.php");
include("functions.php");

if($confirm) {

	$sql = "select * from migwatch_users where hashkey='$confirm'";
	$result=mysql_query($sql);
	$num = mysql_num_rows($result);

if($num > 0 ) {		
    $sql = "update migwatch_users SET confirm=1 where hashkey='$confirm'";
    mysql_query($sql);
    header("Location: login.php?cmd=confirmed"); 
} else {

    header("Location: reconfirm.php");
}

}

?>
