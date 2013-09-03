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
        <!--<script>
           $().ready(function() {
               $('#upload_box').hide();
           });
        </script>-->
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
                //.tablesorterPager({container: $("#pager")});
                .tablesorterPager({container: $("#pager"), positionFixed: false});

                $('#upload_box').hide();
        });

           
</script>

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

	// Set the sorting field
	if(!isset($_SESSION['myspecies']['sort']) || $_SESSION['myspecies']['sort'] == '') {
		$_SESSION['myspecies']['sort'] = 'obcno';
		$_SESSION['myspecies']['sort_order'] = 'ASC';
		$firstTime = 1;
	} elseif(isset($_POST['fieldSort'])) {

		if (empty($_POST['fieldSort'])) {
			$_POST['fieldSort'] = 'obcno';
			$_POST['fieldOrder'] = 'ASC';
		}

		// Not found .. go away
		if (!in_array($_POST['fieldSort'],$allowedOrdByFlds)) {
			header('Location: main.php');
			exit();
		}

		// Ok, sort by it, note this isn't the actual db field name
		$_SESSION['myspecies']['sort'] = $_POST['fieldSort'];
		$_SESSION['myspecies']['sort_order'] = $_POST['fieldOrder'];
	}

	// If set put the selected sesason in session.
	if(isset($_POST['season'])) {
		$season = $_POST['season'];
	} else if (!empty($_SESSION['myspecies']['season'])) {
		$season = $_SESSION['myspecies']['season'];
	} else {
		$season = getCurrentSeason();
	}

	// Remember the season.
	$_SESSION['myspecies']['season'] = $season;

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


		    <div class="box" id="upload_box" style="width:700px">

<script> 

	 function showBox(species) { document.getElementById('upload_species_id').value = species; } 

        $(document).ready(function() { 
    // bind form using ajaxForm 
    $('#uploadForm').ajaxForm({ 
         beforeSubmit: function() {
            
            $('#photoUploadOutput').html('Submitting...');
        },

 
        // target identifies the element(s) to update with the server response 
        target: '#photoUploadOutput', 
 
        // success identifies the function to invoke when the server response 
        // has been received; here we apply a fade-in effect to the new content 
        success: function() { 
            //$('#htmlExampleTarget').fadeIn('slow'); 
        } 
    }); 
});


</script>
		    	      <form id="uploadForm" enctype="multipart/form-data" action="upload.php" method="post">
			      	               <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                                               <input type="hidden" id="upload_species_id" name="upload_species_id">
					       	                        Choose a file to upload: <input id="userfile1" name="userfile1" type="file" />

                                        <input type="submit" value="Upload File" id="uploadbutton"/>
                     </form>
                              <div id="photoUploadOutput"></div>


                </div>


<div class='column span-24 last' id='tab-set' style="background-color:#fff">
   
   
   
   <ul class='tabs'>
   
   <li><a href='#text1' class='selected'>My Species</a></li>
   <li><a href='#text2'>My Locations</a></li>
 
   </ul>
   
   <div id='text1' style="width:700px">
