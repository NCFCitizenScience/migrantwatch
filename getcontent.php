<? 
    include("db.php"); 
    include("functions.php"); 
    $where_clause = "";
    include("query_reports.php");
    $where_clause3 = " AND s.Active = '1' order by l1.id DESC";
   $sql .=  $where_clause3;
  
    $result = mysql_query($sql);   
    $total_count = mysql_num_rows($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
  <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
  </head> 
  <body> 
      <?
        $i=1;
        $final_report = '';
        while($line = mysql_fetch_assoc($result)) {
                  
                      $loc_name ="<h4>" . addslashes($line['location_name']) . ", " . addslashes($line['city']) . ", " . addslashes($line['state']) . "</h4><br>";
                      $user_reports  ="<dt><small>Displaying " . $i ."/" . $total_count . "<br>" . addslashes($line['common_name']) . ",   " .  addslashes($line['user_name']);
                      //if($line['user_friend']) { $user_reports .= " on behalf of "  . addslashes($line['user_friend']); }
                      $user_reports .=",   " . addslashes($line['sighting_date']);
                      $user_reports .= "</small></dt>"; 
                      $final_report .= stripslashes($user_reports);
		      
		       $total_reports['location'] =  $loc_name;
                      $i++;    
        } 
?>
<div class="tickerContainer" style='padding:10px;height:120px'>
         <div id='loc_name'><? echo  $total_reports['location']; ?></div>
	 <dl class='ticker'><? echo $final_report; ?></dl>
         <div class="nav" style='text-align:right'><a id="prev2" href="#x">Prev</a> <a id="next2" href="#x">Next</a></div>
</div>
  </body> 
</html>
