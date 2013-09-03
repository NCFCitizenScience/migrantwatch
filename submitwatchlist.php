<? /* session_start();
   include("db.php");

   if ( !($_SESSION['userid']) ) {

      echo "<div class='error1'>You session has expired. Please login back to continue further</div>";
      exit();

   }
   if($_REQUEST['save_changes']) {
        $id =  $_REQUEST['id'];
        if( $season = $_POST['season'] ) { $url = "season=" . $season; }
        if( $location = $_POST['location'] ) { $url .= "&location=" . $location; }
        if( $state = $_POST['state'] )  { $url .= "&state=" . $state; }
	if( $type = $_POST['type'] ) { $url .= "&type=" . $type; }
	if( $user = $_POST['user'] ) { $url .= "&user=" . $user; }
        echo $url;
        $sql = "UPDATE migwatch_species_watchlist SET url='$url' where id='$id'";
	mysql_query($sql);

   }*/

echo "hello";
?>
