<?php session_start();
$type='general';
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	}

	// unset the sessions for other files used for sorting
	unset($_SESSION['mylocations']);
	unset($_SESSION['locsightings']);
	unset($_SESSION['myspecies']);
	unset($_SESSION['speciessighting']);
    unset($_SESSION['trackspecies']);

	include("header.php");
	include("functions.php");
	include('db.php');

	$user_id = $_SESSION['userid'];

	if($_POST['cmd'] != 'speciessighting') {
		$lastData = $_POST;
		$rowCount = count($lastData['obdate']);
	} else {
		unset($lastData);
	}

	if (!empty($_GET['loc_id'])) {
		$lastData['location'] = $_GET['loc_id'];
	}

	$last = 0;
	$start = 'start';
	$look = 'do you look';
	$sighting = 'General';
	$after = 'before';

	//  The current season..
	$today = getdate();
	$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;

	// TO be continued.
	if (!empty($_GET['loc_id']) || (($_POST['cmd'] == 'loc_change') && !empty($_POST['location']))) {
		$location_id = (!empty($_POST['location'])) ? $_POST['location'] : $_GET['loc_id'];
		if (isValidLocation($location_id,$connect)) {
			$sql = "SELECT obs_start,frequency FROM migwatch_l1 WHERE location_id='$location_id' " .
				   "AND user_id = '$user_id' AND obs_start >'$currentSeason-06-30' AND obs_type = 'first' AND deleted = '0' ORDER BY sighting_date DESC LIMIT 0,1";
			$res = mysql_query($sql);
			if (mysql_num_rows($res) > 0) {
				list($lastData['obstart'],$lastData['often']) = mysql_fetch_array($res,MYSQL_NUM);
				$lastData['obstart'] = date('d-m-Y',strtotime($lastData['obstart']));
			} else {
				$lastData['obstart'] = '';
				$sql2 = "SELECT frequency FROM migwatch_l1 WHERE location_id='$location_id' ";
				$sql2.= "AND user_id = '$user_id' AND obs_start > '$currentSeason-06-30' AND deleted = '0' ORDER BY sighting_date DESC LIMIT 1";
				$res2 = mysql_query($sql2);
				if(mysql_num_rows($res2) > 0) {
					$row2 = mysql_fetch_array($res2);
					$lastData['often'] =  $row2['frequency'];
				} else {
					$lastData['often'] = '';
				}
			}
		} else {
			header("Location: main.php");
			exit;
		}
	}

	if(isset($_GET['state']) && isset($_GET['loc'])) {
		$_SESSION['location'] = $_GET['loc'];
	}

