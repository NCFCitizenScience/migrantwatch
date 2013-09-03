<?php
    session_start();
    header("Cache-control: private"); // IE 6 Fix
 
    if(!isset($_POST['cmd']) || !isset($_SESSION['userid'])) {
        //$message = "This page is for internal operations only";
        
        exit;
    }
    foreach($_POST as $key=>$value){
        $_SESSION[$key]=$value;
    }

    //while(list($key,$val) = each ($_POST))
    //   $_SESSION[$key] = $val;

    include("./db.php");
    include("./functions.php");
    $referer = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'main.php';
    if(substr($referer, -4) == '.php') {
        $newreferer = $referer."?";
    } else {
        $newreferer = $referer."&";
    }

    $cmd = $_POST['cmd'];

if ($cmd == 'sighting_general') {
        
        if($_POST['location'] == '-1') {
            $error[] = 'location';
        }
        if(!isset($_POST['obstart']) || $_POST['obstart'] == '') {
            $error[] = 'no_obStart';
        } elseif(!isValidDate($_POST['obstart'])) {
            $error[] = 'obstart';
        } else {
            $obstart = $_POST['obstart'];
        }
        if(!isset($_POST['often']) || trim($_POST['often']) == "") {
            $error[] = 'often';
        }

        $obs_type = 'general';
        $obs = "General";

        $speciesHintText = 'Type part of a species name';
        $obdate = array();
        $species = array();
        $species_id = array();
        $number = array();
        $accuracy = array();
        $entry_notes = array();
        for($i=0; $i<count($_POST['obdate']); $i++) {
            if($_POST['species'][$i] != '' && $_POST['species'][$i] != $speciesHintText) {
                if($_POST['obdate'][$i] != '') {
                    $obdate[] = $_POST['obdate'][$i];
                    $species[] = getEscaped($_POST['species'][$i]);
                    $number[] = $_POST['number'][$i];
                    $accuracy[] = $_POST['accuracy'][$i];
                    $entry_notes[] = getEscaped($_POST['entry_notes'][$i]);
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
                } elseif(!compareDates($obstart, $sight)) {
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
            $_SESSION['gensightings'] = $_POST;
            $_SESSION['gensightings']['errors'] = $error;
            //header('Location: gensightings.php?error=1');
            //exit;
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

                    /*Check for duplicate first sighting record*/
                    $sql = "SELECT NULL from migwatch_l1 WHERE user_id=".$_SESSION['userid'];
                    $sql .= " AND species_id=".$species_id[$i];
                    $sql .= " AND deleted != '1'";
                    $sql .= " AND location_id=".$loc_id;
                    $sql .= " AND obs_type='general'";
                    $sql .= " AND sighting_date='".$sightingdate."'";
                    //$sql .= " AND obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'";
                    $sql .= " ORDER BY sighting_date DESC";

                    $result1 = mysql_query($sql);
                    if($result1) {
                        if(mysql_num_rows($result1) == 0) {

                            // If its the hint text make it blank
                            if ($entry_notes[$i] == 'Please enter identification details') {
                                $entry_notes[$i] = '';
                            }
                            echo $loc_id.",".$species_id[$i].",".$sightingdate;

                            $sql = "INSERT INTO migwatch_l1(user_id,location_id,species_id,sighting_date,obs_start,frequency,notes,user_friend,obs_type,number,accuracy,entry_notes)";
                            $sql .= " VALUES (".$_SESSION['userid'].",".$loc_id.",".$species_id[$i].",'".$sightingdate."',";
                            $sql .= $obst.",'".$often."','".$other_notes."','".$user_friend."','general','".$number[$i]."','".$accuracy[$i]."','".$entry_notes[$i]."')";
                            $email_body_species_section .="Species name:\t". getStripped($species[$i])."\nSighting type:\tGeneral\nSighting Date:\t".$sightingdate;
                            $email_body_species_section .="\nNumber:\t\t".$number[$i]."\nAccuracy\t".$accuracy[$i]."\nNotes:\t".stripslashes($entry_notes[$i])."\n\n";
                            $success = mysql_query($sql);
                            if (!$success) {
                                $insertfailed = true;
                                break;
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
                        }
                    }
                }
            }
        }
		 echo "</table>";
        if ($insertfailed ) {
	  echo "<div class='error' style='width:700px'><b>Data enrty failed.</b></div>";

	} else if ($duplicate) {
            
       
             $message = "You have already entered a general sighting date for the following species at this location.<br />";
             $message .= "You can edit this information through <a href='editsightings.php'>Edit Sightings</a>";


         } else {
            $sql = "SELECT user_email FROM migwatch_users WHERE user_id=".$_SESSION['userid'];
            $result = mysql_query($sql);
            if($result){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $email = $row{'user_email'};
                }
            }
            $to = $email;
            $subject = "MigrantWatch - Data entry - ".$_SESSION['username'];
            $body = "Thank you ".$_SESSION['username'].",<br>You have just entered the following information into the MigrantWatch database:<br>";
            $sql = "SELECT l.location_name, s.state FROM migwatch_locations l INNER JOIN migwatch_states s ON l.state_id=s.state_id WHERE l.location_id=$loc_id";
            $result = mysql_query($sql);
            if($result){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $loc_name = stripslashes($row{'location_name'});
                    $state = stripslashes($row{'state'});
                }
            }	

	    $body = '<table style="width:930px; margin-left:auto;margin-right:auto">';
           $body .= "<tr><td>Thank you ".$_SESSION['username'].", You have just entered the following information into the MigrantWatch database</td></tr>";
	    $body .= "<tr><td>Location: $loc_name , $state </td></tr>";
	    if ($user_friend) { $body .= "<tr><td>On behalf of ".stripslashes($user_friend)."</td></tr>"; }
	    echo "</table>";
            $body = $body . '<table id="table1" class="tablesorter" style="width:950px;border:solid 1px" cellpadding=3 cellspacing=0>';
            $body = $body . '<thead><tr><th>Species</th><th>Date</th><th>Type</th><th>Number</th><th>Accuracy</th><th>Sighting type</th><th>Upload Photos</th></tr></thead><tbody>';
            
            
            $body .= $email_body_species_section;
            echo "</tbody></table>";
            
            if ($other_notes) {$body = $body . "\nOther notes = ".stripslashes($other_notes)."\n\n";}
          //$body .= "\n\nIf you have not submitted this information, or if there is an error,\n please let us know immediately by replying to this email.\n";
            //$body = $body .  "All dates are in the format yyyy-mm-dd.";

            $headers = "From: migrantwatch@ncbs.res.in\r\n" .
                "X-Mailer: php\r\n" .
                "Cc: migrantwatch@ncbs.res.in";
            if (mail($to, $subject, $body, $headers)) {
            if (true) {
                
                foreach($_POST as $key=>$value) {
                    unset($_SESSION[$key]);
                }
						 echo "<div class='success'>";
                                         //echo $body;
                                         echo "You sightings have been submitted successfully</div>";
                                         echo "<div style='width:930px'>". $body . "</div>";
                                         ?><script>$(document).ready(function() { $('#full_box_general').hide();  });</script><?
                
            } else {
                $message = "The data has been submitted, but there was a problem sending you a confirmation email.";
           }
	   
	  
	  
     }
   }
}

?>
