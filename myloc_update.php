<?php
	include("db.php");
	include("functions.php");
	$connect2 = $connect;

	/**
	 * First find all users for which we have entries in the migwatch_l1 table,
	 * instead of all users
	 */
	$sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
	$sql .= "l1.user_id = u.user_id WHERE l1.valid=1 ";
	$sql .= "ORDER BY u.user_name";
	$result = mysql_query($sql);

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
		$users[$row['user_id']] = $row['user_name'];
	}

	print_r(" This is the script created to enter all the 'My Location' entries for a user into the database :\n" .
			" Use View source to view it properly in case you are viewing it in the browser. \n\n");
	print_r("--------------------------------------------------- \n\n");

	/**
	 * Loop over the user ids and find the location_ids for all the users 'my location'
	 * except the ones that are already present in the 'migwatch_user_locs'.
	 */
	foreach ($users as $user_id => $user_name) {

		// Initialize all arrays to avoid bugs
		$validArrays = array();
		$myLocationIds = array();
		$userAddedLocIds = array();
		$userSightingLocIds = array();
		$valArrCnt = '';

		/**
		 * Get all the locations for this user:
		 */
		// 1. user added locations.
		$userAddedLocIds = getUserAddedLocIds($user_id,$connect);
		if (!empty($userAddedLocIds)) {
			$validArrays[] = $userAddedLocIds;
		}

		// 2. All user sightings on the locations
		$userSightingLocIds = getUserSightingLocIds($user_id,$connect);
		if (!empty($userSightingLocIds)) {
			$validArrays[] = $userSightingLocIds;
		}


		$valArrCnt = count($validArrays);
		$myLocationIds = array();

		// Merge all to get all the location ids
		if ($valArrCnt == 1) {
			$myLocationIds = array_merge($validArrays[0]);
		} else if ($valArrCnt == 2) {
			$myLocationIds = array_merge($validArrays[0],$validArrays[1]);
		} else if ($valArrCnt == 3) {
			$myLocationIds = array_merge($validArrays[0],$validArrays[1],$validArrays[2]);
		}

		$myLocationIds = array_unique($myLocationIds);
		$locCnt = count($myLocationIds);

		$echo = <<<EOT
User     : $user_id
Username : $user_name
Count    : $locCnt
Location Ids :\n
EOT;
		print_r($echo);
		print_r($myLocationIds);
		print_r("INSERTs :");

		foreach ($myLocationIds as $locId) {
			$sql = "INSERT INTO migwatch_user_locs SET user_id = '$user_id', location_id = '$locId'";
			print_r($sql);
			//if (true) {
			if (mysql_query($sql,$connect2)) {
				echo " -> Success ";
			} else {
				echo " -> Failed ! \n ";
				echo "Coz Mysql Says : ".mysql_error()." \n ";
				$failedEntries .= $sql."\n";
				file_put_contents('failed.sql', $sql."\n" , FILE_APPEND);
			}
			print_r("\n");
		}

		print_r("\n\n --------------------------------------------------- \n\n");
	}

	if (!empty($failedEntries)) {
		echo "Unfortunately, the following inserts failed : \n";
		echo $failedEntries;
	}