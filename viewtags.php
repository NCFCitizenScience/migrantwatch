<?php
	session_start(); 
	$strLocation = "Type a location name";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>
	<link href="combine_css.php?version=<?php require('combine_css.php'); ?>" rel="stylesheet" type='text/css'>
	<style type="text/css">
	       .input[type=text] {
	       	 padding:2px;
	        }
		.unselect {
  		  padding:3px;
  		  width:100px;
  		  text-decoration:none;
  		  color:#fff;
  		  font-weight:normal;
  		  background-color:#ddd;
		}

		.unselect:hover {
  		   color:#fff;
  		   text-decoration:none;
		}

</style>
</head>
<body>
<?
include("header.php");

if( $_GET['page'] ) {
$cur_page = $_GET['page'];
} else {
   $cur_page = 1;
}


include("pagination.php");
$sql = "SELECT distinct p.photo_id, p.photo_filename,p.photo_caption,l.location_id, l.location_name,l.city,l.district, u.user_name, s.state, c.common_name FROM migwatch_l1 l1 INNER JOIN migwatch_species c ON l1.species_id = c.species_id INNER JOIN migwatch_locations l ON l1.location_id = l.location_id INNER JOIN migwatch_states s ON s.state_id = l.state_id INNER JOIN migwatch_users u ON u.user_id = l1.user_id INNER JOIN migwatch_photos p ON p.sighting_id = l1.id WHERE 1=1 ";

if(isset($_GET['season']) && $_GET['season'] != 'All') {
	$season = $_GET['season'];                	        
} 

if($season) {       
$seasonArr = explode('-',$season);
$seasonStart = $seasonArr[0];
$seasonEnd = $seasonArr[1];
$where_clause = '';
$where_clause .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";

if( ( $_GET['species'] != "All" ) || $_GET['species'] != "" ) {
    $species = $_GET['species'];
} else {
    $species = "All";
}
}
                                                              
if( $species && is_numeric($species) ) {                                 
	$where_clause .= " AND l1.species_id=". $species;
	$tempsql = "select common_name from migwatch_species where species_id=".$species;
   $result = mysql_query($tempsql);
   if($result) {
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $strSpecies = $row{'common_name'};
     }
   }
}
                                        
if( (strtolower($_GET['location']) != 'all') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
	$location = $_GET['location'];
} else {	          
	$location = 'All';
}

if( is_numeric($location) ) {   
	$where_clause .= " AND l1.location_id=". $location;
}
              			
if( is_numeric($_GET['user'])) {
	$user = $_GET['user'];
} else {
   $user = 'All';
}

if ( is_numeric($user)) {
	$where_clause .= " AND l1.user_id = ".$_GET['user'];
}
				
if($_GET['type'] != 'All' || $_GET['type'] != '') {
   $type =  $_GET['type'];    
} else {
	$type = 'All';	
}

if($type) {
     if( strtolower($type) != 'all') {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
 		} else {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0'";
      }
}

if ($_SESSION['dev_entries'] == 1) {
	// Show all entries : including the ones from developer
} else {
	$where_clause .= " AND u.user_name != 'Developer'";
}

$sql .= $where_clause;

$result = mysql_query($sql);
$total_items = mysql_num_rows($result);
$name='photos';
$per_page=4;

if($cur_page == 1 ) {  $cur_page_start = 0; } else { $cur_page_start = $cur_page_start + 4; }
$query1 = "$sql order by photo_id DESC LIMIT $cur_page_start , $per_page";

$result_final = mysql_query($query1);

?>

<div class="container first_image">
     <div id='tab-set'>   
             <ul class='tabs'> 
                 <li><a href='#' class='selected'>photos</a></li> 
             </ul> 
     </div>
     <div class="container">
     	  <FORM name="reports" action="viewtags.php" method="GET">
	  <table class="filter" style="width:930px;margin-left:auto;margin-right:auto;">
	  <tr>
		<td>season</td>
	 	<td>species</td>
         	<td>participant</td>
         	<td></td>
       	  </tr>
       	  <tr>
		<td style="width:300px;">
                    <select style="width:85%;font-size:12px;" name='season'>
