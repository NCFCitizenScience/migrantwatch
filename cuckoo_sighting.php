<?php 
        $user_id = $_SESSION['userid'];
	if( !$user_id) { exit(); }
         $today = getdate();
        $currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;
	include("cuckooform.php");   	
	$speciesHintText = 'Type a name here';
	$dateHintText = 'Select';
	$numberHintText = 'In numerals';
	$notesHintText = 'Enter notes here';
	addSighting();
       include("javascript_cuckooform.php"); 
?>

<script>
 mainForm();
</script>


