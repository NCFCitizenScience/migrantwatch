<?php
	session_start();

$cmd = $_POST['cmd'];
if ($cmd == "")
	$cmd = $_GET['cmd'];

include("./db.php");
include("functions.php");

if ($cmd == "createnew"){

	$name		= getEscaped($_POST['fullname']);
	$email		= getEscaped($_POST['email']);
	$pwdHash	= sha1($_POST['pwd1']);
	$loc		= getEscaped($_POST['location']);
	$loc1		= getEscaped($_POST['location1']);
	$loc2		= getEscaped($_POST['location2']);
	$city		= getEscaped($_POST['city']);
	$dist		= getEscaped($_POST['dist']);
	$state		= getEscaped($_POST['state']);
	$country	= getEscaped($_POST['country']);
	$pin		= getEscaped($_POST['pin']);

	$sql = "SELECT user_id, user_name FROM migwatch_users WHERE user_email='".$email."'";
	$result = mysql_query($sql);
	if($result){
		if (mysql_num_rows($result) > 0){
			header("Location: register.php?cmd=duplicateemail");
			exit();
		} else {
			$sql = "INSERT INTO migwatch_users(user_name,user_email,password,address,address1,address2,city,district,state_id,country,pincode,registered_on)";
			$sql = $sql."VALUES ('".$name."','".$email."','".$pwdHash."','".$loc."','".$loc1."','".$loc2."','".$city."','".$dist."',".$state;
			$sql = $sql.",'".$country."','".$pin."',CURDATE())";
			$success = mysql_query($sql);
			if ($success){

				$subject = "Welcome to MigrantWatch";
				$body =
<<<MAILTXT
Dear $name,

Welcome to MigrantWatch! We look forward to your participation, and
hope that you will enjoy working together with many others across the
country to advance our collective knowledge of the natural world.

MigrantWatch collects data on First Sightings and Last Sightings of
winter migrants for each migration season (roughly July to May). These
sightings are of most value if they occur at a location where you look
for birds regularly. In addition to First and Last sightings, if you
are willing to keep a regular record of migrants through the season,
please do let us know and we can provide several options for this.

 Thank you again for contributing your time and effort to
MigrantWatch. Do let us know if you have any questions about how to
collect or send in the information.

With best wishes,
The MigrantWatch Team.
http://www.migrantwatch.in
mw@migrantwatch.in

If you forget the password for your individual account, simply click
on "Reset password" under "My account" and a new password will be
emailed to you.
MAILTXT;

				$headers = "From: mw@migrantwatch.in\r\n"."Cc:migrantwatch@ncbs.res.in\r\nX-Mailer: php\r\n";
				mail($email, $subject, $body, $headers);

				header("Location: login.php?cmd=registered");
				exit();
			}else
				$message = "There was a problem registering you. Please contact mw@migrantwatch.in.";
		}
	}else
		$message = "There was a problem registering you. Please contact mw@migrantwatch.in.";
}

if ($cmd == "editprofile"){

	$name		= getEscaped($_POST['fullname']);
	$loc		= getEscaped($_POST['location']);
	$loc1		= getEscaped($_POST['location1']);
	$loc2		= getEscaped($_POST['location2']);
	$city		= getEscaped($_POST['city']);
	$dist		= getEscaped($_POST['dist']);
	$state		= getEscaped($_POST['state']);
	$country	= getEscaped($_POST['country']);
	$pincode	= getEscaped($_POST['pin']);

	$sql = "UPDATE migwatch_users SET user_name='".$name."',address='".$loc."',address1='".$loc1."',address2='".$loc2."',city='".$city."',district='".$dist."',state_id=".$state.",country='".$country."',pincode='".$pincode."' WHERE user_id=".$_SESSION['userid'];

	$success = mysql_query($sql);
	if ($success){
		$_SESSION['username'] = $name;
		header("Location: index.php?cmd=profilesaved");
		exit();
	} else {
		$message = "There was a problem saving your changes. Please contact support team.";
	}
}

