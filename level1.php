<?php
	session_start();
	header("Cache-control: private"); // IE 6 Fix
	if(($_POST[cmd] == "statechanged") || ($_POST['cmd']=="locselected")){
		foreach($_POST as $key=>$value){
			$_SESSION[$key]=$value;
		}
	}
	if($_POST['cmd'] != 'level1') {
		$lastData = $_POST;
	} else {
		unset($lastData);
	}
	include("banner.html");

	if(empty($_POST) && (empty($_GET) || $_GET['last'] = 1)) {
		unset($_SESSION['other_name']);
		unset($_SESSION['obstart']);
		unset($_SESSION['other_notes']);
	}
	if(isset($_GET['state']) && isset($_GET['loc'])) {
		$_SESSION['location'] = $_GET['loc'];
	}

	$dcity = "";
	$ddist = "";
	$dlat = "";
	$dlong = "";

	if ($_GET['last'] == 1 || $_POST['last'] == 1) {
		$last = 1;
		$sighting = 'last';
		$ucsighting = 'Last';
	} else {
		$last = 0;
		$sighting = 'first';
		$ucsighting = 'First';
	}
?>
<html>
<head>
<title>MigrantWatch -- Level 1 Online Form</title>
<link rel="shortcut icon" href="favicon.ico">
<link href="CalendarControl.css"  rel="stylesheet" type="text/css">
<link rel=stylesheet href="style.css" type="text/css">
<script src="CalendarControl.js" language="javascript"></script>
<script src="date.js" language="javascript"></script>
<script language=javascript>

		function isEmpty(s)
		{   return ((s == null) || (s.length == 0))
		}

		// BOI, followed by one or more whitespace characters, followed by EOI.
		var reWhitespace = /^\s+$/
		// BOI, followed by one or more characters, followed by @,
		// followed by one or more characters, followed by .,
		// followed by one or more characters, followed by EOI.
		var reEmail = /^.+\@.+\..+$/
		var defaultEmptyOK = false
		// Returns true if string s is empty or
		// whitespace characters only.

		function isWhitespace (s)

		{   // Is s empty?
			return (isEmpty(s) || reWhitespace.test(s));
		}

	function displaytr(opt){
		if(opt==1)
			document.getElementById("loctr").style.display = "block";
		else
			document.getElementById("loctr").style.display = "none";
	}

	function stateChanged(){
		document.frm_level1.cmd.value = "statechanged";
		document.frm_level1.action = "level1.php";
		document.frm_level1.submit();
	}
	function locSelected(){
		document.frm_level1.cmd.value = "locselected";
		document.frm_level1.action = "level1.php";
		document.frm_level1.submit();
	}
	function addLocation(){
		if(isWhitespace(document.frm_level1.nlname.value)){
			alert("Please enter location name.");
			return;
		}
		if (document.frm_level1.nlstate.value == -1){
			alert("Please select a state for new location");
			return;
		}
		document.frm_level1.cmd.value = "newlocation";
		document.frm_level1.action="process_level1.php";
		document.frm_level1.submit();
	}

	function validate(){
		var now = new Date();
		var flag = false;
		var obstart = document.frm_level1.obstart.value;
		var el, i = 0, oForm = document.frm_level1;

		if(document.frm_level1.location.value == -1){
			alert("Please select a location for the Observations.");
			return false;
		}

		if (document.frm_level1.obstart.value == "") {
			alert("Please enter Observation <?php echo ($last == 1) ? 'End' : 'Start'; ?> date.");
			return false;
		} else if(compareDates(obstart, "dd-MM-yyyy", formatDate(now, "dd-MM-yyyy"), "dd-MM-yyyy") == 1) {
			alert("Observation <?php echo ($last == 1) ? 'End' : 'Start'; ?> date cannot be a future date");
			return false;
		}
		if (document.frm_level1.often.value == "") {
			alert("Please select How often you look for birds at this location?");
			return false;
		}

		var check = <?php print(($last == 1)? 0 : 1); ?>;
		while (el = oForm.elements[i++]) {
			if ((el.type == 'text') && (el.name.indexOf('dt') != -1)){
				if (el.value != "") {
					flag = true;

					if((compareDates(el.value,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1)) {
						alert("sighting dates cannot be future dates ");
						return false;
					} else if((check == 1) && compareDates(obstart, "dd-MM-yyyy", el.value, "dd-MM-yyyy") == 1) {
						alert("The sighting date cannot be a date before start of observations");
						return false;
					} else if((check == 0) && compareDates(el.value, "dd-MM-yyyy", obstart, "dd-MM-yyyy") == 1) {
						alert("The sighting date cannot be a date after the end of observations");
						return false;
					}
				}
			}
		}

		if (!flag){
			alert("Please enter <?php echo $sighting; ?> sighting date for atleast one Species.");
			return false;
		}

		return true;
	}
</script>

</head>
<body>

<a name="Top"></a>
	<?php
		//include("banner.html");
		include("functions.php");
		include("./db.php");
		if (!isset($_SESSION['userid']))
			die("No Session. Fatal Error...<br><a href='login.php'>Login Here</a>");

		if($_GET['error'] == 1) {
		$error = 1;
		$lastData = $_SESSION['level1'];
		$errors = $_SESSION['level1']['errors'];
		} else {
			unset($_SESSION['level1']);
		}
		$errorStrings = array(
							'location' => "Please select location",
							'obstart' => "Start/end date of observations should not be empty or a future date.",
							'often' => "Please select how often you look for birds",
							'obdate' => "Please select a valid observation date"
						);

		if(!empty($errors)) {
			foreach($errors as $field) {
				print("<p style='color:red'><b>".$errorStrings[$field]."</b></p>");
			}
		}
	?>
	<form name=frm_level1 action=process_level1.php method=POST>
  		<input type="hidden" name="last" value="<?php echo $last ?>"/>
		<table border=0 width=580 cellpadding=2 cellspacing=1>
			<tr><td colspan=2>
			&nbsp;
			</td></tr>
			<tr>
			<td colspan=2 style="font-weight:bold;">
				Form for reporting <?php echo $ucsighting; ?> Dates
			</td></tr>
			<tr>
				<td bgcolor="dedede" colspan=2><b>Reported By</b></td>
			</tr>
			<tr>
				<td width=40% align=right>Name :</td>
				<td><b><?php print($_SESSION['username']); ?></b></td>
			</tr>
			<tr>
				<td align=right>
				If you are reporting sightings on behalf of someone else, please
				give that person's name :
				</td>
				<td><input type=text name="other_name" value="<?php print getStripped($lastData['other_name']); ?>" style="width:150px"></tr>
			</tr>
			<tr>
				<td valign=top bgcolor="dedede" colspan=2>
					<b>Location of Observations</b>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td align=right>Select State or Union Territory </td>
		        <td>
			       <SELECT name=state width=150 onchange="javascript:stateChanged();">
						<option value=-1>--Select State--</option>
					<?php
						$result = mysql_query("SELECT state_id, state FROM migwatch_states order by state");
						$state = $_POST['state'];
						if (!$state)
							$state = $_GET['state'];

						if($result){
							while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
								print "<option value=".$row{'state_id'};
								if (($row{'state_id'} == $state) || ($lastData['state'] == $row{'state_id'}))
									print " selected ";
								print ">".$row{'state'}."</option>\n";
							}
						}
					?>
					</select>
		        </td>
			</tr>
			<tr>
		        <td align=right>Select Location</td>
				<td>
					<select name=location width=150 onchange="javascript:locSelected();">
							<option value=-1> - Select A Location - </option>
						<?php

							if(($_POST['cmd'] == "statechanged") || ($state != "") || ($_POST['cmd']=="locselected")) {
								$result = mysql_query("SELECT location_id, location_name, city, district, latitude, longitude FROM migwatch_locations WHERE state_id=".$state." ORDER BY location_name");
								if($result){
									while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
										print "<option value=".$row{'location_id'};
										if (($_GET['loc'] == $row{'location_id'}) || ($lastData['location'] == $row{'location_id'}) || ($_POST['location'] == $row{'location_id'}))
											print " selected ";
										print ">".$row{'location_name'}." - ".$row{'city'}."</option>\n";
									}
								}
							}
						?>
					</select> *
				</td>
			</tr>
			<?php
				if(($_POST['cmd']=="locselected") && (isset($lastData['location'])) || isset($_GET['state']) && isset($_GET['loc'])) {
					$result = mysql_query("SELECT city, district, latitude, longitude FROM migwatch_locations WHERE location_id=".$lastData['location']);
					if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
							$dcity = $row{'city'};
							$ddist = $row{'district'};
							$dlat  = $row{'latitude'};
							$dlong = $row{'longitude'};
						}
					}
				}
			?>
			<tr>
					<td align=right>City/Town:</td>
					<td><?php print $dcity; ?></td>
			</tr>
			<tr>
					<td align=right>District:</td>
					<td><?php print $ddist; ?></td>
			</tr>
			<tr>
					<td align=right>Latitude:</td>
					<td><?php print $dlat; ?></td>
			</tr>
			<tr>
					<td align=right>Longitude:</td>
					<td><?php print $dlong; ?></td>
			</tr>
			<tr><td colspan=2><a href="javascript:displaytr(1);">Add New Location</a></td>
			</tr>
			<tr>
				<td colspan=2>
					<table width="100%" style="border-style:solid;border-width:1px; display:none" align='center' id="loctr">
						<tr>
							<td width=20%>Location Name:</td>
							<td><input type=text name=nlname style="width:250px;"/>
							(eg. Okhla Bird Sanctuary)
							</td>
						</tr>
						<tr>
							<td>City/Town:</td>
							<td><input type=text name=nlcity style="width:250px;"/></td>
						</tr>
						<tr>
							<td>District:</td>
							<td><input type=text name=nldist style="width:250px;"/></td>
						</tr>
						<tr>
							<td>State:</td>
							<td>
							<SELECT name=nlstate style="width:250">
								<option value=-1>--Select State--</option>
								<?php
									$result = mysql_query("SELECT state_id, state FROM migwatch_states order by state");
									if($result){
										while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
											print "<option value=".$row{'state_id'};
											print ">".$row{'state'}."</option>\n";
										}
									}
								?>
							</select>
							</td>
						</tr>
						<tr>
							<td>Latitude:</td>
							<td><input type=text name=nllat style="width:250px;"/>&nbsp;(If known)</td>
						</tr>
						<tr>
							<td>Longitude:</td>
							<td><input type=text name=nllong style="width:250px;"/>&nbsp;(If known)

							</td>
						</tr>
						<tr>
							<td colspan=2>
							If you don't know the geographical coordinates,
							please describe the location in detail (eg, distance and direction
							from the nearest town)
							</td>
						</tr>
						<tr>
							<td colspan=2>
								<textarea name=nladditional rows=5 cols=40></textarea>
							</td>
						</tr>
						<tr>
							<td colspan=2>
								<input type=hidden name=currentfile value="level1.php">
								&nbsp;<input type=button value="Add" class=buttonstyle onclick="addLocation();" />
							&nbsp;<input type=button value="Hide" class=buttonstyle onclick="javascript:displaytr(0);"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="dedede" colspan=2><b>Observation Details</b></td>
			</tr>
			<tr>
				<td align=right><?php echo ($last == 1) ? 'End' : 'Start'; ?> of Observations:</td>
				<td>
					<input type=text name=obstart value="<?php print $lastData['obstart']; ?>" readonly><input type=button value="Choose" onclick="showCalendarControl(obstart);" /> *

					<br>
					(Date when you <?php echo ($last == 1) ? 'stopped' : 'started'; ?> observing at this location after 1st Aug 2007)
				</td>
			</tr>
			<tr>
				<td align=right>
					How often do you look for birds at this location?
				</td>
				<td>
					<select name="often">
						<option value="" >Select</option>
						<option value="Daily" <?php print($lastData['often']=="Daily" ? " selected " : "") ?>>Daily</option>
						<option value="Weekly" <?php print($lastData['often']=="Weekly" ? " selected " : "") ?>>Weekly</option>
						<option value="Fortnightly" <?php print($lastData['often']=="Fortnightly" ? " selected " : "") ?>>Fortnightly</option>
						<option value="Monthly" <?php print($lastData['often']=="Monthly" ? " selected " : "") ?>>Monthly</option>
						<option value="Irregular" <?php print($lastData['often']=="Irregular" ? " selected " : "") ?>>Irregular</option>
						<option value="First visit" <?php print($lastData['often']=="First visit" ? " selected " : "") ?>>This is my first visit</option>
					</select> *
				</td>
			</tr>
			<tr>
				<td align=left colspan='2'>* <?php echo $ucsighting; ?> Dates of Sighting for:<br>(dd-mm-yyyy)</td>
			</tr>
			<tr>
				<td>
					<?php
						$result = mysql_query("SELECT species_id, common_name FROM migwatch_species WHERE core='1' AND active=1");
						if($result){
							$i = 1;
							$speciescount = mysql_num_rows($result);
							$table1rows = ceil($speciescount / 2);
							$table2rows = $speciescount - $table1rows;

							echo "<table width='98%' cellpadding='2' cellspacing='1' style='border-width:1px;border-style:solid;'>";
							while ($i <= $table1rows){
								$row = mysql_fetch_array($result, MYSQL_ASSOC);
								print "<tr bgcolor=".($i++ % 2 == 0 ? "#dedede" : "#FFFFFF")."><td width=40% align=right>";
								print $row{'common_name'};
								print "</td><td>";
								print "<input name=dt".$row{'species_id'}." type=text readonly value='".$lastData["dt".$row{species_id}]."'>";
								print "<input name=spname".$row{'species_id'}." type=hidden value='".$row{'common_name'}."' />";
								print "<input type=button value='Choose' onclick='showCalendarControl(dt".$row{'species_id'}.")' />";
								print "</td></tr>\n";
							}
						}
					?>
					</table>
                </td>
				<td width='232px'>
					<table width='232px' cellpadding=2 cellspacing=1 style="border-width:1px;border-style:solid;">
					<?php
						if($result && $table2rows > 0){
							$i = 1;
							while ($i <= $table2rows){
								$row = mysql_fetch_array($result, MYSQL_ASSOC);
								print "<tr bgcolor=".($i++ % 2 == 0 ? "#dedede" : "#FFFFFF")."><td width=40% align=right>";
								print $row{'common_name'};
								print "</td><td>";
								print "<input name=dt".$row{'species_id'}." type=text readonly value='".$lastData["dt".$row{species_id}]."'>";
								print "<input name=spname".$row{'species_id'}." type=hidden value='".$row{'common_name'}."' />";
								print "<input type=button value='Choose' onclick='showCalendarControl(dt".$row{'species_id'}.")' />";
								print "</td></tr>\n";
							}
						}
					?>
					</table>
                </td>
			</tr>
			<tr>
				<td align=right>Other Notes:</td>
				<td><textarea name="other_notes" class="editbox" rows="4" cols=40 ><?php print getStripped($lastData['other_notes']); ?></textarea>
					<br>(eg, habitat, numbers of individuals seen, etc)
				</td>
			</tr>
			<tr>
				<td colspan=2>
				<br><input type=submit value= "Submit Data"  class=buttonstyle onclick="return validate();">
				&nbsp;<input type=button value="Go Back to Main Page" class=buttonstyle onclick="javascript:window.location.href='main.php';" />
				<input type=hidden name=cmd value="level1"/>
				</td>
			</tr>
			<tr><td colspan=2>&nbsp;</td></tr>
			<tr>
				<td colspan=2>
					<li> <b>Asterisks (*) indicate required fields.</b>
					<li>A message with the information you submit
will be sent to your email address. Please check this carefully
and tell us about any errors.

				</td>
			</tr>
		</table>
	</form>
</body>
</html>
<!-----------------------------------------------------
Written by: Suresh V [verditer at gmail.com]
For: National Center for Biological Sciences, Bangalore
http://ncbs.res.in

In Love of Birds.

------------------------------------------------------->
