<? include("db.php"); 
$today_date = date('Y-m-d',strtotime("now"));
$today_date_h = date('d-M-Y',strtotime("now"));
$sixm_date = date('Y-m-d',strtotime("-1 month"));
$sixm_date_h = date('d-M-Y',strtotime("-1 month"));
?>
<div class='realtime'><div class='realtime_header'>top contributors<br><small><? echo $sixm_date_h; ?> to <? echo $today_date_h ; ?></small></div><br>
 <div class="popuser">
<? $sql = "";
$sql2 ="select id from migwatch_l1 where sighting_date BETWEEN '$sixm_date' AND '$today_date' and valid='1' and deleted='0'";
$result2=mysql_query($sql2);
$total_count =mysql_num_rows($result2);

$sql = "select u.user_name,l1.user_id, COUNT(*) as num from migwatch_l1 as l1, migwatch_users as u where l1.user_id=u.user_id and sighting_date BETWEEN ";
$sql .=" '$sixm_date' AND '$today_date' and l1.valid='1' and l1.deleted='0' group by user_id order by num desc limit 6";


  $result=mysql_query($sql);
  while($data = mysql_fetch_assoc($result)) { 

    $percent_num = ( $data['num'] / $total_count ) * 100;
    $percent_num = number_format($percent_num,0);
   ?>
<ul class="chartlist"> 
      <li> 
        <a href="http://migrantwatch.in/data.php?user=<? echo $data['user_id']; ?>"><? echo $data['user_name']; ?></a> 
        <span class="count"><? echo $data['num']; ?></span> 
        <span class="index" style="width: <? echo $percent_num; ?>%">(<? echo $percent_num; ?>)</span> 
      </li>
  </ul><?
  }
?>
       </div> 
</div>