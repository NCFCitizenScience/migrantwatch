<?php session_start();
	if(!isset($_SESSION['userid'])){
		echo "<div class='error1'>Looks like your session has expired. Please login back</div>";
		exit();
	}

	include('db.php');
	include('functions.php');
	echo "<html><head>";
	include('main_includes_thickbox.php');
?>
<link type="text/css" href="js/CalendarControl.css" rel="stylesheet" />
<script type="text/javascript" src="js/CalendarControl.js"></script>
<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery/jquery.form.js"></script>
<script src="js/jquery/ac/jquery.autocomplete.js" language="javascript"></script>
<script src="js/jquery/ac/jquery.bgiframe.min.js" language="javascript"></script>
<link href="js/jquery/ac/jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="js/jquery/jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>

<?
	$sql = "SELECT s.common_name, s.alternative_english_names, s.scientific_name, l.location_name, ";
	$sql.= "l.city, st.state, l1.number, l1.accuracy, l1.entry_notes, l1.sighting_date, l1.species_id, l1.sighting_type,l1.flight_dir,";
	$sql.= "l1.location_id, l1.obs_start, l1.frequency, l1.notes, l1.user_friend, l1.entered_on, l1.obs_type ";
	$sql.= "FROM migwatch_l1 l1 INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
	$sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
	$sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
	$sql.= "WHERE l1.user_id = ".$_SESSION['userid']." AND l1.id=".$_GET['id']." ORDER BY sighting_date DESC";
	$sql;
	$result = mysql_query($sql);
	if($result){
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$sdt = date("d-m-Y",strtotime($row{'sighting_date'}));
			$species_name = $row{'common_name'};
			$species_id = $row{'species_id'};
			$location_id = $row{'location_id'};
			$obstart = date("d-m-Y",strtotime($row{'obs_start'}));
			$often = $row{'frequency'};
			$user_friend = $row{'user_friend'};
			$notes = $row{'notes'};
			$often = $row['frequency'];
			$loc = $row{'location_name'}." - ".$row{'city'}." - ".$row{'state'};
			$obs_type = $row['obs_type'];
			$number = $row['number'];
			$alternative_eng_names = $row['alternative_english_names'];
			$scientific_name = $row['scientific_name'];
			$number = empty($row['number']) ? '' : $row['number'];
			$accuracy = $row['accuracy'];
			$entry_notes = $row['entry_notes'];
			$sighting_type = $row['sighting_type'];
			$flight_dir = $row['flight_dir'];
		}


	}

        $sql2="select resident_check from piedcuckoo_migwatch where sighting_id=".$_GET['id'];
        $result2=mysql_query($sql2);
        while($data = mysql_fetch_assoc($result2)) {
            $checked = $data['resident_check'];
        }
?>

<style type="text/css">
table  td:first-child {
     text-align:right;
     font-weight:bold;
     width:120px;
  }
table td + td { width:110px; }
table td+td+td {
      text-align:right;
      font-weight:bold;
      width:60px;        
}
.error { color: red; }
table td+td+td+td {
      text-align:left;
      margin-right:40px;
      width:130px;
}

table td+td+td+td select {
      width:70%
}

table select { width:150px; }
</style>
<script type="text/javascript">

