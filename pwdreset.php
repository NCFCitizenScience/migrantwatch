<?php
	session_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>
<? 
      
        include("header.php");
?>
</head>
	<!----------PASSWORD RESET FORM START--------------------->
		<script language="javascript">
				function isEmpty(s){
					return ((s == null) || (s.length == 0))
				}

				// BOI, followed by one or more whitespace characters, followed by EOI.
				var reWhitespace = /^\s+$/
				// BOI, followed by one or more characters, followed by @,
				// followed by one or more characters, followed by .,
				// followed by one or more characters, followed by EOI.
				var reEmail = /^.+\@.+\..+$/
				var defaultEmptyOK = false
				// Returns true if string s is empty or
				// whitespace characters only.

				function isWhitespace (s){   // Is s empty?
					return (isEmpty(s) || reWhitespace.test(s));
				}

				function validate(){
					if (isWhitespace(document.frm_reset.email.value)){
						alert('Please enter email address.');
						return false;
					}



					return true;

				}

		</script>
		<FORM name=frm_reset action=process_details.php method=POST>
		<table width=575 cellpadding=4 cellspacing=4>
			<tr><td bgcolor="dedede" colspan=2><b>Email a new password</b></td></tr>
			<tr><td colspan=2 align=right style="font-weight:bold;color:red;">
				<?php
					if($_GET['cmd'] == "incorrectemail")
						print ("Sorry, that email isn't registered with Migrant Watch.");
				?>
			</td></tr>

			<?php
				if ($_GET['cmd']=="mailed"){
					print "<tr><td>";
					print "New password has been emailed to you. Please check your mail.";
					print "<br><br><input type=button onclick=javascript:window.location.href='login.php'; value='Back to Login Page' class=buttonstyle/>";
					print "</td></tr>";
				}
				else{
			?>
			<tr>
				<td align=right width=150>
						Your Email Address :
				</td>
				<td>
					<input type=text name=email style="width:200px;"> *
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center>
					<input type=submit value="Issue New Password" onclick="javascript:return validate();" class=buttonstyle />
					&nbsp;<input type=button value="Cancel" onclick="javascript:window.location.href='login.php';" class=buttonstyle />
					<br><input type=hidden name="cmd" value="pwdreset" /><br>
				</td>
			</tr>
			<?php
				}
			?>
		</table>
		</form>

     <!---------PASSWORD RESET FORM END----------------------->
