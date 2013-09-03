<?php session_start();
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	}

	include('db.php');
	include('functions.php');
	include('main_includes.php');
	include('page_includes_js.php');

	$id = '';
	$id = (int) $_GET['id'];
	if (!hasAny('migwatch_l1',0,'id',$id,$connect)) {
		header('Location: main.php');
		exit();
	}

	if($_GET['error'] == 1) {
		$error = 1;
		$lastData = $_SESSION['editL1'];
		$errors = $_SESSION['editL1']['errors'];
	} else {
		unset($_SESSION['editL1']);
	}

	$errorStrings = array(
						'obstart' => "Please check the observation start/end date",
						'dt'      => "Please check the date of sighting",
						'often'   => "Please select how often you look for birds",
						'number'  => "Please make sure that the number is an integer",
						'accuracy' => "Please select the 'Accuracy of Count' for the 'Number'",
						'sighting_type' => "Please select the sighting type for the 'Number'"
						
					);

	
	if(!empty($errors)) {
		foreach($errors as $field) {
			print("<p style='color:red'><b>".$errorStrings[$field]."</b></p>");
		}
	}

	$sql = "SELECT s.common_name, s.alternative_english_names, s.scientific_name, l.location_name, ";
	$sql.= "l.city, st.state, l1.number, l1.accuracy, l1.entry_notes, l1.sighting_date, l1.species_id, l1.sighting_type,l1.flight_dir,";
	$sql.= "l1.location_id, l1.obs_start, l1.frequency, l1.notes, l1.user_friend, l1.entered_on, l1.obs_type ";
	$sql.= "FROM migwatch_l1 l1 INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
	$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
	$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
	$sql.= "WHERE l1.user_id = ".$_SESSION['userid']." AND l1.id=".$_GET['id']." ORDER BY sighting_date DESC";
	print $sql;
	$result = mysql_query($sql);
	if($result){
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$sdt = date("d-m-Y",strtotime($row{'sighting_date'}));
			$species_name = $row{'common_name'};
			$obstart = date("d-m-Y",strtotime($row{'obs_start'}));
			$often = $row{'frequency'};
			$user_friend = $row{'user_friend'};
			$notes = $row{'notes'};
			$often = $row['frequency'];
			$loc = $row{'location_name'}." - ".$row{'city'}." - ".$row{'state'};
			$obs_type = $row['obs_type'];
			$number = $row['number'];
			$alternative_eng_names = $row['alternative_english_names'];
			$scientific_name = $row['scientific_name'];
			$number = empty($row['number']) ? '' : $row['number'];
			$accuracy = $row['accuracy'];
			$entry_notes = $row['entry_notes'];
			$sighting_type = $row['sighting_type'];
			$flight_dir = $row['flight_dir'];
		}

		if ($error == 1 && !empty($errors)) {
			$sdt = $lastData['dt'];
			$obstart = date("d-m-Y",strtotime($lastData['obstart']));
			$often = $lastData['often'];
			$user_friend = getStripped($lastData['other_name']);
			$notes = getStripped($lastData['other_notes']);
			$number = getStripped($lastData['number']);
			$accuracy = getStripped($lastData['accuracy']);
			$entry_notes = getStripped($lastData['entry_notes']);
			$sighting_type = getStripped($lastData['sighting_type']);
			$flight_dir = getStripped($lastData['flight_dir']);
		}
	}


	/*$sql = "select resident_check from piedcuckoo_migwatch where sighting_id = '$id'";
	$result = mysql_query($sql);
	$data = mysql_fetch_assoc($result);
	$resident_check = $data['resident_check'];
	if($resident_check == "1")
	{
		$checked = 1;
	}*/
	
