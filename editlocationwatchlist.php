<? session_start();
   include("db.php");
   $strSpecies = 'Type a species name';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>
	<script src="js/jquery/ac/jquery.autocomplete.js" language="javascript"></script>
<script src="js/jquery/ac/jquery.bgiframe.min.js" language="javascript"></script>
<link href="js/jquery/ac/jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="js/jquery/jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>

</head>
<body>
<?
   if ( !($_SESSION['userid']) ) {
      
      echo "<div class='error1'>You session has expired. Please login back to continue further</div>";
      exit();

   }
   if($_REQUEST['save_changes']) {

        $id =  $_REQUEST['id'];
        if( $season = $_POST['season'] ) { $url = "season=" . $season; } else { $season = ''; }
        if( $species = $_POST['species'] ) { $url .= "&species=" . $species; } else { $species = ''; }
        if( $state = $_POST['state'] )  { $url .= "&state=" . $state; } else { $state = ''; }
        if( $type = $_POST['type'] ) { $url .= "&type=" . $type; } else { $type = ''; }
        if( $user = $_POST['user'] ) { $url .= "&user=" . $user; } else { $user = ''; }
        $sql = "UPDATE migwatch_location_watchlist SET url='$url', season='$season', species='$species', state='$state', stype='$type', user='$user' where id='$id'";
        mysql_query($sql);
	?><script> 
	parent.page_refresh();
	parent.tb_remove();
	</script><?
   }

   if(is_numeric($_GET['species'])) {
       $species = $_GET['species'];
       $sql="select common_name, scientific_name from migwatch_species where species_id='$species'";
       $result = mysql_query($sql);
       $data = mysql_fetch_assoc($result);       
       $strSpecies = $data['common_name'] . " (" . $data['scientific_name'] . ")";
   }
?>
<? include("main_includes_thickbox.php"); ?>
<script src="js/jquery/ac/jquery.autocomplete.js" language="javascript"></script>
<script src="js/jquery/ac/jquery.bgiframe.min.js" language="javascript"></script>
<link href="js/jquery/ac/jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="js/jquery/jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>
<style>


	table { width:600px;margin-left:auto; margin-right:auto; }
	table td:first-child { font-weight:bold; text-align:right;width:150px }
	input[type=text] { width:200px; }
	select { width:200px }
</style>
<form  enctype="multipart/form-data" action="editlocationwatchlist.php" method="post">
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
		    echo "<option value=''>All seasons</option>";
		    // The sighting started in 2007-2008 so start from this season
                    for ($i = 2007;$i <= $endSeason; $i++) {
                        $fromTo = "$i-".($i+1);
                        echo '<option value="' . $fromTo  . '"';
                         echo ($_GET['season'] == $fromTo) ? ' selected>' : '>';                        
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
         <option value="">All</option>
         <option value="first"<?php if($_GET['type'] == 'first') print("selected"); ?>>First</option>
         <option value="general"<?php if($_GET['type'] == 'general') print("selected"); ?>>General</option>
         <option value="last"<?php if($_GET['type'] == 'last') print("selected"); ?>>Last</option>
     </select>
   </td>
</tr>

        <input type='hidden' name='id' value="<? echo $_GET['id']; ?>">

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
</tr><!--
<tr>
	<td>State</td>
	<td>
	    <select name='state'>
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
</tr>-->
<tr>
	<td>Species</td>
	<td>
	    <input type='text' id='species' value="<? echo $strSpecies; ?>">
            <input type='hidden' id='species_hidden' name='species' value="<? echo $species; ?>">
	</td>
</tr>
<tr><td colspan='2' style='text-align:center'><input type='submit' name='save_changes' value='save all changes'></td></tr>
</table>
</form>
<script>

function update_lurl(url,id) {
   $('#lurl-' + id).attr({'href': 'data.php?' + url});
   $('#ledit-' + id).attr({'href': 'editwatchlist.php?id='+ id + '&' + url + '&TB_iframe=true&width=600&height=400'});
}


$().ready(function() {
$("#species").autocomplete("auto_species_watchlist.php", {
  width: 400,
  selectFirst: false,
  matchSubset :0,
  mustMatch: true,                          
});

$('#species').emptyonclick();

$("#species").result(function(event , data, formatted) {
  if (data) {         
         document.getElementById('species_hidden').value = data[1];
   }
});

});
</script>

</body>
</html>