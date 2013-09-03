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

     	<div class='sidebar_<? echo $type; ?> sidebar'>
       	     <h4 class='sidebar-header'>do more</h4>
	      <!--<a class="thickbox" href="userfriend.php?&height=400&width=600"></a>
		<div id="other_name_box_<? echo $type; ?>" style="height:45px">
		     <div id="other_name_update_<? echo $type; ?>"></div>
                
		</div>
	
 		<script type="text/javascript">
        		var loc_details  = '';
      			function update_others_<? echo $type; ?>(){
                        	 var other_name = $('#other_name_<? echo $type; ?>').val();
         			 if(other_name) {
			       	 	var other_name_link = '<div style="background-color:#fff;text-align:right">' + 
		'See your earlier sightings<br>for <a class="thickbox" id="user_friend_href_<? echo $type; ?>" href="">' + other_name + '</a></div>';
                                 

            		        	$('#other_name_update_<? echo $type; ?>').html(other_name_link);
                                        $("#user_friend_href_<? echo $type; ?>" ).attr({ 
                                            href: 'userfriend.php?name=' + other_name + '&sighting=<? echo $type;?>&height=400&width=600',
                                            title: 'Your sightings',
					    class: 'thickbox',
                                         });                                        

         	        	} else {
                 	       	       $('#other_name_update_<? echo $type; ?>').empty();
                 
				}
      			}
        	</script>-->
                 <table style="margin-top:40px"><tr><td><hr></td></tr></table>
   		<table  id="locDetails_<? echo $type; ?>" class="addsighting_loc">
		
		<tr>
			<td style='text-align:right'><div id='your_location_<? echo $type; ?>'></div>
			<script>
			    
			    var your_sightings = "see all your <? echo $type; ?> sightings from <br><a id='your_loc_link_<? echo $type; ?>' href='' title='Your earlier sightings' class='thickbox'><span class='details_loc_<? echo $type; ?>'></span></a>";
                            $('#your_location_<? echo $type; ?>').html(your_sightings);
                           
                        </script>
			</td>
		</tr>
	
        	<tr>
			<td style='text-align:right;padding-top:15px'>
			    see other participant' sightings from <a class='thickbox' id='parti_loc_link_<? echo $type; ?>' href='#'><span class="details_loc_<? echo $type; ?>"></span></a>
			</td>


		</tr>
	 	</table>
		<table>
		<tr>
