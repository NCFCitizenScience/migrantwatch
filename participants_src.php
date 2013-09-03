<h3> MigrantWatch Participant List HTML Source</h3>
<?php
$contents = file_get_contents('http://www.wildindia.org/migwatch/participants.php');
?>
<textarea rows='80' cols='100'>
	<?php echo $contents ?>
</textarea>