<?php
/**
 * Function isvalidState
 * Purpose : Checks if a state identified by the given state id exists in the states table
 */
 function isValidState($id,$connect) {
	$id = (int) $id;
	if (empty($id)) {
		return false;
	}
	$result = mysql_query("SELECT state_id FROM migwatch_states WHERE state_id = '$id'");
	$row = mysql_num_rows($result);
	return ($row < 1) ? false : true;
 }

 /**
  * Function isvalidlocation
  * Purpose : Checks if a location identified by the given location id exists in the location table
  */
 function isValidLocation($id,$connect) {
	$id = (int) $id;
	if (empty($id)) {
		return false;
	}
	$result = mysql_query("SELECT location_id,location_name FROM migwatch_locations WHERE location_id = '$id'");
	$row = mysql_num_rows($result);
	return ($row < 1) ? false : true;
 }

 /**
  * function isValidDate
  * Purpose : Checks if a given date is valid or not
  */
 function isValidDate($date,$noLaterThanNow = true) {
	if (empty($date)) {
		return false;
	}
	//$dateStr = $date;
	//$time = strtotime($dateStr);
 	//$date = date('d-m-Y',$time);
	$date = explode('-',$date);

	// If he has not entered a valid format then
	if (!is_array($date) || count($date) < 3) {
		return false;
	}

	$return = (checkdate((int)$date[1],(int)$date[0],(int)$date[2])) ? true : false;
 	/*$time = strtotime($dateStr);
 	$dateStr = date('d-m-Y',$time);
	if ($dateStr > sprintf('%02d-%02d-%4d',$date[0],$date[1],$date[2]))
		$return = false;*/
	if($return == true) {
		$today = getdate();
		if($date[2] > $today['year']) {
			$return = false;
		} elseif($date[2] < $today['year']) {
			$return = true;
		} elseif(($date[2] == $today['year']) && ($date[1] > $today['mon'])) {
			$return = false;
		} elseif($date[1] < $today['mon']) {
			$return = true;
		} elseif(($date[1] == $today['mon']) && ($date[0] > $today['mday'])) {
			$return = false;
		} else {
			$return = true;
		}
	}
	return $return;
 }

 /**
  * function compareDates
  * Purpose : Checks if a sighting date is a date after/before the observation start/end date
  */
 function compareDates($date, $obdate, $last=0) {
	$date1 = explode('-', $date);
	$date2 = explode('-', $obdate);

	// If the length are not what we want just return false
	if (count($date1) < 3 || count($date2) < 3) {
		return false;
	}

	if($last == 1) {
		$tempdate = $date1;
		$date1 = $date2;
		$date2 = $tempdate;
	}
	if($date1[2] > $date2[2]) {
		return false;
	} elseif($date1[2] < $date2[2]) {
		return true;
	} elseif(($date1[2] == $date2[2]) && ($date1[1] > $date2[1])) {
		return false;
	} elseif($date1[1] < $date2[1]) {
		return true;
	} elseif(($date1[1] == $date2[1]) && ($date1[0] > $date2[0])) {
		return false;
	}
	return true;
 }

 /**
  * function isValidSpecies
  * Purpose : Checks if a species identified by the given species id exists in the species table
  */
 function isValidSpecies($id,$connect) {
	if (empty($id)) {
		return false;
	}
 	if (is_numeric($id)) {
		$id = (int) $id;
		$result = @mysql_query("SELECT species_id FROM migwatch_species WHERE species_id = '$id' WHERE Active = 1");
		$row = mysql_num_rows($result);
		return ($row < 1) ? false : true;
 	} else {
 		$ids = implode("','",$id);
		$result = @mysql_query("SELECT species_id FROM migwatch_species WHERE Active = 1");
		while($results = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$validSpecies[] = $results['species_id'];
		}
 	}

 	if (is_array($id) && is_array($validSpecies)) {
 		$diff = array_diff($id,$validSpecies);
 		$return = empty($diff) ? true : false;
		return $return;
 	} else {
		return false;
 	}
 }


  /**
  * function isValidSpecies
  * Purpose : Checks if a species identified by the given species id exists in the species table
  */
 function isValidSpeciesName($names_arr,$connect) {
	if (empty($names_arr)) {
		return false;
	} elseif(count($names_arr) == 1 && trim($names_arr[0]) == "") {
		return false;
	}
$names = array();
	// convert to lower case
	foreach ($names_arr as $key => $name) {
		if (empty($names_arr[$key])) {
			continue;
		}
		$names[$key] = strtolower($name);
	}

	$result = @mysql_query("SELECT common_name FROM migwatch_species WHERE Active = 1");
	while($results = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$validSpecies[] = strtolower($results['common_name']);
	}

 	if (is_array($names) && is_array($validSpecies)) {
 		$diff = array_diff($names,$validSpecies);
 		$return = empty($diff) ? true : false;
		return $return;
 	} else {
		return false;
 	}
 }

 /**
  * function isValidSpeciesRetId
  * Purpose : Checks if an array of species names exists in the species table in common name
  *           all alternative names or the scientific names then return their ids
  */
 function isValidSpeciesNameRetId($names_arr,$connect) {

	/**
	 * If we have an empty array return
	 */
	if (empty($names_arr)) {
		return false;
	} elseif(count($names_arr) == 1 && trim($names_arr[0]) == "") {
		// If no value in it . return false
		return false;
	}

	$names = array();
	// convert to lower case
	foreach ($names_arr as $key => $name) {
		if (empty($names_arr[$key])) {
			continue;
		}
		$names[$key] = trim(stripslashes(strtolower($name)));
	}


	// Total nos of entries
	if (is_array($names) && !empty($names)) {
		$totalEntries = count($names);
	}

	// Get the same auto completed names that appear
	$formattedSpeciesNms = getSpeciesForAutoComplete($connect);
	/**
	 * First, we assume that the user has selected from the jquery auto complete drop - down
	 * Since it is the post probable case
	 */
	$i = 0;
	foreach ($names as $nm => $entry) {

		// If we have an matching entry go gets its id
		if (isset($formattedSpeciesNms[$entry])) {
			$ids[$i] = $formattedSpeciesNms[$entry];
		} else {
			$notFound[] = $i;
		}

		$i++;
	}

	// If we have all valid matches return the array of ids
	if (@count($ids) == $totalEntries) {
		return $ids;
	}

	/**
	 * If we are getting here that means that the user has not selected atleast one entry
	 * from the drop-down and has manually entered it.
	 * For this case : We will need to compare the entered species names with common_name ,
	 * alternative english names name , scientific name , all lower cased
	 */
	$allSpeciesNames = getSpeciesForValidation($connect);
	foreach ($notFound as $ind) {

		// Check if it is present in the array of valid species
		if (@isset($allSpeciesNames[$names[$ind]])) {
			// If found put its id in the array
			$ids[$ind] = $allSpeciesNames[$names[$ind]];
		} else {
			// If not found we have an error
			$ids[$ind] = 'error';
		}
	}

	if (is_array($ids)) {
		return $ids;
	}
 }



 /**
  * Function : has any
  * Purpose : Checks if the table identified by $tablename has a field identified by
  *           $idField with a value $idValue for the given user_id
  */
 function hasAny($tableName,$user_id,$idField, $idValue,$connect = NULL) {
	$where = '';
 	if (is_null($connect)) {
		global $connect;
 	}

 	$fieldCount = count($idField);
 	if (is_array($idField) && is_array($idValue)) {
 		if ($fieldCount == count($idValue)) {
 			if (!empty($user_id)) {
 				$where .= "`user_id` = '$user_id'";
 			} else {
 				$where .= "1 = 1";
 			}

			for($i = 0; $i < $fieldCount; $i++) {
				$where .= " AND `$idField[$i]` = '$idValue[$i]' ";
			}
			$oneField =  $idField[0];
 		} else {
 			return false;
 		}
 	} else {
		$where = "`$idField` = '$idValue'";

		if (!empty($user_id)) {
			$where .= "AND `user_id` = '$user_id'";
		}

		$oneField = $idField;
 	}

 	$sql = "SELECT `$oneField` FROM $tableName WHERE $where";
 	$res = mysql_query($sql,$connect);
 	if (mysql_num_rows($res) > 0) {
		return true;
 	} else {
 		return false;
 	}
 }

 /**
  * Function : jsRedirect
  * Purpose : Redirect using js
  */
 function jsRedirect ($to) {
	echo "<script language='javascript'>";
	echo "window.location = '".dirname($_SERVER[PHP_SELF])."/$to';";
	echo "</script>";
	exit;
 }

  /**
  * Function : getUserNameById
  * Purpose : The name is sufficient
  */
 function getUserNameById($id,$connect) {
 	if (empty($connect)) {
		return false;
 	}
 	$sql = "SELECT `user_name` FROM migwatch_users WHERE user_id = '$id'";
 	$res = mysql_query($sql,$connect);
 	if (mysql_num_rows($res) > 0) {
 		$dt = mysql_fetch_assoc($res);
 		$username = $dt['user_name'];
		return $username;
 	} else {
 		return false;
 	}
 }

  /**
  * Function : getEscaped
  * Purpose : Prevents the SQL Injection
  */
 function getEscaped($value) {
	if(ini_get('magic_quotes_gpc')) {
		$value = stripslashes($value);
	}
	return mysql_real_escape_string($value);
 }

  /**
  * Function  getStripped
  * Purpose : Get the original unescaped value to return to the form in case of failures
  */
 function getStripped($value) {
	if(ini_get('magic_quotes_gpc')) {
			return stripslashes($value);
	} else {
		return $value;
	}
 }


 /**
  * Function : getSpeciesNames
  * Purpose : get names of species such that it is used by autocomplete.php
  */
 function getSpeciesForValidation($connect) {

	$sql = "SELECT species_id,LOWER(common_name) as common_name,LOWER(scientific_name) as scientific_name,LOWER(alternative_english_names) as alternative_english_names FROM migwatch_species";
	$res = mysql_query($sql,$connect);

	while($results = mysql_fetch_assoc($res)) {

		$alt_species = array();
		$mod_species = array();

		$validSpecies[trim($results['common_name'])] = $results['species_id'];

		/**
		 * Check if the alternative_english_names has a comma separated values if yes , all of them are valid
		 * species.
		 */
		if (!empty($results['alternative_english_names'])) {
			// If there is only one alternate english name
			if (strpos($results['alternative_english_names'],',') === false) {
				$validSpecies[trim($results['alternative_english_names'])] = $results['species_id'];
			} else {
			// There are more than one alternative english names
				$specis = explode(',',$results['alternative_english_names']);
				foreach ($specis as $speci) {
					$validSpecies[trim($speci)] = $results['species_id'];
				}
			}
		}

		/**
		 * the scientific name is also a valid species name
		 */
		$validSpecies[$results['scientific_name']] = $results['species_id'];
	}

	if (!empty($validSpecies)) {
		return $validSpecies;
	} else {
		return false;
	}
 }

 /**
  * function : getSpeciesForAutoComplete
  */
 function getSpeciesForAutoComplete($connect) {

	$sql = "SELECT species_id,LOWER(common_name) as common_name,LOWER(scientific_name) as scientific_name,LOWER(alternative_english_names) as alternative_english_names FROM migwatch_species";
	$res = mysql_query($sql,$connect);

	while($row = mysql_fetch_assoc($res)) {

		$key = $row['common_name'];

		if (!empty($row['alternative_english_names'])) {
			$row['alternative_english_names'] = str_replace(',',' or ',$row['alternative_english_names']);
			$key .= ' or '.$row['alternative_english_names'];
		}

		if (!empty($row['scientific_name'])) {
			$key .= ' ('.$row['scientific_name'].')';
		}

		$items[$key] = $row['species_id'];
	}

	if (!empty($items)) {
		return $items;
	}
 }