<?php
		    echo '<option value="">Select a season</option>';
                    /**
                     * Use the current month and year to find out the last season to be
                     * displayed in the drop down. (Season : 1st July - 31st Aug)
                     */
                    $currentMonth = date('m');
                    $currentYear  = date('Y');

                    /**
                     * If the current month is greater than June i.e July and onwords only then display
                     * the current year in the season
                     */
                    if ($currentMonth > 6) {
                        $endSeason = $currentYear;
                    } else {
                        $endSeason = $currentYear - 1;
                    }

                    // The sighting started in 2007-2008 so start from this season
                    for ($i = 2007;$i <= $endSeason; $i++) {
                        $fromTo = "$i-".($i+1);
                        echo '<option value="' . $fromTo  . '"';
                        if (empty($_GET)) {
                            echo ' selected>';
                           
                        } else {
                            echo ($_GET['season'] == $fromTo) ? ' selected>' : '>';
                        }
                        echo $fromTo;
                        echo '</option>';
                    }
?>
           
                    </select>
		    <? $current_season = getCurrentSeason();
                   
                       if( $_GET['season'] != '') {		       
		    ?>
                     <a href="#" onClick="get_remove('season');">X</a>
                    
                       <? } ?>

                   </td> 
		   
                   <td style="width:400px;">
             <input type='text' id='species' size="25" style="padding:2px;width:90%;border:solid 1px #666" value="<? if ($strSpecies) { echo $strSpecies;  } else { echo 'Type a species name'; } ?>">
		       <input type='hidden' id='species_hidden' name='species' value="<? echo $species; ?>">
                             <? if( is_numeric($_GET['species'] )) {  ?>
                                  <a href="#" onclick="get_remove('species');">X</a>
                             <? } ?>
	           </td>
		   <td style="width:300px;">
                       <select name=user style="width:91%">
                       	       <option value='All'>-- Select --</option>
                    <?php
			$today = getdate();
                    	$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;
                    	$season = (isset($_POST['season'])) ? substr($_POST['season'], 0, 4) : $currentSeason;
                    	$seasonEnd = (int)$season + 1;

                    $sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
                     //$sql = " INNER JOIN migwatch_photos p ON ";
                    $sql .= "l1.user_id=u.user_id WHERE l1.valid=1  ";
                    //$sql .= "AND l1.obs_start BETWEEN '$season-07-01' AND '$seasonEnd-06-30'  ";
                    $sql .= "ORDER BY u.user_name";
                    echo $sql;
                    $result = mysql_query($sql);
                    if(mysql_num_rows($result) <= 0) {
                        $sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
                        //$sql = " INNER JOIN migwatch_photos p ON ";
                        $sql .= " l1.user_id=u.user_id WHERE l1.valid=1 ORDER BY u.user_name";
                        $result = mysql_query($sql);
                    }
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                        print "<option value=".$row{'user_id'};
                        if (($_GET['user'] != "") && ($_GET['user'] == $row{'user_id'}))
                            print " selected ";
                        print ">".$row{'user_name'}."</option>";
                    }
                    ?>
			</select>
                <? if( is_numeric( $_GET['user'] )) {  ?>
                     <a href="#" onclick="get_remove('user');">X</a>
                <? } ?>
		</td>
		
	</tr>
        </tr>
	<tr>
		<td>State</td>
         	<td>Location</td>
       </tr>
       <tr>   
		<td>
             	    <select style="width:85%;"  id="state" name=state >
                        <option value="All">All the States</option>
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
                   <? if( is_numeric($_GET['state'] )) {  ?>
                     <a href="#" onclick="get_remove('state');">X</a>
                   <? } ?>

          	   </td>
		   <td style="width:400px;">
               	       <input type='text' id='location' value="<? echo $strLocation; ?>" style="padding:2px;width:90%;border:solid 1px #666">
               	       <input type='hidden' id='location_hidden' name='location' value="<? echo $location; ?>">
               	       <? if( is_numeric($_GET['location'] )) {  ?>
                       	     <a href="#" onclick="get_remove('location');">X</a>
               	        <? } ?>
         	   </td>
        
		   <td style='text-align:right'>
		   <? if ( count($_GET) > 0 ) { ?>
                        <a title='unselect all filters' class='unselect'  href="/viewtags.php">unselect&nbsp;all</a>&nbsp;
                   <? } ?>
		        <input type='submit' class='submit' value='go'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   </td>
        </form>
        </tr>
	</table>
    </div>
    <div class="container" style='width:930px;margin-left:auto;margin-right:auto'>
    	 <table style="width:930px;text-align:center" BORDER="0" CELLPADDING="3" CELLSPACING="1">
	 <tr>
	 	<td><? echo paginator($base_url, $cur_page, $total_items, $per_page, false, $name); ?></td>
	</tr>
	</table>
	<table style="width:930px;text-align:center" BORDER="0" CELLPADDING="3" CELLSPACING="1">
