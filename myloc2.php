<?php
/**
 * The my locations script
 *
 * This page lists the all locations :
 *
 * 1. Added by this user
 * 2. Marked as 'my location' from all the locations available
 * 3. All locations for which the user has added entries till date.
 *
 * Additionally, allows the user to add new locations to 'My Locations'
 *
 * @author Jatin Chimote <jatin@sanisoft.com>
 * @version 1.0
 * @package migwatch
 */

session_start();

// unset the sessions for other files used for sorting
unset($_SESSION['locsightings']);

include('db.php');
include('functions.php');
include('header.php');

// Some style for this page and js code
echo '
	<style>
		.error {
			font-weight : bold;
			color: red;
		}
		.success {
			font-weight : bold;
			color: green
		}
	</style>
	<script language="JavaScript" type="text/javascript" src="jquery.js"></script>
	<script language="javascript">
		function addloc() {
			$("#addloc").toggle();
			$("#locDetails").hide();
            $("#addCurrentLocation").val("");
            $("#footer").toggle();
		}

		function useext() {
			$("#addloc").hide();
			$("#existloc").toggle();
		}

		// BOI, followed by one or more whitespace characters, followed by EOI.
		var reWhitespace = /^\s+$/
		var decimals = /\d.+\d/
		// Returns true if string s is empty or
		// whitespace characters only.

		function isEmpty(s)
		{   return ((s == null) || (s.length == 0))
		}

		function isWhitespace (s)
		{   // Is s empty?
			return (isEmpty(s) || reWhitespace.test(s));
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
			if (document.frm_level1.nllat.value !="" && (!decimals.test(document.frm_level1.nllat.value) || isNaN(document.frm_level1.nllat.value))){
				alert("Please enter latitude and longitude in decimals");
				return false;
			}
			if (document.frm_level1.nllong.value !="" && (!decimals.test(document.frm_level1.nllong.value) || isNaN(document.frm_level1.nllong.value))) {
				alert("Please enter latitude and longitude in decimals");
				return false;
			}
			document.frm_level1.cmd.value = "newlocation";
			document.frm_level1.action="process_level1.php";
			document.frm_level1.submit();
		}

		function stateChanged() {
			document.frm.cmd.value = "statechanged";
			document.frm.action = "mylocations.php#a";
			document.frm.submit();
		}

		function locSelected(){
			document.frm.cmd.value = "locselected";
			document.frm.action = "mylocations.php#a";
			document.frm.submit();
		}

		function addToMyLocations() {
			/*if (document.frm.state.value == "-1") {
				alert("Please select the name of the state from the drop-down");
				return false;
			}*/

			if (document.frm.addCurrentLocation.value == "") {
				alert("Please enter a location");
                document.frm.addCurrentLocation.focus();
				return false;
			}

			document.frm.cmd.value = "newmylocation";
			document.frm.action = "process_level1.php";
			document.frm.submit();
		}
	</script>
	';

if ($_GET['cmd'] == 'insertsuccess') {
	echo '<p class="success">Your sighting(s) have been entered successfully. Thank you.</p>';
}

/**
 * Success message if the record was entered successfully, and error message
 * if something went wrong, if we get a duplicate in the mysql_error. It means
 * the user tries to add a location which is already added to the 'My Locations'
 * Show an error about the same
 */
if ($_GET['extlocadded'] == 1) {
	echo '<p class="success">The location has been added to the \'My Locations\' list</p>';
	unset($_POST);
	unset($_GET);
} else if ($_GET['error'] == 1) {
	if ($_GET['duplicate'] == 1) {
		$error = 'This location already exists in your \'My Locations\'.';
	} else {
		$error = 'There was a problem entering the location. Please check if you have filled in all details correctly.';
	}
	echo "<p class='error'>$error</p>";
}

// The User Id
$user_id = $_SESSION['userid'];

// Set the sorting field
if(!isset($_SESSION['mylocations']['sort']) || $_SESSION['mylocations']['sort'] == '') {
	$_SESSION['mylocations']['sort'] = 'state';
	$_SESSION['mylocations']['sort_order'] = 'ASC';
} elseif(isset($_POST['fieldSort'])) {
	$_SESSION['mylocations']['sort'] = $_POST['fieldSort'];
	$_SESSION['mylocations']['sort_order'] = $_POST['fieldOrder'];
}
$sortBy = $_SESSION['mylocations']['sort'];
$sortOrder = $_SESSION['mylocations']['sort_order'];
//print($sortBy."<br/>".$sortOrder);
// Get this user's locations.
$locations = getAllMyLocationData($user_id,$connect,$sortBy,$sortOrder);
// get $myloctionIds here somewhere
$message = '';
if($_POST['remove_loc'] != '') {
	$sql = "DELETE FROM migwatch_user_locs WHERE location_id='".$_POST['remove_loc']."' AND user_id='".$user_id."'";
	mysql_query($sql);
	$message = "The location $locs[location_name] has been removed from 'My Locations'";
	// the locations array may have changed after the deletion. Get it again.
	$locations = getAllMyLocationData($user_id,$connect,$sortBy,$sortOrder);
}

