<? function addSighting($type,$look,$start,$sighting,$before,$last) { 

   	$speciesHintText = 'Type a name here';
	$dateHintText = 'Select';
	$numberHintText = 'In numerals';
	$notesHintText = 'Enter notes here';
?>
   <script type="text/javascript">

	var obstartHint = 'Select a date';
	var speciesHintText = '<? echo $speciesHintText; ?>';
	var dateHintText = '<? echo $dateHintText; ?>';
	var numberHintText = '<? echo $numberHintText; ?>';
	var notesHintText = '<? echo $notesHintText; ?>';

   </script>
	<div id="sightingTarget_<? echo $type; ?>"></div>
	<div id="full_box_<? echo $type; ?>">    

     	<div class='sidebar_<? echo $type; ?> sidebar' style=''>
       	     <h4 class='sidebar-header'>do more</h4>                
	     <div id="other_name_box_<? echo $type; ?>" style="text-align:right;padding-top:10px;min-height:23px">
                     <a id="ulink_<? echo $type; ?>" class="thickbox"  href="userfriend.php?&TB_iframe=true&height=400&width=600">See all your sightings for <span id="other_name_update_<? echo $type; ?>"></span></a>
		</div>
		     
 		 <script type="text/javascript">
                        var loc_details  = '';
			 $('#ulink_<? echo $type; ?>').hide();
                        function update_others_<? echo $type; ?>(){
                                 var other_name = $('#other_name_<? echo $type; ?>').val();
                                 if(other_name) {                              
				        $('#ulink_<? echo $type; ?>').show();                                      
                                        $('#other_name_update_<? echo $type; ?>').html(other_name);
                                        $('#name_<? echo $type; ?>').html(other_name);
                                        $("#ulink_<? echo $type; ?>" ).attr({ 
                                          href: 'userfriend.php?name=' + other_name + '&sighting=<? echo $type;?>&TB_iframe=true&height=400&width=600',
                                         
                                           
                                         });                                        

                                } else {
				
                                       $('#other_name_update_<? echo $type; ?>').empty();
				        $('#ulink_<? echo $type; ?>').hide();
                 
                                }
                        }
                </script>
		      <table style=""><tr><td><hr></td></tr></table>
   	     	     <table  id="locDetails_<? echo $type; ?>" class="addsighting_loc">
	     	     <tr>
                        <td style='text-align:right'>
                            see all your <? echo $type; ?> sightings from <br><a href='#'><span class="details_loc_<? echo $type; ?>"></span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align:right;padding-top:15px'>
                            see other participant' sightings from <a href='#'><span class="details_loc_<? echo $type; ?>"></span></a>
                        </td>
                    </tr>
		    </table>
		    <table>
                    <tr>
                        <td style='text-align:right'><a href='#'>See all MigrantWatch species</a></td>
                    </tr>
                    </table>
		</div>
		<div id="form_div_cuckoo">

         
     		<form id="sighting" method='post' action='savesighting.php'>
     	   	      <input type='hidden' name='last' value='0' />
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
					<span><input type="button" style='height:28px' value="X" onclick="$('#locDetails_<? echo $type; ?>').hide(); document.getElementById('addCurrentLocation_<? echo $type; ?>').value = 'Type a location'; document.getElementById('location_<? echo $type; ?>').value = ''; 
					document.getElementById('often_<? echo $type; ?>').value = '';
                                        document.getElementById('obstart_<? echo $type; ?>').value = '';
					"> </span>
				</td>
	   		</tr>
	   		<tr>
				<td>    
					<a href="addnewlocation.php?sighting=<? echo $type; ?>&height=600&width=800&TB_iframe=true" class="thickbox" title="Add a new location" style='font-size:13px;'>Add a new location</a>
                                          
		    			<input type="hidden" name="location" id="location_<? echo $type; ?>" value="" />
	  			</td>
			</tr>
       	  		<tr>
				<td style='width:155px'>Add notes about this location<br>(eg, habitat, disturbance, etc.)</td>
       				<td style='width:400px'>
		    		    <textarea id="loc_notes_<? echo $type; ?>" name="other_notes" class="editbox" style="width:380px;height:50px;"></textarea>
                		</td>
         		</tr>
			<tr>
				<td style='width:155px'>When did you <?php print(strtoupper($start)); ?> looking for birds at this location?</td>
				<td style='width:400px'>
		    		    <input type=text id="obstart_<? echo $type; ?>" readonly="readonly" name=obstart size="30"  value="Select a date">&nbsp;(dd-mm-yyyy)*
				</td>
			</tr>
			<tr>
				<td style='width:155px'>How often <?php echo $look; ?> for birds at this location?</td>
				<td style='width:380px'>
		    		    <select name="often" id="often_<? echo $type; ?>" style="border:solid 1px #777;width:228px;">
		    	    	    	    <option value="">--Select--</option>
			    		    <option value="Daily">Daily</option>
			    		    <option value="Weekly">Weekly</option>
			    		    <option value="Fortnightly">Fortnightly</option>
			    		    <option value="Monthly">Monthly</option>
			    		    <option value="Irregular">Irregular</option>
			    		    <option value="First visit">This is my first visit</option>
		     		    </select>
				 </td>
			</tr>
			</table>
</div>

<div style='margin-left:auto;margin-right:auto'>
       
	<table id='catTable_<? echo $type; ?>' style="width:900px;margin-left:20px;font-size:11px" >
	<tbody>
	<tr><td colspan=6"><hr></td></tr>
	<tr>		
		
		<td style='text-align:center;font-weight:bold'>Date</td>
		<td style='text-align:center;font-weight:bold'>Number</td>
		<td style='text-align:center;font-weight:bold;X'>Accuracy of count</td>
		<td style='text-align:center;font-weight:bold'>Notes on this Record</td>
		<td style='text-align:center;font-weight:bold'>Sighting type</td>
                <td style='text-align:center;font-weight:bold'>Flight direction</td>
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
		<input type='hidden' id='species_<?php echo $i; ?>' name='species[]' value='Pied Cuckoo'>
			<input style='font-size:11px' type='text' id='obdate_<? echo $type; ?>_<?php echo $i; ?>' name='obdate[]' value='<? echo $dateHintText; ?>' readonly='readonly' size='8'>
      		</td>
		<td>
			<input type="text" style='font-size:11px' id='number_<? echo $type; ?>_<?php echo $i; ?>' name='number[]' size="8" value='<? echo $numberHintText; ?>'>
		</td>
		<td>
			<select style='width:120px'  id= 'accuracy_<? echo $type; ?>_<?php echo $i; ?>' name='accuracy[]'>
				<option value=''>-- SELECT --</option>
				<option value='exact' <?php if(isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'exact') echo 'selected' ?>>Exactly</option>
				<option value='approximate' <?php if (isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'approximate') echo 'selected' ?>>Approximately</option>
			</select>
		</td>
		<td>
<textarea style='width:211px' id='entry_notes_<? echo $type; ?>_<?php echo $i; ?>' name='entry_notes[]'><? echo $notesHintText; ?></textarea>
		</td>
		<td>
			<select style='width:100px' id='sighting_type_<? echo $type; ?>_<? echo $i; ?>' name='sighting_type[]'>
				<option value=''>--SELECT--</option>
				<option value='saw'>seen</option>
				<option value='heard'>heard</option>
				<option value='saw_heard'>seen & heard</option>
			</select>

		</td>
			<td>
				<select style='width:180px;' id= 'flight_dir_<? echo $type; ?>_<?php echo $i ?>' name='flight_dir[]'>
             				<option value=''>--SELECT--</option>
             				<option value='S-N'>South -> North</option>
            				<option value='SE-NW'>South-east -> North-west</option>
            				<option value='E-W'>East -> West</option>
           				<option value='NE-SW'>North-east -> South-west</option>         
					<option value='N-S'>North -> South</option>
          				<option value='NW-S'>North-west -> South</option>
           				<option value='W-E'>West -> East</option>
            				<option value='SW-NE'>South-west -> North-east</option>
				</select>
                     </td>

		
	</tr>
	<tr>
	    <td colspan="6" style='text-align:right'><b>Please check this box if you have seen Pied Cuckoos at your location between Dec and Mar in any year.</b>&nbsp;<input type="checkbox" name="resident_check[]" value="1"></td>
        </tr>
	<?php
			if ($i > 1) {

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
			<input type="hidden" name="cmd" value="sighting_cuckoo" id='cmd'/>
	
		 </td>
    </tr>
    <tr>

	</tr>
	<tr>
			<td><input type="submit" value= "Submit"  class="buttonstyle" onclick="return validate('<? echo $type; ?>');"></td>
	</tr>
	</table>
</form>
</div>
</div>
<? } ?>


