<?php
	session_start();
	include("db.php");
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	} elseif($_SESSION['origuserid'] == '0' || $_SESSION['userid'] == '0') {
		header("location: login.php");
		exit;
	}

	$user_id = $_SESSION['userid'];

	if(!isset($_GET['id'])) {
		header("location:myspecies.php");
		exit;
	} else {
		$id = $_GET['id'];
	}

	// Sorting logic with remember option.
	// SortOrder : Sighting Date
	if (isset($_GET['sd'])) {
		if($_GET['sd'] == 'asc') {
			$sort = 'ASC';
			$next_sort = 'desc';
			$next_sort_ty = 'asc';
		} else {
			$sort = 'DESC';
			$next_sort = 'asc';
			$next_sort_ty = 'asc';
		}
		$orderBy = 'sighting_date';
	} else if (isset($_GET['typ'])) {
		if($_GET['typ'] == 'asc') {
			$sortTy = 'ASC';
			$next_sort_ty = 'desc';
			$next_sort = 'desc';
		} else {
			$sortTy = 'DESC';
			$next_sort_ty = 'asc';
			$next_sort = 'desc';
		}
		$orderBy = 'obs_type';
	} else if (!empty($_SESSION['speciessighting']['orderBy'])) {
		$orderBy = $_SESSION['speciessighting']['orderBy'];
		$sort = $_SESSION['speciessighting']['sd'];
		$next_sort = ($_SESSION['speciessighting']['sd'] == 'asc') ? 'desc' : 'asc';
		$sortTy = $_SESSION['speciessighting']['typ'];
		$next_sort_ty = ($_SESSION['speciessighting']['typ'] == 'asc') ? 'desc' : 'asc';
	}

	if (empty($orderBy)) {
		$orderBy = 'sighting_date';
		$sort = 'DESC';
		$sortTy = 'DESC';
		$next_sort = 'asc';
		$next_sort_ty = 'asc';
	}

	if (!empty($sort)) {
		$_SESSION['speciessighting']['sd'] = strtolower($sort);
	}

	if (!empty($sortTy)) {
		$_SESSION['speciessighting']['typ'] = strtolower($sortTy);
	}

	if (!empty($orderBy)) {
		$_SESSION['speciessighting']['orderBy'] = strtolower($orderBy);
	}

	include("header.php");
	$_SESSION['referrer'] = "speciessighting.php?id=$id";
?>
<table width="770" cellpadding=4 cellspacing=0>
	<tr bgcolor="dedede">
		<td><b>MigrantWatch</b></td>
		<td align=right><a href="myspecies.php"><img style="border-width: 0px;" src="images/back.gif"/></a>
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
	<?php
	//first get count
	$sql = "SELECT l1.species_id, s.common_name FROM migwatch_l1 l1 INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
	$sql.= "WHERE user_id = '".$user_id."' AND l1.species_id = $id AND l1.deleted = '0'";
	$result = mysql_query($sql, $connect);
	//$record_array = mysql_fetch_array($result);
	$total_sightings = mysql_num_rows($result);
	if($total_sightings > 0) {
		$record_array = mysql_fetch_array($result);
		$specie_name = $record_array['common_name'];
	?>
	<tr><td colspan=2>You have reported the following sightings for <b><?php print($specie_name) ?></b> so far:</td></tr>
			<tr><td colspan=2>
				<table width=100% cellpadding=3 cellspacing=0 style="border-width:1px;border-style:solid;border-color:#dedede">
					<tr style="font-weight:bold;font-size:x-small;background-color:#efefef">
						<td>&nbsp;</td>
						<td width="60%">Location, Town, State</td>
						<td><a href="<?php print("speciessighting.php?id=$id&sd=$next_sort") ?>" title="<?php print("sort $next_sort") ?>" >Sighting Date</a></td>
						<td><a href="<?php print("speciessighting.php?id=$id&typ=$next_sort_ty") ?>" title="<?php print("sort $next_sort_ty") ?>" >Type</a></td>
						<td>Number</td>
						<td>Details</td>
					</tr>
					<?php
					$rows_per_page = 10;

					if (isset($_GET['pageno'])) {
					   $pageno = $_GET['pageno'];
					} else {
					   $pageno = 1;
					} // if

					$lastpage  = ceil($total_sightings/$rows_per_page);

					$pageno = (int)$pageno;
					if ($pageno > $lastpage) {
					   $pageno = $lastpage;
					} // if
					if ($pageno < 1) {
					   $pageno = 1;
					} // if

					$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
					$sql = "SELECT l1.number, s.common_name, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
					$sql.= "l1.obs_type, l1.id, l1.deleted FROM migwatch_l1 l1 ";
					$sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
					$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
					$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
					$sql.= "WHERE l1.user_id = '".$user_id."'  AND l1.deleted = '0' ";

					$season = $_SESSION['myspecies']['season'];
					if ($season != 'all') {
						list($seasonStart,$seasonEnd) = explode('-',$season);
						$sql .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'
						                AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00'";
					}

					$sort = ($orderBy == 'sighting_date') ? $sort : $sortTy;
					$sql.= $srt = "ORDER BY $orderBy $sort $limit";

					$result = mysql_query($sql);

					if($result) {
						$i =1;
						$j = (($pageno - 1) * $rows_per_page) + 1;
						while($row = mysql_fetch_array($result)) {
							$sLinkBegin = "<a class=tablelink href=\"viewsighting.php?id=".$row['id']."\">";
							$sLinkEnd = "</a>";
							print "<tr bgcolor=".($i % 2 == 0?"#efefef":"#ffffff").">";
							print "<td>".$j."</td>";
							print "<td>".$row{'location_name'}.", ".$row{'city'}.", ".$row{'state'}."</td>";
							print "<td>".date("d-M-Y",strtotime($row{'sighting_date'}))."</td>";
							print "<td>".ucfirst($row['obs_type'])." Sighting</td>";
							print "<td>"; echo (empty($row['number'])) ? '--' : $row['number']; print "</td>";
							print "<td>$sLinkBegin<img src=\"images/view.gif\" style=\"border-width:0px;\" title='View details of this sighting'/>$sLinkEnd</td>";
							print "</tr>";
							$i++;
							$j++;
						}
					}
					?>
				</table>
			</td></tr>
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
						   echo " <a href='{$_SERVER['PHP_SELF']}?id=$id&sd=$next_sort&pageno=$nextpage'>Next >></a> ";
						   echo " <a href='{$_SERVER['PHP_SELF']}?id=$id&sd=$next_sort&pageno=$lastpage'>Last >>></a> ";
						} // if
					}
					?>
					</td>
				</tr>

	<?php
	} else {
	?>
		<tr>
			<td colspan=2 style='color:red; font-weight: bold' align='center'>
				You have not yet reported any sightings for this species so far.
			</td>
		</tr>
	<?php
	}
	?>
</table>
<?php
	include('footer.php');
?>
