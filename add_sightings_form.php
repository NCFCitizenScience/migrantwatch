<?php 
	$user_id = $_SESSION['userid'];
	$today = getdate();
	$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;
   	include("addsightingform.php");
	$speciesHintText = 'Type a name here';
	$dateHintText = 'Select';
	$numberHintText = 'In numerals';
	$notesHintText = 'Enter notes here';	
	addSighting();
	include("javascript_addsighting.php"); 
?>
<script type="text/javascript">
 mainForm();

</script>
