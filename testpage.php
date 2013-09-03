<html><head>
<!--<link href="CalendarControl.css"  rel="stylesheet" type="text/css">
<script src="CalendarControl.js" language="javascript"></script>-->
<script src="jquery.js" language="javascript"></script>
<!--<script src="jquery.autocomplete.js" language="javascript"></script>
<script src="jquery.bgiframe.min.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<link href="jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="date.js" language="javascript"></script>-->

</head>
<html>

<script type="text/javascript">
function addFormField() {
	var id = document.getElementById("id").value;
	$("#divTxt").append("<tr><td id='row" + id + "'><td><input type='text' size='20' name='species_name[]' id='species_name" + id + "'></td><td><input type='text' size='20' name='sighting_desc[]' id='sighting_desc_" + id + "'></td><td><input type='text' size='20' name='sighting_date[]' id='sighting_date_" + id + "'></td><td><a href='#' onClick='removeFormField(\"#row" + id + "\"); return false;'>Remove</a></td></tr>");
	
	
	$('#row' + id).highlightFade({
		speed:1000
	});
	
	id = (id - 1) + 2;
	document.getElementById("id").value = id;
}

function removeFormField(id) {
	$(id).remove();
}
</script>



<body>

<?

	if($_REQUEST['submit']){
		print_r($_REQUEST['txt']);
	}
		

?>

<table>
<form action="testpage.php" method="post" id="form1">

<tr><td>Species Name</td><td>Notes</td><td>Date</td></tr> <!-- TODO - Select same date for all -->
<input type="hidden" id="id" value="1">
<div id="divTxt"></div>
<tr><td><input type="submit" value="Submit" name="submit"><p><a href="#" onClick="addFormField(); return false;">Add</a></p></td><td>
<input type="reset" value="Reset" name="reset"></td></tr>
</form>
</table>

</body>


</html>
