<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<? include("db.php"); ?>
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

  <!-- Framework CSS -->
        <link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
        <link rel="stylesheet" href="pager/style.css" type="text/css" media="print, projection, screen"/>

        <!-- Import fancy-type plugin for the sample page. -->
        <link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
        <script type="text/javascript" src="jquery-1.3.2.min.js"></script>
       <script type="text/javascript" src="jquery.form.js"></script>      

       <link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">


<style>
        table.tablesorter tr.even td { background:#E5ECF9;}
</style> 

<script type="text/javascript" src="pager/jquery.tablesorter.js"></script>
<script type="text/javascript" src="pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="pager/chili-1.8b.js"></script>
<script type="text/javascript" src="pager/docs.js"></script>

<script type="text/javascript">
        $(function() {
                $("#table1")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager")});
                
        });

        
</script>
<link href="thickbox.css"  rel="stylesheet" type="text/css" />
<script src="thickbox.js" language="javascript"></script>

<script src="../jquery.autocomplete.js" language="javascript"></script>
<script src="../jquery.bgiframe.min.js" language="javascript"></script>
<script src="../jquery.autocomplete.js" language="javascript"></script>
<link href="../jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<?php
	//session_start();
	include("db.php");
	include("functions.php");
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	}

	//include("header.php");
	unset($_SESSION['referrer']);
	$user_id = $_SESSION['userid'];

	// Values will be added later
	$allowedOrdByFlds = array('obcno','spc','fsd',
							  'fsloc','lsd','lsloc');


	// If set put the selected sesason in session.
	if(isset($_POST['season'])) {
		$season = $_POST['season'];
	} else if (!empty($_SESSION['myspecies']['season'])) {
		$season = $_SESSION['myspecies']['season'];
	} else {
		$season = getCurrentSeason();
	}


   if(isset($_POST['get_location'])) {
      if( $_POST['get_location']!='All') {
		  $get_location = $_POST['get_location'];
      } else if (!empty($_SESSION['myspecies']['location'])) {
      	$get_location = $_SESSION['myspecies']['location'];
        } 
	}

   if(isset($_POST['get_species'])) {
     if( $_POST['get_species']!='All') {
		$get_species = $_POST['get_species'];
      }
	}  ?>
<a href='#' onclick="<? $_SESSION['myspecies']['location']= NULL; ?>">remove</a>

<script></script>

<?
	// Remember the season.
	$_SESSION['myspecies']['season'] = $season;
        $_SESSION['myspecies']['location'] = $get_location;

	if ($season) {
		// Now for the first sightings query.
		$seasonArr = explode('-',$season);
		$seasonStart = $seasonArr[0];
		$seasonEnd = $seasonArr[1];
	}

	// Put this in vars for convinience
	$sortBy = $_SESSION['myspecies']['sort'];
	$sortOrder = $_SESSION['myspecies']['sort_order'];

	// Get the actual field name from the allowed order by arr
	$orderBy = $orderByArr[$sortBy];
?></head><body><div class="container">

<div class='column span-24 last' id='tab-set' style="background-color:#fff">
   
   
   
   <ul class='tabs'>
   
   <li><a href='#text1' class='selected'>My Species</a></li>
   <li><a href='#text2'>My Locations</a></li>
 
   </ul>
   
   <div id='text1' style="width:700px">
