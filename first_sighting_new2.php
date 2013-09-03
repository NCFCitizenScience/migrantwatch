

<div id="gen_form">
	
	<form id="firstsighting" method='post'  name='frm_level1' action='savesighting.php'>
			<input type='hidden' name='last' value='<?php echo $last; ?>' />
			<table border=0 cellpadding=2 cellspacing=1 style='margin-left:20px; margin-top:0; margin-right:auto;width:650px; font-size:11px'> 
			<tr>
				<td style='width:157px; font-size:11px'>
							If you are reporting sightings on behalf<br> of someone else,<br> please enter their name :
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
						<input style="width:388px" type="text" id="addCurrentLocation_<? echo $type; ?>" name="addCurrentLocation" value="Type a location">
						<input type="reset" value="X" onclick="$('#locDetails_<? echo $type; ?>').hide(); document.getElementById('addCurrentLocation_<? echo $type; ?>').disabled = false;"> 
				</td>
			</tr>
			
       	<tr>
                <td style='width:155px'>Add notes about this location<br>(eg, habitat, disturbance, etc.)</td>
       			 <td style='width:400px'>
								<textarea id="loc_notes_<? echo $type; ?>" name="other_notes" class="editbox" style="width:400px;height:50px"></textarea>
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
				<td style='width:400px'>
					<select name="often" id="often_<? echo $type; ?>" style="width:265px;">
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
	<table id='catTable_<? echo $type; ?>' style="width:700px;margin-left:20px;font-size:11px" >
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
			<select style='font-size:11px' id= 'accuracy_<? echo $type; ?>_<?php echo $i; ?>' name='accuracy[]'>
				<option value=''>-- SELECT --</option>
				<option value='exact' <?php if(isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'exact') echo 'selected' ?>>Exactly</option>
				<option value='approximate' <?php if (isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'approximate') echo 'selected' ?>>Approximately</option>
			</select>
		</td>
		<td><input style='font-size:11px' type="text" id='entry_notes_<? echo $type; ?>_<?php echo $i; ?>' name='entry_notes[]' size="28" value='<? echo $notesHintText; ?>' style='width:211px'>
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
	                maxHeight: 100,
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
			<td><span><a href='#x' onclick='appendNewInput_<? echo $type; ?>();'>Add another species</a></td>
	</tr>
	<tr>
			<td><input type='submit' value= "Submit"  class='buttonstyle' onclick="return validate();"></td>
	</tr>
	</table>
</form>
</div>

