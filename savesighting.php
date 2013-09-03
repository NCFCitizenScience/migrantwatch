<?php
    session_start();
    header("Cache-control: private"); // IE 6 Fix
 
    if(!isset($_SESSION['userid'])) {
        exit;
    }
    foreach($_POST as $key=>$value){
        $_SESSION[$key]=$value;
    }

    include("./db.php");
    include("./functions.php");
    function get_submit_value($val) {
      $none="---";
      if($val) { 
         return $val;
      } else {
      	 return $none;
      }
    }
    $referer = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'main.php';
    if(substr($referer, -4) == '.php') {
        $newreferer = $referer."?";
    } else {
        $newreferer = $referer."&";
    }

if ($_POST['sighting1_submit']) {
   echo "<pre>";
        print_r($_POST);
	echo "</pre>";
exit;

}

if ($_POST['sighting_submit']) {
        //print_r($_POST);exit;
        if($_POST['location'] == '-1') {
            $error[] = 'location';
        }

	
    
        if(!isValidDate($_POST['obstart'])) {
            $error[] = 'obstart';
        } else {
            $obstart = $_POST['obstart'];
        }
        if(!isset($_POST['often']) || trim($_POST['often']) == "") {
            $error[] = 'often';
        }
	
	$obs_type = $_POST['sigtype'];      
	if( strtolower($obs_type) == 'last') { $last_sighting = 1; }
	$resident_check = $_POST['resident_check'];
        $speciesHintText = 'Type a name here';
        $obdate = array();
        $species = array();
        $species_id = array();
        $number = array();
        $accuracy = array();
        $entry_notes = array();
	$sighting_type = array();
	$flight_dir = array();
        for($i=0; $i<count($_POST['obdate']); $i++) {
            if($_POST['species'][$i] != '' && $_POST['species'][$i] != $speciesHintText) {
                if($_POST['obdate'][$i] != '') {
                    $obdate[] = $_POST['obdate'][$i];
                    $species[] = getEscaped($_POST['species'][$i]);
		    if ( $_POST['number'][$i] != "In numerals" ) {
                    	$number[] = $_POST['number'][$i];
	            }
                    $accuracy[] = $_POST['accuracy'][$i];
	            if ( $_POST['entry_notes'][$i] != "Enter notes here" ) {
                       $entry_notes[] = getEscaped($_POST['entry_notes'][$i]);
                    }
		    $sighting_type[] = $_POST['sighting_type'][$i];
		    $flight_dir[] = $_POST['flight_dir'][$i];

                } else {
                    $error[] = 'no_obdate';
                }
            } else {
                continue;
            }
        }

        if(isset($obstart)) {
            foreach ($obdate as $sight) {
                if (!isValidDate($sight)) {
                    $error[] = 'obdate';
                    break;
                } elseif(!compareDates($obstart, $sight, $last_sighting)) {
                    $error[] = 'ob_compare';
                }
            }
        }

        
        if(!empty($obdate) && !empty($species)) {
            $validSpecies = isValidSpeciesNameRetId($species, $connect);
            foreach($validSpecies as $key => $valid_id) {
                if($valid_id == 'error') {
                    $error[] = 'species';
                    break;
                } else {
                    $species_id[$key] = $valid_id;
                }
            }

            foreach($number as $k => $num) {
                $num = trim($num);
                /**
                * If user has entered a value in the 'number' field and its not
                * numeric show error
                */
	        if( $num != "In numerals" ) {
                if(!empty($num) && (!is_numeric($num) || $num <= 0 || $num > 9999)) {
                    $error[] = 'number';
                    break;
                } else if (!empty($num) && empty($accuracy[$k])) {
                    $error[] = 'accuracy';
                    break;
                } else if (empty($num)) {
                    $accuracy[$k] = '';
                }
	             }
            }
        }

        if (!empty($error)) {
            $_SESSION['sighting'] = $_POST;
            $_SESSION['sighting']['errors'] = $error;
	    print_r($error);
            //header('Location: addsighting.php?error=1');
            exit;
        }

        // Proceed to insert
        $user_friend = getEscaped($_POST['other_name']);
        $loc_id = $_POST['location'];

        if($obstart != "") {
            $obst = "'".substr($obstart,6,4)."-".substr($obstart,3,2)."-".substr($obstart,0,2)."'";
            $obs_year = (int)substr($obstart,6,4);
            $obs_month = (int)substr($obstart,3,2);
            if($obs_month >= 7) {
                $seasonStart = $obs_year;
            } else {
                $seasonStart = $obs_year - 1;
            }
            $seasonEnd = $seasonStart + 1;
        } else {
            $obst = "null";
        }

        $often = $_POST['often'];
	$sigtype = $_POST['sigtype'];
        $other_notes = getEscaped($_POST['other_notes']);

        $insertfailed = false;
        $email_body_species_section = "";
        $result = mysql_query("SELECT species_id, common_name FROM migwatch_species WHERE active=1");
        $duplicate = false;
        $duplicate_species = array();
        if($result) {
            for($i=0; $i<count($obdate); $i++) {
                $sightingdate = $obdate[$i];
                if($sightingdate != "") {
                    $sightingdate = substr($sightingdate,6,4)."-".substr($sightingdate,3,2)."-".substr($sightingdate,0,2); 

		   if(strtolower($_POST['sigtype']) == 'general' ) {
			$sql = "SELECT NULL from migwatch_l1 WHERE user_id=".$_SESSION['userid'];
                    	$sql .= " AND species_id=".$species_id[$i];
                    	$sql .= " AND deleted != '1'";
                    	$sql .= " AND location_id=".$loc_id;
                   	$sql .= " AND obs_type='general'";
                    	$sql .= " AND sighting_date='".$sightingdate."'";
                    	$sql .= " ORDER BY sighting_date DESC";
                    } else {
                    /*Check for duplicate first sighting record*/
                    $sql = "SELECT sighting_date from migwatch_l1 WHERE user_id=".$_SESSION['userid'];
                    $sql .= " AND species_id=".$species_id[$i];
                    $sql .= " AND deleted != '1'";
                    $sql .= " AND location_id=".$loc_id;
                    $sql .= " AND obs_type='".$obs_type."'";
                    $sql .= " AND obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'";
                    $sql .= " ORDER BY sighting_date DESC";
	
		    }

                    $result1 = mysql_query($sql);
                    if($result1) {
                        if(mysql_num_rows($result1) == 0) {

                            // If its the hint text make it blank
                            if ($entry_notes[$i] == 'Enter notes here') {
                                $entry_notes[$i] = '';
                            }
			    //echo $loc_id.",".$species_id[$i].",'".$sightingdate;
                            $sql = "INSERT INTO migwatch_l1(user_id,location_id,species_id,sighting_date,obs_start,frequency,notes,user_friend,obs_type,number,accuracy,entry_notes,sighting_type,flight_dir)";
                            $sql .= " VALUES (".$_SESSION['userid'].",".$loc_id.",".$species_id[$i].",'".$sightingdate."',";
                            $sql .= $obst.",'".$often."','".$other_notes."','".$user_friend."','".$obs_type."','".$number[$i]."','".$accuracy[$i]."','".$entry_notes[$i]."','" . $sighting_type[$i] . "','" . $flight_dir[$i] . "')";
			    

                       	    $email_body_species_section .="<tr><td>". getStripped($species[$i])."</td><td>" . $sightingdate  . "</td><td>". ucfirst($obs_type) ."</td>";
                            $email_body_species_section .=" <td>". get_submit_value(ucfirst($sighting_type[$i])) ."</td><td>". get_submit_value($number[$i]) ."</td><td>". get_submit_value(ucfirst($accuracy[$i])) ."</td>";

			     $success = mysql_query($sql);
                            if (!$success) {
                                $insertfailed = true;
                                break;
                            } else {

			      $success_species[] = $species[$i];
			    }

                            $sighting_id = mysql_insert_id();

			    $email_body_species_section .="<td style='border-right:solid 1px #ffcb1a;'><a class='thickbox' title='Upload photos' href='uploadphotos.php?id=" . $sighting_id . "&TB_iframe=true&height=400&width=800'>upload photos</a></tr>";

			    if( $species_id[$i] == '21' ) { 
			    	if($resident_check) { 
				    $resident_value = '1'; 
				} else {
				    $resident_value = '0';
				}

				$sql="insert into piedcuckoo_migwatch(sighting_id, resident_check) values('$sighting_id','$resident_value')";
				mysql_query($sql);
			    }
			    
                        } else {
                            $duplicate = true;
                            $duplicate_species[] = $species[$i];
                            $new_POST['location'] = $_POST['location'];
                            $new_POST['obstart'] = $_POST['obstart'];
                            $new_POST['other_name'] = $_POST['other_name'];
                            $new_POST['often'] = $_POST['often'];
                            $new_POST['other_notes'] = $_POST['other_notes'];
                            $new_POST['species'][] = $species[$i];
                            $new_POST['obdate'][] = $sightingdate;
                            $new_POST['number'][] = $number[$i];
                            $new_POST['accuracy'][] = $accuracy[$i];
                            $new_POST['entry_notes'][] = $entry_notes[$i];
			    $new_POST['sighting_type'][] = $sighting_type[$i];
			    $new_POST['flight_dir'][] = $flight_dir[$i];
                        }
                    }
                }
            }
        }
        echo "</table>";
        if ($insertfailed ) {
		  echo "<div class='error' style='width:700px'><b>Data entry failed.</b></div>";
	} else { 

	    if($success_species) {
              $sql = "SELECT user_email FROM migwatch_users WHERE user_id=".$_SESSION['userid'];
              $result = mysql_query($sql);
              if($result){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $email = $row{'user_email'};
                }
              }
              $to = $email;
              $subject = "MigrantWatch - Data entry - ".$_SESSION['username'];
              $sql = "SELECT l.location_name, s.state FROM migwatch_locations l INNER JOIN migwatch_states s ON l.state_id=s.state_id WHERE l.location_id=$loc_id";
              $result = mysql_query($sql);
              if($result){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $loc_name = stripslashes($row{'location_name'});
                    $state = stripslashes($row{'state'});
                }
              }
	      $body = '<table class="success page_layout">';
              $body .= "<tr><td>Thank you <b>".$_SESSION['username']."</b>, You have successfully entered the sighting(s) for <b>" . $loc_name .",". $state ."</b> into the MigrantWatch database";
	      if ($user_friend) { $body .= " on behalf of <b>".stripslashes(ucfirst($user_friend))."<b></td></tr>"; } else { $body .= "</td></tr>"; }
	      $body .="<table>";
              $body = $body . '<table id="table1" class="tablesorter page_layout">';
              $body = $body . '<thead><tr><th>Species</th><th>Date</th><th>Type</th><th>Sighting type</th><th>Number</th><th>Accuracy</th><th>Upload Photos</th></tr></thead><tbody>'; 
              $body .= $email_body_species_section;
              $body .= "</tbody></table>";
               $user_id = $_SESSION['userid'];
	          
              $headers = "From: mw@migrantwatch.in\r\n" .
                "X-Mailer: php\r\n" .
                "Cc: mw@migrantwatch.in";
            
               if (true) {                
                  foreach($_POST as $key=>$value) {
                     unset($_SESSION[$key]);
                  }
		   
                   echo "<div style='font-size:14px;margin-left:auto;margin-right:auto'>". $body . "</div>";
                   ?><script>$(document).ready(function() { $('#full_box').hide();  });</script><?                
               } else {
                   $message = "The data has been submitted, but there was a problem sending you a confirmation email.";
              }
	   }

	   if ($duplicate) {
               $message =  "You have already entered a " . ucfirst($_POST['sigtype']).  " sighting date for the following species at this location.";
               $message .= "You can edit this information through <a style='color:#d95c15;font-weight:bold;text-decoration:underline'";
	       $message .= "href='editsightings.php'>Edit Sightings</a>";
               echo "<div class='notice' style='width:900px;margin-left:auto;margin-right:auto;font-size:13px;margin-bottom:10px;'>" .  $message . "<br>";
               for ( $i=0; $i < count( $duplicate_species );  $i++ ) {
                     echo "<b>( " . $i + 1 . ")<b>  " . $duplicate_species[$i] . "<br>";
               }
                echo  "</div>";
            }

	}
}




?><script type="text/javascript" src="js/jquery/thickbox/thickbox.js"></script>
<link href="js/jquery/thickbox/thickbox.css"  rel="stylesheet" type="text/css" />