if ($cmd == "login"){
	/*Login the user by checking his email address against hashed pasword in database*/
	$email = getEscaped($_POST['email']);
	$pwdHash	= sha1($_POST['pwd']);

	$sql = "SELECT user_id, user_name FROM migwatch_users WHERE user_email='".$email."' AND password='".$pwdHash."'";
	$result = mysql_query($sql);
	if($result){
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$name = $row{'user_name'};
				$_SESSION['userid'] = $row{'user_id'};
				$_SESSION['username'] = $row['user_name'];
				$tempsql = "UPDATE migwatch_users SET last_login=curdate() WHERE user_id=".$row{'user_id'};
				mysql_query($tempsql);
			}
			header("Location: main.php?cmd=loggedin");
			exit();
		}else{
			header("Location: login.php?cmd=error");
			exit();
		}
	}
}

if ($cmd == "logout"){
	/*Logout the user by unsetting all used session_variables*/
	session_unset();
	header("Location: login.php");
	exit();

}

if ($cmd == "chpass"){
	/*Check user's old password, if matching, reset it to new password*/
	if(isset($_SESSION['userid'])){
		$opwd = sha1($_POST['opwd']);
		$pwd  = sha1($_POST['pwd']);

		$result = mysql_query("SELECT user_name FROM migwatch_users WHERE user_id=".$_SESSION['userid']." AND password='".$opwd."'");
		if($result){
			if(mysql_num_rows($result) == 0){
				header("Location: chpass.php?cmd=incorrectopwd");
				exit();
			}
			else{
				$sql = "UPDATE migwatch_users SET password='".$pwd."' WHERE user_id=".$_SESSION['userid'];
				$success = mysql_query($sql);
				if ($success){
					header("Location: main.php?cmd=passwordchanged");
					exit();
				}
				else{
					header("Location: main.php?cmd=passwordchangeerror");
					exit();
				}
			}
		}
	}
	else
		die("Session Not Available. Fatal Error...");
}

function Random_Password($length) {
    srand(date("s"));
    $possible_characters = "abcdefghijkmnpqrstuvwxyz23456789";
    $pwd = "";
    while(strlen($pwd)<$length) {
        $pwd = $pwd.substr($possible_characters, rand()%(strlen($possible_characters)),1);
    }
    return($pwd);
}

if ($cmd == "pwdreset"){
	$to = getEscaped($_POST['email']);

	$sql = "SELECT user_id FROM migwatch_users WHERE user_email='".$email."'";
	$result = mysql_query($sql);
	if($result){
		if (mysql_num_rows($result) > 0){
			$subject = "MigrantWatch - Password Reset";
			$pwd = Random_Password(6);
			$body = "Hi, your new password is ".$pwd;
			$body .= "\n\nNote:If you have not asked for your MigrantWatch password to be reset, please contact us immediately at mw@migrantwatch.in.\n\n";
			$body .= "\n\n- MigrantWatch Team.";
			$headers = "From: mw@migrantwatch.in\r\n"."X-Mailer: php\r\n";
			if (mail($to, $subject, $body, $headers)){
				$sql = "UPDATE migwatch_users SET password='".sha1($pwd)."' WHERE user_email='".$to."'";
				$success = mysql_query($sql);
				if($success){
					header("Location: pwdreset.php?cmd=mailed");
					exit();
				}
				else
					$message = "There was a problem resetting your password. Please contact the support team.";
			}
			else
				$message = "There was a problem resetting your password. Please contact the support team.";
		}
		else{
			header("Location: pwdreset.php?cmd=incorrectemail");
			exit();
		}
	}

}




//print $sql;
print "Error: ".$message;
?>
<li><a href="login.php">Login</a></li>
<li><a href="process_details.php?cmd=logout">Logout</a></li>
<li><a href="editprofile.php">Edit Profile</a></li>
<li><a href="chpass.php">Change Password</a></li>