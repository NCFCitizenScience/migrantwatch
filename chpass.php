<?php
	session_start();


	include("header.php");
?>
<!----------Change Password FORM START--------------------->
	<script language="javascript">
		function isEmpty(s)
		{   return ((s == null) || (s.length == 0))
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

		function isWhitespace (s)

		{   // Is s empty?
			return (isEmpty(s) || reWhitespace.test(s));
		}




		function validate(){
			if(isWhitespace(document.frm_chpass.opwd.value))
			{
				alert('Please enter old password');
				return false;
			}


			if(document.frm_chpass.pwd.value != document.frm_chpass.pwd1.value){
				alert("The new passwords dont match. Please re-enter.");
				return false;
			}

			if(document.frm_chpass.pwd.value.length < 6){
				alert("The new password should be atleast 6 characters long.");
				return false;
			}

			return true;

		}
	</script>
	<div class="container first_image">
	<FORM name=frm_chpass action=process_details.php method=POST>
	<table width=770 cellpadding=2 cellspacing=0>
	<tr bgcolor="dedede"><td ><b>Change Password&nbsp;</b></td>
	<td align=right>					
			
	</td>
	</tr>
		<tr>			
			<td colspan=2 style="color:red;text-align:right;font-weight:bold;">
				<?php
					if ($_GET['cmd'] == "incorrectopwd")
						print("Sorry, the old password was not correct.");
				?>&nbsp;
			</td>
		</tr>

		<tr>
			<td align=right>Email Address :</td>
			<td><b>
			<?php
				if (isset($_SESSION['userid'])){
					include("./db.php");
					$result = mysql_query("SELECT user_email FROM migwatch_users WHERE user_id=".$_SESSION['userid']);
					if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
							$email = $row{'user_email'};
					}
					print ($email);
				}
				else
					die("Session not available. Fatal error.");
			?>
			</b></td>
		</tr>
		<tr><td align=right>Enter Current Password :</td>
			<td><input type=password name=opwd style="width:200px;" maxlength=20> *</td>
		</tr>
		<tr>
			<td align=right>Enter New Password :</td>
			<td><input type=password name=pwd style="width:200px;" maxlength=20> *</td>
		</tr>
		<tr>
			<td align=right>Reenter New Password :</td>
			<td><input type=password name=pwd1 style="width:200px;" maxlength=20> *</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>				
				<input type=submit value= "Change Password" class=buttonstyle onclick="javascript:return validate();">
				&nbsp;
				<input type=hidden name="cmd" value="chpass" />				
			</td>
		</tr>
		<tr><td colspan=2><br><li><b>Asterisks (*) indicate required fields.</b></td></tr>
	</table>
	</form>
	</div>
     <!---------Change Password FORM END----------------------->
<?php
	include('footer.php');
?>