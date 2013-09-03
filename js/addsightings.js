
function appendNewInput() {	 
	var tbl = $('#catTable');     
	var lastRow = $('#catTable tr').length;
	var iteration = lastRow - 1;
 	var newElement ="<tr>" + "<td width='150px'>"
   		+ "<input type='text' id='species_" + iteration + "' name='species[]' style='font-size:11px' size='20' value='<? echo $speciesHintText; ?>'></td>"
		+ "<td>"
       		+ "<input type='text' class='sighting_date' style='font-size:11px' id='obdate_" + iteration + "' name='obdate[]' readonly='readonly' value='<? echo $dateHintText; ?>' size='8'>" + "</td>"
		+ "<td><input type='text' id='number_" + iteration +"' name='number[]' size='8' value='<? echo $numberHintText; ?>'></td>"
		+ "<td>"
			+ "<select style='width:120px' id= 'accuracy_" + iteration + "' name='accuracy[]'>"
			+ "<option value=''>-- SELECT --</option>"
			+ "<option value='exact'>Exactly</option>"
			+ "<option value='approximate'>Approximately</option>"
			+ "</select>"
		+ "</td>"
		+ "<td><textarea id='entry_notes_" + iteration + "' name='entry_notes[]' size='25' style='font-size:11px; width:180px'><? echo $notesHintText; ?></textarea></td>"

		+ "<td>"
                + "<select style='width:120px' id='sighting_type_" + iteration + "' name='sighting_type[]'>"
                              +  "<option value=''>-- SELECT --</option>"
                              +  "<option value='seen'>Seen</option>"
                              +  "<option value='heard'>Heard</option>"
                              +  "<option value='both'>Seen and heard</option>"
                              + "</select>"
                + "</td>"

		+ "</tr>";

	$('#catTable').append(newElement);
  	$("#species_" + iteration).autocomplete("autocomplete.php", {
		width: 160,
		selectFirst: false,
		matchSubset :0,
		extraParams : {all : 1},
		formatItem:formatItem
	  });
	  
	$("#species_" + iteration).emptyonclick(); 
     	$("#obdate_" + iteration).emptyonclick(); 
 	$("#number_" + iteration).emptyonclick(); 
     	$("#entry_notes_" + iteration).emptyonclick(); 
     	$("#entry_notes_" + iteration).autogrow({  maxHeight: 180,lineHeight: 16 });
	var id = "species_" + iteration;
	$("#obdate_" + iteration).datepicker({dateFormat: 'dd-mm-yy'});		
}


function new_info(from_form) {
  $('#addCurrentLocation').val(from_form);
}


