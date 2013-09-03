<?php 
	
	include("functions.php");
	include('db.php');

	$user_id = $_SESSION['userid'];

	$last = 0;
	$start = 'start';
	$look = 'do you look';
	$sighting = 'General';
	$after = 'before';
        $type = 'general';

	//  The current season..
	$today = getdate();
	$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;

	if(isset($_GET['state']) && isset($_GET['loc'])) {
		$_SESSION['location'] = $_GET['loc'];
	}

?>


  
<script language="JavaScript" type="text/javascript">
	
	// define a custom method on the string class to trim leading and training spaces
	String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); };

	var reWhitespace = /^\s+$/

	function isEmpty(s){
		return ((s == null) || (s.length == 0))
	}

	function isWhitespace (s) {	// Is s empty?
		return (isEmpty(s) || reWhitespace.test(s));
	}

	function displaytr(opt){
		if(opt==1)
			document.getElementById("loctr").style.display = "block";
		else
			document.getElementById("loctr").style.display = "none";
	}

	

	var HintClass = 'hintTextbox';
	var hintText = 'Please enter identification details';
	var HintActiveClass = "hintTextboxActive";
	var speciesHintText = 'Type part of a species name';

	function appendNewInput_<? echo $type; ?>()
	{
	  var tbl = $('#catTable_<? echo $type; ?>');
	  var lastRow = $('#catTable_<? echo $type; ?> tr').length;

	  // if there's no header row in the table, then iteration = lastRow + 1
	  var iteration = lastRow;

	  var newElement =
		"<tr>"
			
	+ "<td width='150px'>"
+ "<input type='text' id='species_<? echo $type; ?>_" + iteration +"' name='species[]' style='font-size:11px' size='20' value='Type a name here' class='hintTextbox'></td>"
		 + "<td>"
                        + "<input type='text' style='font-size:11px' id='obdate_<? echo $type; ?>_" + iteration + "' name='obdate[]' readonly='readonly' value='Select' onclick='showCalendarControl(obdate_<? echo $type; ?>_" + iteration + ");' size='8'>" + "</td>"
		+ "<td><input style='font-size:11px' type='text' id='number_<? echo $type; ?>_" + iteration +"' name='number[]' size='8' value='In numerals'></td>"
		+ "<td>"
			+ "<select style='font-size:11px' id= 'accuracy_<? echo $type; ?>_" + iteration + "' name='accuracy[]'>"
			+ "<option value=''>-- SELECT --</option>"
			+ "<option value='exact'>Exactly</option>"
			+ "<option value='approximate'>Approximately</option>"
			+ "</select>"
		+ "</td>"
		+ "<td><input style='font-size:11px' type='text' id='entry_notes_<? echo $type; ?>_" + iteration + "' name='entry_notes[]' size='28' style='width:211px'></td>"
		"</tr>";

		$('#catTable_<? echo $type; ?>').append(newElement);
	  	$("#species_<? echo $type; ?>_" + iteration).autocomplete("autocomplete.php", {
			width: 200,
			selectFirst: false,
			matchSubset :0,
			extraParams : {all : 1},
			//formatItem:formatItem
	  	});

		var hintText = 'Please enter identification details';
	  	$("#species_<? echo $type; ?>_" + iteration).result(function(event, data, formatted) {
			if (data[1] == 1) {
				if ($("#entry_notes_<? echo $type; ?>_" + iteration).val != '') {
					$("#entry_notes_<? echo $type; ?>_" + iteration).val(hintText);
					$("#entry_notes_<? echo $type; ?>_" + iteration).addClass(HintClass);
					var id = "entry_notes_<? echo $type; ?>_" + iteration;
					$("#entry_notes_<? echo $type; ?>_" + iteration).focus( function() { onHintTextboxFocus(id,hintText) } );
					$("#entry_notes_<? echo $type; ?>_" + iteration).blur( function() { onHintTextboxBlur(id,hintText) } );
				}
			} else {
				if ($("#entry_notes_<? echo $type; ?>_" + iteration).val() == hintText) {
					$("#entry_notes_<? echo $type; ?>_" + iteration).removeClass(HintClass);
					$("#entry_notes_<? echo $type; ?>_" + iteration).removeAttr('onFocus');
					$("#entry_notes_<? echo $type; ?>_" + iteration).removeAttr('onBlur');
					$("#entry_notes_<? echo $type; ?>_" + iteration).val('');
				}
			}
	  	});

		var id = "species_<? echo $type; ?>_" + iteration;
		$("#species_<? echo $type; ?>_" + iteration).focus( function() { onHintTextboxFocus(id,speciesHintText) } );
		$("#species_<? echo $type; ?>_" + iteration).blur( function() { onHintTextboxBlur(id,speciesHintText) } );

	}

	 $(document).ready(function() {
		$('#species_<? echo $type; ?>_1').autocomplete("autocomplete.php", {
			width: 200,
			selectFirst: false,
			matchSubset :0,
			extraParams : {all : 1},
                         mustMatch: true,                       
			//formatItem:formatItem
		});
               
		    //$('#dialog').jqm();
		 	
		var iteration = 1;
		var hintText = 'Please enter identification details';
		$("#species_<? echo $type; ?>_1").result(function(event, data, formatted) {
			if (data[1] == 1) {
				if ($("#entry_notes_<? echo $type; ?>_" + iteration).val != '') {
					$("#entry_notes_<? echo $type; ?>_" + iteration).val(hintText);
					$("#entry_notes_<? echo $type; ?>_" + iteration).addClass(HintClass);
					var id = "entry_notes_<? echo $type; ?>_" + iteration;
					$("#entry_notes_<? echo $type; ?>_" + iteration).focus( function() { onHintTextboxFocus(id,hintText) } );
					$("#entry_notes_<? echo $type; ?>_" + iteration).blur( function() { onHintTextboxBlur(id,hintText) } );
				}
			} else {
				if ($("#entry_notes_<? echo $type; ?>_" + iteration).val() == hintText) {
					$("#entry_notes_<? echo $type; ?>_" + iteration).removeClass(HintClass);
					$("#entry_notes_" + iteration).removeAttr('onFocus');
					$("#entry_notes_<? echo $type; ?>_" + iteration).removeAttr('onBlur');
					$("#entry_notes_<? echo $type; ?>_" + iteration).val('');
				}
			}
                      

		});
              

		var id = "species_<? echo $type; ?>_" + iteration;
		$("#species_<? echo $type; ?>_" + iteration).focus( function() { onHintTextboxFocus(id,speciesHintText) } );
		$("#species_<? echo $type; ?>_" + iteration).blur( function() { onHintTextboxBlur(id,speciesHintText) } );
	});




