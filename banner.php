<?php
 // If user is not logged in then he should not be here
 if (!isset($_SESSION['userid'])) {
	header('Location: login.php?sessionExpired=1');
	exit;
 }

 include('banner.html');
?>
<?php
	include('menu.php');
?>
