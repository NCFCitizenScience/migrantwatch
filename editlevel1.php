<?php session_start();
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	}

	include("banner.html");
?>
<script language=javascript>


	function deleter(locid,sid){
		var url = "viewsighting.php?cmd=deletesighting&locid=" + locid + "&sid=" + sid;
		if(confirm("You are going to delete this record permanently.\nAre you sure you want to proceed?"))
			location.href=url;
	}

	function canceler(locid,sid){
		url="viewsighting.php?locid="+locid+"&sid="+sid;
		location.href = url;
	}

	function validater(){
		var obstart = document.frm_editlevel1.obstart.value;
		var sdt = document.frm_editlevel1.sdt.value;
		var now	= new Date();
		
		if ((compareDates(obstart,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy"))==1){
			alert("The observation start date cannot be a future date.");
			return false;
		}

		if((compareDates(sdt,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) || (compareDates(obstart,"dd-MM-yyyy",sdt,"dd-MM-yyyy")==1))	{
			alert("The date of first sighting is either in future or before observation start date. Please check.");
			return false;
		}

		document.frm_editlevel1.obstart.value = formatDate(parseDate(obstart,true),'yyyy-MM-dd');
		document.frm_editlevel1.sdt.value = formatDate(parseDate(sdt,true),'yyyy-MM-dd');
		/*All is OK*/
		return true;
	}

</script>
<link href="CalendarControl.css"  rel="stylesheet" type="text/css">
<script src="CalendarControl.js" language="javascript"></script>
<script src="date.js" language="javascript"></script>
	<!----------SIGHTING DETAIL START--------------------->
	<p>
		<form name="frm_editlevel1" method=post action="process_level1.php">
		<table width=575 cellpadding=4 cellspacing=0>
			<tr bgcolor="dedede">
				<td><b>MigrantWatch</b> - <?php print $_SESSION['username']; ?></td>
				<td align=right>					
					 &nbsp;
				</td>
			</tr>
			<tr><td colspan=2 align=right>&nbsp;
				<!--<a href="javascript:deleter(<?php print $_GET['locid'].",".$_GET['sid']; ?>);"><img src="images/delete.gif" style="border-width:0px;" alt="Delete this sighting" />				-->
			</td></tr>
			<tr style="background-color:#efefef;"><td colspan=2>
				<table width=98% cellpadding=3 cellspacing=1 style="border-width:1px;border-style:solid;border-color:#dedede;background-color:#efefef;">

				<?php
					include("./db.php");
					$sql = "SELECT s.common_name,l.location_name,l.city,st.state,l1.sighting_date,l1.species_id,l1.location_id,l1.obs_start,l1.frequency,l1.notes,l1.user_friend,l1.entered_on FROM migwatch_l1 l1 INNER JOIN migwatch_species s ON l1.species_id = s.species_id INNER JOIN migwatch_locations l ON l1.location_id = l.location_id INNER JOIN migwatch_states st ON st.state_id = l.state_id WHERE l1.user_id = ".$_SESSION['userid']." AND l1.species_id=".$_GET['sid']." AND l1.location_id=".$_GET['locid']." ORDER BY sighting_date desc";
					//print $sql;
					$result = mysql_query($sql);
					if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
							$sdt = date("d-m-Y",strtotime($row{'sighting_date'}));
							$species_name = $row{'common_name'};
							$obstart = date("d-m-Y",strtotime($row{'obs_start'}));
							$often = $row{'frequency'};
							$user_friend = $row{'user_friend'};
							$notes = $row{'notes'};
							$loc = $row{'location_name'}." - ".$row{'city'}." - ".$row{'state'};
						}
					}
				?>
				<tr bgcolor=#ffffff><td width=180px>Species</td><td><?php  print $species_name; ?></td></tr>
				<tr bgcolor=#ffffff><td width=180px>Location</td><td>
					<?php print $loc; ?>
				</td></tr>
				<tr bgcolor=#ffffff><td>Observation start date</td>
					<td>
					<input type=text name=obstart value="<?php print $obstart; ?>" readonly><input type=button value="Choose" onclick="showCalendarControl(obstart);" /> *
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Date of first sighting</td><td>
					<input type=text name=sdt value="<?php print $sdt; ?>" readonly><input type=button value="Choose" onclick="showCalendarControl(sdt);" /> *
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
				<tr bgcolor=#ffffff><td width=180px>Reported on behalf of</td><td>
					<input type=text name="other_name" value="<?php print $user_friend; ?>" style="width:150px">					
				</td></tr>
				<tr bgcolor=#ffffff><td width=180px>Additional notes</td><td>
					<textarea name="other_notes" class="editbox" rows="4" cols=40 ><?php print $notes; ?></textarea>
				</td></tr>
				<tr bgcolor=#ffffff><td>&nbsp;</td><td>
				<input type=submit value="Update" name=submitter onclick="return validater();" class=buttonstyle />
				&nbsp;<input type=hidden name="cmd" value="updatelevel1" />
				<input type=hidden name="locid" value=<?php print $_GET['locid']; ?> />
				<input type=hidden name="sid" value=<?php print $_GET['sid']; ?> />
				<input type=button value=Cancel onclick="javascript:canceler(<?php print $_GET['locid'].",".$_GET['sid']; ?>);" class=buttonstyle />
				&nbsp;</td></tr>
				</table>
			</td></tr>
		</table>
		</form>		
		<!-----------------------------------------------------
Written by: Suresh V [verditer at gmail.com]
For: National Center for Biological Sciences, Bangalore
http://ncbs.res.in

In Love of Birds.

------------------------------------------------------->

     <!---------SIGHTING DETAIL END----------------------->