<?
$n = 0;
$t = 0;
$thumbs = 0;
while ($line = mysql_fetch_array($result_final)) {
	list($name,$ext) = split('\.',$line["photo_filename"]);
	
	if ($skip) {
		if ($t < $skip) {
			$t++;
			continue;
		}
	}
	
	$matches = split('_',$name);
	$x = count($matches);
	$photo_id =  $line['photo_id'];
	
	$get_url = '';
	foreach ($_GET as $key=>$value) {
          if( $key != 'id' ) {   
              $get_url .= '&' . $key . '=' . $value;
          }
        }


     
	if ($n==0 or $n==4) { 
        	$n=0 ; 
        	print "</tr><tr>"; 
        	print "<td style='text-align:center;padding-bottom:20px;'> <a href=\"photo.php?id=$photo_id&$get_url\"><img style='border:solid 5px #c0c0c0;' title='" . $line['photo_caption'] . "' src=\"image_uploads/metn_" . $line["photo_filename"] . "\"></a> <br>";
		//print "<small>" .  stripslashes($line["photo_caption"]) . "</small>";
        	print "</td>";
	} else {
        	print "<td style='text-align:center;padding-bottom:20px;'> <a href=\"photo.php?id=$photo_id&$get_url\"><img style='border:solid 5px #c0c0c0;' title='" . $line['photo_caption'] ."'  src=\"image_uploads/metn_" . $line["photo_filename"] . "\"></a><br>";
		//print "<small>" . stripslashes($line["photo_caption"]) . "</small>"; 
        	print "</td>";
	}

	
	$n++;
	$t++;
	
}

?> 
</table>
</div>
</div>
</div>
<div class="container bottom">

</div>

<script type="text/javascript">
function get_remove(parameter) {
<?
   if($_GET['species']){
      $remove_species = $_GET['species'];
   }

   if($_GET['user']){
     $remove_user = $_GET['user'];
   }
   
   if($_GET['state']){
      $remove_state = $_GET['state'];
   }

   if($_GET['location']){
       $remove_location = $_GET['location'];
    }

    if($_GET['season']){
       $remove_season = $_GET['season'];
    }
?>
var remove_season = '<? echo $remove_season; ?>';
var remove_species = '<? echo $remove_species; ?>';
var remove_user = '<? echo $remove_user; ?>';
var remove_state = '<? echo $remove_state; ?>';
var remove_location = '<? echo $remove_location; ?>';
if ( parameter == 'season') {    
     remove_season = 'All';
}
   
if (parameter == 'species') {
   remove_species = 'All'; 
}

if (parameter == 'user') {
   remove_user = 'All';   
}

if (parameter == 'location') {
   remove_location = 'All';
}

if ( parameter == 'state') {
   remove_state = 'All'; 
} 

  var url = "viewtags.php?season=" + remove_season + "&species=" + remove_species + "&user=" + remove_user + "&state=" + remove_state + "&location=" + remove_location;
  
  window.location = url;
}

 $().ready(function() {
    $('#location').emptyonclick();
    $("#species").autocomplete("autocomplete_reports.php", {
         width: 260,
         selectFirst: false,
         matchSubset :0,
         mustMatch: true,                   
    });

    $("#species").result(function(event , data, formatted) {
    if (data) {                                          
           document.getElementById('species_hidden').value = data[1];
    }                                                 
    });

    $('#species').emptyonclick();
    $('#location').autocomplete("auto_miglocations.php", {
    	width: 495,
        selectFirst: false,
        matchSubset :0,
        mustMatch: true,
        //formatItem:formatItem         
    });

    $("#location").result(function(event , data, formatted) {
    if (data) {
          document.getElementById('location_hidden').value = data[1];
    }                             
    });
});
</script>
<script src="combine_js.php?version=<?php require('combine_js.php'); ?>" type="text/javascript"></script>
<? include("login_includes.php"); 
  include("footer.php"); ?>
</body>
</html>

