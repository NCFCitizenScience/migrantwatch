<?php
	session_start();
	include("banner.html");
?>
<html>

	<!----------LOGIN FORM START--------------------->
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
					if (isWhitespace(document.frm_login.email.value)){
						alert('Please enter email address.');
						return false;
					}


					if(isWhitespace(document.frm_login.pwd.value)){
						alert("Please enter password.");
						return false;
					}
					return true;

				}

		</script>
		<FORM name=frm_login action=process_details.php method=POST>
		<table width="770" cellpadding=4 cellspacing=4>
			<tr><td style="text-align:right;font-weight:bold;color:red;" colspan=2>
				<?php
					if($_GET['cmd'] == "error")
						print ("Incorrect email address or password.");
					else if($_GET['cmd'] == "registered")
						print("You are registered now. Please login.");
					if($_SESSION['origuserid'] == '0' || $_SESSION['userid'] == '0') {
						session_destroy();
						print("<a href='admin/'>Click here</a> To login as admin.");
					}
				?>
			</td></tr>
			<tr><td bgcolor="dedede" colspan=2><b>Login to MigrantWatch</b></td></tr>
			<tr>
				<td align=right>
						Your Email Address :
				</td>
				<td>
					<input type=text name=email style="width:200px;"> *
				</td>
			</tr>
			<tr>
				<td align=right>
					Password :
				</td>
				<td>
					<input type=password name=pwd style="width:200px;"> *
					&nbsp;<input type=submit value= "Login"  class=buttonstyle onclick="javascript:return validate();">
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center>
					<br><input type=hidden name="cmd" value="login" /><br>
				</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td align=left>
				<a href="pwdreset.php">Reset Password</a>
				&nbsp;|&nbsp;
				<a href="register.php">New User?</a>
			</td></tr>
		</table>
		</form>

     <!---------LOGIN FORM END----------------------->
<?php
	include('footer.php');
?>