<table width="770" cellpadding=4 cellspacing=0>
	<tr bgcolor="dedede">
		<td><b>My Species</b></td>
		
	
             </tr>
		
	


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
	$firstQuery = "
					SELECT l1.id, s.OBC_Number ,s.common_name, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id,
					l1.location_id, l1.obs_type, l1.id FROM migwatch_l1 l1
					INNER JOIN migwatch_species s ON l1.species_id = s.species_id
					INNER JOIN migwatch_locations l ON l1.location_id = l.location_id
					INNER JOIN migwatch_states st ON st.state_id = l.state_id
					WHERE l1.user_id = '$user_id' AND deleted = '0' 
					AND l1.sighting_date != '0000-00-00'
				  ";
	if ($season != 'all') {
		$firstQuery .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
		                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
	}

	$res = mysql_query($firstQuery);
	if (mysql_num_rows($res) > 0) {
		while ($data = mysql_fetch_assoc($res)) {

			// If this key already exists then do not proceed
			if (is_array($records) &&
				array_key_exists($data['common_name'],$records) &&
				$records[$data['common_name']]['obs_type'] == 'first' &&
				$data['obs_type'] == 'first'
				) {
				continue;
			}

			if ($data['obs_type'] == 'first') {
				$records[$data['common_name']] = $data;
				$records[$data['common_name']]['sighting_date'] = date('d-M-Y',strtotime($records[$data['common_name']]['sighting_date']));
			//If the entry is type last
			} else {
				// Put the last sighting data in array
				$records[$data['common_name']]['species_id'] = $data['species_id'];
				$records[$data['common_name']]['common_name'] = $data['common_name'];
				$records[$data['common_name']]['OBC_Number'] = $data['OBC_Number'];
				$records[$data['common_name']]['last_sighting_date'] = date('d-M-Y',strtotime($data['sighting_date']));
				$records[$data['common_name']]['last_sighting_location_name'] = $data['location_name'];
				$records[$data['common_name']]['last_sighting_city'] = $data['city'];
				$records[$data['common_name']]['last_sighting_state'] = $data['state'];
				unset($records['sighting_date']);
			}

			$records[$data['common_name']][$data['obs_type']] = 1;
		}

		//print_r($records);

		$orderByArr = array(
						'obcno' => 'OBC_Number',
						'spc'   => "common_name",
						'fsd'   => "sighting_date",
						'fsloc' => "location_name",
						'lsd'   => "last_sighting_date",
						'lsloc' => "last_sighting_location_name"
						);

		$orderBy = $orderByArr[$sortBy];

		if ($sortBy != 'spc' && !empty($records)) {
			$i = 0;
			foreach ($records as $spc_name => $details) {

				// Put it in another array
				$key = $details[$orderBy];

				if (in_array($sortBy,array('fsd','lsd'))) {
					$key = strtotime($key);
				}

				if (is_array($sortedRecords) && !empty($key) &&  array_key_exists($key,$sortedRecords)) {
					$sortedRecords["$key-$i"] = $details;
				} else {
					if (empty($key)) {
						$lastUnSorted[$i] = $details;
						$i++;
						continue;
					}

					$sortedRecords[$key] = $details;
				}

				/**
				 * Unset the records from the main array as soon as we have put this
				 * in another array
				 */
				unset($records[$spc_name]);
				$i++;
			}

			$records = $sortedRecords;
		}

		// Only sort if they are not present
		if (!empty($records)) {
			($sortOrder == 'ASC') ? ksort($records) : krsort($records);
		}

		// If we have some unsorted ones
		if (isset($lastUnSorted) && !empty($lastUnSorted)) {
			// Merge only if we have some records in $records
			if (!empty($records)) {
				$records = array_merge($records,$lastUnSorted);
			} else {
				$records = $lastUnSorted;
			}
		}

		//print_r($orderBy); print_r('<br/>'); print_r($sortOrder);
		/*
		print_r('<PRE>');
		print_r($records);
		print_r('</PRE>');
		*/
?>
		<br /><br />
		<table id="table1" width=100% cellpadding=3 cellspacing=0 style="">
		<thead>
			<tr>
				<th>&nbsp;No</th>
				<th width="120px">Species Name</th>
				<th>Pictures</th>
			</tr>
		</thead>
		<tbody>
<?php
		$i = 1;
		foreach ($records as $record) {

			echo "<tr>";
			echo " 	<td>$i</td>";
			echo "<td>$record[common_name]</td>";
                         $species_id =  $record['species_id'];
			

			 $sLinkBegin = "<a class=tablelink href=\"speciessighting.php?id=".$record['species_id']."\">";
			 $sLinkEnd = "</a>";
				  echo "<td align=center><a href='#' onclick=\"$('#upload_box').show(); showBox($species_id);\">Upload Picture</a></td></tr>";
			$i++;
		}
	echo "</tr></tbody>";
	echo "<input type='hidden' name='fieldSort'>" .
		 "<input type='hidden' name='fieldOrder'>";
	  echo "</table>";
		echo  "</form>";
	?>
		 
                        <form name="" action="" method="post">
                        <table id="pager" class="span-4">
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
               

	<?
	} else {
	?>
	<tr>
		<td colspan=2 class='error' align='center'>You have not yet reported any sightings for the selected season.
		</td>
	</tr>
	<?php
	}
	?>
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
