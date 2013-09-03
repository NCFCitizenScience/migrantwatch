<? include('page_includes_js.php'); ?>
<script type="text/javascript" src="jquery.emptyonclick.js"></script>

<!-- JqModal for Maps -->
<style type="text/css">

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
		var id = "species_<? echo $type; ?>_" + iteration;
		

	}



function validate() {
		var last = <?php print($last); ?>;
		var now = new Date();
		var obstart = document.frm_level1.obstart.value;
		var tbl = $('#catTable_<? echo $type; ?>');
		var lastRow = $('#catTable_<? echo $type; ?> tr').length - 1;

		if(document.frm_level1.location.value == "") { 
			alert("Please select a valid location for the Observations.");
			return false;
		}

      if((obstart == "") || (obstart == obstartHint)) {
			alert("Please enter the date when you started looking for birds at this location \n(in the current migration season)");
			return false;
      } 

      
      if(compareDates(obstart, "dd-MM-yyyy", formatDate(now, "dd-MM-yyyy"), "dd-MM-yyyy") == 1) {
			alert("The Start/end date of observation cannot be a future date");
			return false;
      } 

      if (document.frm_level1.often.value == "") {
			alert("Please select How often you look for birds at this location?");
			return false;
      }

       
      for(var i = 1; i <= lastRow; i++) {
        

          if( ( $('#species_<? echo $type; ?>_' + i).val() == '' ) || ( $('#species_<? echo $type; ?>_' + i).val() == speciesHintText ) ){
              alert('Please enter the species name for entry no. ' + i );
	           $('#species_<? echo $type; ?>_' + i).focus();
              return false;

           } else if( ( $('#obdate_<? echo $type; ?>_' + i).val() == '' ) || ( $('#obdate_<? echo $type; ?>_' + i).val() == dateHintText ) ){
              alert('Please enter the date of the sighting for entry no. ' + i );
	           $('#obdate_<? echo $type; ?>_' + i).focus();
              return false;

           } else {
					var obdate = $('#obdate_<? echo $type; ?>_' + i).val();

					if(compareDates(obdate,"dd-MM-yyyy",formatDate(now,"dd-MM-yyyy"),"dd-MM-yyyy") == 1) {
						alert("Sighting dates cannot be future dates ");
						return false;
               }
			  }



      }


}

$().ready(function() {
    $('#addCurrentLocation_<? echo $type; ?>').autocomplete("auto_miglocations.php", {
      width: 300,
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
           $('#entry_notes_<? echo $type; ?>_' + i).autogrow({
       maxHeight: 180,
        lineHeight: 16
      });
        }      

      $("#obstart_<? echo $type; ?>").emptyonclick(); 
		
      $('a#loc_ob_box_<? echo $type; ?>').click(function() {
               $('#loc_info_box_<? echo $type; ?>').toggle();
      });

      $('.sidebar').corner();
  }); 

 
</script>
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
