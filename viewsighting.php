<?php
	session_start();
	include("./db.php");
	include("./functions.php");

	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	}

	$id = $rid = $user_id = '';
	$id = (int) $_GET['id'];
	$rid = (int) $_GET['rid'];
	$user_id = (int) $_SESSION['userid'];

        


	if (!empty($rid) && !empty($id)) {
		$fields = array('entry_id','id');
		$values = array($id,$rid);
		if (!hasAny('migwatch_history',0,$fields,$values,$connect)) {
			header('Location: main.php');
			exit();
		}
	}
	$referer = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'main.php';
	if(substr($referer, -4) == '.php') {
		$newreferer = $referer."?";
	} else {
		$newreferer = $referer."&";
	}

	if ($_GET['cmd'] == "deletesighting") {
		$sql = "SELECT user_email FROM migwatch_users WHERE user_id=".$_SESSION['userid'];
		$result = mysql_query($sql);
		if($result){
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$email = $row{'user_email'};
			}
		}
		$to = $email;
		$subject = "MigrantWatch - entry - deletion - ".$_SESSION['username'];
		$body = "Hi ".$_SESSION['username'].",\n\nYou have just deleted the following information from the MigrantWatch database:\n\n";
		$body = $body . "\nObservation Details:\n\n";
		$body .= $_SESSION['email_body'];
		$body .= "\n\nIf you have not deleted this information, or if there is an error,\n please let us know immediately by replying to this email.\n\n";
		$headers = "From: migrantwatch@ncbs.res.in\r\n" .
			"X-Mailer: php\r\n" .
			"Cc: migrantwatch@ncbs.res.in";
		//$sql = "DELETE FROM migwatch_l1 WHERE user_id='".$user_id."' AND id='$id'";
		$sql = "UPDATE migwatch_l1 SET deleted = '1' WHERE user_id = '".$user_id."' AND id = '$id' LIMIT 1";
		$success = mysql_query($sql);
		if ($success){
			//if(mail($to, $subject, $body, $headers)) {
			if(true) {
				header("Location: ".$newreferer."cmd=sightingdeleted");
				exit();
			} else {
				print("The record has been deleted, But there was a problem sending you a confirmation email");
			}
		}else {
			die("Fatal error...");
		}
	}
	unset($_SESSION['email_body']);

	//include("banner.php");
?>
 <link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
        
        <!-- Import fancy-type plugin for the sample page. -->
        <link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
          <script type="text/javascript" src="jquery-1.3.2.min.js"></script>        
         <link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">
<script language=javascript>


	function deleter(id){
		var url = "viewsighting.php?cmd=deletesighting&id=" + id;
		if(confirm("You are going to delete this record permanently.\nAre you sure you want to proceed?"))
			location.href=url;
	}

	function switchToVersion() {
		var sel_ver = document.getElementById('version').value;
		document.l1.action = document.l1.action + '&rid=' + sel_ver;
		document.l1.submit();
	}

