<?php
session_start();
header('Cache-control: private'); 
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$src_base='http://migrantwatch.in/';
$cmd = $_POST['cmd'];
if ($cmd == "")
	$cmd = $_GET['cmd'];

include("./db.php");
include("functions.php");

if ($cmd == "createnew"){
	$name		= getEscaped($_POST['fullname']);
	$email		= getEscaped($_POST['email']);
	$hashkey 	= md5("migrantwatch_hacker" . $email);
	$pwdHash	= sha1($_POST['pwd1']);
	$loc		= getEscaped($_POST['location']);
	$loc1		= getEscaped($_POST['location1']);
	$loc2		= getEscaped($_POST['location2']);
	$city		= getEscaped($_POST['city']);
	$dist		= getEscaped($_POST['dist']);
	$state		= getEscaped($_POST['state']);
	$country	= getEscaped($_POST['country']);
	$pin		= getEscaped($_POST['pin']);
        $mobile_no      = getEscaped($_POST['mobile_no']);

	$sql = "SELECT user_id, user_name FROM migwatch_users WHERE user_email='".$email."'";
	$result = mysql_query($sql);
	if($result){
		if (mysql_num_rows($result) > 0){
			header("Location: register.php?cmd=duplicateemail");
			exit();
		} else {
			$sql = "INSERT INTO migwatch_users(user_name,user_email,password,address,address1,address2,city,district,state_id,country,pincode,registered_on, mobile_no, hashkey)";
			$sql = $sql." VALUES ('".$name."','".$email."','".$pwdHash."','".$loc."','".$loc1."','".$loc2."','".$city."','".$dist."',".$state;
			$sql = $sql.",'".$country."','".$pin."',CURDATE(), '$mobile_no', '$hashkey')";
			
			$success = mysql_query($sql);
			if ($success){

				$subject = "MigrantWatch: Confirm your registration";
				$body = 
<<<MAILTXT
Dear $name,

Thank you for registering with MigrantWatch. Please confirm your email address by clicking on the link below.

$src_base/confirm.php?val=$hashkey

We look forward to your participation, and hope that you will enjoy
working together with many others across the country to advance our
collective knowledge of the natural world.

MigrantWatch focusses on collecting information on First Sightings and
Last Sightings of winter migrants for each migration season (roughly
July to May). These sightings are of most value if they occur at a
location where you look for birds regularly. In addition to First and
Last sightings, participants may also submit "General" sightings (ie,
sightings in the middle of the migration season) to the database.

Since it is impossible to know in advance whether a sighting is the
Last of a season, we suggest that you upload all sightings towards the
end of the migration season as "General" and edit the last one of
these in retrospect to change the sighting type from General to Last.

If you have photos of migrant species, we encourage you to upload them
through your account. All photos must be accompanied by essential
accompanying information. Once you have submitted your sighting of a
migrant species, you will be given the option to upload up to 4 photos
per sighting.

Thank you again for contributing your time and effort to MigrantWatch.
Do let us know if you have any questions about how to collect or send
in the information. If you have suggestions for improvements, we would
love to hear from you!


With best wishes,
The MigrantWatch Team.
http://www.migrantwatch.in
mw@migrantwatch.in

MigrantWatch is a project of the Citizen Science Programme of the
National Centre for Biological Sciences, in association with Indian
Birds Journal

MAILTXT;
	                        $headers = "To: " . $name . "<" . $email . "> \r\n";
				$headers .= "From: MigrantWatch Team <mw@migrantwatch.in>\r\n";
				$headers .= "Cc: migrantwatch@ncbs.res.in \r\n";
				$headers .= "X-Mailer: php\r\n";
				mail($email, $subject, $body, $headers);

				header("Location: index.php?cmd=confirmation");
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
	$mobile_no      = getEscaped($_POST['mobile_no']);

	$sql = "UPDATE migwatch_users SET user_name='".$name."',address='".$loc."',address1='".$loc1."',address2='".$loc2."',city='".$city."',district='".$dist."',state_id=".$state.",country='".$country."',pincode='".$pincode."', mobile_no='" . $mobile_no ."' WHERE user_id=".$_SESSION['userid'];
	

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
	$sql = "SELECT user_id, user_name, password FROM migwatch_users WHERE user_email='".$email."' AND password='".$pwdHash."'";
	$result = mysql_query($sql);
	if($result){
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$name = $row{'user_name'};
				$_SESSION['userid'] = $row{'user_id'};
				$_SESSION['username'] = $row['user_name'];
				$_SESSION['password'] = $row['password'];

				
				$tempsql = "UPDATE migwatch_users SET last_login=curdate() WHERE user_id=".$row{'user_id'};
				mysql_query($tempsql);
				$expire_time = time()+60*60*24*30;
			       if( $_POST['remember'] == '1'){
			           setcookie("cookuid", $_SESSION['userid'], $expire_time, "/");
                                   setcookie("cookname", $_SESSION['username'], $expire_time, "/");
                	       	   setcookie("cookpass", $_SESSION['password'], $expire_time, "/");
        			   
         			}

			}
			if($_POST['done']) {
			   //header("Location: " . $_POST['done']);
                           header('Location: ' . $src_base . '/index.php');
			} else {
			  
			  header('Location: ' . $src_base . '/index.php');
			
			}
			
		}else{
			header("Location: " . $src_base . "login.php?cmd=error");
			
		}
	}
}

if ($cmd == "logout"){
	/*Logout the user by unsetting all used session_variables*/
	setcookie("cookuid", "", time()-3600,"/");
	setcookie("cookname", "", time()-3600,"/");
	setcookie("cookpass", "", time()-3600,"/");
	session_destroy();
	header("Location: /index.php");
        die();

}

if ($cmd == "chpass"){
	/*Check user's old password, if matching, reset it to new password*/
	if(isset($_SESSION['userid'])){
		$opwd = sha1($_POST['current_pass']);
		$pwd  = sha1($_POST['new_pass']);
                $pwd2  = sha1($_POST['new_pass2']);

                if($pwd == $pwd2 ) {
		$result = mysql_query("SELECT user_name FROM migwatch_users WHERE user_id=".$_SESSION['userid']." AND password='".$opwd."'");
		if($result){
			if(mysql_num_rows($result) == 0){
				header("Location: updateprofile.php?cmd=incorrectopwd");
				exit();
			}
			else{
				$sql = "UPDATE migwatch_users SET password='".$pwd."' WHERE user_id=".$_SESSION['userid'];
				$success = mysql_query($sql);
				if ($success){
				         setcookie("cookuid", "", time()-3600,"/");
        				 setcookie("cookname", "", time()-3600,"/");
        				 setcookie("cookpass", "", time()-3600,"/");
        				 session_destroy();
					 header("Location: updateprofile.php?cmd=passwordchanged");
					 exit();
				}
				else{
					header("Location: updateprofile.php?cmd=passwordchangeerror");
					exit();
				}
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
					header("Location: " . $src_base ."/index.php?cmd=mailed");
					exit();
				}
				else
					$message = "There was a problem resetting your password. Please contact the support team.";
			}
			else
				$message = "There was a problem resetting your password. Please contact the support team.";
		}
		else{
			header("Location: " . $src_base . "/index.php?cmd=incorrectemail");
			exit();
		}
	}

}


if($cmd == 'reconfirm' ) {
$confirm_email = $_POST['email'];
   $sql = "SELECT user_name , hashkey FROM migwatch_users WHERE user_email='".$confirm_email."'";
   $result = mysql_query($sql);
   $hashkey_exists = mysql_num_rows($result);
   while($data = mysql_fetch_assoc($result)) {
	$hashkey_confirm = $data['hashkey'];
	$user_name_confirm = $data['user_name'];
   }
  if($hashkey_exists > 0 ) {

	

				$subject = "MigrantWatch: Confirm your registration";
				$body = 
<<<MAILTXT
Dear $user_name_confirm,

Thank you for registering with MigrantWatch. Please confirm your email address by clicking on the link below.

http://migrantwatch.in/confirm.php?val=$hashkey_confirm

 We look forward to your participation, and
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

MAILTXT;
	                        $headers = "To: " . $user_name_confirm . "<" . $confirm_email . "> \r\n";
				$headers .= "From: MigrantWatch Team <mw@migrantwatch.in>\r\n";
				$headers .= "Cc: migrantwatch@ncbs.res.in \r\n";
				$headers .= "X-Mailer: php\r\n";
				mail($email, $subject, $body, $headers);

				header("Location: " . $src_base . "/index.php?cmd=confirmation");
				exit();
			}else {

		            header("Location: " . $src_base . "/reconfirm.php?cmd=incorrectmail");
	           }
}

?>