function validate() {
     	var now = new Date(); 
     	var obstart = document.getElementById('obstart').value;
     	var tbl = $('#catTable');
     	var lastRow = $('#catTable tr').length - 1;
     	var location = document.getElementById('location').value; 
	var sigtype = $('#sigtype').val();
	sigtype = sigtype.toLowerCase();
    	if(location == "") { 
      		jAlert('Please select a valid location for the Observations.', 'Alert Dialog');                  
		$('#addCurrentLocation').focus();
		return false;
    	}

     	if((obstart == "") || (obstart == obstartHint)) {
	  	jAlert("Please enter the date when you started looking for birds at this location \n(in the current migration season)");
	  	$('#obstart').focus();
	  	return false;
      	} 
    
	if(compareDates(obstart, "dd-MM-yyyy", formatDate(now, "dd-MM-yyyy"), "dd-MM-yyyy") == 1) {
		jAlert("The Start/end date of observation cannot be a future date");
		$('#obstart').focus();
		return false;
      	} 
 	
	if (document.getElementById('often').value == "") {
		jAlert("Please select how often you look for birds at this location?");
		document.getElementById('often').focus();
		return false;
      	} 
	

	
	for(var i = 1; i < lastRow; i++) { 
 		if ( ( i == 1 ) ||  (  (( $('#species_' + i).val() != '' ) && ( $('#species_' + i).val() != speciesHintText )  ) || ( ( $('#obdate_' + i).val() != '' ) && ( $('#obdate_' + i).val() != dateHintText ) ) )) {
			if( ( $('#species_' + i).val() == '' ) || ( $('#species_' + i).val() == speciesHintText ) ){
              			jAlert('Please enter the species name for entry no. ' + i );
	           	    	$('#species_' + i).focus();
              		    	return false;
			}  
			
          		if( ( $('#obdate_' + i).val() == '' ) || ( $('#obdate_' + i).val() == dateHintText ) ){
               	    		jAlert('Please enter the date of the sighting for entry no. ' + i );
	           	    	$('#obdate_' + i).focus();
              	   	    	return false;
			} 
		        
			var obdate = document.getElementById('obdate_' + i).value;
			if(compareDates(obdate,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) {
		        	jAlert("Sighting dates cannot be future dates ");
			      	return false;
               		}
			
			if( sigtype == 'last') {                       
			   
				if(compareDates(obdate, "dd-MM-yyyy", obstart, "dd-MM-yyyy") == 1) {
                               		jAlert("The LAST Sighting Date cannot be a date after the end of observations");
                            	     	return false;
                           	} 
			} else  {
			        if(compareDates(obstart, "dd-MM-yyyy", obdate, "dd-MM-yyyy") == 1) {
                                     jAlert("The sighting Date cannot be a date after the end of observations");
                                     return false;
        			}
                        } 
			
                        if (  $('#species_' + i).val() != '' && $('#obdate_' + i).val() != '') {
				var num = document.getElementById('number_' + i).value;
                                var acc = document.getElementById('accuracy_' + i).value;
                                var sighting_type = document.getElementById('sighting_type_' + i).value;
                               
                               	if( num != numberHintText ) { 			       	    
                                	if ( isNaN(num)) {
                                       		jAlert('Please enter only numerals under \'Number\'');
                                        	$('#number_' + i).focus();
                                        	return false;
                                     	} 
		               	} 
                           	  
				if ( num != numberHintText &&  num != ''  && acc == '' ) {
                               		jAlert('Please select the \'Accuracy of count\' for entries where \'Number\' has been entered for entry no. ' + i);
                                        $('#accuracy_' + i).focus();
                                        return false;
                               	}
                	}
       		}
       	}
}

function mainForm() {
  String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); }
  $().ready(function() {
	$('#addCurrentLocation').autocomplete("auto_miglocations.php", {
      	   width: 388,
	   selectFirst: false,
	   matchSubset :true,
	   mustMatch: true,
      	   matchContains: false,
	}); 

    	$("#addCurrentLocation").result(function(event, data, formatted) {
        	$('#location').val(data[1]);
  		$("#your_loc_link").attr({ 
          		href: 'yoursightings.php?loc=' + data[1] + '&TB_iframe=true&height=400&width=600',
          		title: 'Your sightings from ' + data[2],
        	});
        	
		$("#all_loc_link").attr({
          		href: 'partisightings.php?loc=' + data[1] + '&TB_iframe=true&height=400&width=600',
          		title: 'Other participants sightings from ' + data[2]
        	});
 
         	if(data[1] == '0' ) {
	        	$('#addCurrentLocation').val(''); 
                        $('#locDetails').hide(); 
                        var url = 'addnewlocation.php?sighting=first&height=600&width=800&TB_iframe=true';
                        tb_show("Add a new location", "addnewlocation.php?&height=600&width=800&TB_iframe=true", "");
		} else { 
        		$('#locDetails').show();
        		$('#addloc').hide();
        		$('.details_loc').html(data[2]);
			
        		$('#details_city').html(data[3]);
        		$('#details_dist').html(data[4]);
        		$('#details_state').html(data[5]);
        
                        var obs_start = data[6];
			if( obs_start != '' ) {
        		obs_start = obs_start.split('-');
        		obsstart = obs_start[2] + '-' +  obs_start[1] + '-' + obs_start[0];
        	        if(obsstart != '') { $('#obstart').val( obsstart); }
			}
        		document.getElementById('often').value = data[7];			
        		var loc_data = data[1];
			var table = "<a href=\"prevsightings.php?id=" + loc_data + "\" class=\"thickbox\" title=\"Detailed information of selected issue\">View Previous</a>";
			document.getElementById('prev_sightings').innerHTML = table; 
				tb_init('a.thickbox'); // Initialise again
            
        	}        
      	}); 
	
	$('#loc_notes').autogrow({
		minHeight: 50,
                maxHeight: 180,
                lineHeight: 16
      	});
    
	$('#add_loc_box').hide();
	$("#loc_info_box").hide();

      	$("#other_name").emptyonclick(); 
      	$("#addCurrentLocation").emptyonclick(); 
      	$("#loc_notes").emptyonclick();
    
      	var tbl = $('#catTable');
	var lastRow = $('#catTable tr').length - 1;
	
      	for(var i = 1; i <= lastRow; i++) {
          $("#species_" + i).emptyonclick(); 
          $("#obdate_" + i).emptyonclick(); 
          $("#number_" + i).emptyonclick(); 
          $("#entry_notes_" + i).emptyonclick(); 
	}
      
      	$("#obstart").emptyonclick(); 
      	$("#obstart").datepicker({dateFormat: 'dd-mm-yy'});		
      	$('a#loc_ob_box').click(function() {
               $('#loc_info_box').toggle();
      	});

      	$('.sidebar').corner();
	
	$('#species_1').autocomplete("autocomplete.php", {
		width: 160,
		selectFirst: false,
		matchSubset :0,
		extraParams : {all : 1},
		formatItem: formatItem,
         	//mustMatch: true
	});

       	$("#obdate_1").datepicker({dateFormat: 'dd-mm-yy'});   
   	$('#entry_notes_1').autogrow({maxHeight: 180,lineHeight: 16});
	   
	$('#sighting').ajaxForm({ 
        	target: '#sightingTarget', 
        	success: function() { 
            		$('#sightingTarget').fadeIn('slow'); 
        	} 
    	}); 

    	$('#addNewLocation').ajaxForm({ 
        	target: '#newLocationTarget',
        	success: function() { 
            		$('#newLocationTarget').fadeIn('slow'); 
        	} 
    	}); 
  }); 
}