function formatLocation(row) {
  var completeRow;
  completeRow = new String(row);
  splittedStr = completeRow.split(",");
  var newrow = '';
  for(var i=0; i< splittedStr.length; i++) {
    if(isNaN(splittedStr[i])) {
      newrow += splittedStr[i] +",";
    } else if(splittedStr[i] > 0 ){
      break;
    }
  }
  newrow = newrow.substr(0, newrow.lastIndexOf(","));
  return newrow;
}


	function onHintTextboxFocus(id,text) {
	  var input = document.getElementById(id);
	  if (text != undefined) {
	  	hintText = text;
	  }

	  if (input.value.trim()== hintText) {
	    input.value = "";
	    input.className = HintActiveClass;
	  }
	}

	function onHintTextboxBlur(id,text) {
	  var input = document.getElementById(id);

	  if (text != undefined) {
	  	hintText = text;
	  }

	  if (input.value.trim().length==0) {
	    input.value = hintText;
	    input.className = HintClass;
	  }
	}

	function onSpeciesChange(id,text) {

		if (text != undefined) {
			hintText = text;
		}

		if ($("#" + id).val() == hintText) {
			$("#" + id).removeClass(HintClass);
			$("#" + id).removeAttr('onFocus');
			$("#" + id).removeAttr('onBlur');
			$("#" + id).val('');
		}
	}

	function strrpos(haystack, needle, offset) {
	    // http://kevin.vanzonneveld.net
	    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // *     example 1: strrpos("Kevin van Zonneveld", "e");
	    // *     returns 1: 16

	    var i = haystack.lastIndexOf( needle, offset ); // returns -1
	    return i >= 0 ? i : false;
	}

	function substr( f_string, f_start, f_length ) {
	    // http://kevin.vanzonneveld.net
	    // +     original by: Martijn Wieringa
	    // *         example 1: substr("abcdef", 0, -1);
	    // *         returns 1: "abcde"

	    if(f_start < 0) {
	        f_start += f_string.length;
	    }

	    if(f_length == undefined) {
	        f_length = f_string.length;
	    } else if(f_length < 0){
	        f_length += f_string.length;
	    } else {
	        f_length += f_start;
	    }

	    if(f_length < f_start) {
	        f_length = f_start;
	    }

	    return f_string.slice(f_start,f_length);
	}

	function formatItem(row) {
		var completeRow;
		completeRow = new String(row);
		var scinamepos = completeRow.lastIndexOf("(");
		var rest = substr(completeRow,0,scinamepos);
		var sciname = substr(completeRow,scinamepos);
		var commapos = sciname.lastIndexOf(",");
		sciname = substr(sciname,0,commapos);
		var newrow = rest + ' <i>' + sciname + '</i>';
		return newrow;
	}/*

	function validate() {
		var last = <?php print($last); ?>;
		var now = new Date();
		var obstart = document.frm_level1.obstart_<? echo $type; ?>.value;
		if(last == 1) {
			var first = 'Last';
		} else {
			var first = 'First'
		}
                
      var currentLocation = document.getElementById("addCurrentLocation_<? echo $type; ?>");

		if( currentLocation.value == "" || ) {
			alert("Please select a location for the Observations.");
			return false;
		}
		if (obstart == "" || obstart == "Select") {
			if(last == 1) {
				alert("Please enter the date when you stopped looking for birds at this location \n(in the current migration season)");
			} else {
				alert("Please enter the date when you started looking for birds at this location \n(in the current migration season)");
			}
			return false;
		} else if(compareDates(obstart, "dd-MM-yyyy", formatDate(now, "dd-MM-yyyy"), "dd-MM-yyyy") == 1) {
			alert("The Start/end date of observation cannot be a future date");
			return false;
		}

		if (document.frm_level1.often_<? echo $type; ?>.value == "") {
			alert("Please select How often you look for birds at this location?");
			return false;
		}

		var tbl = $('#catTable_<? echo $type; ?>');
		var lastRow = $('#catTable_<? echo $type; ?> tr').length - 1;
        var obSeason = season($('#obstart_<? echo $type; ?>').val());
		for(var i = 1; i <= lastRow; i++) {

			if ($('#obdate_<? echo $type; ?>_' + i).val() != '' || (i == 1)) {
				var obdate = $('#obdate_<? echo $type; ?>_' + i).val();
				if ($('#species_<? echo $type; ?>_' + i).val() == '' || $('#species_<? echo $type; ?>_' + i).val() == speciesHintText) {
					alert('Please enter the species for the for the entry no. ' + i);
					$('#species_<? echo $type; ?>_' + i).focus();
					return false;
				}

				if (compareDates(obdate,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) {
					alert("The observation date cannot be a future date.");
					return false;
				}
			}

			if (($('#species_<? echo $type; ?>_' + i).val() != '' && $('#species_<? echo $type; ?>_' + i).val() != speciesHintText) || (i == 1)) {
				if ($('#obdate_<? echo $type; ?>_' + i).val() == '') {
					alert('Please enter the ' + first + ' Sighting Date for this species (entry no. ' + i +')');
					$('#obdate_<? echo $type; ?>_' + i).focus();
					return false;
				} else {
					var obdate = $('#obdate_<? echo $type; ?>_' + i).val();
					var ob_type = $('#ob_type_<? echo $type; ?>_' + i).val();

					if(compareDates(obdate,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) {
						alert("Sighting dates cannot be future dates ");
						return false;
					} else if(last == 0) {
                        if(season(obdate) != obSeason) {
                            var alertstr = "You have entered a START date in the "+formatSeason(obSeason)+" migration season. ";
                            alertstr += "If your record is not from the "+formatSeason(obSeason)+" season,";
                            alertstr += " please enter a start date between 1 July and 30 June of the correct season.";
                            alertstr += "\nAre you sure you want to enter sighting for season "+formatSeason(obSeason)+"?";
                            if(!confirm(alertstr)) {
                                return false;
                            }
                        } else if(currentSeason() != obSeason) {
                            if(!confirm("Are you sure you want to enter sighting for season "+formatSeason(obSeason)+"?")) {
                                return false;
                            }
                        }
                        if(compareDates(obstart, "dd-MM-yyyy", obdate, "dd-MM-yyyy") == 1) {
                            alert("The " + first + " Sighting Date cannot be a date before start of observations");
                            return false;
                        }
					} else if(last == 1) {
                       */ /*if(season(obdate) != obSeason) {
                            var alertstr = "You have entered a Observation END date in the "+formatSeason(obSeason)+" migration season. ";
                            alertstr += "If your record is not from the "+formatSeason(obSeason)+" season,";
                            alertstr += " please enter a observation end date between 1 July and 30 June of the correct season.";
                            alertstr += "\nAre you sure you want to enter sighting for season "+formatSeason(obSeason)+"?";
                            alert(alertstr);
                            return false;
                        }*//*
                        if(compareDates(obdate, "dd-MM-yyyy", obstart, "dd-MM-yyyy") == 1) {
                            alert("The " + first + " Sighting Date cannot be a date after the end of observations");
                            return false;
                        }
					}
				}
			}*/

			if ($('#species_<? echo $type; ?>_' + i).val() != '' && $('#obdate_<? echo $type; ?>_' + i).val() != '') {
				var num = $('#number_<? echo $type; ?>_' + i).val();
				var acc = $('#accuracy_<? echo $type; ?>_' + i).val();
				if (!isWhitespace(num) && isNaN(num)) {
					alert('Please enter only numerals under \'Number\'');
					$('#number_' + i).focus();
					return false;
				}

				if ((!isWhitespace(num) && num != '') && acc == '') {
					alert('Please select the \'Accuracy of count\' for entries where \'Number\' has been entered.');
					$('#accuracy_<? echo $type; ?>_' + i).focus();
					return false;
				}
			}
		}
	}

	function setLocationData() {
		document.forms[0].cmd.value = 'loc_change';
		document.forms[0].action = 'gensightings.php';
		document.forms[0].submit();
	}

    function season(inputDate) {
        var seasonArray = inputDate.split("-");
        if(seasonArray[1] < 7) {
            return parseInt(seasonArray[2]) - 1;
        } else {
            return seasonArray[2];
        }
    }

    function currentSeason() {
        var currDate = new Date();
        if(currDate.getMonth() < 7) {
            return parseInt(currDate.getFullYear()) - 1;
        } else {
            return currDate.getFullYear();
        }
    }
    function formatSeason(seasonStart) {
        var seasonEnd = parseInt(seasonStart) + 1;
        var str = new String(seasonEnd);
        str = str.substring(2);
        return (seasonStart+'/'+str);
    }