/**
 * function : getUserSightingLocIds
 * Purpose  : The the location ids of locations added by a specified user.
 */
 function getUserSightingLocIds($user_id,$connect) {
	if ((empty($user_id) && $user_id != '0') || empty($connect)) {
		return false;
	}

	$sql = "SELECT DISTINCT(location_id) as location_id FROM `migwatch_l1` " .
		   "WHERE user_id = '$user_id'";
	$res = mysql_query($sql,$connect);
	if (mysql_num_rows($res) > 0) {
		$results = false;
		while($result = mysql_fetch_assoc($res)) {
			$results[] = $result['location_id'];
		}
		return $results;
	} else {
		return false;
	}
 }

/**
 * function : getUserAddedLocsIds
 * Purpose  : Get the location ids of locations added by a specified user.
 */
 function getUserAddedLocIds($user_id,$connect) {
	if ((empty($user_id) && $user_id != '0') || empty($connect)) {
		return false;
	}

	$sql = "SELECT location_id FROM `migwatch_locations` " .
		   "WHERE created_by_user_id = '$user_id'";
	$res = mysql_query($sql,$connect);
	if (mysql_num_rows($res) > 0) {
		$results = false;
		while($result = mysql_fetch_assoc($res)) {
			$results[] = $result['location_id'];
		}
		return $results;
	} else {
		return false;
	}
 }

  /**
   * function : getMyLocIds
   * Purpose  : Get the location ids of locations marked as 'My location'
   */
 function getMyLocIds($user_id,$connect) {

	if ((empty($user_id) && $user_id != '0') || empty($connect)) {
		return false;
	}

	$sql = "SELECT location_id FROM `migwatch_user_locs` " .
		   "WHERE user_id = '$user_id'";
	$res = mysql_query($sql,$connect);
	if (mysql_num_rows($res) > 0) {
		$results = false;
		while($result = mysql_fetch_assoc($res)) {
			$results[] = $result['location_id'];
		}
		return $results;
	} else {
		return false;
	}
 }

  /**
   * function : getLocationDetails
   * Purpose  : Get the details of the location from the given id array
   */
 function getLocationDetails($ids,$connect,$sort,$order) {

	if (empty($ids) || empty($connect)) {
		return false;
	}

	// Get the ids string.
	$idStr = implode("','",$ids);

	$sql = "SELECT * FROM `migwatch_locations` m,`migwatch_states` s " .
		   "WHERE location_id IN ('$idStr') AND m.state_id = s.state_id ORDER BY $sort $order";
	$res = mysql_query($sql,$connect);
	if (mysql_num_rows($res) > 0) {
		$results = false;
		while($result = mysql_fetch_assoc($res)) {
			$results[] = $result;
		}
		return $results;
	} else {
		return false;
	}
 }

  /**
   * Function : getAllMyLocationData
   * Purpose  : Get the details of the location from the given id array
   */
 function getAllMyLocationData($user_id,$connect,$sort='state',$order='ASC') {

	$validArrays = array();
	$myLocationIds = array();

	// All User 'My Locations'
	$myLocationIds = getMyLocIds($user_id,$connect);
	$valArrCnt = count($myLocationIds);

	// Merge all to get all the location ids
	if (empty($myLocationIds)) {
		return false;
	}

	/**
	 * Get the details from the location ids we have.
	 */
	if (!empty($myLocationIds)) {
		$myLocationIds = array_unique($myLocationIds);
		$locations = getLocationDetails($myLocationIds,$connect,$sort,$order);
	}

	// also mark the deletable entries in the array.
	$i=0;
	foreach($locations as $locs) {
		$can_delete = false;
		$sql = "SELECT NULL FROM migwatch_l1 WHERE location_id='$locs[location_id]'";
		$result = mysql_query($sql, $connect);
		if(mysql_num_rows($result) > 0) {
			if($locs['created_by_user_id'] != $user_id) {
				$sql2 = "SELECT NULL FROM migwatch_l1 WHERE location_id='$locs[location_id]' AND user_id='$user_id'";
				$result2 = mysql_query($sql2, $connect);
				if(mysql_num_rows($result2) <= 0) {
					$can_delete = true;
				}
			}
		} elseif($locs['created_by_user_id'] == $user_id) {
			$sql3 = "SELECT NULL FROM migwatch_user_locs WHERE location_id='$locs[location_id]' AND user_id!='$user_id'";
			$result3 = mysql_query($sql3, $connect);
			if(mysql_num_rows($result3) <= 0) {
				$can_delete = true;
			}
		} else {
			$can_delete = true;
		}
		$locations[$i++]['can_delete'] = $can_delete;
	}

	return $locations;
 }

