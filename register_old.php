
	<!----------REGISTRATION FORM START--------------------->
	<?php
		include("banner_noauth.html");
	?>


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

		function isWhitespace(s) {   // Is s empty?
			return (isEmpty(s) || reWhitespace.test(s));
		}

		function isEmail(s) {
			if (isEmpty(s)) {
				if (isEmail.arguments.length == 1) {
					return defaultEmptyOK;
				} else {
					return (isEmail.arguments[1] == true);
				}
			} else {
				return reEmail.test(s)
			}
		}


		function validate(){
			if(isWhitespace(document.frm_register.fullname.value)) {
				alert('Name cannot be empty.');
				document.frm_register.fullname.focus();
				return false;
			}
			if (!isEmail(document.frm_register.email.value)) {
				alert('Email Address either empty or invalid! Please re-enter.');
				document.frm_register.email.focus();
				return false;
			}
			if(isWhitespace(document.frm_register.pwd.value)) {
				alert("Please enter Password.");
				document.frm_register.pwd.focus();
				return false;
			}
			if(document.frm_register.pwd.value.length < 6){
				alert("The password should be atleast 6 characters long.");
				document.frm_register.pwd.focus();
				return false;
			}
			if(isWhitespace(document.frm_register.pwd1.value)) {
				alert("Please re-enter the Password");
				document.frm_register.pwd1.focus();
				return false;
			}
			if(document.frm_register.pwd.value != document.frm_register.pwd1.value){
				alert("The passwords dont match. Please re-enter.");
				document.frm_register.pwd1.focus();
				return false;
			}
			if(isWhitespace(document.frm_register.city.value)) {
				alert("Please enter your city/town/Village");
				document.frm_register.city.focus();
				return false;
			}
			if(document.frm_register.state.value == '-1') {
				alert("Please select the state");
				return false;
			}
			if(isWhitespace(document.frm_register.country.value) || (document.frm_register.state.value == '36' && document.frm_register.country.value.toLowerCase() == 'india')) {
				alert("Please enter country name");
				document.frm_register.country.focus();
				return false;
			}
			if(document.frm_register.state.value != '36') {
				document.frm_register.country.value = 'India';
			}
			return true;

		}
	</script>
	<FORM name=frm_register action=process_details.php method=POST>
	<table width=585 cellpadding=2 cellspacing=2>
		<tr>
			<td style="font-weight:bold;">Participant Registration Form</td>
			<td align=right style="color:red;font-weight:bold;">
				<?php
					if ($_GET['cmd'] == "duplicateemail")
						print("This email is already registered.");
				?>
			</td>
		</tr>
		<tr><td bgcolor="dedede" colspan=2><b>Personal Details</b></td></tr>
		<tr>
			<td align=right>Your Full Name :</td>
			<td><input type=text name=fullname style="width:200px;"> *</td>
		</tr>
		<tr>
			<td align=right>Email Address :</td>
			<td><input type=text name=email style="width:200px;"> *</td>
		</tr>
		<tr>
			<td align=right>Enter a Password :</td>
			<td><input type=password name=pwd style="width:200px;"> *</td>
		</tr>
		<tr><td align=right>Reenter the Password :</td>
			<td><input type=password name=pwd1 style="width:200px;"> *</td>
		</tr>
		<tr>
			<td valign=top bgcolor="dedede" colspan=2><b>Where do you live?</b></td>
		</tr>
		<tr>
			<td align=right>Mailing Address </td>
	        <td><input type=text size=28 name=location></td>
		</tr>
		<tr>
			<td align=right>Address (line 2) </td>
	        <td><input type=text size=28 name=location1></td>
		</tr>
		<tr>
			<td align=right>Address (line 3)</td>
	        <td><input type=text size=28 name=location2></td>
		</tr>
		<tr>
			<td align=right>City/Town/Village</td>
			<td><input type=text size=28 name=city> *</td>
		</tr>
		<tr>
			<td align=right>District </td>
	        <td><input type=text size=28 name=dist></td>
		</tr>
		<tr><td align=right>State or Union Territory </td>
			<td>
				<SELECT name=state >
					<option value=-1> -- SELECT -- </option>
				<?php

					include("./db.php");
					$result = mysql_query("SELECT state_id, state FROM migwatch_states order by state");
					if($result){
						while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							if($row['state'] != 'Not In India') {
								print "<option value=".$row{'state_id'}.">".$row{'state'}."</option>\n";
							} else {
								$other_id = $row['state_id'];
								$other = 'Not In India';
							}
						}
						print("<option value=".$other_id.">".$other."</option>");
					}
				?>
				</select> *
			</td>
		</tr>
		<tr><td align=right>Country</td>
			<td>
				<input type=text name=country size=28 value = "India" /> *
			</td>
		</tr>
		<tr><td align=right>Post code (PIN code)</td>
			<td>
				<input type=text name=pin size=28 />
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center>
				<br>
				<input type=submit value= "Register Me"  class=buttonstyle onclick="javascript:return validate();">
				&nbsp;&nbsp;
				<input type=reset  value="Clear"  class=buttonstyle>
				&nbsp;
				<input type=reset  value="Cancel"  class=buttonstyle onclick="javascript:window.location.href='login.php';">
				<input type=hidden name="cmd" value="createnew" />
				<br>
			</td>
		</tr>
		<tr><td colspan=2><br><li> <b>Asterisks (*) indicate required fields.</b>
			<li>Providing your mailing address will enable us to send you printed
material about MigrantWatch, including educational material and
summaries of results.
		</td></tr>

	</table>
</form>

     <!---------REGISTRATION FORM END----------------------->
<?php
	include('footer.php');
?>