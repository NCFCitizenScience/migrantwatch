<? session_start();
   include("db.php");
   $strLocation = 'Type a location name';

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
        
	$sql = "UPDATE migwatch_species_watchlist SET url='$url', location='$location', state='$state',stype='$type',user='$user' where id='$id'";
        mysql_query($sql);
	?><script>parent.update_surl('<? echo $url; ?>','<? echo $id; ?>'); 
	parent.tb_remove();</script><?
   }

   if(is_numeric($_GET['location'])) {
       $location = $_GET['location'];
       $sql="select location_name,city,district from migwatch_locations where location_id='" . $_GET['location'] ."'";
       $result = mysql_query($sql);
       $data = mysql_fetch_assoc($result);
       
       $strLocation = $data['location_name'] . ", " . $data['city'] . ", " . $data['district'];
   }
?>
<? include("main_includes_thickbox.php"); ?>
<style>


	table { width:600px;margin-left:auto; margin-right:auto; }
	table td:first-child { font-weight:bold; text-align:right;width:150px }
	input[type=text] { width:200px; }
	select { width:200px }
</style>
<form  enctype="multipart/form-data" action="editwatchlist.php" method="post">
<table>
<tr>
   <td style=''>season</td>
   <td>
        <?php
                    $currentMonth = date('m');
                    $currentYear  = date('Y');
                    if ($currentMonth > 6) {
                        $endSeason = $currentYear;
                    } else {
                        $endSeason = $currentYear - 1;
                    }
		    echo "<select name='season'>";
		    // The sighting started in 2007-2008 so start from this season
                    for ($i = 2007;$i <= $endSeason; $i++) {
                        $fromTo = "$i-".($i+1);
                        echo '<option value="' . $fromTo  . '"';
                         
			    if(!$_GET['season']) { echo ' selected>'; } 
			    else if( $_GET['season'] == $fromTo ){ echo ' selected>'; } 
                            //echo ($_GET['season'] == $fromTo) ? ' selected>' : '>';
                        
                        echo $fromTo;
                        echo '</option>';
                    }
                    ?>
           
                    </select>
</tr>
<tr>
   <td>sighting&nbsp;type</td>
   <td>
     <select name="type">
         <option value="All">All</option>
         <option value="first"<?php if($_GET['type'] == 'first') print("selected"); ?>>First</option>
         <option value="general"<?php if($_GET['type'] == 'general') print("selected"); ?>>General</option>
         <option value="last"<?php if($_GET['type'] == 'last') print("selected"); ?>>Last</option>
     </select>
   </td>
</tr>

        <input type='hidden' id='species_hidden' name='id' value="<? echo $_GET['id']; ?>">

<tr>
   <td>participant</td>
   <td>
<?
	$sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
        $sql .= "l1.user_id=u.user_id WHERE l1.valid=1 ORDER BY u.user_name";
	$result = mysql_query($sql);
	echo "<select name=user>";
       	echo "<option value='All'>-- Select --</option>";
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
              print "<option value=".$row{'user_id'};
              if (($_GET['user'] != "") && ($_GET['user'] == $row{'user_id'}))
                     print " selected ";
               print ">".$row{'user_name'}."</option>";
        }
	echo "</option>";
	echo "</select>";
?>
   </td>
</tr>
<tr>
	<td>State</td>
	<td>
	    <select>
            <option value="all">All the States</option>
            <?php
                            $result = mysql_query("SELECT state_id, state FROM migwatch_states order by state");
                            if($result){
                                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                                if($row['state'] != 'Not In India') {
                                    print "<option value=".$row{'state_id'};
                                    if (($_GET['state'] != "") && ($_GET['state'] == $row{'state_id'}))
                                       print " selected ";
                                        print ">".$row{'state'}."</option>";
                                                                  } else {
                                        $other_id = $row['state_id'];
                                        $other = $row['state'];
                                    }
                                }
                                print("<option value=".$other_id);
                                                        if($other_id == $state_id)
                                    print " selected ";
                                                          print ">".$other."</option>\n";
                            }

                    ?></select>
	</td>
</tr>
<tr>
	<td>Location</td>
	<td>
	    <input type='text' id='location' value="<? echo $strLocation; ?>">
            <input type='hidden' id='location_hidden' name='location' value="<? echo $location; ?>">
	</td>
</tr>
<tr><td><input type='submit' name='save_changes' value='save all changes'></td></tr>
</table>
</form>
<script>
$().ready(function() {
$("#location").autocomplete("auto_location_watchlist.php", {
  width: 400,
  selectFirst: false,
  matchSubset :0,
  mustMatch: true,                          
});

$("#location").result(function(event , data, formatted) {
  if (data) {
         
         document.getElementById('location_hidden').value = data[1];
   }
});

});
</script>
<script src="js/jquery/ac/jquery.autocomplete.js" language="javascript"></script>
<script src="js/jquery/ac/jquery.bgiframe.min.js" language="javascript"></script>
<link href="js/jquery/ac/jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="js/jquery/ac/jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>
