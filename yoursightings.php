<? session_start();
   
   if ( !($_SESSION['userid']) ) {
      
      echo "<div class='error1'>You session has expired. Please login back to continue further</div>";
      exit();

   }
$user_id = $_SESSION['userid'];
include("db.php");
include("functions.php");
include("main_includes_thickbox.php");

$location_id = $_GET['loc'];
$sighting_type = $_GET['sighting'];
$species_id = $_GET['species'];

list($seasonStart,$seasonEnd) = getCurrentSeason(true);
$sql = "SELECT l1.obs_start,l1.number,l1.notes,s.common_name, l1.user_id, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
                $sql.= "l1.obs_type, l1.id, l1.deleted FROM migwatch_l1 l1 ";
                $sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
                $sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
                $sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
                $sql.= "WHERE l1.deleted = '0' AND l1.user_id = '$user_id' AND ";
                $sql.= " l1.location_id = '$location_id' AND l1.valid='1' ";
                /* $sql.= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
		$sql.= " AND l1.obs_start BETWEEN '2008-07-01' AND '2009-06-30'
                 AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' "; */
	        if( $species ) { $sql.= " AND l1.species_id = '$species_id'"; }
                $orderBy = 'sighting_date ';

                $sort = 'DESC';
                $sql.= " ORDER BY l1.id $sort ";
		
                $result = mysql_query($sql,$connect);
                $total_rows = mysql_num_rows($result);

?>

	<?  if($total_rows > 0) { ?>
	<table id="table1" cellpadding=3 cellspacing=0 class="tablesorter">
	       <thead>
                        <tr>
                                <th>No.</th>
                                <th>Species</th>
                                <th><?php echo $sighting ?> Sighting Date</th>
                                <th>Observation type</th>
                        </tr>
	   </thead>
	   <tbody>
                        <?php
                        
                                $i = 1;
                                $j = (($pageno - 1) * $rows_per_page) + 1;
                                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                     
                                        print "<tr bgcolor=".($i % 2 == 0?"#efefef":"#ffffff").">";
                                        print "<td>".$j."</td>";
                                        print "<td>".$row{'common_name'}."</td>";
                                        print "<td>".date("d-M-Y",strtotime($row{'sighting_date'}))."</td>";
                                        print "<td>"; echo $row{'obs_type'}; print "</td>";
                                        print "</tr>";
                                        $i++;
                                        $j++;
                                }
                        
                ?>
</tbody>
        </table>

	<div id="pager">
     <form name="" action="" method="post">
          <table>
          <tr>
                <td><img src='pager/icons/first.png' class='first'/></td>
                <td><img src='pager/icons/prev.png' class='prev'/></td>
                <td><input type='text' size='8' class='pagedisplay'/></td>
                <td><img src='pager/icons/next.png' class='next'/></td>
                <td><img src='pager/icons/last.png' class='last'/></td>
                <td>
                        <select class='pagesize' style='width:50px'>
                        <option selected='selected'  value='10'>10</option>
                        <option value='20'>20</option>
                        <option value='30'>30</option>
                        <option  value='40'>40</option>
                        </select>
                </td>
          </tr>
          </table>
     </form>
<? } else {  

   echo "<div class='notice'>You have no previous " . ucfirst($sighting_type) . " sighting from this location</div>";
    
 } ?>
</div>
<style>
#table1 { border-right: solid 0.5px #ffcb1a; width:90%; margin-left:auto;margin-right:auto; font-size:12.5px }
#pager { width:200px;margin-left:20px;}
.notice { width: 90%; margin-left:auto;margin-right:auto; font-weight:bold; font-size:12.5px; text-align:center }
</style>
<script type="text/javascript" src="pager/jquery.tablesorter.js"></script>
<script type="text/javascript" src="pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="pager/chili-1.8b.js"></script>
<script type="text/javascript" src="pager/docs.js"></script>
<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
<link href="pager/style.css"  rel="stylesheet" type="text/css" />
<script>
 $(function() {
             $("#table1")
                .tablesorter({  headers: { 
                   5: { sorter: false }, 6: { sorter: false }, 7 : { sorter: false }, 8: { sorter: false } },widthFixed: true, widgets: ['zebra']})
                   .tablesorterPager({container: $("#pager"), positionFixed: false});
		 });
</script>
</body>
</html>