</script>
<script type="text/javascript">

 $().ready(function() {
    $('#addCurrentLocation_<? echo $type; ?>').autocomplete("auto_miglocations.php", {
      width: 300,
	  //selectFirst: false,
	 matchSubset :true,
          //mustMatch: true,
	  //formatItem:formatLocation,
          //autoFill: true,
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
        obs_start_<? echo $type; ?> = obs_start.split('-');
        obsstart_<? echo $type; ?> = obs_start_<? echo $type; ?>[2] + '-' +  obs_start_<? echo $type; ?>[1] + '-' + obs_start_<? echo $type; ?>[0];
        $('#obstart_<? echo $type; ?>').val(obsstart_<? echo $type; ?>);
        document.getElementById('often_<? echo $type; ?>').value = data[7];
        var loc_data_<? echo $type; ?> = data[1];
       
      var table = "<a href=\"prevsightings.php?id=" + loc_data + "\" class=\"thickbox\" title=\"Detailed information of selected issue\">View Previous</a>";

document.getElementById('prev_sightings_<? echo $type; ?>').innerHTML = table;
tb_init('a.thickbox'); // Initialise again

        }

        
      });

     $('#species_notes_<? echo $type; ?>').autogrow({
       maxHeight: 180,
        lineHeight: 16
      });

     
      $('#loc_notes_<? echo $type; ?>').autogrow({
	maxHeight: 180,
        lineHeight: 16
      });

      $("#loc_info_box_<? echo $type; ?>").hide();

		$("#other_name_<? echo $type; ?>").emptyonclick(); 
		
      $('a#loc_ob_box_<? echo $type; ?>').click(function() {
               $('#loc_info_box_<? echo $type; ?>').toggle();
      });
  }); 

 
