<? include("db.php"); ?>

<div class='realtime'><div class='realtime_header'>recent reports</div><br>
 <div id="items">
	<? $sql = "";
$sql = "SELECT l1.id,s.common_name,l.location_name,l.city,st.state,u.user_name,l1.sighting_date,l1.obs_type,l1.frequency,l1.obs_start,l1.user_friend,";
$sql .= "l.latitude,l.longitude";
$sql .= " FROM migwatch_l1 l1 INNER JOIN migwatch_users u ON l1.user_id=u.user_id ";
$sql .= " INNER JOIN migwatch_locations l ON l.location_id=l1.location_id ";
$sql .= " INNER JOIN migwatch_species s ON s.species_id=l1.species_id ";
$sql .= " INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
$sql .= " WHERE l1.valid='1' and l1.deleted='0'  order by l1.sighting_date DESC LIMIT 5";

  $result=mysql_query($sql);
  while($data = mysql_fetch_assoc($result)) { 
     $data_get[] = "<a href='sighting.php?id=" .  $data['id'] . "'>" . $data['common_name'] . " from  " . $data['city'] . "</a> by " . $data['user_name'] . " ("  . date("d-M",strtotime($data{'sighting_date'})) . ")";
  }

  for($i=0; $i <count($data_get);$i++) { 
?>
        <div id="recent<? echo $i; ?>" class="item">
          <? echo $data_get[$i]; ?><hr>
	  
        </div>
      <? } ?>
        
      
    </div> 
</div>