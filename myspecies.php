<?php
	session_start();
	include("db.php");
	include("functions.php");
	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
		die();
	}

	include("header.php");
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
?>
<table width="770" cellpadding=4 cellspacing=0>
	<tr bgcolor="dedede">
		<td><b>My Species</b></td>
		<td align=right><a href="main.php"><img style="border-width: 0px;" src="images/back.gif"/></a>
	</tr>
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
		<form method="post" name="frm_sortfield">
		<tr style="background-color: rgb(239, 239, 239);">
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
		</select> &nbsp;&nbsp;<a href="javascript:setSorting('obcno', '')">Sort according to taxonomic order</a>
		  &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('obcno', 'ASC')">
		  &nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('obcno', 'DESC')">
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
		<table width=100% cellpadding=3 cellspacing=0 style="border-width:1px;border-style:solid;border-color:#dedede">
			<tr style="font-weight:bold;font-size:x-small;background-color:#efefef">
				<td>&nbsp;</td>
				<td width="120px"><a href="javascript:setSorting('spc', '')">Species Name</a>  <br /><img src="images/s_asc.png" title="sort ascending" onclick="setSorting('spc', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('spc', 'DESC')"></td>
				<td width="80px"><a href="javascript:setSorting('fsd', '')">First Sighting Date </a>  &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('fsd', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('fsd', 'DESC')"></td>
				<td width="200px"><a href="javascript:setSorting('fsloc', '')">First Sighting Location</a><br /><img src="images/s_asc.png" title="sort ascending" onclick="setSorting('fsloc', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('fsloc', 'DESC')"></td>
				<td width="80px"><a href="javascript:setSorting('lsd', '')">Last Sighting Date</a> &nbsp;<img src="images/s_asc.png" title="sort ascending" onclick="setSorting('lsd', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('lsd', 'DESC')"></td>
				<td width="200px"><a href="javascript:setSorting('lsloc', '')">Last Sighting Location</a><br /><img src="images/s_asc.png" title="sort ascending" onclick="setSorting('lsloc', 'ASC')">&nbsp;<img src="images/s_desc.png" title="sort descending" onclick="setSorting('lsloc', 'DESC')"></td>
				<td>Details</td>
			</tr>
<?php
		$i = 1;
		foreach ($records as $record) {

			echo "<tr bgcolor=".($i % 2 == 0?"#efefef":"#ffffff").">";
			echo " 	<td>$i</td>";
			echo "<td>$record[common_name]</td>";

			if (!isset($record['first'])) {
			echo "
				  <td>--</td>
				  <td>--</td>";
			} else {
				echo "
				  	  <td>$record[sighting_date]</td>
				  	  <td>$record[location_name], $record[city], $record[state]</td>
			 	  	 ";
			}

			 if ($record['last'] == 1) {
				echo
				 "  <td>$record[last_sighting_date]</td>
					<td>$record[last_sighting_location_name], $record[last_sighting_city], $record[last_sighting_state]</td>
				";
			 } else {
				echo
				 "  <td>--</td>
					<td>--</td>
				 ";
			 }

			 $sLinkBegin = "<a class=tablelink href=\"speciessighting.php?id=".$record['species_id']."\">";
			 $sLinkEnd = "</a>";
				  echo "<td align=center>$sLinkBegin<img src=\"images/view.gif\" style=\"border-width:0px;\" title=\"View all sightings for $record[common_name]\"/>$sLinkEnd</td></tr>";
			$i++;
		}
	echo "</tr>" ;
	echo "<input type='hidden' name='fieldSort'>" .
		 "<input type='hidden' name='fieldOrder'>";
	echo "</table>".
		 "</form>";
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
	include('footer.php');
?>