</script>
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
<?php
if ($_GET['error'] == 1) {


	$error = 1;
	$lastData = $_SESSION['gensightings'];
	$rowCount = count($lastData['obdate']);
	$duplicate = (isset($lastData['duplicate']))? $lastData['duplicate'] : '';
	$errors = $lastData['errors'];
	$duplicate_species = $lastData['duplicate_species'];
} else {
	$rowCount = ($rowCount > 0) ? $rowCount : 1;
}
unset($_SESSION['gensightings']);

$errorStrings = array(
						'location_' . $type => 'Please select a location',
						'no_obStart_'  . $type => 'Please enter the date when you started looking for birds at this location (in the current migration season)',
						'no_obEnd_'  . $type => 'Please enter the date when you stopped looking for birds at this location (in the current migration season)',
						'obstart_'  . $type => "The Observation $start date should be in (DD-MM-YYYY) format and should not be a future date",
						'no_obdate_'  . $type => "Please enter $sighting Sighting Date for this species",
						'often_'  . $type => 'Please select how often you look for birds',
						'obdate_'  . $type => "The Sighting date should be in (DD-MM-YYYY) format and should not be a future date",
						'ob_compare_'  . $type => "The $sighting Sighting Date cannot be a date $after observation start date",
						'species_'  . $type => 'This species is not been tracked currently.',
						'number_'  . $type => "Please enter only numerals under 'Number'",
						'accuracy_'  . $type => "Please select the 'Accuracy of Count' in the entries for which the 'Number' has been entered",
						'duplicate_'  . $type => $duplicate
					);