<table width="770" cellpadding=4 cellspacing=0>
	
	<script language="javascript">
	function setSorting(field, order) {
		document.frm_sortfield.fieldSort.value = field;
		if(order != '') {
			document.frm_sortfield.fieldOrder.value = order;
		} else {
			var sortBy = '<?php print($sortBy);?>';
			var sortOrder = '<?php print($sortOrder);?>';
			if(field == sortBy) {
				document.frm_sortfield.fieldOrder.value = (sortOrder=='ASC') ? 'DESC': 'ASC';
			} else {
				document.frm_sortfield.fieldOrder.value = 'ASC';
			}
		}
			document.frm_sortfield.submit();
	}
	</script>
		<form method="post" name="frm_sortfield">
		<tr style="/*background-color: rgb(239, 239, 239);*/">
		<td colspan=2>
		Select Season :
		<select name='season' style="margin-left:10px;" onChange='this.form.submit();'>
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
		</select> 
	<?php

       
                                       $sql = "SELECT distinct l.location_id, l.location_name,l.city,l.district";
                                        $sql.= " FROM migwatch_l1 l1 ";
                                        $sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
                                        $sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
                                        $sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
                                        $sql.= "WHERE l1.user_id = '".$user_id."' AND l1.deleted = '0' ";
                                        $get_location = $_SESSION['myspecies']['location'];
                                        $season = $_SESSION['myspecies']['season']; 
                                         if ($season != 'all') {
                $sql .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
                                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
               }

                 

                                        
        //echo $sql;
	$res = mysql_query($sql);


              echo "<select name='get_location' style='width:200px' onChange='this.form.submit();'><option style='width:200px' value='All'>-- SELECT --</option>";
     while ($data1 = mysql_fetch_assoc($res)) {
			echo "<option value=$data1[location_id]>$data1[location_name] , $data1[city] , $data1[district]</option>";
      }
      echo "</select>";

   

        $sql = "SELECT distinct s.common_name, s.species_id ";
                                        $sql.= " FROM migwatch_l1 l1 ";
                                        $sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
                                        $sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
                                        $sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
                                        $sql.= "WHERE l1.user_id = '".$user_id."' AND l1.deleted = '0' ";
                                        $get_location = $_SESSION['myspecies']['location'];
                                        $season = $_SESSION['myspecies']['season'];
                                          if ($season != 'all') {
                $sql.= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
                                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
        }
 
                                        
        //echo $sql;
	$res = mysql_query($sql);


     echo "<select name='get_species' onChange='this.form.submit();'><option value='All'>-- SELECT --</option>";
       while ($data1 = mysql_fetch_assoc($res)) {
			echo "<option value=$data1[species_id]>$data1[common_name]</option>";
      }

      echo "</select>";


					$sql = "SELECT l1.number, s.common_name, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
					$sql.= "l1.obs_type, l1.id,l1.species_id,l1.location_id, l1.deleted FROM migwatch_l1 l1 ";
					$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
					$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
					$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
					$sql.= "WHERE l1.user_id = '".$user_id."'  AND l1.deleted = '0' ";
                                        echo $get_location = $_SESSION['myspecies']['location'];
					echo $season = $_SESSION['myspecies']['season'];
					if ($season != 'all') {
						list($seasonStart,$seasonEnd) = explode('-',$season);
						$sql .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
						                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
					}

               if($get_location) {

                   $sql.=" AND l1.location_id='$get_location'";
               }

               if($get_species) {

                   $sql.=" AND s.species_id='$get_species'";
               }
  
	$res = mysql_query($sql);



	
	
echo "</form>";
?>
		
		<table id="table1" width=100% cellpadding=3 cellspacing=0 style="">
		<thead>
			<tr>
				<th>&nbsp;No -- <? echo $season; ?></th>
				<th width="120px">Species Name -- <? echo $get_species; ?></th>
				
				<th>Location -- <? echo $get_location; ?></th>
            <th>Date</th>
            <th>Type</th>
				<th>Count</th>
				
				
			</tr>
		</thead>
		<tbody>
<?php
	
      
     
		$result = mysql_query($sql);

					if($result) {
						$i =1;
						$j = (($pageno - 1) * $rows_per_page) + 1;
						while($row = mysql_fetch_array($result)) {
                                                        
							//$sLinkBegin = "<a class=tablelink href=\"uploadphoto.php?id=".$row['id']."\">";
							//$sLinkEnd = "</a>";
							print "<tr>";
$sLinkBegin = "<a class=thickbox rel=gallery-plants href=\"uploadphotos.php?id=".$row['id']."&height=400&width=700\">Upload Photo</a>";
							print "<td>".$j."</td>";
                     print "<td>".$row{'common_name'}."</td>";
							print "<td>".$row{'location_name'}."<br> ".$row{'city'}.", ".$row{'state'}."</td>";
							print "<td>".date("d-M-Y",strtotime($row{'sighting_date'}))."</td>";
							print "<td>".ucfirst($row['obs_type'])." Sighting</td>";
							print "<td>"; echo (empty($row['number'])) ? '--' : $row['number']; print "</td>";
							//<td>$sLinkBegin<img src=\"images/view.gif\" style=\"border-width:0px;\" title='View details of this sighting'/>$sLinkEnd</td>";
                                                        print "<td>$sLinkBegin</td>";
?>
<?
							print "</tr>";
							$i++;
							$j++;
						}
					}
					?>
				</table>
		 
	
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
