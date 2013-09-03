<?php 
	$user_id = $_SESSION['userid'];
	//  The current season..
	$today = getdate();
	$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;
        include("addsightingform.php");
?>


<style>
input { font-size:11px; padding:2px }

#sighting_first input { font-size:11px;border: solid 1px #777777; padding:5px }

#sighting_first td { font-size:13px; }



select { font-size 11px; border: solid 1px #777777;  }

select option { padding:2px; font-size:11px; }
textarea { font-size:11px;border: solid 1px #777777; padding:5px }
</style>

<? 
$speciesHintText = 'Type a name here';
	$dateHintText = 'Select';
	$numberHintText = 'In numerals';
	$notesHintText = 'Enter notes here';


addSighting('first','do you look','START','First','before',0);
include("javascript_addsighting.php"); 
?>
<script type="text/javascript"> 
 mainForm('first');
</script>