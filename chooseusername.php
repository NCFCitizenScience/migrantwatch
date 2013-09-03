<? session_start();
   include("db.php"); 
   if($_SESSION) {
     if($_POST['change_username']) {
   	$username = $_POST['username'];
        $user_id = $_POST['userid'];
        $sql="update migwatch_users set username='$username' where user_id='$user_id'";
	mysql_query($sql);
	header("Location: editprofile.php");	
    } }
?>