?>
<script src="jquery.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<script src="jquery.bgiframe.min.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<link href="jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
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
function removeLocation(del_id, creator) {
	userId = '<?php print($user_id); ?>';
	var confirmed = confirm("You are about to remove the record from My Locations. Continue?");

	if(confirmed) {
		document.frm_sortfield.remove_loc.value = del_id;
		document.frm_sortfield.submit();
	}
}

$().ready(function() {
	$('#addCurrentLocation').autocomplete("auto_miglocations.php", {
		width: 400,
		selectFirst: false,
		matchSubset :0,
		formatItem:formatLocation
	});
	$("#addCurrentLocation").result(function(event, data, formatted) {

	    //$('#location').val( !data[1] ? "No match!" : "Selected: " + formatted); 
	    
          $('#location').val(data[1]);
	  
	
        $('#locDetails').show();
        $('#locerr').hide();
        $('#addloc').hide();
        $('#details_loc').html(data[2]);
        $('#details_city').html(data[3]);
        $('#details_dist').html(data[4]);
        $('#details_state').html(data[5]);
        $('#details_lat').html(data[6]);
        $('#details_long').html(data[7]);

	    
	 
    });


	$("#txt_username").blur(function() 
	{ 
	  var location_field; 
 
	       username_length = $("#location").val().length; 
	       $("#username_warning").empty(); 
 
		if (username_length < 4) 
				    $("#username_warning").append("Username is too short"); 
		}); 

        });




function formatLocation(row) {
    var completeRow;
    completeRow = new String(row);
    splittedStr = completeRow.split(",");
    var newrow = '';
    for(var i=0; i< splittedStr.length; i++) {
        if(isNaN(splittedStr[i])) {
            newrow += splittedStr[i] +",";
        } else if(splittedStr[i] > 0 ){
            break;
        }
    }
    newrow = newrow.substr(0, newrow.lastIndexOf(","));
    return newrow;
}

</script>
<table cellspacing="0" cellpadding="4" width="770">
	<tbody>
		<tr bgcolor="#dedede">
			<td align='left'>
				<b>My Locations</b>
			</td>
			<td align="right"><a href='main.php'><img style="border-width: 0px;" src="images/back.gif"/></a></td>
		</tr>
	</tbody>
