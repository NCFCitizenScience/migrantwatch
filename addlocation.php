<?
session_start();
include("db.php");
$user_id = $_SESSION['userid'];
if( $_REQUEST['add_location'] ) {
     $loc_name = strip_tags(trim(addslashes($_REQUEST['loc_name'])));
     $loc_lat = trim($_REQUEST['lat']);
     $loc_lng = trim($_REQUEST['lng']);
     $loc_state_id = trim($_REQUEST['state']);
     $sql = "select state from migwatch_states where state_id='$loc_state_id'";
     $result = mysql_query($sql);
     while ($data = mysql_fetch_assoc($result)){
       $loc_state = $data['state'];
     }
     $loc_city = strip_tags(trim($_REQUEST['city']));
     $loc_country = strip_tags(trim($_REQUEST['country']));
     $loc_zoom  = strip_tags(trim($_REQUEST['zoom']));
     $loc_sighting  = trim($_REQUEST['sighting']);

     if( strtolower($loc_country) != 'india' ) {
        echo "<div class='notice'>Please choose a location only from India</div>";
     } else if ( ($loc_state != '') && ($loc_name != '') && ($loc_lat != '') && ($loc_lng != '') ) {

	$sql = "INSERT INTO migwatch_locations (location_name,city,state_id,latitude,longitude,created_by_user_id,loc_map_zoom) VALUES ('$loc_name','$loc_city','$loc_state_id','$loc_lat','$loc_lng','$user_id','$loc_zoom')";
        mysql_query($sql);
        $lastInsertId = mysql_insert_id();
        $sql = "INSERT INTO migwatch_user_locs (user_id,location_id) VALUES ($_SESSION[userid],$lastInsertId)";
        mysql_query($sql);
    
?>
		<script>
                                var loc = "<? echo $loc_name; ?>, " + "<? echo $loc_city; ?>, " + "<? echo $loc_state; ?>";
                                var sighting = '<? echo $loc_sighting; ?>';
				var loc_id = '<?  echo $lastInsertId; ?>';
     				parent.new_info(loc_id,loc);
                               	parent.tb_remove();                                
		
	        </script>
      <?
}
}

?>

