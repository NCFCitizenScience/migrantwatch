<?php session_start();
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	} elseif($_SESSION['origuserid'] == '0' || $_SESSION['userid'] == '0') {
		header("location: login.php");
		exit;
	}

	// unset the sessions for other files used for sorting
	unset($_SESSION['mylocations']);

	$user_id = $_SESSION['userid'];

	include("db.php");
	include("header.php");
	include("functions.php");

	if(isset($_GET['id'])) {
		$id = (int)$_GET['id'];
	} elseif(isset($_POST['id'])) {
		$id = (int)$_POST['id'];
	} else {
		$id = '';
	}

	// If set put the selected sesason in session.
	if(isset($_POST['season'])) {
		$season = $_POST['season'];
	} else if (!empty($_SESSION['locsightings']['season'])) {
		$season = $_SESSION['locsightings']['season'];
	} else {
		$season = getCurrentSeason();
	}

	// Remember the season.
	$_SESSION['locsightings']['season'] = $season;
	list($seasonStart,$seasonEnd) = explode('-',$season);

	// Set the sorting field
	if(!isset($_SESSION['locsightings']['sort']) || $_SESSION['locsightings']['sort'] == '') {
		$_SESSION['locsightings']['sort'] = 'OBC_number';
		$_SESSION['locsightings']['sort_order'] = 'ASC';
	} elseif(isset($_POST['fieldSort'])) {
		$_SESSION['locsightings']['sort'] = $_POST['fieldSort'];
		$_SESSION['locsightings']['sort_order'] = $_POST['fieldOrder'];
	}
	$sortBy = $_SESSION['locsightings']['sort'];
	$sortOrder = $_SESSION['locsightings']['sort_order'];

	$_SESSION['referrer'] = "locsightings.php?id=$id";

	$sql = "SELECT l.location_name, l.city, l.district, l.latitude, l.longitude, st.state FROM migwatch_locations l ";
	$sql.= "INNER JOIN migwatch_states st ON l.state_id = st.state_id WHERE location_id = '$id' ";
	$result = mysql_query($sql);
	$loc_details = mysql_fetch_assoc($result);