</script>
<body style="width:500px" class="">
	<!----------SIGHTING DETAIL START--------------------->
	<p>
		<table cellpadding=2 cellspacing=0 style="width:500px">
			
			<?php
			$sql = "SELECT id FROM migwatch_l1 WHERE user_id=".$_SESSION['userid']." AND id=".$id;
			$result = mysql_query($sql);
			if(!$result || mysql_num_rows($result) <= 0) {
				print("<tr style='color:red;font-weight:bold'><td colspan='2' align='right'>You are allowed to view your entries only.</td></tr>");
			} else {
			?>
			<tr>
				<td align='left'>&nbsp;
				<form name='l1' action='viewsighting.php?id=<?php echo $id;?>' method='post'>
				<?php
				$sql = "SELECT id FROM migwatch_history WHERE entry_id = '$id'";
				$res = mysql_query($sql);
				if (mysql_num_rows($res) > 0) {
				?>
				View Version :
				<select name='version' onchange='switchToVersion();' id='version'>
					<option value='current'>Current</option>
				<?php
					$i = 1;
					while ($row = mysql_fetch_array($res)) {
						echo "<option value='{$row[id]}' ";
						if ($row['id'] == $rid) {
							echo " selected ";
						}
						echo ">$i</option>";
						$i++;
					}
				}
				?>
  				<input type="hidden" name="name" value="<?php echo $id;?>"/>
				</select>
				</form>
				</td>
				<td align=right>&nbsp;
				<?php
				if (!isset($_GET['rid']) || $_GET['rid'] == 'current') {
				?>
					<a href="editlevel.php?id=<?php print $_GET['id'];?>"><img src="images/edit.gif" style="border-width:0px;" alt="Edit this sighting"/>
					<a href="javascript:deleter(<?php print $_GET['id']; ?>);"><img src="images/delete.gif" style="border-width:0px;" alt="Delete this sighting" />
				<?php
				}
				?>
				</td>
			</tr>
			<tr style="background-color:#efefef;"><td colspan=2>
				<table width=98% cellpadding=3 cellspacing=1 style="border-width:1px;border-style:solid;border-color:#dedede;background-color:#efefef;">

				<?php
					include("./db.php");
					$email_body_species_section = "";
					if ($_GET['id']) {
						if (!($rid) || ($rid == 'current')) {
							$sql = "SELECT s.common_name, s.scientific_name, s.alternative_english_names, l1.number, l1.accuracy,l1.sighting_type,l1.flight_dir, ";
							$sql.= "l1.entry_notes, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, ";
							$sql.= "l1.location_id, l1.obs_start, l1.frequency, l1.notes, l1.user_friend, l1.entered_on, ";
							$sql.= "l1.user_id, l1.obs_type, l1.updated_by, u.user_name FROM migwatch_l1 l1 ";
							$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
							$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
							$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
							$sql.= "INNER JOIN migwatch_users u ON u.user_id = l1.user_id ";
							$sql.= "WHERE l1.id='$id' AND l1.deleted='0' ORDER BY sighting_date DESC";
							$current = 1;
						} else {
							$sql = "SELECT s.common_name, s.scientific_name, s.alternative_english_names, l1h.number, l1h.accuracy,l1h.sighting_type,l1h.flight_dir, ";
							$sql.= "l1h.entry_notes, l.location_name, l.city, st.state, l1h.sighting_date, l1h.species_id, ";
							$sql.= "l1h.location_id, l1h.obs_start, l1h.frequency, l1h.notes, l1h.user_friend, l1h.entered_on, ";
							$sql.= "l1h.user_id,l1h.obs_type,u.user_name FROM migwatch_history l1h ";
							$sql.= "INNER JOIN migwatch_species s ON l1h.species_id = s.species_id ";
							$sql.= "INNER JOIN migwatch_locations l ON l1h.location_id = l.location_id ";
							$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
							$sql.= "INNER JOIN migwatch_users u ON u.user_id = l1h.user_id ";
							$sql.= "WHERE l1h.entry_id='$id' AND l1h.id = '$rid' ORDER BY l1h.sighting_date DESC";
							$current = 0;
						}

						$result = mysql_query($sql);
						print mysql_error();
						if(hasAny('migwatch_l1',0,'id',$id,$connect) && $result){
							while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
								$number = empty($row['number']) ? '--' : $row['number'];
								$accuracy = empty($row['accuracy']) ? '--' : $row['accuracy'];
								$entry_notes = !empty($row['entry_notes']) ? nl2br($row['entry_notes']) : '--';
								$sighting_type = empty($row['sighting_type']) ? '--' : $row['sighting_type'];
								$flight_dir = empty($row['flight_dir']) ? '--' : $row['flight_dir'];
								print "<tr bgcolor=#ffffff><td width=180px>Species</td><td>".$row{'common_name'}."</td></tr>";
								print "<tr bgcolor=#ffffff><td width=180px>Alternative Names</td><td>"; empty($row{'alternative_english_names'}) ? print '--' : print $row{'alternative_english_names'}; print("</td></tr>");
								print "<tr bgcolor=#ffffff><td>Scientific Name</td><td><em>"; empty($row{'scientific_name'}) ? print '--' : print $row{'scientific_name'}; print "</em></td></tr>";
								print "<tr bgcolor=#ffffff><td>Location - Town - State</td>";
print "<td onMouseOut=\"this.style.background='#fff'; show('remove','hidden');\"  onMouseOver=\"this.style.background='#204a87'; show('remove','visible');\">".$row{'location_name'}." - ".$row{'city'}." - ".$row{'state'}."<a style='text-align:right' id='remove' href='edit'>Edit</a></td></tr>";
?>


<script type="text/javascript">
document.getElementById("remove").style.visibility = 'hidden';
function show(object,val) {

document.getElementById("remove").style.visibility = val;
}
</script> 

<?

								print "<tr bgcolor=#ffffff><td>Observation ";echo $row['obs_type'] == 'last' ? 'End' : 'Start'; echo " Date</td><td>".date("d-M-Y",strtotime($row{'obs_start'}))."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Frequency </td><td>";echo $row['frequency']."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Number </td><td>".$number."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Accuracy </td><td>".$accuracy."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Reported on behalf of </td><td>";echo !empty($row['user_friend']) ? $row['user_friend'] : '--'."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Notes on this record</td><td>".$entry_notes."</td></tr>";
								if($row{'common_name'} == 'Pied Cuckoo'){
								print "<tr bgcolor=#ffffff><td>Sighting Type</td><td>".$sighting_type."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Flight Direction</td><td>".$flight_dir."</td></tr>";
								$query1 = "select resident_check from piedcuckoo_migwatch where sighting_id='$id'";
								$res1 = mysql_query($query1);
								$data1 = mysql_fetch_assoc($res1);
							if($data1['resident_check'] == "1") { $resident_check = "Yes"; }else{ $resident_check = "No"; }
								print "<tr bgcolor=#ffffff><td>Resident Check</td><td>".$resident_check."</td></tr>";
								 }
 
								print "<tr bgcolor=#ffffff><td>General notes about this location</td><td>";echo !empty($row['notes']) ? nl2br($row['notes']) : '--'."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Sighting Date</td><td>".date("d-M-Y",strtotime($row{'sighting_date'}))."</td></tr>";
								print "<tr bgcolor=#ffffff><td>Sighting Entered/Modified  On</td><td>".date("d-M-Y",strtotime($row{'entered_on'}))."</td></tr>";
								if ($current == 1 && !is_null($row['updated_by'])) {
									print "<tr bgcolor=#ffffff><td>Created  By</td><td>".$row{'user_name'}."</td></tr>";
									print "<tr bgcolor=#ffffff><td>Modified By</td><td>".getUserNameById($row['updated_by'],$connect)."</td></tr>";
								} else if ($current == 1 && is_null($row['updated_by'])) {
									print "<tr bgcolor=#ffffff><td>Created By</td><td>".$row{'user_name'}."</td></tr>";
								} else {
									print "<tr bgcolor=#ffffff><td>Modified By</td><td>".$row{'user_name'}."</td></tr>";
								}
								if($row['obs_type'] == 'last') {
									$observationDate = "Observation End Date:\t".date("d-M-Y",strtotime($row{'obs_start'}));
								} else {
									$observationDate = "Observation Start Date:\t".date("d-M-Y",strtotime($row{'obs_start'}));
								}
								$email_body_species_section .= "Species name:\t". $row['common_name']."\n";
								$email_body_species_section .= "Location of Observations:".$row['location_name']."--".$row['city']."--".$row['state']."\n";
								$email_body_species_section .= $observationDate."\n";
								$email_body_species_section .= "Sighting Date:\t\t".date("d-M-Y",strtotime($row{'sighting_date'}))."\n";
								$email_body_species_section .= "Sighting type:\t".$row['obs_type']."\n";
								$email_body_species_section .= "Number:\t\t". $number."\n";
								$email_body_species_section .= "Accuracy:\t".$accuracy."\n";
								$email_body_species_section .= "Notes:\t\t".$entry_notes."\n\n";
								$_SESSION['email_body'] = $email_body_species_section;
							}
						} else {
							//jsRedirect('main.php');
							exit;
						}
					}
				?>
				<tr><td>&nbsp;</td></tr>
			<!--	<tr><td colspan=2>
				A system to edit your records will soon be in place.
				In the meantime, if there is an error in your record, please *first* delete
				the record, and *then* submit a new, corrected record.
				</td></tr>-->
				</table>

			</td></tr>
			<?php } ?>
		</table>
<!-----------------------------------------------------
Written by: Suresh V [verditer at gmail.com]
For: National Center for Biological Sciences, Bangalore
http://ncbs.res.in

In Love of Birds.

------------------------------------------------------->

     <!---------SIGHTING DETAIL END----------------------->
<?php
	//		include('footer.php');
?>
