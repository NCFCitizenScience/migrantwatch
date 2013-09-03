<?php
	session_start();
	$referer = '';
	if(isset($_SESSION['userid']) && $_SESSION['userid'] != '0' ) {
		$referer = "main.php";
	} else {
		$referer = "login.php";
	}
	header("location:$referer");
?>
<html>
<body>
<a href="login.php">Login</a>
<br>
<a href="register.php">Participant Registration Form</a>
</body>
</html>