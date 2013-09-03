<? include("auth.php");
include("db.php");
$species_id = $_GET['species'];
$user_id = $_SESSION['userid'];
$location_id = $_GET['location'];
if($species_id) {
	  
	  $sql="insert into migwatch_species_watchlist(species_id,user_id) values ('$species_id','$user_id')";
	  mysql_query($sql);
	  header("Location: watchlist.php");
	  
} else if($location_id) {

          $sql="insert into migwatch_location_watchlist(location_id,user_id) values ('$location_id','$user_id')";
          mysql_query($sql);
          header("Location: watchlist.php");

}

?>
