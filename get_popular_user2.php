

<div class='realtime'><div class='realtime_header'>top contributors</div><br>
 <div class="popuser">
	<? $sql = "";
$year=date("Y");
$month=date("m");
$prev_month=$month - 1;

 $season = getCurrentSeason();                        
   $seasonArr = explode('-',$season);
   $seasonStart = $seasonArr[0];
   $seasonEnd = $seasonArr[1];


$sql = "select u.user_name,l1.user_id, COUNT(*) as num from migwatch_l1 as l1, migwatch_users as u where l1.user_id=u.user_id and sighting_date BETWEEN ";
$sql .=" '$seasonStart-07-01' AND '$seasonEnd-06-30' group by user_id order by num desc limit 7";

  $result=mysql_query($sql);
  while($data = mysql_fetch_assoc($result)) { 

   ?><div style='text-align:center'><?
      echo $data['user_name'] . " (" . $data['num'] . " records)";
      echo "<hr></div>";
  }
?>
       </div> 
</div>