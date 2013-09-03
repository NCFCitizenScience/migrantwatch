<?php session_start();
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	} elseif($_SESSION['origuserid'] == '0' || $_SESSION['userid'] == '0') {
		//unset($_SESSION);
		//$_SESSION['no_admin'] = true;
		header("location: login.php");
		exit;
	}

	include('db.php');
	include('header.php');
	unset($_SESSION['myspecies']);
?>
<!-----------------------------------------------------
Written by: Suresh V [verditer at gmail.com]
For: National Center for Biological Sciences, Bangalore
http://ncbs.res.in

In Love of Birds.

------------------------------------------------------->

	<!----------LANDING PAGE START--------------------->
		<table width="770" cellpadding=4 cellspacing=0>
			<tr bgcolor="dedede">
				<td><b>MigrantWatch</b></td>
				<td align=right>
					<?php print($_SESSION['username']); ?>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="editprofile.php">Edit Profile</a> | <a  href="chpass.php">Change Password</a> | <a href="process_details.php?cmd=logout">Logout</a>
			</tr>
			<tr>
				<td  colspan=2 align=right
				<?php
				$sErrorColor = " style=\"color:#ff1111;\">";
				$sSuccessColor = " style=\"color:#229922;\">";
				$sSightingDeletedMessage = "Sighting deleted successfully.";
				$sWelcomeMessage = "Welcome back to MigrantWatch, <b>".$_SESSION['username'].".</b>";
				$sSightingEnteredMessage = "Your $sighting sighting(s) have been entered successfully. Thank you.";
				$sPasswordChangedMessage = "Your password has been changed successfully.";
				$sPasswordChangeErrorMessage = "There was an error changing your password.";
				$sProfileSavedMessage = "Your profile has been updated successfully.";
				$sLevel1UpdateFailedMessage = "There was a problem saving your data.";
				$sLevel1UpdateSuccessMessage = "Sighting updated successfully.";

				switch($_GET['cmd']){
					case "updatelevel1failed":
						print $sErrorColor;
						print $sLevel1UpdateFailedMessage;
						break;

					case "updatelevel1success":
						print $sSuccessColor;
						print $sLevel1UpdateSuccessMessage;
						break;

					case "insertsuccess":
						print $sSuccessColor;
						print $sSightingEnteredMessage;
						break;

					case "sightingdeleted":
						print $sSuccessColor;
						print $sSightingDeletedMessage;
						break;

					case "passwordchanged":
						print $sSuccessColor;
						print $sPasswordChangedMessage;
						break;

					case "passwordchangeerror":
						print $sErrorColor;
						print $sPasswordChangeErrorMessage;
						break;

					case "profilesaved":
						print $sSuccessColor;
						print $sProfileSavedMessage;
						break;

					default:
						print $sSuccessColor;
						print $sWelcomeMessage;
				}
				print "&nbsp;</td>";

				?>
			</td></tr>
		</table>
<?php
$sql = 'SELECT message_text FROM migwatch_messages';
$res = mysql_query($sql);
if (mysql_num_rows($res) > 0) {
?>
<table cellspacing="0" cellpadding="4" width="770">
	<tbody>
		<tr bgcolor="#dedede">
			<td align='left'>
				<b>New Messages</b>
			</td>
		</tr>
		<tr>
			<td>
			<ul>
			<?php
				while ($messages = mysql_fetch_assoc($res)) {
					echo "<li>$messages[message_text]</li>";
				}
			?>
			</ul>
			<?php
			} else {
				echo "<p class='error'>No New Messages</p>";
			}
			?>
			</td>
		</tr>
	</tbody>
</table>
<table cellspacing="0" cellpadding="4" width="770">
	<tbody>
		<tr bgcolor="#dedede">
			<td align='left'>
				<b>Recent First Sightings</b>
			</td>
		</tr>
	</tbody>
</table>
<?php
$limit = 'LIMIT 0,5';
$sql = "SELECT s.common_name, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
$sql.= "l1.obs_type, l1.id, l1.deleted, l1.number, u.user_name FROM migwatch_l1 l1 ";
$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
$sql.= "INNER JOIN migwatch_users u ON l1.user_id = u.user_id ";
$sql.= "WHERE l1.deleted = '0' AND valid = '1'";
//$sort = ($orderBy == 'sighting_date') ? $sort : $sortTy;
$sort = ' sighting_date DESC,number';
$sql.= $srt = " ORDER BY $orderBy $sort $limit";
$result = mysql_query($sql,$connect);
if($result && (mysql_num_rows($result) > 0)) {
?>
<table width=770 cellpadding=3 cellspacing=0 style="border-width:1px;border-style:solid;border-color:#dedede">
	<tr style="font-weight:bold;font-size:x-small;background-color:#efefef">
		<td>&nbsp;</td>
		<td>Date</td>
		<td>Species</td>
		<td>Number</td>
		<td>Location, Town, State</td>
		<td>Observer</td>
</tr>
<?php
	$i = 1;
	$j = (($pageno - 1) * $rows_per_page) + 1;
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$sLinkBegin = "<a class=tablelink href=\"viewsighting.php?id=".$row['id']."\">";
		$sLinkEnd = "</a>";
		print "<tr bgcolor=".($i % 2 == 0?"#efefef":"#ffffff").">";
		print "<td>".$j."</td>";
		print "<td>".date("d-M-Y",strtotime($row{'sighting_date'}))."</td>";
		print "<td>".$row{'common_name'}."</td>";
		print "<td>"; print ($row{'number'} == '' || $row{'number'} == '0') ? '--' : $row{'number'}; print "</td>";
		print "<td>".$row{'location_name'}.", ".$row{'city'}.", ".$row{'state'}."</td>";
		print "<td>".$row{'user_name'}."</td>";
		print "</tr>";
			$i++;
			$j++;
	}
} else {
	echo "<p class='error'>No Recent First Sightings Added</p>";
}
?>
</table>
<p>
To report general sightings of migrants (not First or Last sightings of the season) <a href='gensightings.php'>click here </a>
</p>
<!---------LANDING PAGE END----------------------->
<?php
	include('footer.php');
?>