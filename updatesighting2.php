<?
include("auth.php");
if(!isset($_SESSION['userid'])) {

   echo "<div class='notice'>Looks like you are not logged in. You have to login back</div>";
   exit();
}

include("db.php");
include("functions.php");
if ($_REQUEST['updatesighting']){       
   	$validate = array
        (
            'obstart'  => 'isValidDate'
            ,'dt'      => 'isValidDate'
        );


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
	
	$filtered_data['obs_type'] = trim($_POST['obs_type']);
	if( empty($filtered_data['dt'])) {
	    echo "<div class='error1'>Please enter a valid observation date</div>"; exit();
	} else if ( empty( $filtered_data['obstart'] )) {
	      echo "<div class='error1'>Please enter a valid observation start/end date</div>"; exit();
        }

        if($filtered_data['obstart'] != "") {
            //$obst = "'".substr($obstart,6,4)."-".substr($obstart,3,2)."-".substr($obstart,0,2)."'";
            //echo $obs_year = (int)substr($obstart,6,4);
            //echo $obs_month = (int)substr($obstart,3,2);
	    $obst = explode('-',trim($filtered_data['obstart']));
	    $obs_year  = (int)$obst[2];
	    $obs_month = (int)$obst[1];
            if($obs_month >= 7) {
                $seasonStart = $obs_year;
            } else {
                $seasonStart = $obs_year - 1;
            }
            $seasonEnd = $seasonStart + 1;
        } else {
            $obst = "null";
        }
	
	$filtered_data['id'] = trim($_POST['id']);
	$filtered_data['species_id'] = trim($_POST['species_id']);
	$filtered_data['location_id'] = trim($_POST['location_id']);

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

	$filtered_data['sighting_type'] = $_POST['sighting_type'];
	$filtered_data['flight_dir'] = $_POST['flight_dir'];      
	$filtered_data['resident_check'] = $_POST['resident_check'];

         //print_r($filtered_data);

	//$filtered_data = array_merge($_POST,$restpost_data);
        if($filtered_data['number'] == '') $filtered_data['number'] = 0;

	$arr = explode('-',$filtered_data['obstart']);
        $arr2 = explode('-',$filtered_data['dt']);

        $arr = array_reverse($arr);
        $arr2 = array_reverse($arr2);

        $filtered_data['obstart'] = implode('-',$arr);
        $filtered_data['dt'] = implode('-',$arr2);

        $connect2 = $connect;

	$newData = $filtered_data;

                
	$result = mysql_query("SELECT species_id, common_name FROM migwatch_species WHERE active=1");
        $duplicate = false;
        $duplicate_species = array();
        if($result) {
            
                $sightingdate = $filtered_data['dt'];
                if($sightingdate != "") {
                    $sightingdate = substr($sightingdate,6,4)."-".substr($sightingdate,3,2)."-".substr($sightingdate,0,2); 
                    /*Check for duplicate first sighting record*/
                   $sql = "SELECT sighting_date from migwatch_l1 WHERE user_id=".$_SESSION['userid'];
                    $sql .= " AND species_id=".$filtered_data['species_id'];
                    $sql .= " AND deleted != '1'";
                    $sql .= " AND location_id=".$filtered_data['location_id'];
                    $sql .= " AND obs_type='".$filtered_data['obs_type']."'";
		    $sql .= " AND id != '" . $filtered_data['id'] . "'";
		    $sql .= " AND sighting_date='$sightingdate'";
                    $sql .= " AND obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30'";
                    $sql .= " ORDER BY sighting_date DESC";

		    //echo $sql;

		    $result1 = mysql_query($sql);
                    if($result1) {
                        if(mysql_num_rows($result1) == 0) {
			 $updated_by = ", updated_by = '$_SESSION[userid]'";
       			  $sql = "UPDATE migwatch_l1 SET " .
			 "species_id='".$filtered_data['species_id']."'," .
			 "location_id='".$filtered_data['location_id']."'," .
                	 "obs_start='".$filtered_data['obstart']."'," .
                	 "sighting_date='".$filtered_data['dt']."'," .
                	 "frequency = '".$filtered_data['often']."'," .
                	 "user_friend = '".$filtered_data['other_name']."',".
                	 "number = '".$filtered_data['number']."',".
                	 "accuracy = '".$filtered_data['accuracy']."'," .
                	 "entry_notes = '".$filtered_data['entry_notes']."',".
                	 "notes = '".$filtered_data['other_notes']."',".
 			 "obs_type = '".$filtered_data['obs_type']."'," . 
			 "sighting_type = '".$filtered_data['sighting_type']."'," .			 
			 "flight_dir = '".$filtered_data['flight_dir']."'$updated_by WHERE id = '$filtered_data[id]'";

			 echo $sql;

        		 $success = mysql_query($sql,$connect);			 

			 if( $species_id == '21') {
			 $sql1 = "UPDATE piedcuckoo_migwatch SET resident_check='".$filtered_data['resident_check']."'WHERE sighting_id = '$filtered_data[id]'";
        		 mysql_query($sql1,$connect);
        
                         }

			 if( $success ) {
			  echo "<div class='success'>Sighting has been updated</div>"; 
			  
			  ?>
			  <script type="text/javascript">
			 
			  parent.update_sighting_fields("<? echo $_POST['id'];?>", "<? echo $_POST['species_name'];?>", "<? echo $_POST['location_name'];?>","<? echo $filtered_data['dt']; ?>", "<? echo $filtered_data['obs_type']; ?>","<? echo $filtered_data['number']; ?>");
			  parent.editsightingupdate("<div class='notice'>Sighting has been updated successfully</div>") ; 
			  parent.tb_remove();
			  </script>
			  <?

			 }

                    } else {

		          
			  echo "<div class='error1'>There is a duplicate sighting with the same entry</div>";
			  exit();

			}             
		    }
		 }
        }  
}   	

?>
