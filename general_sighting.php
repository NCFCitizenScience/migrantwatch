<style>


#sighting_general input { font-size:11px;border: solid 1px #777777; padding:5px }

#sighting_general td { font-size:13px; }

</style>
<? 
$speciesHintText = 'Type a name here';
	$dateHintText = 'Select';
	$numberHintText = 'In numerals';
	$notesHintText = 'Enter notes here';

	addSighting('general','do you look','START','General','before',0);

?>
<script type="text/javascript">
 mainForm('general');
</script>