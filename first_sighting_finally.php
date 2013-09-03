<?php 
	include('page_includes_js.php');
	
	include('db.php');

	$user_id = $_SESSION['userid'];



	//  The current season..
	$today = getdate();
	$currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;

		
        $last = 0;
	$start = 'start';
	$look = 'do you look';
	$sighting = 'First';
	$after = 'before';
  	
   	
?>


<style>
input { font-size:11px; padding:2px }

#sighting_first input { font-size:11px;border: solid 1px #777777; padding:5px }

#sighting_first td { font-size:13px; }

#sighting_last input { font-size:11px;border: solid 1px #777777; padding:5px }

#sighting_last td { font-size:13px; }

select { font-size 11px; border: solid 1px #777777;  }

select option { padding:2px; font-size:11px; }
textarea { font-size:11px;border: solid 1px #777777; padding:5px }
</style>

<? 
$speciesHintText = 'Type a name here';
	$dateHintText = 'Select';
	$numberHintText = 'In numerals';
	$notesHintText = 'Enter notes here';

include("addsightingform.php"); 


addSighting('first');
?>

<?
include("javascript_addsighting.php"); 


?>
<script>
 mainForm('first');


</script>