$(document).ready(function() { 
   $('.pied_cuckoo_spec').hide();
   var species_id = <? echo $species_id; ?>;
   if( species_id == '21' ) {
       $('.pied_cuckoo_spec').show();
   }
   $("#editsightings").validate({      
        rules: { 
            species_name: { required: true },
	    location_name: { required: true },
	     obstart: { required: true },
	     obs_type: { required: true },
	     often: { required: true },
	     dt: { required: true },
	     number: { digits: true },
	     accuracy: { 
	        required : function(element) {
                     return $('#number').val() != '';
                }
             }
				
        },
	
	messages: {
           species_name: "<br><div class='error'>Please enter a species name</div>",
           location_name: "<br><div class='error'>Please select a location name</div>",
	   obstart: "<br><div class='error'>Please select a date</div>",
	   obs_type: "<br><div class='error'>Please select a sighting type</div>",
	   often: "<br><div class='error'>Please choose how often you visit this location</div>",
	   dt : { required: "<br><div class='error'>Please choose a date</div>"},
	   number: {
	   	   required: "<br><div class='error'>Please enter the number of species</div>",
		   digits: "<br><div class='error'>Please enter only digits</div>"
           },
	   accuracy: { required: "<br><div class='error'>Please enter the accuracy</div>" }
	   
	}
    });



    $("#editLocation").autocomplete("edit_miglocations.php", {
            width: 250,
                selectFirst: false,
                mustMatch:true
    });

    $("#species").autocomplete("autocomplete.php", {
                 width: 200,
                        selectFirst: false,
                        //matchSubset :0,
                        extraParams : {all : 1},
			mustMatch:true
     });

     $("#species").result(function(event, data, formatted) {
           $('#species_id').val(data[1]);
	   if(data[1] == '21' ) {
	     $('.pied_cuckoo_spec').show();
	   } else { 
	     $('.pied_cuckoo_spec').hide();
     	   }
      });

      /* $(".sdate").datepicker({dateFormat: 'dd-mm-yy'});      */
      $("#editLocation").result(function(event, data, formatted) {
	$('#location_id').val(data[1]);
     });

    function checkForm () {
         if (!$('#editsightings').valid()) {
           return false;
         } else {
           return true;
         }
     }    

    $('#editsightings').ajaxForm({ 
        target: '#updateSightingTarget',
        beforeSubmit: checkForm,
	iframe: true,
        success: function() {
	    $('#updateSightingTarget').fadeIn('slow');
        }
    });
    
 });


    



</script>


</head><body>


	<!----------SIGHTING DETAIL START--------------------->
<table class="editlevel" style="700px">
			
			<tr><td><div id="updateSightingTarget"></div></td></tr>
     </table>
		<form name="frm_editlevel1" id="editsightings" method=post action="updatesighting.php">
	
				<table style="">
				<tr bgcolor=#ffffff><td>Species</td>
				<td colspan='3'><input type="text" style="width:480px" id="species" name="species_name" value="<?php  print $species_name; ?>">	
				<input id="species_id" type="hidden" name="species_id" value="<? echo $species_id; ?>">
