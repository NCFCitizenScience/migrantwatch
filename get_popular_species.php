<div class='realtime'><div class='realtime_header'>popular species<br><small><? echo $sixm_date_h; ?> to <? echo $today_date_h ; ?></small></div><br>
 <div class="popspecies">
	<? $sql = "";
	$sql = "select s.species_id, s.common_name, s.scientific_name, COUNT(*) as num from migwatch_l1 as l1, migwatch_species as s where s.species_id=l1.species_id ";
$sql .=" and sighting_date BETWEEN '$sixm_date' AND '$today_date' and l1.valid='1' and l1.deleted='0' group by  l1.species_id order by num desc limit 6";
     	 $result=mysql_query($sql);
  	 while($data = mysql_fetch_assoc($result)) { 
    	 	     $percent_num = ( $data['num'] / $total_count ) * 100;
    		     $percent_num = number_format($percent_num,0);
   ?>
<ul class="chartlist">
      <li>
        <a href="http://migrantwatch.in/data.php?species=<? echo $data['species_id']; ?>"><? echo $data['common_name']; ?></a>
        <span class="count"><? echo $data['num']; ?></span>
        <span class="index" style="width: <? echo $percent_num; ?>%">(<? echo $percent_num; ?>)</span>
      </li>
  </ul>
<?
}
?>
       </div> 
</div>