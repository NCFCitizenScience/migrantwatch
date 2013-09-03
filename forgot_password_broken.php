<?php
	session_start(); 
	$page_title="MigrantWatch: Reset password";
	include("main_includes.php"); 
?>
<body>
<? 
      
        include("header.php");
?>
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

				function validate_reset(){
					if (isWhitespace(document.frm_reset.email.value)){
						alert('Please enter email address.');
						return false;
					}



					return true;

				}

		</script>
		<div class="container first_image">
		     <FORM name=frm_reset action=userlogin.php method=POST>
		     <table style="width:500px;margin-left:auto;margin-right:auto">
			<tr><td colspan=2><h4>Forgot password ?</h4></td></tr>
			<tr><td colspan="2"><b>Please enter your email address. The new password will be mailed to you</b></td></tr>
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
				<td style="text-align:right" width=150>
						<b>Your Email Address</b>
				</td>
				<td>
					<input type=text name=email style="width:200px;"> *
				</td>
			</tr>
			<tr>
				<td colspan=2 style='text-align:center'>
					<input type=submit value="Issue New Password" onclick="javascript:return validate_reset();" class=buttonstyle />
					&nbsp;<input type=button value="Cancel" onclick="javascript:window.location.href='login.php';" class=buttonstyle />
					<br><input type=hidden name="cmd" value="pwdreset" /><br>
				</td>
			</tr>
			<?php
				}
			?>
		</table>
		</form>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<? include("footer.php"); ?>
</body>
</html>

