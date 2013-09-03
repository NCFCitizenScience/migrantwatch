<?php 
	include('page_includes_js.php');
	
	include('db.php');

	$user_id = $_SESSION['userid'];

	$last = 0;
	$start = 'start';
	$look = 'do you look';
	$sighting = 'First';
	$after = 'before';
   	$type = 'first';

	//  The current season..
	$today = getdate();
	$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;

	if(isset($_GET['state']) && isset($_GET['loc'])) {
		$_SESSION['location'] = $_GET['loc'];
	}

	$speciesHintText = 'Type a name here';
	$dateHintText = 'Select';
	$numberHintText = 'In numerals';
	$notesHintText = 'Enter notes here';

?>
<style>

  /* jqModal base Styling courtesy of;
     Brice Burgess <bhb@iceburg.net> */

  /* The Window's CSS z-index value is respected (takes priority). If none is supplied,
  the Window's z-index value will be set to 3000 by default (in jqModal.js). You
  can change this value by either;
    a) supplying one via CSS
    b) passing the "zIndex" parameter. E.g.  (window).jqm({zIndex: 500}); */
  
.jqmWindow {
    display: none;
    
    position: fixed;
    top: 17%;
    left: 40%;
    
      /*margin-left: -300px; */
    width: 600px;
    
      background-color: #EEE;
    color: #333;
    border: 1px solid black;
    padding: 12px;
  }

