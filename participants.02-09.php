<?php
/**
 * Database Connection Creation
 */
include('db.php');

// First Get a list of all states
$sql = "SELECT state FROM migwatch_states";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 0) {
	// Get all states in a array.
	while($state = mysql_fetch_assoc($result)) {
		$states[] = $state['state'];
	}
}

$stateCount = count($states);
$unknownStUids = array(747, 664, 712, 665, 651);

/**
 * Use joins to get all matching records from the states table
 */
$sql = "SELECT u.user_name, u.user_id, u.city, st.state, st.state_id,u.country FROM migwatch_users u " .
	   " LEFT JOIN migwatch_states st ON u.state_id = st.state_id" .
	   " WHERE u.user_name NOT IN ( 'admin',  'Developer',  'MigrantWatch Admin',  'Guest') " .
	   "ORDER BY st.state,u.user_name";
$result = mysql_query($sql);
$error = mysql_error();
if (!empty($error)) {
	print("<br /><font color='red'><b>Ok, so we so not have the correct database details yet.</b><br>" .
		  "MySql Says : <br /> $error </font>");
	exit;
}
// The total found participants
$total_participants = mysql_num_rows($result);

  if ($total_participants > 0) {
  	while($details = mysql_fetch_assoc($result)) {
		// If there is no city make it Undefined

		if ($details['state_id'] != '36') {

			/**
			 * If we do not have city but have district then usem that
			 */
			if (empty($details['city']) && !empty($details['district'])) {
				$details['city'] = ucfirst($details['district']);
			}

			if (!empty($details['state'])) {
				$users[$total_participants][$details['state']][] =
				array(
					'user_name' => $details['user_name'],
					'city'      => ucfirst($details['city']),
				);
			} else if (is_null($details['state']) && in_array($details['user_id'],$unknownStUids)) {
				$unknowns['No Location Listed'][] =
				array(
					'user_name' => $details['user_name'],
					'city'      => ucfirst($details['city']),
				);
			}
		} else {
			$outsiders[$details['state']][] =
			array(
				'user_name' => $details['user_name'],
				'city'      => ucfirst($details['city']),
				'district'  => ucfirst($details['district']),
				'country'   => ucfirst($details['country'])
			);
		}
  	}

	if (!empty($outsiders) && !empty($users)) {
		$users[$total_participants][key($outsiders)] = $outsiders[key($outsiders)];
	}

	if (!empty($unknowns) && !empty($users)) {
		$users[$total_participants][key($unknowns)] = $unknowns[key($unknowns)];
	}

	// The users who have entered the states
	$foundStates = array_keys($users[$total_participants]);

	// The users who have not entered the state
	$notFoundStates = array_diff($states,$foundStates);

	// No. of states which have no entries
	$statesWithNoEntries = ($stateCount) - ((int) @count($notFoundStates));

	// No. of users outside India
	$outsidersTotal = (int) @count($outsiders[key($outsiders)]);

	// Count the unknowns
	$unknownTotal = (int) @count($unknowns[key($unknowns)]);

	// Total Users
	$users[$total_participants] = array_merge(array_flip($states),$users[$total_participants]);
  } else {
	echo 'Error Querying DataBase. Please try Later.';
  }

  $indian = $total_participants - $outsidersTotal - $unknownTotal;
  $today = date('d M Y');
  echo <<<INFO
	<!-- Information on States / Registrants Start -->
	 $indian registrants from $statesWithNoEntries States/UTs as on $today
	 <br /><br />
	<!-- Information on States / Registrants Ends -->
INFO;

  $i = 1;
  if ($total_participants > 0) {
  	echo "<small>";
  	foreach($users[$total_participants] as $state => $data) {
  		if ($i != 1) {
  			echo "<br />";
  		}
		echo "<span style='text-decoration: underline;'>$state</span><br>";
		if (is_array($data)) {
	  		foreach ($data as $details) {

				echo "$details[user_name]";
				$details['city'] = trim($details['city']);

				if (!empty($details['city'])) {
					echo ", $details[city]";
				}

				if (isset($details['country']) &&
					!empty($details['country']) &&
					strtolower($details['country']) != 'india') {
					echo ", $details[country]";
				}
				echo "<br />";
	  		}
		} else {
			echo "&lt;None so far&gt;<br />";
	  	}
  		$i++;
  	}
  	echo "</small>";
  } else {
  	echo '--';
  }