$speciesHintText = 'Type part of a species name';

if (!empty($errors)) {
	foreach ($errors as $field) {
		print("<p style='color:red'><b>". $errorStrings[$field]."</b></p>");
	}
}
?>
<div style='width:250px;float:right;margin-top:10px;padding-left:10px;margin-right:-50px'>
                  <script>

                  var loc_details  = '';
   



                  function update_others_<? echo $type; ?>(){
			 var other_name = document.getElementById('other_name_<? echo $type; ?>').value;
          if(other_name) {
            document.getElementById('other_name_update_<? echo $type; ?>').innerHTML = '<div class="box" style="background-color:#fff;text-align:right">See earlier reports from <a href="#">' + other_name + '</a></div>';
                  } else {

	                     $('#other_name_update_<? echo $type; ?>').hide();
                  }
            }
	         </script>
		  <div id="other_name_box_<? echo $type; ?>" style="height:80px"><div id="other_name_update_<? echo $type; ?>"></div></div>
       <table class="box" id="locDetails_<? echo $type; ?>" style="height:200px;background-color:#fff;text-align:right;display:none;">
	   <tr width="100%"><td style="text-align:right;"><strong>Location Details</strong></td></tr>
		<tr>
				<td style="text-align:right;">
							<div id="details_loc_<? echo $type; ?>"></div><br>
					      <div id="details_city_<? echo $type; ?>"></div><br>
                     <div id="details_dist_<? echo $type; ?>"></div><br>
                     <div id="details_state_<? echo $type; ?>"><div><br><div id="prev_sightings"></div>
				</td>
	</tr>
  </table>

               </div>
<div id="gen_form">
<form method='post'  name='frm_level1' action='process_level1.php'>
<input type='hidden' name='last' value='<?php echo $last; ?>' />
<table border=0 cellpadding=2 cellspacing=1 style='margin-left:20px; margin-top:0; margin-right:auto;width:700px; font-size:11px'>
    
	<tr style>
		<td style='width:157px; font-size:11px'>
		If you are reporting sightings on behalf<br> of someone else,<br> please enter their name :
		</td>
          
