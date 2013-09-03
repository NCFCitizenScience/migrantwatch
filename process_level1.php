<?php
    session_start();
    header("Cache-control: private"); // IE 6 Fix
    error_reporting(E_ALL);
    if(!isset($_POST['cmd']) || !isset($_SESSION['userid'])) {
        //$message = "This page is for internal operations only";
        header("location:main.php");
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

    if($cmd == "newlocation") {
        $nlname = getEscaped($_POST['nlname']);
        $nlcity = getEscaped($_POST['nlcity']);
        $nldist = getEscaped($_POST['nldist']);
        $nllat = getEscaped($_POST['nllat']);
        $nllong = getEscaped($_POST['nllong']);
        $nlstate = $_POST['nlstate'];
        $nladditional = getEscaped($_POST['nladditional']);
        $currentfile = $_POST['currentfile'];

        $sql = "INSERT INTO migwatch_locations (location_name,city,district,state_id,latitude,longitude,additional_notes,created_by_user_id) VALUES ('".$nlname."','".$nlcity."','".$nldist."',".$nlstate.",'".$nllat."','".$nllong."','".$nladditional."',".$_SESSION['userid'].")";
        $success = mysql_query($sql);
        $lastInsertId = mysql_insert_id();

        $sql = "INSERT INTO migwatch_user_locs (user_id,location_id) VALUES ($_SESSION[userid],$lastInsertId)";
        $success = mysql_query($sql);

        if ($success){
            $body = "New location has been created. Please validate. Location ID: ".mysql_insert_id();
            $headers = "From: migrantwatch@ncbs.res.in\r\n" .
                                "X-Mailer: php\r\n";
                    //"Cc: migrantwatch@ncbs.res.in";
            //mail("jatin@sanisoft.com", "MigrantWatch - New Location - ".$_SESSION['username'], $body, $headers);

            if ($currentfile == 'mylocations.php') {
                header("Location: $currentfile?extlocadded=1");
                exit;
            }

            //header("Location: $currentfile?state=".$nlstate."&loc=".mysql_insert_id());
            //exit();
        }else
            $message = "There was a problem creating the new location. Please contact IT team.";
    }

    if ($cmd == "updatelevel1"){
        // Filer the data first

        // Validate the keys using the value functions
        $validate = array
        (
            'obstart'  => 'isValidDate'
            ,'dt'      => 'isValidDate'
        );

        // use the validation function to filter data
        foreach ($validate as $field => $callBackFunc) {
            if (!$callBackFunc($_POST[$field])) {
                $error[] = $field;
            } else {
                if (!is_array($_POST[$field])) {
                    $filtered_data[$field] = getEscaped($_POST[$field]);
                } else {
                    $filtered_data[$field] = $_POST[$field];
                }
            }
        }

        /**
         * The number is now optional, but when entered needs to be a number
         * Also if the number is entered the accuracy should also be selected
         */
        $_POST['number'] = trim($_POST['number']);
        if (!empty($_POST['number']) && !is_numeric($_POST['number'])) {
            $error[] = 'number';
        } else if (!empty($_POST['number']) && empty($_POST['accuracy'])) {
            $error[] = 'accuracy';
        } else if (empty($_POST['number'])) {
            $_POST['accuracy'] = '';
        }

        if(!isset($_POST['often']) || trim($_POST['often']) == "") {
            $error[] = 'often';
        } else {
            $filtered_data['often'] = $_POST['often'];
        }
        $filtered_data['other_name'] = getEscaped($_POST['other_name']);
        $filtered_data['other_notes'] = getEscaped($_POST['other_notes']);

        $filtered_data['number'] = getEscaped($_POST['number']);
        $filtered_data['accuracy'] = $_POST['accuracy'];
        $filtered_data['entry_notes'] = getEscaped($_POST['entry_notes']);

        // Get array of data which is not compulsary or which does not need validation
        $restpost_data = array_diff_key($_POST,$filtered_data);

        // Make a common array which is trustable
        $filtered_data = array_merge($_POST,$restpost_data);
        if($filtered_data['number'] == '') $filtered_data['number'] = 0;

        // If there is some error redirect to last page.
        if (!empty($error)) {
            $_SESSION['editL1'] = $_POST;
            $_SESSION['editL1']['errors'] = $error;
            header('Location: editlevel.php?id='.$filtered_data['id']."&error=1");
            exit;
        }

        $arr = explode('-',$filtered_data['obstart']);
        $arr2 = explode('-',$filtered_data['dt']);

        $arr = array_reverse($arr);
        $arr2 = array_reverse($arr2);

        $filtered_data['obstart'] = implode('-',$arr);
        $filtered_data['dt'] = implode('-',$arr2);

        $connect2 = $connect;
        // If there is not change in the data then we will not be doing anything
        $newData = $filtered_data;

        // Find the old data first
        $sql = "SELECT * FROM migwatch_l1 WHERE id = '".$filtered_data['id']."' AND user_id = '".$_SESSION['userid']."'";
        $res = mysql_query($sql,$connect2);
        $data = mysql_fetch_assoc($res);
        $oldData = array(
                            'id'          => $data['id'],
                            'obstart'     => $data['obs_start'],
                            'dt'          => $data['sighting_date'],
                            'often'       => $data['frequency'],
                            'other_name'  => getEscaped($data['user_friend']),
                            'other_notes' => getEscaped($data['notes']),
                            'number'      => getEscaped($data['number']),
                            'accuracy'    => getEscaped($data['accuracy']),
                            'entry_notes' => getEscaped($data['entry_notes'])
                        );
        unset($newData['submitter']);
        unset($newData['cmd']);
//print_r($oldData);print("<br>");print_r($newData);exit;
        /**
         * Compare old and new data and do nothing if found equal.
         */
        if ($oldData == $newData) {
            header("Location: ".$newreferer."cmd=updatelevel1success");
            exit();
        }

        // Update the selected data
        $updated_by = ", updated_by = '$_SESSION[userid]'";
        $sql = "UPDATE migwatch_l1 SET " .
                "obs_start='".$filtered_data['obstart']."'," .
                "sighting_date='".$filtered_data['dt']."'," .
                "frequency = '".$filtered_data['often']."'," .
                "user_friend = '".$filtered_data['other_name']."',".
                "number = '".$filtered_data['number']."',".
                "accuracy = '".$filtered_data['accuracy']."'," .
                "entry_notes = '".$filtered_data['entry_notes']."',".
                "notes = '".$filtered_data['other_notes']."' $updated_by WHERE id = '$data[id]'";
        $success = mysql_query($sql,$connect);
        $insertStr = '';

        if ($success){
            foreach ($data as $field => $value) {
                if ($field == 'entered_on' || $field == 'id' || $field == 'updated_by' || $field == 'deleted') {
                    continue;
                }

                if ($field == 'valid') {
                    $value = 1;
                }

                if ($field == 'user_id') {
                    $value = is_null($data['updated_by']) ? $data['user_id'] : $data['updated_by'];
                }

                $insertStr .= "`$field` = '".mysql_real_escape_string($value)."' , ";
            }

            $insertStr .= " `entry_id` = '$filtered_data[id]'";

            /**
             * If something changed then we need to move the older record to the
             */
            $sql = "INSERT INTO migwatch_history SET $insertStr";
            $success = mysql_query($sql);

            header("Location: ".$newreferer."cmd=updatelevel1success");
            exit();
        } else {
            header("Location: ".$newreferer."cmd=updatelevel1failed");
            exit();
        }
    }

    if ($cmd == 'speciessighting') {//print_r($_POST);exit;

        if($_POST['location'] == '-1') {
            $error[] = 'location';
        }
        if(!isset($_POST['obstart']) || $_POST['obstart'] == '') {
            if($_POST['last'] == 1) {
                $error[] = 'no_obEnd';
            } else {
                $error[] = 'no_obStart';
            }
        } elseif(!isValidDate($_POST['obstart'])) {
            $error[] = 'obstart';
        } else {
            $obstart = $_POST['obstart'];
        }
        if(!isset($_POST['often']) || trim($_POST['often']) == "") {
            $error[] = 'often';
        }
        if ($_POST['last'] == 1) {
            $obs_type = 'last';
            $obs = "End";
        } else {
            $obs_type = 'first';
            $obs = "Start";
        }

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
                } elseif(!compareDates($obstart, $sight, $_POST['last'])) {
                    $error[] = 'ob_compare';
                }
            }
        }

        /*if (empty($species)) {
            $error[] = 'species';
        } else {*/
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

        if (!empty($error)) {
            $_SESSION['trackspecies'] = $_POST;
            $_SESSION['trackspecies']['errors'] = $error;
            header('Location: trackspecies.php?error=1');
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
                   $sql = "SELECT sighting_date from migwatch_l1 WHERE user_id=".$_SESSION['userid'];
                    $sql .= " AND species_id=".$species_id[$i];
                    $sql .= " AND deleted != '1'";
                    $sql .= " AND location_id=".$loc_id;
                    $sql .= " AND obs_type='".$obs_type."'";
                    $sql .= " AND obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'";
                    $sql .= " ORDER BY sighting_date DESC";

                    $result1 = mysql_query($sql);
                    if($result1) {
                        if(mysql_num_rows($result1) == 0) {

                            // If its the hint text make it blank
                            if ($entry_notes[$i] == 'Please enter identification details') {
                                $entry_notes[$i] = '';
                            }
									//echo $loc_id.",".$species_id[$i].",'".$sightingdate;
                            $sql = "INSERT INTO migwatch_l1(user_id,location_id,species_id,sighting_date,obs_start,frequency,notes,user_friend,obs_type,number,accuracy,entry_notes)";
                            $sql .= " VALUES (".$_SESSION['userid'].",".$loc_id.",".$species_id[$i].",'".$sightingdate."',";
                            $sql .= $obst.",'".$often."','".$other_notes."','".$user_friend."','".$obs_type."','".$number[$i]."','".$accuracy[$i]."','".$entry_notes[$i]."')";
                            $email_body_species_section .="Species name:\t". getStripped($species[$i])."\nSighting type:\t". $obs_type ."\nSighting Date:\t".$sightingdate;
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
        if ($insertfailed || $duplicate) {
            /*$message = "There was a problem entering your data.<br><br>";
            $message .= "The form is meant for submitting dates of *$obs_type* sighting
                        of a species at a location.<br>";
            $message .= "The system will not allow you to submit a *second* $obs_type sighting date for the same species at the same location.<br><br>";
            $message .= "If the date you originally submitted was in error, please first delete the original record (from the main page), and then submit a new, corrected record.<br><br>";
            $message .= "If this is not the reason for the error, please contact the MigrantWatch team <MigrantWatch@ncbs.res.in><br><br>";
            $message .= "Press the \"Back\" button on your browser to return to the previous screen.";*/
            $message = "You have already entered a $obs_type sighting date for this species at this location.<br />";
            $message .= "You can edit this information through <a href='myspecies.php'>My Species</a> or <a href='mylocations.php'>My Locations</a>";
            $error[] = 'duplicate';

            // Put back in the original date format.
            foreach($new_POST['obdate'] as $index => $value) {
                $dtar = explode('-',$value);
                krsort($dtar);
                $new_POST['obdate'][$index] = implode('-',$dtar);
            }

            $_SESSION['trackspecies'] = $new_POST;
            $_SESSION['trackspecies']['duplicate'] = $message;
            $_SESSION['trackspecies']['duplicate_species'] = $duplicate_species;
            $_SESSION['trackspecies']['errors'] = $error;
            header('Location: trackspecies.php?error=1');
            exit;
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
            $body = "Hi ".$_SESSION['username'].",\n\nYou have just entered the following information into the MigrantWatch database:\n\n";
            $sql = "SELECT l.location_name, s.state FROM migwatch_locations l INNER JOIN migwatch_states s ON l.state_id=s.state_id WHERE l.location_id=$loc_id";
            $result = mysql_query($sql);
            if($result){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $loc_name = stripslashes($row{'location_name'});
                    $state = stripslashes($row{'state'});
                }
            }
            $body = $body . "Location: $loc_name\n";
            $body = $body . "State: $state\n";
            if ($user_friend) { $body = $body . "On behalf of ".stripslashes($user_friend)."\n\n"; }
            if($obstart != "null")
                $body = $body . "$obs of Observation: ".str_replace("'","",$obst)."\n";
            if($often)
                $body = $body . "Frequency with which you look for birds at this location: $often\n\n";

            $body = $body . "\n\nBird Observations:\n\n";
            $body .= $email_body_species_section;

            if ($other_notes) {$body = $body . "\nOther notes = ".stripslashes($other_notes)."\n\n";}
            $body .= "\n\nIf you have not submitted this information, or if there is an error,\n please let us know immediately by replying to this email.\n";
            $body = $body .  "\nAll dates are in the format yyyy-mm-dd.";

            $headers = "From: migrantwatch@ncbs.res.in\r\n" .
                "X-Mailer: php\r\n" .
                "Cc: migrantwatch@ncbs.res.in";
            //if (mail($to, $subject, $body, $headers)) {
            if (true) {
                header("Location: mylocations.php?cmd=insertsuccess");
                foreach($_POST as $key=>$value) {
                    unset($_SESSION[$key]);
                }
                exit();
            } else
                $message = "The data has been submitted, but there was a problem sending you a confirmation email.";
        }
    }

    if ($cmd == 'gensightings') {

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

        /*if (empty($species)) {
            $error[] = 'species';
        } else {*/
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

        if (!empty($error)) {
            $_SESSION['gensightings'] = $_POST;
            $_SESSION['gensightings']['errors'] = $error;
            header('Location: gensightings.php?error=1');
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
        if ($insertfailed || $duplicate) {
            $message = "You have already entered a general sighting for this species at this location on the same day.<br />";
            $message .= "You can edit this information through <a href='myspecies.php'>My Species</a> or <a href='mylocations.php'>My Locations</a>";
            $error[] = 'duplicate';

            // Put back in the original date format.
            foreach($new_POST['obdate'] as $index => $value) {
                $dtar = explode('-',$value);
                krsort($dtar);
                $new_POST['obdate'][$index] = implode('-',$dtar);
            }

            $_SESSION['gensightings'] = $new_POST;
            $_SESSION['gensightings']['duplicate'] = $message;
            $_SESSION['gensightings']['duplicate_species'] = $duplicate_species;
            $_SESSION['gensightings']['errors'] = $error;
            header('Location: gensightings.php?error=1');
            exit;
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
            $body = "Hi ".$_SESSION['username'].",\n\nYou have just entered the following information into the MigrantWatch database:\n\n";
            $sql = "SELECT l.location_name, s.state FROM migwatch_locations l INNER JOIN migwatch_states s ON l.state_id=s.state_id WHERE l.location_id=$loc_id";
            $result = mysql_query($sql);
            if($result){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $loc_name = stripslashes($row{'location_name'});
                    $state = stripslashes($row{'state'});
                }
            }
            $body = $body . "Location: $loc_name\n";
            $body = $body . "State: $state\n";
            if ($user_friend) { $body = $body . "On behalf of ".stripslashes($user_friend)."\n\n"; }
            if($obstart != "null")
                $body = $body . "Start of Observation: ".str_replace("'","",$obst)."\n";
            if($often)
                $body = $body . "Frequency with which you look for birds at this location: $often\n\n";

            $body = $body . "\n\nBird Observations:\n\n";
            $body .= $email_body_species_section;

            if ($other_notes) {$body = $body . "\nOther notes = ".stripslashes($other_notes)."\n\n";}
            $body .= "\n\nIf you have not submitted this information, or if there is an error,\n please let us know immediately by replying to this email.\n";
            $body = $body .  "\nAll dates are in the format yyyy-mm-dd.";

            $headers = "From: migrantwatch@ncbs.res.in\r\n" .
                "X-Mailer: php\r\n" .
                "Cc: anushshetty@gmail.com";
            //if (mail($to, $subject, $body, $headers)) {
            if (true) {
                //header("Location:test.php");
                foreach($_POST as $key=>$value) {
                    unset($_SESSION[$key]);
                }
                exit();
            } else
                $message = "The data has been submitted, but there was a problem sending you a confirmation email.";
        }
    }
/*

    if ($cmd == 'newmylocation') {

        $loc_id    = (trim($_POST['location']) != '') ? (int) $_POST['location'] : -1;
        $user_id   = $_SESSION['userid'];

        if($loc_id != -1) {
            $locations = getAllMyLocationData($user_id, $connect);
            $mylocations = array();
            foreach($locations as $location) {
                $mylocations[] = $location['location_id'];
            }
            if(in_array($loc_id, $mylocations)) {
                header('location: mylocations.php?error=1&duplicate=1');
                exit;
            } else {
                $sql = "SELECT location_id FROM migwatch_locations l WHERE location_id=$loc_id";
                $result = mysql_query($sql);
                if(mysql_num_rows($result) > 0) {
                    $sql = "INSERT INTO migwatch_user_locs SET " .
                        "user_id = '".$user_id."',".
                        "location_id = '".$_POST['location']."'";

                    $res = mysql_query($sql,$connect);

                    if (mysql_affected_rows() > 0) {
                        header('Location: mylocations.php?extlocadded=1');
                        exit;
                    }
                } else {
                    header('location: mylocations.php?error=1');
                    exit;
                }
            }
        } else {
            header('location: mylocations.php?error=1');
            exit;
        }
    }
*/
    //print ($sql."<br>");
    print ("Error : ".$message);
    if(isset($_SESSION['userid'])) {
        $referer = "<a href='process_details.php?cmd=logout'>Logout</a>";
    } else {
        $referer = "<a href='login.php'>Login</a>";
    }
?>
<li><?php print $referer ?></li>
<li><a href="main.php">Go to the main page</a></li>