// If we are on php 4
 if (!function_exists('array_diff_key')) {
    function array_diff_key()
    {
        $arrs = func_get_args();
        $result = array_shift($arrs);
        foreach ($arrs as $array) {
            foreach ($result as $key => $v) {
                if (array_key_exists($key, $array)) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
   }
 }

 // again if we are on php4
if (!function_exists("stripos")) {
	function stripos($str,$needle,$offset=0)
  	{
    	return strpos(strtolower($str),strtolower($needle),$offset);
  	}
}

function getCurrentSeason($asArray = false) {
	//NOTE : the migration year is 1 July to 30 June

	$todayMonth = date('m');
	$todayYear = date('Y');

	/**
	 * If the current mon the anything greater than or equal to July
	 * Its a new season and the year will be the "current to (current + 1)"
	 */
	if ($todayMonth >= 7) {
		$seasonStart = $todayYear;
		$seasonEnd = $todayYear + 1;
	} else {
		/**
		 * If the month is less than 7 (July) the season is "(current - 1) to current"
		 */
		$seasonStart = $todayYear - 1;
		$seasonEnd = $todayYear;
	}

	if ($asArray) {
		$currentSeason = array($seasonStart,$seasonEnd);
	} else {
		$currentSeason =  $seasonStart.'-'.$seasonEnd;
	}

	return $currentSeason;
}

function get_latest_pics(){
         $connectw=mysql_connect("", "","");
         mysql_select_db("migwatch",$connectw );
         $sql="select u.user_name, p.photo_filename, p.photo_caption,p.photo_id from migwatch_photos as p, migwatch_users as u, migwatch_l1 as l1 where p.user_id = u.user_id and l1.id=p.sighting_id and l1.valid='1' and l1.deleted='0' order  by p.photo_id DESC LIMIT 4";
         $result=mysql_query($sql);
         while($data = mysql_fetch_array($result)) {
              $mw_pics['title'][] = $data['photo_caption'];
              $mw_pics['id'][] = $data['photo_id'];
              $mw_pics['photo'][] = $data['photo_filename'];
	      $mw_pics['user_name'][] = $data['user_name'];
         }
         return $mw_pics;
}


function mw_get_page($page_id) {

	 $connectw=mysql_connect("", "","");
	 $wordpress_db=mysql_select_db("migwatch",$connectw );
	 $query = "Select post_title,post_content FROM wp_posts WHERE post_type='page' AND post_status='publish' AND ID='$page_id'";
	 $query_result = mysql_query($query);
	 mysql_close();

	 $page_wordpress[] = mysql_result($query_result, 0, "post_title");
	 $page_wordpress[] = mysql_result($query_result, 0, "post_content");
	 
	 return $page_wordpress;
}


function get_recent_news() {
	$connectw=mysql_connect("", "","");
        $wordpress_db=mysql_select_db("migwatch",$connectw );
        $sql="SELECT ID,post_title,post_excerpt,post_date  FROM wp_posts, wp_term_relationships, wp_term_taxonomy WHERE wp_term_relationships.object_id = wp_posts.id AND post_status= 'publish' and post_type = 'post' AND wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_taxonomy.taxonomy ='category' AND wp_term_taxonomy.term_id = 12 ORDER BY id DESC LIMIT 5";

        $query_result = mysql_query($sql);

	while($news_feed = mysql_fetch_assoc($query_result)) {
           $recent_news['id'][] = $news_feed['ID'];
           $recent_news['title'][] = $news_feed['post_title'];
	   $recent_news['content'][] = $news_feed['post_excerpt'];
           $recent_news['time'][] = $news_feed['post_date'];
	 }
	 mysql_close();
         return $recent_news;
}

function get_latest_blog() {
        $connectw=mysql_connect("", "","");
	$wordpress_db=mysql_select_db("migwatch",$connectw );
         $sql="SELECT distinct ID,post_title,post_content,post_date  FROM wp_posts, wp_term_relationships, wp_term_taxonomy WHERE wp_term_relationships.object_id =wp_posts.id AND post_status= 'publish' and post_type = 'post' AND wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_taxonomy.taxonomy ='category' AND wp_term_taxonomy.term_id != 12 ORDER BY id DESC LIMIT 5";
       	$query_result = mysql_query($sql);
	while($data = mysql_fetch_assoc($query_result)) {
		    $latest_blog['id'][] = $data['ID'];
		    $latest_blog['title'][] = $data['post_title'];

	}
	mysql_close();
	return $latest_blog;
}

function mw_get_species($species_id) {

        $sql = "select s.common_name,s.alternative_english_names,s.scientific_name, s.population, s.habitat_type,s.OBC_number, s2.size, s2.descr,s2.behaviour,s2.dist_char,s2.winter_dist,s2.breed_dist,s2.iucn_status,s2.call_text,s2.other_notes,s2.img_general,s2.img_flight,s2.img_male,s2.img_female,s2.img_juv,s2.img_hab,s2.call_audio,s2.img_behav,s2.habitat,s2.video from migwatch_species as s,migwatch_species_set as s2 where s.species_id =  s2.species_id AND s.species_id='$species_id'";

        $result = mysql_query($sql);

         while ($row = mysql_fetch_array($result) ) {
                     $species['common_name'] = $row['common_name'];
		     $species['alternative_english_names'] = $row['alternative_english_names'];
		     $species['scientific_name'] = $row['scientific_name'];
		     $species['population'] = $row['population'];
		     $species['habitat_type'] = $row['habitat_type'];
		     $species['obc_number'] = $row['OBC_number'];

		     $species['size'] = $row['size'];
		     $species['descr'] = $row['descr'];
		     $species['behaviour'] = $row['behaviour'];
		     $species['dist_char'] = stripslashes($row['dist_char']);
		     $species['winter_dist'] = $row['winter_dist'];
		     $species['breed_dist'] = $row['breed_dist'];
		     $species['iucn_status'] = $row['iucn_status'];
		     $species['call_text'] =	 $row['call_text'];
		     $species['other_notes'] =	$row['other_notes'];
		     $species['img_male']  = $row['img_male'];
		     $species['img_female']  = $row['img_female'];
		      $species['img_general']  = $row['img_general'];
		       $species['img_flight']  = $row['img_flight'];
		     $species['img_juvenile']  = $row['img_juv'];
		     $species['call_audio']  = $row['call_audio'];
		     $species['video']  = $row['video'];
		     $species['habitat']  = $row['habitat'];
         }
         return $species;
}

function mw_get_all_species() {
	 $sql = "select s.species_id, s.common_name,s.alternative_english_names,s.scientific_name, s.population, s.habitat_type,s.OBC_number, s2.size, s2.descr,s2.behaviour,s2.dist_char,s2.winter_dist,s2.breed_dist,s2.iucn_status,s2.call_text,s2.other_notes,s2.img_male,s2.img_female,s2.img_juv,s2.img_hab,s2.call_audio,s2.img_behav,s2.img_general, s2.img_flight,s2.habitat,s2.video from migwatch_species as s,migwatch_species_set as s2 where s.species_id =  s2.species_id order by s.common_name";

	 $result = mysql_query($sql);

	 while ($row = mysql_fetch_array($result) ) {
	             $species['species_id'][] = $row['species_id'];
                     $species['common_name'][] = $row['common_name'];
		     $species['scientific_name'][] = $row['scientific_name'];
		     if ( $row['img_male'] ) {
		     	$species['image'][] = $row['img_male'];
                     } else if ( $row['img_female'] ) {
                        $species['image'][] = $row['img_female'];
                     } else if ( $row['img_juv'] ) {
                        $species['image'][] = $row['img_juv'];
                     } else if ( $row['img_general'] ) {
                        $species['image'][] = $row['img_general'];
                     } else if ( $row['img_flight'] ) {
                        $species['image'][] = $row['img_flight'];
                     }


          }
	  return $species;

}

function get_photo_count($id) {
	 $sql="select * from migwatch_photos where sighting_id='$id'";
	 $result=mysql_query($sql);
	 $photo_num = mysql_num_rows($result);
	 return $photo_num;
}


?>