.jqmOverlay { background-color: #000; }

/* Fixed posistioning emulation for IE6
     Star selector used to hide definition from browsers other than IE6
     For valid CSS, use a conditional include instead */
* html .jqmWindow {
position: absolute;
top: expression((document.documentElement.scrollTop || document.body.scrollTop) + Math.round(17 * (document.documentElement.offsetHeight || document.body.clientHeight) / 100) + 'px');
}

</style>
<script type="text/javascript">

var obstartHint = 'Select a date';
var speciesHintText = '<? echo $speciesHintText; ?>';
var dateHintText = '<? echo $dateHintText; ?>';
var numberHintText = '<? echo $numberHintText; ?>';
var notesHintText = '<? echo $notesHintText; ?>';

function appendNewInput_<? echo $type; ?>()
	{
	  var tbl = $('#catTable_<? echo $type; ?>');
	  var lastRow = $('#catTable_<? echo $type; ?> tr').length;

	  // if there's no header row in the table, then iteration = lastRow + 1
	  var iteration = lastRow;

	  var newElement =
		"<tr>"			
	   + "<td width='150px'>"
   + "<input type='text' id='species_<? echo $type; ?>_" + iteration +"' name='species[]' style='font-size:11px' size='20' value='<? echo $speciesHintText; ?>'></td>"
		 + "<td>"
       + "<input type='text' style='font-size:11px' id='obdate_<? echo $type; ?>_" + iteration + "' name='obdate[]' readonly='readonly' value='<? echo $dateHintText; ?>' onclick='showCalendarControl(obdate_<? echo $type; ?>_" + iteration + ");' size='8'>" + "</td>"
		+ "<td><input style='font-size:11px' type='text' id='number_<? echo $type; ?>_" + iteration +"' name='number[]' size='8' value='<? echo $numberHintText; ?>'></td>"
		+ "<td>"
			+ "<select style='font-size:11px' id= 'accuracy_<? echo $type; ?>_" + iteration + "' name='accuracy[]'>"
			+ "<option value=''>-- SELECT --</option>"
			+ "<option value='exact'>Exactly</option>"
			+ "<option value='approximate'>Approximately</option>"
			+ "</select>"
		+ "</td>"
		+ "<td><input style='font-size:11px' type='text' id='entry_notes_<? echo $type; ?>_" + iteration + "' name='entry_notes[]' size='28' style='width:211px' value='<? echo $notesHintText; ?>'></td>"
		"</tr>";

		$('#catTable_<? echo $type; ?>').append(newElement);
	  	$("#species_<? echo $type; ?>_" + iteration).autocomplete("autocomplete.php", {
			width: 200,
			selectFirst: false,
			matchSubset :0,
			extraParams : {all : 1},
			//formatItem:formatItem
	  	});

      
		
	  $("#species_<? echo $type; ?>_" + iteration).emptyonclick(); 
     $("#obdate_<? echo $type; ?>_" + iteration).emptyonclick(); 
     $("#number_<? echo $type; ?>_" + iteration).emptyonclick(); 
     $("#entry_notes_<? echo $type; ?>_" + iteration).emptyonclick(); 
     $("#entry_notes_<? echo $type; ?>_" + iteration).autogrow({  maxHeight: 180,
        lineHeight: 16 });
		var id = "species_<? echo $type; ?>_" + iteration;
		

	}



function validate_<? echo $type; ?>() {
	 	 
		var last = <?php print($last); ?>;
		var now = new Date();
		
		var obstart = document.frm_level1.obstart.value;
		var tbl = $('#catTable_<? echo $type; ?>');
		var lastRow = $('#catTable_<? echo $type; ?> tr').length - 1;
		

		if(document.frm_level1.location.value == "") { 
			//alert("Please select a valid location for the Observations.");
                        jAlert('Please select a valid location for the Observations.', 'Alert Dialog');
                        
			return false;
		}

      		if((obstart == "") || (obstart == obstartHint)) {
			jAlert("Please enter the date when you started looking for birds at this location \n(in the current migration season)");
			return false;
      		} 

      
		/*if(compareDates(obstart, "dd-MM-yyyy", formatDate(now, "dd-MM-yyyy"), "dd-MM-yyyy") == 1) {
			jAlert("The Start/end date of observation cannot be a future date");
			return false;
      		} 

      		if (document.frm_level1.often.value == "") {
			alert("Please select How often you look for birds at this location?");
			return false;
      		}*/

       
                //alert(lastRow); exit();
		for(var i = 1; i <= lastRow; i++) {
                      

			if( ( $('#species_<? echo $type; ?>_' + i).val() == '' ) || ( $('#species_<? echo $type; ?>_' + i).val() == speciesHintText ) ){
              		    jAlert('Please enter the species name for entry no. ' + i );
	           	    $('#species_<? echo $type; ?>_' + i).focus();
              		    return false;

           		} 

           		if( ( $('#obdate_<? echo $type; ?>_' + i).val() == '' ) || ( $('#obdate_<? echo $type; ?>_' + i).val() == dateHintText ) ){
               	    	    jAlert('Please enter the date of the sighting for entry no. ' + i );
	           	    $('#obdate_<? echo $type; ?>_' + i).focus();
              	   	    return false;

           		} 
					var obdate = $('#obdate_<? echo $type; ?>_' + i).val();
                                        //alert(obdate);
					if(compareDates(obdate,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) {
						jAlert("Sighting dates cannot be future dates ");
						return false;
               				}
			

                          if ($('#species_<? echo $type; ?>_' + i).val() != '' && $('#obdate_<? echo $type; ?>_' + i).val() != '') {
                                var num = $('#number_<? echo $type; ?>_' + i).val();
                                var acc = $('#accuracy_<? echo $type; ?>_' + i).val();
                                
                                
                               if( (num == numberHintText ) || (num != '' )) {
                                 if ( isNaN(num))  {
                                        jAlert('Please enter numerals under \'Number\' for entry no. ' + i);
                                      $('#number__<? echo $type; ?>_' + i).focus();
                                        return false;
                                  }
                              
      			      }
                              
                              	  if ( ( num != numberHintText || num != '' )  && acc == '' ) {
                                        jAlert('Please select the \'Accuracy of count\' for entries where \'Number\' has been entered for entry no. ' + i);
                                        $('#accuracy__<? echo $type; ?>_' + i).focus();
                                        return false;
                                  }
                              

                             }
		           
       }

}
$().ready(function() {
    $('#addCurrentLocation_<? echo $type; ?>').autocomplete("auto_miglocations.php", {
      width: 398,
	   selectFirst: false,
	   matchSubset :true,
	   mustMatch: true,
      matchContains: false,

	  });
    $("#addCurrentLocation_<? echo $type; ?>").result(function(event, data, formatted) {
        
        $('#location_<? echo $type; ?>').val(data[1]);
         if(data[1] == '0' ) { document.getElementById('addCurrentLocation_<? echo $type; ?>').disabled = true; $('#locDetails_<? echo $type; ?>').hide(); $('#dialog').jqm().jqmShow({
          overlay: 70,
          autofire: true
        });  } else { 
        $('#locDetails_<? echo $type; ?>').show();
        $('#addloc_<? echo $type; ?>').hide();
        $('#details_loc_<? echo $type; ?>').html(data[2]);
        $('#details_city_<? echo $type; ?>').html(data[3]);
        $('#details_dist_<? echo $type; ?>').html(data[4]);
        $('#details_state_<? echo $type; ?>').html(data[5]);
        var obs_start_<? echo $type; ?> = data[6];
        obs_start_<? echo $type; ?> = obs_start_<? echo $type; ?>.split('-');
        obsstart_<? echo $type; ?> = obs_start_<? echo $type; ?>[2] + '-' +  obs_start_<? echo $type; ?>[1] + '-' + obs_start_<? echo $type; ?>[0];
        $('#obstart_<? echo $type; ?>').val(obsstart_<? echo $type; ?>);
        document.getElementById('often_<? echo $type; ?>').value = data[7];
        var loc_data_<? echo $type; ?> = data[1];
       
      var table = "<a href=\"prevsightings.php?id=" + loc_data + "\" class=\"thickbox\" title=\"Detailed information of selected issue\">View Previous</a>";

document.getElementById('prev_sightings_<? echo $type; ?>').innerHTML = table;
tb_init('a.thickbox'); // Initialise again

        }

        
      });

     $('#entry_notes_<? echo $type; ?>_1').autogrow({
       maxHeight: 180,
        lineHeight: 16
      });

     
      $('#loc_notes_<? echo $type; ?>').autogrow({
	maxHeight: 180,
        lineHeight: 16
      });

      $('#dialog').jqm();

      $("#loc_info_box_<? echo $type; ?>").hide();

		$("#other_name_<? echo $type; ?>").emptyonclick(); 
      $("#addCurrentLocation_<? echo $type; ?>").emptyonclick(); 
    
      var tbl = $('#catTable_<? echo $type; ?>');

		var lastRow = $('#catTable_<? echo $type; ?> tr').length - 1;
		
      for(var i = 1; i <= lastRow; i++) {
          $("#species_<? echo $type; ?>_" + i).emptyonclick(); 
          $("#obdate_<? echo $type; ?>_" + i).emptyonclick(); 
          $("#number_<? echo $type; ?>_" + i).emptyonclick(); 
          $("#entry_notes_<? echo $type; ?>_" + i).emptyonclick(); 

      }
      
      

      $("#obstart_<? echo $type; ?>").emptyonclick(); 
		
      $('a#loc_ob_box_<? echo $type; ?>').click(function() {
               $('#loc_info_box_<? echo $type; ?>').toggle();
      });

      $('.sidebar').corner();

     // $(":input").css({ border: none; });
  }); 

 
</script>
<style>
input { font-size:11px; padding:2px }

#firstsighting input { font-size:11px;border: solid 1px #777777; padding:5px }

#firstsighting td { font-size:13px; }

select { font-size 11px; border: solid 1px #777777;  }

select option { padding:2px; font-size:11px; }
textarea { font-size:11px;border: solid 1px #777777; padding:5px }
</style>
<div id="firstsightingTarget"></div>
<div class='sidebar box' style='width:200px;float:right;margin-top:-30px;padding-left:10px;margin-right:-20px;border:solid 0.25px #777; background-color:#fff;'>
       <h4 style='margin-left:auto;margin-right:auto;text-align:center;color:#d95c15;'>do more</h4>
	<script type="text/javascript">
   	var loc_details  = '';
      function update_others_<? echo $type; ?>(){
			var other_name = document.getElementById('other_name_<? echo $type; ?>').value;
         if(other_name) {
            document.getElementById('other_name_update_<? echo $type; ?>').innerHTML = '<div style="background-color:#fff;text-align:right">See your earlier sightings<br>for <a href="#">' + other_name + '</a></div>';
         } else {
	         $('#other_name_update_<? echo $type; ?>').empty();
		 
         }
      }
	</script>
	<div id="other_name_box_<? echo $type; ?>" style="height:45px"><div id="other_name_update_<? echo $type; ?>"></div></div><hr>
   <table  id="locDetails_<? echo $type; ?>" style="height:282px;background-color:#fff; text-align:right;display:none;">
	   <tr width="100%"><td style="text-align:center;"><strong>Location Information</strong></td></tr>
		<tr>
				<td style="text-align:right">
				    <div id="details_loc_<? echo $type; ?>">, </div>
				    <!--<div id="details_city_<? echo $type; ?>"></div>,<br>
                     		    <div id="details_dist_<? echo $type; ?>"></div>-->
                     		    <div id="details_state_<? echo $type; ?>"><div>
				</td>
	</tr>
	<tr>
		<td style='text-align:right'><a href='#'>You last sighting from this location</a></td>
	</tr>
        <tr>
		<td style='text-align:right'><a	href='#'>See other participants' sightings from	this location</a></td>
	</tr>
	<tr><td><hr></td></tr>
        
  </table>
  <table>
	<tr><td style='text-align:right'><a href='#'>See all MigrantWatch species</a></td></tr>
  </table>
</div>


<div id="gen_form">
	
	<form id="firstsighting" method='post'  name='frm_level1' action='savesighting.php'>
			<input type='hidden' name='last' value='<?php echo $last; ?>' />
			<table border=0 cellpadding=2 cellspacing=1 style='margin-left:20px; margin-top:0; margin-right:auto;width:650px; font-size:11px'> 
			<tr>
				<td style='width:180px;'>
							If you are reporting sightings on behalf of someone else,<br> please enter their name :
				</td>
				<td style='width:400px;margin-left:-20px'>
<input size="30" type=text id="other_name_<? echo $type; ?>" name="other_name" value="Name" style="" onKeyUp="update_others_<? echo $type; ?>();" onKeyDown="update_others_<? echo $type; ?>();">
				</td>
			</tr>
			<tr>
				<td colspan='2'><hr></td>
			</tr>
			<tr>
	   			<td style='width:155px'>Location of sighting</td>
           		<td>
						<input style="width:380px" type="text" id="addCurrentLocation_<? echo $type; ?>" name="addCurrentLocation" value="Type a location">
						<input type="reset" value="X" onclick="$('#locDetails_<? echo $type; ?>').hide(); document.getElementById('addCurrentLocation_<? echo $type; ?>').disabled = false;"> 
				</td>
			</tr>
			<tr>
					<td><a href="#" style='font-size:14px;' class="jqModal">Add a new Location</a></td>

         			<div class="jqmWindow" id="dialog" style="width:800px">
							<a href="#" class="jqmClose">Close</a>
							<hr>
								<iframe src="maps.php" style="width:750px; height:400px"></iframe>
						</div>
						<input type="hidden" name="location" id="location_<? echo $type; ?>" value="" />
			</tr>
       	<tr>
                <td style='width:155px'>Add notes about this location<br>(eg, habitat, disturbance, etc.)</td>
       			 <td style='width:400px'>
								<textarea id="loc_notes_<? echo $type; ?>" name="other_notes" class="editbox" style="width:380px;height:50px"></textarea>
                </td>
         </tr>
			<tr>
					<td style='width:155px'>When did you <?php print(strtoupper($start)); ?> looking for birds at this location?</td>
					<td style='width:400px'>
					<input type=text id="obstart_<? echo $type; ?>" readonly="readonly" name=obstart size="30" style='' onclick="showCalendarControl(obstart_<? echo $type; ?>);" value="Select a date">&nbsp;(dd-mm-yyyy)*
		 
					</td>
			</tr>
			<tr>
				<td style='width:155px'>How often <?php print($look); ?> for birds at this location?</td>
				<td style='width:380px'>
					<select name="often" id="often_<? echo $type; ?>" style="border:solid 1px #777;width:228px;">
						<option value="">--Select--</option>
						<option value="Daily" <?php print($lastData[often]=="Daily" ? " selected " : "") ?>>Daily</option>
						<option value="Weekly" <?php print($lastData[often]=="Weekly" ? " selected " : "") ?>>Weekly</option>
						<option value="Fortnightly" <?php print($lastData[often]=="Fortnightly" ? " selected " : "") ?>>Fortnightly</option>
						<option value="Monthly" <?php print($lastData[often]=="Monthly" ? " selected " : "") ?>>Monthly</option>
						<option value="Irregular" <?php print($lastData[often]=="Irregular" ? " selected " : "") ?>>Irregular</option>
						<option value="First visit" <?php print($lastData[often]=="First visit" ? " selected " : "") ?>>This is my first visit</option>
					</select> *
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>	
	</table>
	<table id='catTable_<? echo $type; ?>' style="width:600px;margin-left:20px;font-size:11px" >
	<tbody>
	<tr>		
			<td style='text-align:center;font-weight:bold'>Species name</td>
			<td style='text-align:center;font-weight:bold'>Date</td>
			<td style='text-align:center;font-weight:bold'>Number</td>
			<td style='text-align:center;font-weight:bold'>Accuracy of count</td>
			<td style='text-align:center;font-weight:bold'>Notes on this Record</td>
	</tr>
	<?php
		if ($rowCount == 0) {
			$rowCount = 1;
		}

		for($i = 1; $i <= $rowCount; $i++) {
			$j = $i - 1;
	?>
	 <tr id='cloneme'>
		<td>
 			<input type='text' id='species_<? echo $type; ?>_<?php echo $i; ?>' style='font-size:11px' name='species[]' size='20' value='<? echo $speciesHintText; ?>'>
		</td>
		<td>
	<input style='font-size:11px' type='text' id='obdate_<? echo $type; ?>_<?php echo $i; ?>' name='obdate[]' value='<? echo $dateHintText; ?>' readonly='readonly' size='8' onclick="showCalendarControl(obdate_<? echo $type; ?>_<?php echo $i; ?>);">
      </td>
		<td>
			<input type="text" style='font-size:11px' id='number_<? echo $type; ?>_<?php echo $i; ?>' name='number[]' size="8" value='<? echo $numberHintText; ?>'>
		</td>
		<td>
			<select style='font-size:11px;border:solid 1px #777' id= 'accuracy_<? echo $type; ?>_<?php echo $i; ?>' name='accuracy[]'>
				<option value=''>-- SELECT --</option>
				<option value='exact' <?php if(isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'exact') echo 'selected' ?>>Exactly</option>
				<option value='approximate' <?php if (isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'approximate') echo 'selected' ?>>Approximately</option>
			</select>
		</td>
		<td><input style='font-size:11px' type="text" id='entry_notes_<? echo $type; ?>_<?php echo $i; ?>' name='entry_notes[]' size="25" value='<? echo $notesHintText; ?>' style='width:211px'>
		</td>
	</tr>
	<?php
			if ($i > 1) {


				?>

	
                                 <?
				echo <<<JS
				<script language='javascript'>
				  	 $("#species_" + $type + "_" + $i).autocomplete("autocomplete.php", {
						width: 140,
						selectFirst: false,
						matchSubset :0,
					   extraParams : {all : 1},
						//formatItem:formatItem
				  	});
					
					
                                        $('#entry_notes_' + $type + '_' + $i).autogrow({
       maxHeight: 180,
        lineHeight: 16
      });                            

				  	$("#species_" + $type + "_" + $i).result(function(event, data, formatted) {
						if (data[1] == 1) {
							if ($("#entry_notes_" +  $type + "_" + $i).val != '') {
								$("#entry_notes_" +  $type + "_" + $i).val(hintText);
								$("#entry_notes_" +  $type + "_" + $i).addClass(HintClass);
								var id = "entry_notes_" +  $type + "_" + $i;
						$("#entry_notes_" +  $type + "_" + $i).focus( function() { onHintTextboxFocus(id,hintText) } );
					        $("#entry_notes_" +  $type + "_" + $i).blur( function() { onHintTextboxBlur(id,hintText) } );
							}
						} else {
							if ($("#entry_notes_<? echo $type; ?>_" + $i).val() == hintText) {
								$("#entry_notes_" +  $type + "_" + $i).removeClass(HintClass);
								$("#entry_notes_" +  $type + "_" + $i).removeAttr('onFocus');
								$("#entry_notes_" +  $type + "_" + $i).removeAttr('onBlur');
								$("#entry_notes_" +  $type + "_" + $i).val('');
							}
						}
				  	});
				</script>
JS;
			}
		}
	?>
	</table>
	<table style='width:700px; margin-left:20px'>
	<tr>
		<td>
			<a name='x'></a>
        <input type="hidden" name="last" value="0"/>
			<input type="hidden" name="cmd" value="speciessighting" id='cmd'/>
	
		 </td>
    </tr>
    <tr>
			<td><span><a href='#x' style='font-size:14px' onclick='appendNewInput_<? echo $type; ?>();'>Add another species</a></td>
	</tr>
	<tr>
			<td><input type='submit' value= "Submit"  class='buttonstyle' onclick="return validate_<? echo $type; ?>();"></td>
	</tr>
	</table>
</form>
</div>

<script>
$(document).ready(function() {
		$('#species_<? echo $type; ?>_1').autocomplete("autocomplete.php", {
			width: 200,
			selectFirst: false,
			matchSubset :0,
			extraParams : {all : 1},
         mustMatch: true,                       
			//formatItem:formatItem
		});
               
		   });

$(document).ready(function() { 
    // bind form using ajaxForm 
    $('#firstsighting').ajaxForm({ 
        // target identifies the element(s) to update with the server response 
        target: '#firstsightingTarget', 
 
        // success identifies the function to invoke when the server response 
        // has been received; here we apply a fade-in effect to the new content 
        success: function() { 
            $('#firstsightingTarget').fadeIn('slow'); 
        } 
    }); 
});
</script>