</table>
<div id="username_warning"></div>
<?php
if (!empty($locations)) {
if($message != '') {
	print("<p class='error'>$message</p>");
}
?>
<p>
The following locations have been marked as 'My Locations': <br/><br/>
</p>
<form name="frm_sortfield" method="POST" action="mylocations.php">
<table cellspacing="0" cellpadding="3" width="770" style="border: 1px solid rgb(222, 222, 222);">
	<tbody>
		<tr style="font-weight: bold; font-size: x-small; background-color: rgb(239, 239, 239);">
				<td>SNo.</td>
				<td><a href="javascript:setSorting('location_name', '')">Location</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('location_name', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('location_name', 'DESC')"></td>
				<td><a href="javascript:setSorting('city', '')">Town</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('city', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('city', 'DESC')"></td>
				<!--<td><a href="javascript:setSorting('district', '')">District</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('district', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('district', 'DESC')"></td>-->
				<td><a href="javascript:setSorting('state', '')">State / UT</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('state', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('state', 'DESC')"></td>
				<td colspan="2">Report Sightings</td>
				<td>Details</td>
		</tr>
<?php
$i = 1;
	  foreach ($locations as $location) {
	  	$delete_this = "&nbsp;&nbsp;<img src='images/delete.gif' title='Remove location' onclick='removeLocation($location[location_id],$location[created_by_user_id])'>";
		print "<tr bgcolor=".($i % 2 == 0?"#efefef":"#ffffff").">";
			echo "<td>$i</td>";
			echo "<td>$location[location_name]</td>";
			echo empty($location['city']) ? '<td>--</td>' : "<td>$location[city]</td>";
			//echo empty($location['district']) ? '<td>--</td>' : "<td>$location[district]</td>";
			echo "<td>$location[state]</td>";
			echo "<td><a href='trackspecies.php?loc_id=$location[location_id]' title='Report first sighting at $location[location_name]'>first</a></td>";
			echo "<td><a href='trackspecies.php?last=1&loc_id=$location[location_id]' title='Report last sighting at $location[location_name]'>last</a></td> ";
			echo "<td><a href='locsightings.php?id=$location[location_id]'><img src='images/view.gif' style='border-width:0px;' title=\"View all sightings at $location[location_name]\"></a> $delete_this</td>";
	    	  "</tr>";
	    	  $i++;
	  }
?>
</table>
<input type="hidden" name="fieldSort" value="<?php print($sortBy); ?>">
<input type="hidden" name="fieldOrder" value="<?php print($sortOrder); ?>">
<input type="hidden" name="remove_loc" value="">
</form>
<?php
} else {
	echo "<br/><p class='error'>There are currently no locations marked as your locations. Please use the links below to add.</p>";
}
?>
<br />
<table cellspacing="0" cellpadding="4" width="770">
	<tbody>
		<tr bgcolor="#dedede">
			<td>
				<b>Add to My Locations</b>
			</td>
		</tr>
	</tbody>
</table>
<p style="width:770px;">
Type <strong>part of a location name</strong> in the text box, <strong>choose from the matches</strong>
and click on <strong>"Add to My Locations"</strong>. Please try common variants on the
location name and spelling if at first you do not find matches. <strong>If
your location does not exist</strong> in our database, please enter the details
of the location below and click on <strong>"Add new location"</strong>. <br /><br>
- <strong>Add a location to My Locations</strong>
<form method="POST" name="frm">
<input type="text" name="addCurrentLocation" id="addCurrentLocation" style="width:400px;" />&nbsp;<input type="reset" value="Clear" onclick="$('#locDetails').hide()"><br />
<input type="hidden" name="cmd" value="" />
<input type="hidden" name="location" id="location" value="" />
<table id="locDetails" width="770px" style="border:1px solid rgb(222, 222, 222);display:none;">
    <tr width="100%">
        <td colspan="2" bgcolor="#efefef"><strong>Location Details</strong></td>
    </tr>
    <tr>
        <td width="25%">Location Name</td>
        <td><div id="details_loc"><div></td>
    </tr>
    <tr bgcolor="#efefef">
        <td>City</td>
        <td><div id="details_city"></div></td>
    </tr>
    <tr>
        <td>District</td>
        <td><div id="details_dist"></div></td>
    </tr>
    <tr bgcolor="#efefef">
        <td>State / UT</td>
        <td><div id="details_state"></div></td>
    </tr>
        <td>Latitude</td>
        <td><div id="details_lat"></div></td>
    <tr>
    </tr>
    <tr bgcolor="#efefef">
        <td>Longitude</td>
        <td><div id="details_long"></div></td>
    </tr>
</table><br />
<input type="button" name="sub" value="Add to 'My Locations'" onClick='addToMyLocations();'/>
</form>
</p>

<table id="locerr">
 <tr><td><b>Please add a a new location</b></td></tr>

</table>
- <a style="background-color:#dedede;" href="#a" onClick="javascript:addloc();"><strong>Add new Location</strong></a><br />
<!-- <a style="background-color:#dedede;" href="#a" onClick="javascript:useext();"><b>Choose From Existing Locations</a></b><br />-->
<form method="post" name='frm_level1'>
<table width="770" style="border:1px solid rgb(222, 222, 222);display:none;" align='left' id='addloc'>
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
						if($row['state'] != 'Not In India') {
							print "<option value=".$row{'state_id'};
							print ">".$row{'state'}."</option>\n";
						} else {
							$other_id = $row['state_id'];
							$other = $row['state'];
						}
					}
					print("<option value=".$other_id.">".$other."</option>\n");
				}
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td>Latitude (eg, 26.98):</td>
		<td><input type=text name=nllat style="width:250px;"/>&nbsp;(If known)</td>
	</tr>
	<tr>
		<td>Longitude (eg, 74.70):</td>
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
			<input type=hidden name=cmd value="newlocation"/>
			<input type=hidden name=currentfile value="mylocations.php"/>
			&nbsp;<input type=button value="Add" class=buttonstyle onclick="addLocation();" />
		&nbsp;<input type=button value="Cancel" class=buttonstyle onclick="javascript:addloc(0);"/>
		</td>
	</tr>
</table>
</form><br /><br />
<a name='a'></a>

<br />
<div id="footer">
<?php
	include('footer.php');
?>
</div>
</body>
</html>
