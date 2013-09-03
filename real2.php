<? include("db.php"); include("main_includes.php");  ?>

<script type="text/javascript">
      var delay = 2000;
      var count = 10;
      var showing = 5;
      var i = 0;
      function move(i) {
        return function() {
          $('#recent'+i).remove().css('display', 'none').prependTo('#items');
        }
      }
      function shift() {
        var toShow = (i + showing) % count;
        $('#recent'+toShow).slideDown(1000, move(i));
        $('#recent'+i).slideUp(1000, move(i));
        i = (i + 1) % count;
        setTimeout('shift()', delay);
      }
      $(document).ready(function() {
        setTimeout('shift()', delay);
      });
    </script>


<div class="heading"> 
      RECENT ACTIVITY
    </div> 
    <div id="items"> 
      
	<? $sql = "";
$sql = "SELECT l1.id,s.common_name,l.location_name,l.city,st.state,u.user_name,l1.sighting_date,l1.obs_type,l1.frequency,l1.obs_start,l1.user_friend,";
$sql .= "l.latitude,l.longitude";
$sql .= " FROM migwatch_l1 l1 INNER JOIN migwatch_users u ON l1.user_id=u.user_id ";
$sql .= " INNER JOIN migwatch_locations l ON l.location_id=l1.location_id ";
$sql .= " INNER JOIN migwatch_species s ON s.species_id=l1.species_id ";
$sql .= " INNER JOIN migwatch_states st ON st.state_id = l.state_id order by l1.id DESC LIMIT 10";

  $result=mysql_query($sql);
  while($data = mysql_fetch_assoc($result)) { 
     $data_get[] = $data['common_name'] . " from  " . $data['location_name'];
  }

  for($i=count($data_get); $i>=1; $i--) { 
?>
        <div style="display:none" id="recent<? echo $i; ?>" class="item"> 
          <? echo $i . ", " . $data_get[$i]; ?>
          <div style="clear:both"></div>      
        </div> 
      <? } ?>
        
      
    </div> 
  </div> 
