<?php
/**
 * Database Connection Creation
 */
 // Get a database handle :
$connect = mysql_connect("localhost", "wilduser","infor2ix");
mysql_select_db("wildindia");

/**
 * Use joins to get all matching records from the states table
 */
$sql = "SELECT u.user_name, u.user_id, u.city, st.state, st.state_id,u.country from migwatch_users u " .
	   "INNER JOIN migwatch_states st ON st.state_id=u.state_id WHERE u.active = '1' " .
	   "AND u.user_name != 'admin'" .
	   "AND u.user_name != 'Developer'" .
	   "AND u.user_name != 'MigrantWatch Admin' " .
	   "AND u.user_name != 'Guest '" .
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

			$data[$total_participants][$details['state']][] =
			array(
				'user_name' => $details['user_name'],
				'city'      => ucfirst($details['city']),
			);
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

	if (!empty($outsiders) && !empty($data)) {
		$data[$total_participants][key($outsiders)] = $outsiders[key($outsiders)];
	}

	//print_r($data);
  	print_r(serialize($data));
  } else {
	echo 'Error Querying DataBase. Please try Later.';
  }
?>