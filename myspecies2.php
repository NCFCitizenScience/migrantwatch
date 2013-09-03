<? session_start(); 

include("db.php");
	include("functions.php");
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	}
    unset($_SESSION['referrer']);
	$user_id = $_SESSION['userid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

<? 
	include("main_includes.php");
	include("page_includes_js.php"); 
   
   

 ?>

</head>
<body style="">
 
 
<?

	include("header_new.php");
	//session_start();
	

	//include("header.php");
	

	// Values will be added later
	$allowedOrdByFlds = array('obcno','spc','fsd','fsloc','lsd','lsloc');


	// If set put the selected sesason in session.
	if(isset($_REQUEST['season'])) {
		$season = $_REQUEST['season'];
	} else if (!empty($_SESSION['myspecies']['season'])) {
		$season = $_SESSION['myspecies']['season'];
	} else {
		$season = getCurrentSeason();
	}


   
      if( $_REQUEST['location']!='All') {
         $location = $_REQUEST['location'];
     } else if (!empty($_SESSION['myspecies']['location'])) {
      	 $location = $_SESSION['myspecies']['location'];
     } else {
       	 $location = 'All';
     }
    

   if(isset($_REQUEST['species'])) {
     if( $_REQUEST['species']!='All') {
		$species = $_REQUEST['species'];
      } else if (!empty($_SESSION['myspecies']['species'])) {
        $species = $_SESSION['myspecies']['species'];
      } else {
         $species = 'All';
      }
    }
 

?>




<?
	// Remember the season.
	$_SESSION['myspecies']['season'] = $season;
        $_SESSION['myspecies']['location'] = $location;
         $_SESSION['myspecies']['species'] = $species;

	if ($season) {
		// Now for the first sightings query.
		$seasonArr = explode('-',$season);
		$seasonStart = $seasonArr[0];
		$seasonEnd = $seasonArr[1];
	}

	$get_loc = $_GET['location'];
$get_species = $_GET['species'];



if($get_loc && $get_species) {

    $sql1    = "select location_name from migwatch_locations where location_id='$get_loc'";
    $result1 = mysql_query($sql1);
    while ($data = mysql_fetch_assoc($result1)) {
    
   $location_remove = "<tr><td> " . $data['location_name'] ." <a href='myspecies2.php?species=". $get_species . "'>Remove</a></td></tr>";
   
    }

    $sql2 = "select common_name from migwatch_species where species_id='$get_species'";
    $result2 = mysql_query($sql2);
    $data = mysql_fetch_assoc($result2);
    $species_remove = "<tr><td> " . $data['common_name'] ." <a href='myspecies2.php?location=". $get_loc . "'>Remove</a></td></tr>";
    
    

}
?></head><body>


<div class="container">

<div class='column span-24 last' id='tab-set' style="background-color:#fff">
   
   
   
   <ul class='tabs'>
   
   <li><a href='#text1' class='selected'>My Species</a></li>
   <li><a href='#text2'>My Locations</a></li>
 
   </ul>



   <div id='text1' style="width:920px">
<table style="width:200px;float:right">
	
	
		<form method="get" name="frm_sortfield">
		<tr><td>Select Season</td></tr>
		<tr><td><select name='season' style="" onChange='this.form.submit();'>
		<option value='all' <?php if ($season == 'all') echo ' selected ' ?>>All</option>
<?php
		$sql = "SELECT obs_start FROM migwatch_l1 ORDER BY obs_start DESC LIMIT 0,1";
		$res = mysql_query($sql);
		if (mysql_num_rows($res) > 0) {
			$row = mysql_fetch_assoc($res);
			$endSeason = substr($row['obs_start'],0,4);
		}

		for ($i = 2007;$i <= $endSeason; $i++) {
			$fromTo = "$i-".($i+1);
			echo '<option';
			if ($season == $fromTo) {
				echo ' selected>';
			} else {
				echo '>';
			}

			echo $fromTo;
			echo '</option>';
		}
				?>
		</select></td></tr>

 
	<?php

       
                                       $sql = "SELECT distinct l.location_id, l.location_name,l.city,l.district";
                                        $sql.= " FROM migwatch_l1 l1 ";
                                        $sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
                                        $sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
                                        $sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
                                        $sql.= "WHERE l1.user_id = '".$user_id."' AND l1.deleted = '0' ";
                                        
                                         if ($season != 'all') {
                $sql .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
                                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
               }

                 

                                        
        //echo $sql;
	$res = mysql_query($sql);


              echo "<tr><td>Location</td></tr><tr><td><select id='location' name='location' style='width:200px;display:block' onChange='this.form.submit();'><option style='width:200px' value='All'>-- SELECT --</option>";
     while ($data1 = mysql_fetch_assoc($res)) {
			echo "<option class='selectbox' value=$data1[location_id]";
           if (($_GET['location'] != "") && ($_GET['location'] == $data1['location_id']))
            print " selected ";
            echo ">$data1[location_name] , $data1[city] , $data1[district]</option>";
      }
      echo "</select></td></tr>";

echo $location_remove;


        $sql = "SELECT distinct s.common_name, s.species_id ";
                                        $sql.= " FROM migwatch_l1 l1 ";
                                        $sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
                                        $sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
                                        $sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
                                        $sql.= "WHERE l1.user_id = '".$user_id."' AND l1.deleted = '0' ";
                                        
                                          if ($season != 'all') {
                $sql.= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
                                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
        }
 
                                        
        //echo $sql;
	$res = mysql_query($sql);


     echo "<tr><td>Species</td></tr><tr><td><select name='species' onChange='this.form.submit();'><option value='All'>-- SELECT --</option>";
       while ($data1 = mysql_fetch_assoc($res)) {
        
			echo "<option value=$data1[species_id]";
                        // if( $data1[species_id] == $species) { echo "selected='selected'"; }
                       if (($_GET['species'] != "") && ($_GET['species'] == $data1['species_id']))
                           print " selected ";
                         echo ">$data1[common_name]</option>";
      }

      echo "</select></td></tr>";

echo $species_remove;

		$sql = "SELECT l1.number, s.common_name, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
					$sql.= "l1.obs_type, l1.id, l1.deleted FROM migwatch_l1 l1 ";
					$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
					$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
					$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
					$sql.= "WHERE l1.user_id = '".$user_id."'  AND l1.deleted = '0' ";
               
					if ($season != 'all') {
						list($seasonStart,$seasonEnd) = explode('-',$season);
						$sql .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
						                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
					}

               if( $location) {
                  if($location!= 'All') {
                   $sql .=" AND l1.location_id='$location'";
                  }
               }

               if( $species) {
                  if($species!= 'All')
                   $sql.=" AND s.species_id='$species'";
               }
  
	//$res = mysql_query($sql);



	
	
echo "</form>";

?>
</table>
		
		<table id="table1" style="width:700px" cellpadding=3 cellspacing=0 style="">
		<thead>
			<tr>
				<th>&nbsp;No</th>
				<th>Species Name</th>		
				<th>Location</th>
                                <th>Date</th>
                                <th>Type</th>
				<th>Count</th>
				<th></th>
				
			</tr>
		</thead>
		<tbody>
<?php
	
      //echo $sql;
     
		$result = mysql_query($sql);
      if (mysql_num_rows($result) > 0) {
		if($result) {
			    $i =1;
			    $j = (($pageno - 1) * $rows_per_page) + 1;
			    while($row = mysql_fetch_array($result)) {
				 
			    print "<tr>";
			    $sLinkBegin = "<a class=thickbox rel=gallery-plants href=\"editlevel.php?id=".$row['id']."&TB_iframe=true&height=500&width=700\">edit</a>";
			    print "<td>".$j."</td>";
                            print "<td>".$row{'common_name'}."</td>";
			    print "<td>".$row{'location_name'}."<br> ".$row{'city'}.", ".$row{'state'}."</td>";
			    print "<td>".date("d-M-Y",strtotime($row{'sighting_date'}))."</td>";
			    print "<td>".ucfirst($row['obs_type'])." Sighting</td>";
			    print "<td>"; echo (empty($row['number'])) ? '--' : $row['number']; print "</td>";
                            print "<td>$sLinkBegin</td>";
?>
<?
			    print "</tr>";
			    $i++;
		            $j++;
			 }  } } else { 
            
        ?>
        <tr>
                <td colspan="6" style="background-color:#fff; text-align:center"><div class="error">You have not yet reported any sightings for the selected season.</div></td>
        </tr>
        <?php
        }
       

		
		?>		</tbody></table>
		 
	
		 <div id="pager" class="column span-7">
                        <form name="" action="" method="post">
                                <table>
                                <tr>
                                        <td><img src='pager/icons/first.png' class='first'/></td>
                                        <td><img src='pager/icons/prev.png' class='prev'/></td>
                                        <td><input type='text' size='8' class='pagedisplay'/></td>
                                        <td><img src='pager/icons/next.png' class='next'/></td>
                                        <td><img src='pager/icons/last.png' class='last'/></td>
                                        <td>
                                                <select class='pagesize'>
                                                        <option selected='selected'  value='10'>10</option>
                                                        <option value='20'>20</option>
                                                        <option value='30'>30</option>
                                                        <option  value='40'>40</option>
                                                </select>
                                        </td>
                                </tr>
                                </table>
                        </form>
                </div>

</table>
<?php
	//include('footer.php');
?></div>
<div id='text2'>
<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>
</div>

</div>
</div>

<script type="text/javascript">
$("ul.tabs li.label").hide(); 
$("#tab-set > div").hide(); 
$("#tab-set > div").eq(0).show(); 
  $("ul.tabs a").click( 
  function() { 
  $("ul.tabs a.selected").removeClass('selected'); 
  $("#tab-set > div").hide();
  $(""+$(this).attr("href")).fadeIn('slow'); 
  $(this).addClass('selected'); 
  return false; 
  }
  );
  
  </script>
</body></html>