?>
<link href="CalendarControl.css"  rel="stylesheet" type="text/css">
<script src="CalendarControl.js" language="javascript"></script>
<script src="jquery.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<script src="jquery.bgiframe.min.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<link href="jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="date.js" language="javascript"></script>
<script language="JavaScript" type="text/javascript">

	// define a custom method on the string class to trim leading and training spaces
	String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); };

	var reWhitespace = /^\s+$/

	function isEmpty(s){
		return ((s == null) || (s.length == 0))
	}

	function isWhitespace (s) {	// Is s empty?
		return (isEmpty(s) || reWhitespace.test(s));
	}

	function displaytr(opt){
		if(opt==1)
			document.getElementById("loctr").style.display = "block";
		else
			document.getElementById("loctr").style.display = "none";
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

	function stateChanged() {
		document.frm_level1.cmd.value = "statechanged";
		document.frm_level1.action = "gensightings.php";
		document.frm_level1.submit();
	}

	function locSelected(){
		document.frm_level1.cmd.value = "locselected";
		document.frm_level1.action = "gensightings.php";
		document.frm_level1.submit();
	}

	var HintClass = 'hintTextbox';
	var hintText = 'Please enter identification details';
	var HintActiveClass = "hintTextboxActive";
	var speciesHintText = 'Type part of a species name';

	function appendNewInput_<? echo $type; ?>()
	{
	  var tbl = $('#catTable_<? echo $type; ?>');
	  var lastRow = $('#catTable_<? echo $type; ?> tr').length;

	  // if there's no header row in the table, then iteration = lastRow + 1
	  var iteration = lastRow;

	  var newElement =
		"<tr>"
			+ "<td>" + iteration + "</td>"
			+ "<td width='150px'>"
			+ "<input type='text' id='species_<? echo $type; ?>_" + iteration +"' name='species[]' size='25' value='Type part of a species name' class='hintTextbox'></td>"
		+ "<td>"
			+ "<input type='text' id='obdate_" + iteration + "' name='obdate[]' value='' style='width:75px'> "
			+ "<input type='button' id='obdate_but_" + iteration + "' value='Choose' onclick='showCalendarControl(obdate_" + iteration + ");' /></td>"
		+ "<td><input type='text' id='number_" + iteration +"' name='number[]' size='5'></td>"
		+ "<td>"
			+ "<select id= 'accuracy_" + iteration + "' name='accuracy[]'>"
			+ "<option value=''>-- SELECT --</option>"
			+ "<option value='exact'>Exactly</option>"
			+ "<option value='approximate'>Approximately</option>"
			+ "</select>"
		+ "</td>"
		+ "<td><input type='text' id='entry_notes_" + iteration + "' name='entry_notes[]' size='35' style='width:211px'></td>"
		"</tr>";

		$('#catTable_<? echo $type; ?>').append(newElement);
	  	$("#species_<? echo $type; ?>_" + iteration).autocomplete("autocomplete.php", {
			width: 260,
			selectFirst: false,
			matchSubset :0,
			extraParams : {all : 1},
			formatItem:formatItem
	  	});

		var hintText = 'Please enter identification details';
	  	$("#species_<? echo $type; ?>_" + iteration).result(function(event, data, formatted) {
			if (data[1] == 1) {
				if ($("#entry_notes_" + iteration).val != '') {
					$("#entry_notes_" + iteration).val(hintText);
					$("#entry_notes_" + iteration).addClass(HintClass);
					var id = "entry_notes_" + iteration;
					$("#entry_notes_" + iteration).focus( function() { onHintTextboxFocus(id,hintText) } );
					$("#entry_notes_" + iteration).blur( function() { onHintTextboxBlur(id,hintText) } );
				}
			} else {
				if ($("#entry_notes_" + iteration).val() == hintText) {
					$("#entry_notes_" + iteration).removeClass(HintClass);
					$("#entry_notes_" + iteration).removeAttr('onFocus');
					$("#entry_notes_" + iteration).removeAttr('onBlur');
					$("#entry_notes_" + iteration).val('');
				}
			}
	  	});

		var id = "species_<? echo $type; ?>_" + iteration;
		$("#species_<? echo $type; ?>_" + iteration).focus( function() { onHintTextboxFocus(id,speciesHintText) } );
		$("#species_<? echo $type; ?>_" + iteration).blur( function() { onHintTextboxBlur(id,speciesHintText) } );

	}

	$().ready(function() {
		$('#species_<? echo $type; ?>_1').autocomplete("autocomplete.php", {
			width: 260,
			selectFirst: false,
			matchSubset :0,
			extraParams : {all : 1},
			formatItem:formatItem
		});

		var iteration = 1;
		var hintText = 'Please enter identification details';
		$("#species_1").result(function(event, data, formatted) {
			if (data[1] == 1) {
				if ($("#entry_notes_" + iteration).val != '') {
					$("#entry_notes_" + iteration).val(hintText);
					$("#entry_notes_" + iteration).addClass(HintClass);
					var id = "entry_notes_" + iteration;
					$("#entry_notes_" + iteration).focus( function() { onHintTextboxFocus(id,hintText) } );
					$("#entry_notes_" + iteration).blur( function() { onHintTextboxBlur(id,hintText) } );
				}
			} else {
				if ($("#entry_notes_" + iteration).val() == hintText) {
					$("#entry_notes_" + iteration).removeClass(HintClass);
					$("#entry_notes_" + iteration).removeAttr('onFocus');
					$("#entry_notes_" + iteration).removeAttr('onBlur');
					$("#entry_notes_" + iteration).val('');
				}
			}
		});

		var id = "species_<? echo $type; ?>_" + iteration;
		$("#species_<? echo $type; ?>_" + iteration).focus( function() { onHintTextboxFocus(id,speciesHintText) } );
		$("#species_<? echo $type; ?>_" + iteration).blur( function() { onHintTextboxBlur(id,speciesHintText) } );
	});

	function onHintTextboxFocus(id,text) {
	  var input = document.getElementById(id);
	  if (text != undefined) {
	  	hintText = text;
	  }

	  if (input.value.trim()== hintText) {
	    input.value = "";
	    input.className = HintActiveClass;
	  }
	}

	function onHintTextboxBlur(id,text) {
	  var input = document.getElementById(id);

	  if (text != undefined) {
	  	hintText = text;
	  }

	  if (input.value.trim().length==0) {
	    input.value = hintText;
	    input.className = HintClass;
	  }
	}

	function onSpeciesChange(id,text) {

		if (text != undefined) {
			hintText = text;
		}

		if ($("#" + id).val() == hintText) {
			$("#" + id).removeClass(HintClass);
			$("#" + id).removeAttr('onFocus');
			$("#" + id).removeAttr('onBlur');
			$("#" + id).val('');
		}
	}

	function strrpos(haystack, needle, offset) {
	    // http://kevin.vanzonneveld.net
	    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // *     example 1: strrpos("Kevin van Zonneveld", "e");
	    // *     returns 1: 16

	    var i = haystack.lastIndexOf( needle, offset ); // returns -1
	    return i >= 0 ? i : false;
	}

	function substr( f_string, f_start, f_length ) {
	    // http://kevin.vanzonneveld.net
	    // +     original by: Martijn Wieringa
	    // *         example 1: substr("abcdef", 0, -1);
	    // *         returns 1: "abcde"

	    if(f_start < 0) {
	        f_start += f_string.length;
	    }

	    if(f_length == undefined) {
	        f_length = f_string.length;
	    } else if(f_length < 0){
	        f_length += f_string.length;
	    } else {
	        f_length += f_start;
	    }

	    if(f_length < f_start) {
	        f_length = f_start;
	    }

	    return f_string.slice(f_start,f_length);
	}

	function formatItem(row) {
		var completeRow;
		completeRow = new String(row);
		var scinamepos = completeRow.lastIndexOf("(");
		var rest = substr(completeRow,0,scinamepos);
		var sciname = substr(completeRow,scinamepos);
		var commapos = sciname.lastIndexOf(",");
		sciname = substr(sciname,0,commapos);
		var newrow = rest + ' <i>' + sciname + '</i>';
		return newrow;
	}

	function validate() {
		var last = <?php print($last); ?>;
		var now = new Date();
		var obstart = document.frm_level1.obstart.value;
		if(last == 1) {
			var first = 'Last';
		} else {
			var first = 'First'
		}
		if(document.frm_level1.location.value == -1) {
			alert("Please select a location for the Observations.");
			return false;
		}
		if (obstart == "") {
			if(last == 1) {
				alert("Please enter the date when you stopped looking for birds at this location \n(in the current migration season)");
			} else {
				alert("Please enter the date when you started looking for birds at this location \n(in the current migration season)");
			}
			return false;
		} else if(compareDates(obstart, "dd-MM-yyyy", formatDate(now, "dd-MM-yyyy"), "dd-MM-yyyy") == 1) {
			alert("The Start/end date of observation cannot be a future date");
			return false;
		}

		if (document.frm_level1.often.value == "") {
			alert("Please select How often you look for birds at this location?");
			return false;
		}

		var tbl = $('#catTable_<? echo $type; ?>');
		var lastRow = $('#catTable_<? echo $type; ?> tr').length - 1;
        var obSeason = season($('#obstart').val());
		for(var i = 1; i <= lastRow; i++) {

			if ($('#obdate_' + i).val() != '' || (i == 1)) {
				var obdate = $('#obdate_' + i).val();
				if ($('#species_<? echo $type; ?>_' + i).val() == '' || $('#species_<? echo $type; ?>_' + i).val() == speciesHintText) {
					alert('Please enter the species for the for the entry no. ' + i);
					$('#species_' + i).focus();
					return false;
				}

				if (compareDates(obdate,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) {
					alert("The observation date cannot be a future date.");
					return false;
				}
			}

			if (($('#species_<? echo $type; ?>_' + i).val() != '' && $('#species_<? echo $type; ?>_' + i).val() != speciesHintText) || (i == 1)) {
				if ($('#obdate_' + i).val() == '') {
					alert('Please enter the ' + first + ' Sighting Date for this species (entry no. ' + i +')');
					$('#obdate_' + i).focus();
					return false;
				} else {
					var obdate = $('#obdate_' + i).val();
					var ob_type = $('#ob_type_' + i).val();

					if(compareDates(obdate,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) {
						alert("Sighting dates cannot be future dates ");
						return false;
					} else if(last == 0) {
                        if(season(obdate) != obSeason) {
                            var alertstr = "You have entered a START date in the "+formatSeason(obSeason)+" migration season. ";
                            alertstr += "If your record is not from the "+formatSeason(obSeason)+" season,";
                            alertstr += " please enter a start date between 1 July and 30 June of the correct season.";
                            alertstr += "\nAre you sure you want to enter sighting for season "+formatSeason(obSeason)+"?";
                            if(!confirm(alertstr)) {
                                return false;
                            }
                        } else if(currentSeason() != obSeason) {
                            if(!confirm("Are you sure you want to enter sighting for season "+formatSeason(obSeason)+"?")) {
                                return false;
                            }
                        }
                        if(compareDates(obstart, "dd-MM-yyyy", obdate, "dd-MM-yyyy") == 1) {
                            alert("The " + first + " Sighting Date cannot be a date before start of observations");
                            return false;
                        }
					} else if(last == 1) {
                        /*if(season(obdate) != obSeason) {
                            var alertstr = "You have entered a Observation END date in the "+formatSeason(obSeason)+" migration season. ";
                            alertstr += "If your record is not from the "+formatSeason(obSeason)+" season,";
                            alertstr += " please enter a observation end date between 1 July and 30 June of the correct season.";
                            alertstr += "\nAre you sure you want to enter sighting for season "+formatSeason(obSeason)+"?";
                            alert(alertstr);
                            return false;
                        }*/
                        if(compareDates(obdate, "dd-MM-yyyy", obstart, "dd-MM-yyyy") == 1) {
                            alert("The " + first + " Sighting Date cannot be a date after the end of observations");
                            return false;
                        }
					}
				}
			}

			if ($('#species_<? echo $type; ?>_' + i).val() != '' && $('#obdate_' + i).val() != '') {
				var num = $('#number_' + i).val();
				var acc = $('#accuracy_' + i).val();
				if (!isWhitespace(num) && isNaN(num)) {
					alert('Please enter only numerals under \'Number\'');
					$('#number_' + i).focus();
					return false;
				}

				if ((!isWhitespace(num) && num != '') && acc == '') {
					alert('Please select the \'Accuracy of count\' for entries where \'Number\' has been entered.');
					$('#accuracy_' + i).focus();
					return false;
				}
			}
		}
	}

	function setLocationData() {
		document.forms[0].cmd.value = 'loc_change';
		document.forms[0].action = 'gensightings.php';
		document.forms[0].submit();
	}

    function season(inputDate) {
        var seasonArray = inputDate.split("-");
        if(seasonArray[1] < 7) {
            return parseInt(seasonArray[2]) - 1;
        } else {
            return seasonArray[2];
        }
    }

    function currentSeason() {
        var currDate = new Date();
        if(currDate.getMonth() < 7) {
            return parseInt(currDate.getFullYear()) - 1;
        } else {
            return currDate.getFullYear();
        }
    }
    function formatSeason(seasonStart) {
        var seasonEnd = parseInt(seasonStart) + 1;
        var str = new String(seasonEnd);
        str = str.substring(2);
        return (seasonStart+'/'+str);
    }
</script>
<?php
if ($_GET['error'] == 1) {
	$error = 1;
	$lastData = $_SESSION['gensightings'];
	$rowCount = count($lastData['obdate']);
	$duplicate = (isset($lastData['duplicate']))? $lastData['duplicate'] : '';
	$errors = $lastData['errors'];
	$duplicate_species = $lastData['duplicate_species'];
} else {
	$rowCount = ($rowCount > 0) ? $rowCount : 1;
}
unset($_SESSION['gensightings']);

$errorStrings = array(
						'location' => 'Please select a location',
						'no_obStart' => 'Please enter the date when you started looking for birds at this location (in the current migration season)',
						'no_obEnd' => 'Please enter the date when you stopped looking for birds at this location (in the current migration season)',
						'obstart' => "The Observation $start date should be in (DD-MM-YYYY) format and should not be a future date",
						'no_obdate' => "Please enter $sighting Sighting Date for this species",
						'often' => 'Please select how often you look for birds',
						'obdate' => "The Sighting date should be in (DD-MM-YYYY) format and should not be a future date",
						'ob_compare' => "The $sighting Sighting Date cannot be a date $after observation start date",
						'species' => 'This species is not been tracked currently.',
						'number' => "Please enter only numerals under 'Number'",
						'accuracy' => "Please select the 'Accuracy of Count' in the entries for which the 'Number' has been entered",
						'duplicate' => $duplicate
					);

$speciesHintText = 'Type part of a species name';

if (!empty($errors)) {
	foreach ($errors as $field) {
		print("<p style='color:red'><b>". $errorStrings[$field]."</b></p>");
	}
}
?>

<form method='post' name='frm_level1' action='process_level1.php'>
<input type='hidden' name='last' value='<?php echo $last; ?>' />
<table border=0 width="770" cellpadding=2 cellspacing=1>
    <?php
		$locations = getAllMyLocationData($user_id,$connect);
        if(!empty($locations)) {
    ?>
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
		<td><input type=text name="other_name" value="<?php print getStripped(htmlentities($lastData['other_name'],ENT_QUOTES)); ?>" style="width:150px"></tr>
	</tr>
    <?php
        }
    ?>
	<tr>
		<td valign=top bgcolor="dedede" colspan=2>
			<b>Location of Observations</b>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<?php
		if (!empty($locations)) {
	?>
	<tr>
        <td align=right>Choose From Your Locations :</td>
		<td>
			<select name=location style="width:300" onChange='setLocationData();'>
					<option value=-1> - Select A Location - </option>
			<?php
			foreach ($locations as $row) {
				print "<option value='$row[location_id]'";
				($lastData['location'] == $row['location_id']) ? print ' selected>' : print '>';
				print $row{'location_name'}." - ".$row{'city'}." - (".$row{'state'}.")</option>\n";
			}
			?>
			</select> *
		</td>
	</tr>
	<?php
		} else {
	?>
	<tr>
        <td colspan='2' align='center' style='color:red; font-weight:bold'>You have no locations under 'My Locations' to select from.</td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td align=right colspan='2'>To report a sighting, the location must be listed under 'My Locations'. Go to <a href="mylocations.php">My Locations</a> to add a location to your list. <br /></td>
	</tr>
	<tr><td colspan='2'>&nbsp;</td></tr>
    <?php
        if (!empty($locations)) {
    ?>
	<tr>
		<td bgcolor="dedede" colspan=2><b>Observation Details</b></td>
	</tr>
	<tr>
		<td align=right>When did you <?php print(strtoupper($start)); ?> looking for birds at this location?</td>
		<td>
			<input type=text id="obstart" name=obstart value="<?php print $lastData[obstart]; ?>"><input type=button value="Choose" onclick="showCalendarControl(obstart);" /> (dd-mm-yyyy)*
			<br>
			<!--(Date when you <?php echo ($last == 1) ? 'stopped' : 'started'; ?> observing at this location after 1st Aug 2007)-->
		</td>
	</tr>
	<tr>
		<td align=right>How often <?php print($look); ?> for birds at this location?</td>
		<td>
			<select name="often">
				<option value="" >Select</option>
				<option value="Daily" <?php print($lastData[often]=="Daily" ? " selected " : "") ?>>Daily</option>
				<option value="Weekly" <?php print($lastData[often]=="Weekly" ? " selected " : "") ?>>Weekly</option>
				<option value="Fortnightly" <?php print($lastData[often]=="Fortnightly" ? " selected " : "") ?>>Fortnightly</option>
				<option value="Monthly" <?php print($lastData[often]=="Monthly" ? " selected " : "") ?>>Monthly</option>
				<option value="Irregular" <?php print($lastData[often]=="Irregular" ? " selected " : "") ?>>Irregular</option>
				<option value="First visit" <?php print($lastData[often]=="First visit" ? " selected " : "") ?>>This is my first visit</option>
			</select> *
		</td>
	</tr>
	<tr>
		<td width=40% align=right>General notes about this location:</td>
		<td><textarea name="other_notes" class="editbox" rows="4" cols=60 ><?php print getStripped($lastData['other_notes']); ?></textarea>
			<br>(eg, habitat, disturbance, etc.)
		</td>
	</tr>
</table>
<p>Please use the form below to enter part of the name of a species, and choose from the options that appear.
<br /> If no options appear, either the species is not being tracked under MigrantWatch or the name is mis-spelt.</p>
<table id='catTable_<? echo $type; ?>'>
	<tbody>
	<tr>
		<td>No.</td>
		<td width='150px'>Species</td>
		<td width='180px'><?php print($sighting); ?> Sighting Date(dd-mm-yyyy)</td>
		<td>Number</td>
		<td>Accuracy of count</td>
		<td>Notes on this Record</td>
	</tr>
	<?php
		if ($rowCount == 0) {
			$rowCount = 1;
		}

		for($i = 1; $i <= $rowCount; $i++) {
			$j = $i - 1;
	?>
	<tr id='cloneme'>
		<td><?php print($i);?></td>
		<td>
			<input type='text' id='species_<? echo $type; ?>_<?php echo $i; ?>' name='species[]' size='25'
			value='<?php
						$useDefault = 0;
						if(isset($lastData['species'][$j]) && !empty($lastData['species'][$j])) {
							$species_sel = getStripped(htmlentities($lastData['species'][$j],ENT_QUOTES));
							if ($speciesHintText == $species_sel) {
								$useDefault = 1;
							}
							echo $species_sel;
						} else {
							echo $speciesHintText;
							$useDefault = 1;
						}
					?>'"
					<?php
						// TODO : even in case of error make the functionality work.
						if ($useDefault == 1)
							echo " class=\"hintTextbox\" onFocus=\"onHintTextboxFocus('species_$i',speciesHintText)\" onBlur=\"onHintTextboxBlur('species_$i',speciesHintText)\"";
					?>></td>
		<td>
			<input type='text' id='obdate_<?php echo $i; ?>' name='obdate[]' value='<?php if(isset($lastData['obdate'][$j])) echo $lastData['obdate'][$j]; ?>' style='width:75px'>
			<input type='button' id='obdate_but_<?php echo $i; ?>' value="Choose" onclick="showCalendarControl(obdate_<?php echo $i; ?>);" /></td>
		<td>
			<input type="text" id='number_<?php echo $i ?>' name='number[]' size="5" value='<?php if(isset($lastData['number'][$j])) echo $lastData['number'][$j]; ?>'></td>
		<td>
			<select id= 'accuracy_<?php echo $i ?>' name='accuracy[]'>
				<option value=''>-- SELECT --</option>
				<option value='exact' <?php if(isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'exact') echo 'selected' ?>>Exactly</option>
				<option value='approximate' <?php if (isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'approximate') echo 'selected' ?>>Approximately</option>
			</select>
		</td>
		<td><input type="text" id='entry_notes_<?php echo $i?>' name='entry_notes[]' size="35" value='<?php if(isset($lastData['entry_notes'][$j])) echo getStripped(htmlentities($lastData['entry_notes'][$j],ENT_QUOTES)); ?>' style='width:211px'
		<?php
		if($lastData['entry_notes'][$j] == 'Please enter identification details') {
			echo " onFocus=\"onHintTextboxFocus(id,hintText)\" onBlur=\"onHintTextboxBlur('entry_notes_$i','Please enter identification details')\" class='hintTextbox'";
		}
		?>
		>
		</td>
	</tr>
	<?php
			if ($i > 1) {
				echo <<<JS
				<script language='javascript'>
				  	$("#species_" + $type + "_" + $i).autocomplete("autocomplete.php", {
						width: 260,
						selectFirst: false,
						matchSubset :0,
						extraParams : {all : 1},
						formatItem:formatItem
				  	});

				  	$("#species_" + $type + "_" + $i).result(function(event, data, formatted) {
						if (data[1] == 1) {
							if ($("#entry_notes_" + $i).val != '') {
								$("#entry_notes_" + $i).val(hintText);
								$("#entry_notes_" + $i).addClass(HintClass);
								var id = "entry_notes_" + $i;
								$("#entry_notes_" + $i).focus( function() { onHintTextboxFocus(id,hintText) } );
								$("#entry_notes_" + $i).blur( function() { onHintTextboxBlur(id,hintText) } );
							}
						} else {
							if ($("#entry_notes_" + $i).val() == hintText) {
								$("#entry_notes_" + $i).removeClass(HintClass);
								$("#entry_notes_" + $i).removeAttr('onFocus');
								$("#entry_notes_" + $i).removeAttr('onBlur');
								$("#entry_notes_" + $i).val('');
							}
						}
				  	});
				</script>
JS;
			}
		}
	?>
</table>
<a name='x'></a>
<input type="hidden" name="cmd" value="gensightings" id='cmd'/>
<input type="hidden" name="general" value="1"/>
<span style="margin-left:25px;"><a href='#x' onclick='appendNewInput_<? echo $type; ?>()'> Add another species </a>
<div style="margin-top:5px;">
	&nbsp;&nbsp;<input type='submit' value= "Submit Data"  class='buttonstyle' onclick="return validate();">
	&nbsp;&nbsp;<input type=button value="Go Back to Main Page" class=buttonstyle onclick="javascript:window.location.href='main.php';" />
</div>
<?php
    } else {
        print("</table>");
    }
?>
</form>
<br />
<?php

	if (!empty($lastData['location'])) {
		// Get the current season details
		list($seasonStart,$seasonEnd) = getCurrentSeason(true);

		$sql = "SELECT l1.obs_start,l1.number,l1.notes,s.common_name, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
		$sql.= "l1.obs_type, l1.id, l1.deleted FROM migwatch_l1 l1 ";
		$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
		$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
		$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
		$sql.= "WHERE l1.user_id = '".$user_id."' AND l1.deleted = '0' AND ";
		$sql.= " l1.location_id = '$lastData[location]' AND obs_type = '".strtolower($sighting)."' ";
		$sql.= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
			     AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";
		$orderBy = 'sighting_date ';
		$sort = 'DESC';
		$sql.= $srt = "ORDER BY $orderBy $sort ";
		$result = mysql_query($sql,$connect);
		$total_rows = mysql_num_rows($result);
	}

	if ($total_rows > 0) {
?>
<table border=0 width="770" cellpadding=2 cellspacing=1>
	<tr>
		<td bgcolor="dedede" colspan=2><b><?php if ($total_sightings > 10) { echo '10 '; } echo 'Recent '; echo strtolower($sighting) ?> sightings submitted by you in the current season at this location.</b></td>
	</tr>
	<tr style="background-color: rgb(239, 239, 239);"><td colspan=2>
		<table width=98% cellpadding=3 cellspacing=0 style="border-width:1px;border-style:solid;border-color:#dedede">
			<tr style="font-weight:bold;font-size:x-small;background-color:#efefef">
				<td>&nbsp;</td>
				<td>Species</td>
				<td><?php echo $sighting ?> Sighting Date</td>
				<td>Number</td>
				<td>Notes</td>
			</tr>
			<?php
			if($result) {
				$i = 1;
				$j = (($pageno - 1) * $rows_per_page) + 1;
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$sLinkBegin = "<a class=tablelink href=\"viewsighting.php?id=".$row['id']."\">";
					$sLinkEnd = "</a>";
					print "<tr bgcolor=".($i % 2 == 0?"#efefef":"#ffffff").">";
					print "<td>".$j."</td>";
					print "<td>".$row{'common_name'}."</td>";
					print "<td>".date("d-M-Y",strtotime($row{'sighting_date'}))."</td>";
					print "<td>"; echo ($row{'number'} == 0 || $row{'number'} == NULL) ?  '--' : $row{'number'}; print "</td>";
					print "<td>"; echo (empty($row{'notes'})) ?  '--' : $row{'notes'}; print "</td>";
					print "</tr>";
					$i++;
					$j++;
				}
			}
		?>
		</table>
	</td>
	</tr>

</table>
<?php
	}

	include('footer.php');
?>