</td></tr>
				 <input type="hidden" name="id" value="<? echo $_GET['id']; ?>">
 
				<tr bgcolor=#ffffff><td>Location</td><td colspan='3'>
					<input type="text" style="width:480px" id="editLocation" name="location_name" value="<?php print $loc; ?>">
					<input type="hidden" id="location_id" name="location_id" value="<? echo $location_id; ?>">
				</td></tr>
				<tr bgcolor=#ffffff>
				<td>Observation <?php echo ($obs_type == 'first' || $obs_type == 'general') ? 'Start' : 'End' ; ?> date</td>
					<td>
			<input type=text class="startDate sdate" id="obstart" onclick="showCalendarControl(obstart);"  name=obstart value="<?php print $obstart; ?>" readonly="readonly" style='width:150px'>
				</td><td>
				Observation type</td><td>
        			<select name="obs_type" style="">
                                                <option value="" >Select</option>
                                                <option value="first" <?php print($obs_type == "first" ? " selected " : "") ?>>First</option>
						<option value="general" <?php print($obs_type == "general" ? " selected " : "") ?>>General</option>
                                                <option value="last" <?php print($obs_type == "last" ? " selected " : "") ?>>Last</option>										</select>
				</td></tr>
				<tr bgcolor=#ffffff><td>Frequency of bird watching</td><td>
					<select name="often" style='width:150px'>
						<option value="" >Select</option>
						<option value="Daily" <?php print($often=="Daily" ? " selected " : "") ?>>Daily</option>
						<option value="Weekly" <?php print($often=="Weekly" ? " selected " : "") ?>>Weekly</option>
						<option value="Fortnightly" <?php print($often=="Fortnightly" ? " selected " : "") ?>>Fortnightly</option>
						<option value="Monthly" <?php print($often=="Monthly" ? " selected " : "") ?>>Monthly</option>
						<option value="Irregular" <?php print($often=="Irregular" ? " selected " : "") ?>>Irregular</option>
						<option value="First visit" <?php print($often=="First visit" ? " selected " : "") ?>>This is my first visit</option>
					</select>
				</td></tr>
				<tr><td>Date of <?php echo ucfirst($obs_type); ?> sighting</td><td>
			<input class='sdate' type=text class="endDate" id="obdate" onclick="showCalendarControl(obdate);" name=dt readonly="readonly" value="<?php print $sdt; ?>" style='width:150px'> 
				</td></tr>
				<tr bgcolor=#ffffff><td>Number</td><td>
					<input type=text name="number" id="number" value="<?php print $number; ?>" style="width:150px">
				</td><!--</tr>
				<tr bgcolor=#ffffff>--><td>Accuracy</td><td>
					<select name="accuracy" style="">
						<option value="" <?php print($accuracy=="" ? " selected " : "") ?>>--SELECT--</option>
						<option value="exact" <?php print($accuracy=="exact" ? " selected " : "") ?>>Exactly</option>
						<option value="approximate" <?php print($accuracy=="approximate" ? " selected " : "") ?>>Approximately</option>
					</select>
				</td></tr>
				<tr><td>Notes on this Record</td>
				    <td colspan=3>
					<textarea name="entry_notes" id="entry_notes" class="editbox" style="width:500px;height:50px;"><?php print htmlentities($entry_notes); ?></textarea>
				</td></tr>
				<tr bgcolor=#ffffff><td>Reported on behalf of</td><td>	
					<input type=text name="other_name" value="<?php print htmlentities($user_friend); ?>" style="width:150px">
				</td></tr>     
				<tr><td>General notes about this location</td><td colspan=3>
					<textarea name="other_notes" class="editbox" style="width:500px; height:50px"><?php print $notes; ?></textarea>
				</td></tr>
		 
				<tr bgcolor=#ffffff><td>Sighting Type</td><td>
					<select  name="sighting_type">
						<option value="" <?php print($sighting_type =="" ? " selected " : "") ?>>--SELECT--</option>
						<option value="saw" <?php print($sighting_type =="seen" ? " selected " : "") ?>>Saw</option>
						<option value="heard" <?php print($sighting_type == "heard" ? " selected " : "") ?>>Heard</option>
						<option value="both" <?php print($sighting_type == "both" ? " selected " : "") ?>>Saw & Heard</option>
					</select> 
				</td></tr>
				<tr class='pied_cuckoo_spec' bgcolor=#ffffff><td>Flight Direction</td><td>
					<select name="flight_dir">
<option value="" <?php print($flight_dir =="" ? " selected " : "") ?>>--SELECT--</option>
<option value='S-N' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'S-N') echo 'selected' ?>>S-N</option>        
<option value='SE-NW' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'SE-NW') echo 'selected' ?>>SE-NW</option>   
<option value='E-W' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'E-W') echo 'selected' ?>>E-W</option>         
<option value='NE-SW' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'NE-SW') echo 'selected' ?>>NE-SW</option>   
<option value='N-S' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'N-S') echo 'selected' ?>>N-S</option>         
<option value='NW-S' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'NW-S') echo 'selected' ?>>NW-S</option>        
<option value='W-E' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'W-E') echo 'selected' ?>>W-E</option>          
<option value='SW-NE' <?php if(isset($lastData['flight_dir'][$j]) && $lastData['flight_dir'][$j] == 'SW-NE') echo 'selected' ?>>SW-NE</option> 
					</select> 
				</td></tr>
				
				   <tr class='pied_cuckoo_spec'><td>Please check this box if you have seen Pied Cuckoos at your location between Dec and Mar in any year.</td>
				   <td><input name="resident_check" value=1 type=checkbox <? if($checked == '1'){ ?> checked = "<? echo $checked; }?>"><td></tr>
			
				<tr bgcolor=#ffffff><td colspan=2 style='text-align:center'>
				<input type=submit value="Update sighting" style='width:200px' name="updatesighting">
				</td></tr>
				</table>
			
		</form>

</body></html>