?>
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
<table cellspacing="0" cellpadding="4" width="770">
	<tr bgcolor="#dedede">
		<td align='left'>
			<b>Sightings at this location </b>
		</td>
		<td align="right"><a href='mylocations.php'><img style="border-width: 0px;" src="images/back.gif"/></a></td>
	</tr>
			<?php if(isset($_GET['cmd'])) { ?>
			<tr>
				<td  colspan=2 align=right
				<?php
				$sErrorColor = " style=\"color:#ff1111;\">";
				$sSuccessColor = " style=\"color:#229922;\">";
				$sSightingDeletedMessage = "Sighting deleted successfully.";
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

					case "sightingdeleted":
						print $sSuccessColor;
						print $sSightingDeletedMessage;
						break;

					default:
						//print $sSuccessColor;
						//print $sWelcomeMessage;
				}
				print "&nbsp;</td>";

				?>
			</td></tr>
			<?php } ?>
	<tr><td colspan="2"><p><b>Location Details:</b><br />
		Location: <?php print($loc_details['location_name']); ?><br />
		City: <?php ($loc_details['city'] != '') ? print($loc_details['city']) : print("--"); ?><br />
		District: <?php ($loc_details['district'] != '') ? print($loc_details['district']) : print("--"); ?><br />
		State/UT: <?php ($loc_details['state'] != '') ? print($loc_details['state']) : print("--"); ?><br />
		Latitude: <?php ($loc_details['latitude'] != '') ? print($loc_details['latitude']) : print("--"); ?><br />
		Longitude: <?php ($loc_details['longitude'] != '') ? print($loc_details['longitude']) : print("--"); ?><br />
		<a href="trackspecies.php?loc_id=<?php echo $id; ?>">Report first sightings from this location</a> <br />
		<a href="trackspecies.php?last=1&loc_id=<?php echo $id; ?>">Report last sightings from this location</a> <br />
		</p>
		</td>
	</tr>
	<?php
		$sql = "SELECT COUNT(*) AS count FROM migwatch_l1 WHERE user_id ='$user_id' AND location_id = '$id'";
		$result = mysql_query($sql);
		$count = mysql_fetch_assoc($result);
		//print($count['count']);
		if($count['count'] > 0) {
	?>
		<tr>
			<form name="frm_sortfield" method="POST" action="locsightings.php">
			<td colspan=2>You have reported the following sightings at this location so far: <br>
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
			</td>
		</tr>
		<?php
			$rows_per_page = 10;

			if (isset($_GET['pageno'])) {
			   $pageno = $_GET['pageno'];
			} else {
			   $pageno = 1;
			} // if

			$lastpage  = ceil($count['count'] /$rows_per_page);
			$pageno = (int)$pageno;
			if ($pageno > $lastpage) {
			   $pageno = $lastpage;
			} // if
			if ($pageno < 1) {
			   $pageno = 1;
			} // if

			$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
			$sql = "SELECT s.common_name, l1.id, l1.sighting_date, l1.obs_type FROM migwatch_l1 l1 ";
			$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
			$sql.= "WHERE l1.user_id = '$user_id' AND l1.location_id = '$id' AND l1.deleted = '0' ";
			if ($season != 'all') {
				$sql.= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
				         AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
			}

			$sql.= $srt = " ORDER BY $sortBy $sortOrder $limit";
			$result = mysql_query($sql);
			$rowCount = mysql_num_rows($result);
			if ($rowCount > 0) {
		?>
		<tr style="background-color: rgb(239, 239, 239);"><td colspan=2>
			<table width=98% cellpadding=3 cellspacing=0 style="border-width:1px;border-style:solid;border-color:#dedede">
				<tr style="font-weight:bold;font-size:x-small;background-color:#efefef">
					<td>&nbsp;</td>
					<td><a href="javascript:setSorting('common_name', '')">Species</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('common_name', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('common_name', 'DESC')"></td>
					<td><a href="javascript:setSorting('sighting_date', '')">Sighting Date</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('sighting_date', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('sighting_date', 'DESC')"></td>
					<td><a href="javascript:setSorting('obs_type', '')">Type</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('obs_type', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('obs_type', 'DESC')"></td>
					<td align="center">Details</td>
				</tr>
				<?php
					if($result) {
						$i = 1;
						$j = (($pageno - 1) * $rows_per_page) + 1;
						while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$sLinkBegin = "<a class=tablelink href=\"viewsighting.php?id=".$row['id']."\">";
							$sLinkEnd = "</a>";
							print("<tr bgcolor=".($i % 2 == 0?"#efefef":"#ffffff").">");
							print("<td>".$j."</td>");
							print("<td>".$row['common_name']."</td>");
							print("<td>".date("d-M-Y",strtotime($row['sighting_date']))."</td>");
							print("<td>".ucfirst($row['obs_type'])." Sighting</td>");
							print("<td align='center'>$sLinkBegin<img src=\"images/view.gif\" style=\"border-width:0px;\" title='View details of this sighting' />$sLinkEnd</td>");
							print("</tr>");
							$i++;
							$j++;
						}
					}
				?>
			</table>
			<input type="hidden" name="fieldSort" value='<?php echo $sortBy ?>'>
			<input type="hidden" name="fieldOrder" value='<?php echo $sortOrder ?>'>
			<input type="hidden" name="id" value="<?php print($id);?>">
			</form>
			</td>
		</tr>
				<tr>
					<td colspan='2' align='center'>
					<?php
					// Simple Pagination
					if ($lastpage > 1) {
						if ($pageno == 1) {
						   echo "<<< First << Prev |";
						} else {
						   echo " <a href='{$_SERVER['PHP_SELF']}?id=$id&pageno=1'><<< First</a> ";
						   $prevpage = $pageno-1;
						   echo " <a href='{$_SERVER['PHP_SELF']}?id=$id&pageno=$prevpage'><< Prev |</a> ";
						} // if

						if ($pageno == $lastpage) {
						   echo "Next >> Last >>>";
						} else {
						   $nextpage = $pageno+1;
						   echo " <a href='{$_SERVER['PHP_SELF']}?id=$id&pageno=$nextpage'>Next >></a> ";
						   echo " <a href='{$_SERVER['PHP_SELF']}?id=$id&pageno=$lastpage'>Last >>></a> ";
						} // if
					}
					?>
					</td>
				</tr>

	<?php
			} else {
				print("<input type='hidden' name='id' value='$id'>");
				print("<tr><td colspan='2' class='error'>You have not reported any sightings at this location for the selected season.</td></tr>");
			}
		} else {
			print("<tr><td colspan='2'>You have not reported any sightings at this location so far.</td></tr>");
		}
	?>

</table>
<?php
include("footer.php");
?>