<td style='width:400px;margin-left:-20px'><input size="30" type=text id="other_name_<? echo $type; ?>" name="other_name" value="Name" style="" onkeyup="update_others_<? echo $type; ?>()"></tr>
	</tr>

	</tr>
<tr><td colspan='2'><hr></td></tr>
<tr>
	   <td style='width:155px'>Location of sighting</td>

           <td><input style="width:388px" type="text" id="addCurrentLocation_<? echo $type; ?>" name="addCurrentLocation" value=""><input type="reset" value="X" onclick="$('#locDetails_<? echo $type; ?>').hide(); document.getElementById('addCurrentLocation_<? echo $type; ?>').disabled = false;"> </td>
	</tr><tr><td><a href="#" class="jqModal">Add a new Location</a></td>



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
<textarea id="loc_notes_<? echo $type; ?>" name="other_notes" class="editbox" style="width:400px;height:50px"></textarea>
                       
                </td>
            </tr>
	<tr>
		<td style='width:155px'>When did you <?php print(strtoupper($start)); ?> looking for birds at this location?</td>
		<td style='width:400px'>
<input type=text id="obstart_<? echo $type; ?>" readonly="readonly" name=obstart size="30" style='' onclick="showCalendarControl(obstart_<? echo $type; ?>);">&nbsp;(dd-mm-yyyy)*
			<br>
			<!--(Date when you <?php echo ($last == 1) ? 'stopped' : 'started'; ?> observing at this location after 1st Aug 2007)-->
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
	<tr><td colspan="2"><hr></td></tr>
         
       

	
	
	
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
<input type='text' id='species_<? echo $type; ?>_<?php echo $i; ?>' style='font-size:11px' name='species[]' size='20' value='Type a name here'></td>
		 <td>
     <input style='font-size:11px' type='text' id='obdate_<? echo $type; ?>_<?php echo $i; ?>' name='obdate[]' value='Select' readonly='readonly' size='8' onclick="showCalendarControl(obdate_<? echo $type; ?>_<?php echo $i; ?>);">
                  </td>

			<td><input type="text" style='font-size:11px' id='number_<? echo $type; ?>_<?php echo $i; ?>' name='number[]' size="8" value='In numerals'></td>
		<td>
			<select style='font-size:11px' id= 'accuracy_<? echo $type; ?>_<?php echo $i; ?>' name='accuracy[]'>
				<option value=''>-- SELECT --</option>
				<option value='exact' <?php if(isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'exact') echo 'selected' ?>>Exactly</option>
				<option value='approximate' <?php if (isset($lastData['accuracy'][$j]) && $lastData['accuracy'][$j] == 'approximate') echo 'selected' ?>>Approximately</option>
			</select>
		</td>
		<td><input style='font-size:11px' type="text" id='entry_notes_<? echo $type; ?>_<?php echo $i; ?>' name='entry_notes[]' size="28" value='<?php if(isset($lastData['entry_notes_'.$type][$j])) echo getStripped(htmlentities($lastData['entry_notes_'.$type][$j],ENT_QUOTES)); ?>' style='width:211px'
		<?php/*
		if($lastData['entry_notes_'. $type][$j] == 'Please enter identification details') {
			echo " onFocus=\"onHintTextboxFocus(id,hintText)\" onBlur=\"onHintTextboxBlur('entry_notes_$type_$i','Please enter identification details')\" class='hintTextbox'";
		}*/
		?>
		>
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
</table><table style='width:700px; margin-left:20px'><tr><td>
<a name='x'></a>
<input type="hidden" name="cmd" value="gensightings" id='cmd'/>
<input type="hidden" name="general" value="1"/>
<tr><td><span><a href='#x' onclick='appendNewInput_<? echo $type; ?>(); $("#catTable_<? echo $type; ?>").append("hello");'>Add another species</a></td></tr>
<tr><td><input type='submit' value= "Submit"  class='buttonstyle' onclick="return validate();"></td></tr></table>
<?php
  /*  } else {
        print("</table>");
    }*/	
?>

</form></div>
<br />