?>
<script language=javascript>

	var reWhitespace = /^\s+$/

	function isEmpty(s){
		return ((s == null) || (s.length == 0))
	}

	function isWhitespace (s) {	// Is s empty?
		return (isEmpty(s) || reWhitespace.test(s));
	}

	function deleter(id){
		var url = "viewsighting.php?cmd=deletesighting&id=" + id;
		if(confirm("You are going to delete this record permanently.\nAre you sure you want to proceed?"))
			location.href=url;
	}

	function canceler(id){
		url="viewsighting.php?id="+id;
		location.href = url;
	}

	function validater(){
		var obstart = document.frm_editlevel1.obstart.value;
		var sdt = document.frm_editlevel1.dt.value;
		var now	= new Date();

		if ((compareDates(obstart,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy"))==1){
			alert("The observation <?php echo $obs_type; ?> date cannot be a future date.");
			return false;
		}

	<?php
	if ($obs_type == 'first') {
	?>
		if((compareDates(sdt,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) || (compareDates(obstart,"dd-MM-yyyy",sdt,"dd-MM-yyyy")==1))	{
			alert("The date of first sighting is either in future or before observation start date. Please check.");
			return false;
		}
	<?php
	} else {
	?>
		if((compareDates(sdt,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) || (compareDates(sdt,"dd-MM-yyyy",obstart,"dd-MM-yyyy")==1))	{
			alert("The date of last sighting is either in future or after observation end date. Please check.");
			return false;
		}
	<?
	}
	?>

		var num = document.frm_editlevel1.number.value;
		num = parseInt(num);
		if (!isWhitespace(num) && document.frm_editlevel1.number.value != '' && isNaN(num)) {
			alert("Please enter only numerals under 'Number'.");
			return false;
		}

		if (!isWhitespace(num) && document.frm_editlevel1.number.value != ''&& document.frm_editlevel1.accuracy.value == '') {
			alert("Please select the 'Accuracy' for the 'Number' observed.");
			return false;
		}

		if (document.frm_editlevel1.often.selectedIndex == 0) {
			alert('Please select the frequency of observation');
			return false;
		}

		/*All is OK*/
		return true;
	}

</script>

	<!----------SIGHTING DETAIL START--------------------->
	<p>
	<? if($species_name == 'Pied Cuckoo'){ ?>
		<form name="frm_editlevel1" method=post action="process_cuckoo.php">
	<? }else { ?>
		<form name="frm_editlevel1" method=post action="process_level1.php">
	<? } ?>
		<table width=700 cellpadding=4 cellspacing=0>
			<tr>
				<td><b>MigrantWatch</b> - <?php print $_SESSION['username']; ?></td>
				<td align=right>
					 &nbsp;
				</td>
			</tr>
			<tr><td colspan=2 align=right>&nbsp;
			
			</td></tr>
			<tr style="background-color:#efefef;"><td colspan=2>
				<table width=98% cellpadding=3 cellspacing=1 style="border-width:1px;border-style:solid;border-color:#dedede;background-color:#efefef;">
				<tr bgcolor=#ffffff><td width=180px>Species</td>
<td><input type="text" style="width:250px" id="species" name="species_name" value="<?php  print $species_name; ?>"></td></tr>
				<tr bgcolor=#ffffff><td width=180px>Location</td><td>
					<input type="text" style="width:250px" id="editLocation" name="editlocation" value="<?php print $loc; ?>">
				</td></tr>
				<tr bgcolor=#ffffff><td>Observation <?php echo ($obs_type == 'first') ? 'Start' : 'End' ; ?> date</td>
					<td>
					<input type=text name=obstart value="<?php print $obstart; ?>"><input type=button value="Choose" onclick="showCalendarControl(obstart);" /> *
				</td></tr>
				<tr><td>Observation type</td><td>
        			<select name="often">
                                                <option value="" >Select</option>
                                                <option value="first" <?php print($obs_type == "first" ? " selected " : "") ?>>First</option>
						<option value="general" <?php print($obs_type == "general" ? " selected " : "") ?>>General</option>
                                                <option value="last" <?php print($obs_type == "last" ? " selected " : "") ?>>Last</option>										</select>
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Frequency of bird watching</td><td>
					<select name="often">
						<option value="" >Select</option>
						<option value="Daily" <?php print($often=="Daily" ? " selected " : "") ?>>Daily</option>
						<option value="Weekly" <?php print($often=="Weekly" ? " selected " : "") ?>>Weekly</option>
						<option value="Fortnightly" <?php print($often=="Fortnightly" ? " selected " : "") ?>>Fortnightly</option>
						<option value="Monthly" <?php print($often=="Monthly" ? " selected " : "") ?>>Monthly</option>
						<option value="Irregular" <?php print($often=="Irregular" ? " selected " : "") ?>>Irregular</option>
						<option value="First visit" <?php print($often=="First visit" ? " selected " : "") ?>>This is my first visit</option>
					</select> *
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Date of <?php echo ucfirst($obs_type); ?> sighting</td><td>
					<input type=text name=dt value="<?php print $sdt; ?>"><input type=button value="Choose" onclick="showCalendarControl(dt);" /> *
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Number</td><td>
					<input type=text name="number" value="<?php print $number; ?>" style="width:150px">
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Accuracy</td><td>
					<select name="accuracy">
						<option value="" <?php print($accuracy=="" ? " selected " : "") ?>>--SELECT--</option>
						<option value="exact" <?php print($accuracy=="exact" ? " selected " : "") ?>>Exactly</option>
						<option value="approximate" <?php print($accuracy=="approximate" ? " selected " : "") ?>>Approximately</option>
					</select> *
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Notes on this Record</td><td>
					<input type=text name="entry_notes" value="<?php print htmlentities($entry_notes); ?>" style="width:250px">
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Reported on behalf of</td><td>
					<input type=text name="other_name" value="<?php print htmlentities($user_friend); ?>" style="width:150px">
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>General notes about this location</td><td>
					<textarea name="other_notes" class="editbox" style="width:200px; height:100px"><?php print $notes; ?></textarea>
				</td></tr>
		 <? if($species_name == 'Pied Cuckoo'){ ?>
				<tr bgcolor=#ffffff><td width=180px>Sighting Type</td><td>
					<select name="sighting_type">
						<option value="" <?php print($sighting_type =="" ? " selected " : "") ?>>--SELECT--</option>
						<option value="saw" <?php print($sighting_type =="saw" ? " selected " : "") ?>>Saw</option>
						<option value="heard" <?php print($sighting_type == "heard" ? " selected " : "") ?>>Heard</option>
						<option value="saw_heard" <?php print($sighting_type == "saw_heard" ? " selected " : "") ?>>Saw & Heard</option>
					</select> *
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Flight Direction</td><td>
					<select name="flight_dir">
<option value="" <?php print($flight_dir =="" ? " selected " : "") ?>>--SELECT--</option>
<option value='S-N' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'S-N') echo 'selected' ?>>S-N</option>        
<option value='SE-NW' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'SE-NW') echo 'selected' ?>>SE-NW</option>   
<option value='E-W' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'E-W') echo 'selected' ?>>E-W</option>         
<option value='NE-SW' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'NE-SW') echo 'selected' ?>>NE-SW</option>   
<option value='N-S' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'N-S') echo 'selected' ?>>N-S</option>         
<option value='NW-S' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'NW-S') echo 'selected' ?>>NW-S</option>        
<option value='W-E' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'W-E') echo 'selected' ?>>W-E</option>          
<option value='SW-NE' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'SW-NE') echo 'selected' ?>>SW-NE</option> 
					</select> 
				</td></tr>
				
				   <tr bgcolor=#ffffff><td>Resident Check</td><td><input name="resident_check" value=1 type=checkbox <? if($checked == '1'){ ?> checked = "<? echo $checked; }?>"><td></tr>
				<? } ?>
				<tr bgcolor=#ffffff><td>&nbsp;</td><td>
				<input type=submit value="Update" name=submitter onclick="return validater();" class=buttonstyle />
				&nbsp;<input type=hidden name="cmd" value="updatelevel1" />
				<input type=hidden name="id" value=<?php print $_GET['id']; ?> />
				<!--<input type=button value=Cancel onclick="javascript:canceler(<?php print $_GET['id']; ?>);" class=buttonstyle />-->
				&nbsp;</td></tr>
				</table>
			</td></tr>
		</table>
		</form>
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<script src="jquery.bgiframe.min.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<link href="jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
 


<script type="text/javascript">

$().ready(function() {
    $("#editLocation").autocomplete("edit_miglocations.php", {
            width: 250,
                selectFirst: false,
                mustMatch:true,
        });
});

$("#species").autocomplete("autocomplete.php", {
                        width: 200,
                        selectFirst: false,
                        matchSubset :0,
                        extraParams : {all : 1},
                        //formatItem:formatItem
                });

</script>
		<!-----------------------------------------------------
Written by: Suresh V [verditer at gmail.com]
For: National Center for Biological Sciences, Bangalore
http://ncbs.res.in

In Love of Birds.

------------------------------------------------------->

     <!---------SIGHTING DETAIL END----------------------->