<td style='text-align:right'><a class='thickbox' href='species_popout.php?height=500&width=600' title='add a caption to title attribute / or leave blank'>See all MigrantWatch species</a></td>
		</tr>
  		</table>
                
	</div>
	<div id="form_<? echo $type; ?>">
     	    <form id="sighting_<? echo $type; ?>" method='post' action='savesighting.php'>
     	   	<!--<input type='hidden' name='last' value='<?php echo $last; ?>' />-->
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
					<input type='hidden' name='last' value='<?php echo $last; ?>' />
					<span><input type="button" style='height:28px' value="X" onclick="$('#locDetails_<? echo $type; ?>').hide(); document.getElementById('addCurrentLocation_<? echo $type; ?>').value = 'Type a location'; 
					document.getElementById('location_<? echo $type; ?>').value = '';
					document.getElementById('often_<? echo $type; ?>').value = '';
					document.getElementById('obstart_<? echo $type; ?>').value = '';
					 "> </span>
				</td>
	   		</tr>
	   		<tr>
				<td>
					<a href="addnewlocation.php?sighting=<? echo $type; ?>&height=550&width=800&TB_iframe=true" class="thickbox" title="Add a new location" style='font-size:13px;'>Add a new location</a>                                              
		    			<input type="hidden" name="location" id="location_<? echo $type; ?>" value="" />
				<td>
	  		</tr>
       	  		<tr>
				<td style='width:155px'>
				    Add notes about this location<br>(eg, habitat, disturbance, etc.)
				</td>
       				<td style='width:400px'>
		    		    <textarea id="loc_notes_<? echo $type; ?>" name="other_notes" class="editbox" style="width:380px;height:50px;">enter notes about the location</textarea>
                		</td>
         		</tr>
			<tr>
				<td style='width:155px'>
				    When did you <?php print(strtoupper($start)); ?> looking for birds at this location?
				</td>
				<td style='width:400px'>
				    <input type=text id="obstart_<? echo $type; ?>" readonly="readonly" name=obstart size="30" style=''>&nbsp;(dd-mm-yyyy)*
				</td>
			</tr>
			<tr>
				<td style='width:155px'>How often <?php echo $look; ?> for birds at this location?</td>
				<td style='width:380px'>
		    		    <select name="often" id="often_<? echo $type; ?>">
		    	    		<option value="">--Select--</option>
			    		<option value="Daily" <?php print($lastData[often]=="Daily" ? " selected " : "") ?>>Daily</option>
			    		<option value="Weekly" <?php print($lastData[often]=="Weekly" ? " selected " : "") ?>>Weekly</option>
			    		<option value="Fortnightly" <?php print($lastData[often]=="Fortnightly" ? " selected " : "") ?>>Fortnightly</option>
			    		<option value="Monthly" <?php print($lastData[often]=="Monthly" ? " selected " : "") ?>>Monthly</option>
			    		<option value="Irregular" <?php print($lastData[often]=="Irregular" ? " selected " : "") ?>>Irregular</option>
			   <option value="First visit" <?php print($lastData[often]=="First visit" ? " selected " : "") ?>>This is my first visit</option>
		     		    </select>
				 </td>
			</tr>	
			</table>
		<!--</div>
 		<div class='container' class='species-row'>-->

              <table id='catTable_<? echo $type; ?>' style="width:900px;margin-left:20px;font-size:11px" class="species_table">
		    
                     <tr><td colspan='6'><hr></td></tr>
		     <tr>		
		     	<td>Species name</td>
			<td>Date</td>
			<td>Number</td>
			<td>Accuracy of count</td>
			<td>Notes on this Record</td>
 			<td>Sighting type</td>
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
			<input type='text' id='species_<? echo $type; ?>_<?php echo $i; ?>' name='species[]' size='20' value='<? echo $speciesHintText; ?>'>
			
		</td>
		<td>
			<input style='font-size:11px' type='text' id='obdate_<? echo $type; ?>_<?php echo $i; ?>' name='obdate[]' value='<? echo $dateHintText; ?>' readonly='readonly' size='8'>
      		</td>
		<td>
			<input type="text" style='font-size:11px' id='number_<? echo $type; ?>_<?php echo $i; ?>' name='number[]' size="8" value='<? echo $numberHintText; ?>'>
		</td>
		<td>
			<select style='width:120px' id= 'accuracy_<? echo $type; ?>_<?php echo $i; ?>' name='accuracy[]'>
				<option value=''>-- SELECT --</option>
				<option value='exact' <?php if(isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'exact') echo 'selected' ?>>Exactly</option>
				<option value='approximate' <?php if (isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'approximate') echo 'selected' ?>>Approximately</option>
			</select>
		</td>
		<td><textarea id='entry_notes_<? echo $type; ?>_<?php echo $i; ?>' name='entry_notes[]' style='width:180px'><? echo $notesHintText; ?></textarea>
		</td>
		<td>
			<select style='width:120px' id= 'sighting_type_<? echo $type; ?>_<?php echo $i; ?>' name='sighting_type[]'>
				<option value=''>-- SELECT --</option>
				<option value='seen'>Seen</option>
				<option value='heard'>Heard</option>
				<option value='both'>Seen and heard</option>
			
			</select>
		</td>
	</tr>
	
	<?php
			
		}
	?>
	</table><!--<table>
        <tr>
	
        <td><span><a href='#x' style='font-size:13px' onclick="appendNewInput('<? echo $type; ?>');">Add another species</a></td>
        <td><input type="submit" value= "Submit"  class="buttonstyle" onclick=""></td></tr>
<input type="hidden" name="cmd" value="sighting_<? echo $type; ?>" id='cmd'/>
	</table>
</form>-->
	<table style='width:930px; margin-left:20px'>
	<tr>
		<td>
			<a name='x'></a>
		        <input type="hidden" name="last" value="<? echo $last; ?>"/>
			<input type="hidden" name="cmd" value="sighting_<? echo $type; ?>" id='cmd'/>
	
		 </td>
    	</tr>
    	<tr>
		<td><span><a href='#x' style='font-size:13px' onclick="appendNewInput('<? echo $type; ?>');">Add another species</a></td>
	</tr>
	<tr>
	<td><input type="submit" value= "Submit"  class="buttonstyle" onclick="return validate('<? echo $type; ?>');"></td></tr>
	</table>
</form>

</div>
</div>

<? } ?>