function formatItem(row) {
     var completeRow;
     completeRow = new String(row);
     var scinamepos = completeRow.lastIndexOf("(");
     var rest = completeRow.substr(0,scinamepos)
     var sciname = completeRow.substr(scinamepos);
     var commapos = sciname.lastIndexOf(",");
     sciname = sciname.substr(0,commapos);
     var newrow = rest + ' <br><i>' + sciname + '</i>';
     return newrow;
}

//$('#obs_date').hide();
function openStartDate(type,prefix) {
   if(prefix) {   
    var start_stop = prefix;
    var obs_sent = 'When did you ' + start_stop + ' looking for birds at this location';
    $('#start_stop').html(obs_sent);
    $('.sighting_type').val(type);
    var loc_id = $('#location_first').val();
    $('#obs_date').show();
   } else  {
     $('#obs_date').hide();
     $('.sighting_type').val(type);
   }
}

var toggle_click = false;
$('#date_checkbox').click(function(){ 
	var sighting_date_1 = $('#obdate_1').val();
	$('.sighting_date').val(sighting_date_1);
     
});

$('#s_info_box').hide();
$('#s_info').click( function(){
    $('#s_info_box').toggle();
});

var loc_details  = '';
$('#ulink').hide();
function update_others(){
	var other_name = $('#other_name').val();
        if(other_name) {
 		$('#ulink').show();
            	$('#other_name_update').html(other_name);
		$('#name').html(other_name);
                
        } else {
               	$('#other_name_update').empty();        
		$('#ulink').hide();
	}
}

