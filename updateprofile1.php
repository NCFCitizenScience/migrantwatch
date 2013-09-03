<?php   
	include("auth.php");
	$page_title="MigrantWatch: Update profile";
	include("main_includes.php");
?>
<body>
	<style type="text/css">
	   .season { width:85%;font-size:12px; }
	   .season_td { width:140px; }
	   .species_td { width:400px; }
	   .species { width: 350px; }
	   #state { width:93%; }
	   .editpro input[type=text] { width:250px; }	   
	   .editpro input[type=password] { width:250px; }
	   .editpro select { width:250px; }
	   .errorv { font-size:10px; }
	</style>
<?
	include("header.php");
	if(isset($_SESSION['userid'])){
		$sql = "SELECT user_name, user_email, address, address1,mobile_no, address2, city, district, state_id, country, pincode FROM migwatch_users WHERE user_id=".$_SESSION['userid'];
		$result = mysql_query($sql);
				if($result){
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
						$user_name = $row{'user_name'};
						$user_email = $row{'user_email'};
						$address = $row{'address'};
						$address1 = $row{'address1'};
						$address2 = $row{'address2'};
						$city = $row{'city'};
						$district = $row{'district'};
						$state_id = $row{'state_id'};
						$country = $row{'country'};
						$pincode = $row{'pincode'};
						$mobile_no = $row{'mobile_no'};
					}
				}
	}


	?>

<div class="container first_image">
	<div id='tab-set'>
             <ul class='tabs'>
                 <li><a href='#x' class='selected'>edit&nbsp;profile</a></li>
             </ul>
         </div>

        <!--<form id="choose_username" name="choose_username" action="chooseusername.php" method=POST>
	<table class="editpro">
               <tr><td colspan="2" style="text-align:left"><h4 style='font-weight:bold'>Choose&nbsp;username</h4></td></tr>
	       <tr>
                        <td>Current username</td>
                        <td><? echo $user_name_a; ?></td>
                </tr>
		<tr>
                        <td>Preferred username</td>
                        <td style="width:500px"><input id="username"  type="text" name="username" onkeyup="twitter.updateUrl(this.value)" class="inn"/>
			&nbsp; http://migrantwatch.in/<span id="username_url"  style="color:#006600; font-weight:bold;">USERNAME</span> </td>
                </tr>
		<tr><td></td><td><div id="status"></div></td></tr>
		<tr><td colspan="2" style="text-align:center"><input type="submit" value="Change username" name="change_username">
	</table>
	</form>-->

        <form id="changepass" name=changepass action=userlogin.php method=POST>
	<table class="editpro">
	<tr><td colspan="2" style="text-align:left"><h4 style='font-weight:bold'>Change&nbsp;password</h4></td></tr>
                <tr>
                        <td>Current password *</td>
                        <td><input type="password" name="current_pass" autocomplete="off" maxlength=100></td>
                </tr>

                <tr>
                        <td>New password *</td>
                        <td><input type="password" id="new_pass" name="new_pass" autocomplete="off" maxlength=100></td>
                </tr>

                <tr>
                        <td>New password (repeat) *</td>
                        <td><input type="password" name="new_pass2" id="new_pass2" autocomplete="off" maxlength=100></td>
                </tr>
		<tr><td colspan="2" style="text-align:center"><input type='hidden' name='cmd' value='chpass'><input type="submit" value="Change password" name="change_pass">
	</table>
	</form>
	<FORM  id="editprofile" name=frm_profile action=userlogin.php method=POST>
	<table class="editpro">
		<tr>
			
			<td style="color:red;text-align:right;font-weight:bold;">
				<?php
					if ($_GET['cmd'] == "saved")
						print("Changes saved successfully.");
				?>
			</td>
		</tr>
		<tr><td colspan="2" style="text-align:left"><h4 style='font-weight:bold'>Update&nbsp;personal&nbsp;information</h4></td></tr>
		<tr>
			<td align=right>Your Full Name *</td>
			<td><input type=text name=fullname value="<?php print htmlentities($user_name); ?>" maxlength=100></td>
		</tr>
		<tr><td align=right>Email Address</td>
			<td><?php print($user_email); ?></td>
		</tr>
		<tr>
                        <td align=right>Mobile no. (+91)</td>
                <td><input type=text size=28 name=mobile_no maxlength=200 value="<?php print htmlentities($mobile_no); ?>"></td>
	
		<tr>
			<td align=right>Mailing Address (Line 1)</td>
	        <td><input type=text size=28 name=location maxlength=200 value="<?php print htmlentities($address); ?>"></td>
		</tr>
		<tr>
			<td align=right>Mailing Address (Line 2)</td>
	        <td><input type=text size=28 name=location1 maxlength=200 value="<?php print htmlentities($address1); ?>"></td>
		</tr>
		<tr>
			<td align=right>Mailing Address (Line 3)</td>
	        <td><input type=text size=28 name=location2 maxlength=200 value="<?php print htmlentities($address2); ?>"></td>
		</tr>
		<tr><td align=right>City/Town/Village *</td>
			<td><input type=text size=28 name=city maxlength=100 value="<?php print htmlentities($city); ?>"></td>
		</tr>
		<tr>
			<td align=right>District</td>
			<td><input type=text size=28 name=dist maxlength=50 value="<?php print htmlentities($district); ?>"></td>
		</tr>
		<tr>
			<td align=right>State or Union Territory *</td>
	        <td>
				<SELECT name=state>
			<?php

			
			$result = mysql_query("SELECT state_id, state FROM migwatch_states ORDER BY state");
			if($result){
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
					if($row['state'] != 'Not In India') {
						print "<option value=".$row{'state_id'};
						if ($row{'state_id'} == $state_id)
							print " selected ";
						print ">".$row{'state'}."</option>\n";
					} else {
						$other_id = $row['state_id'];
						$other = $row['state'];
					}
				}
				print("<option value=".$other_id);
				if($other_id == $state_id)
					print " selected ";
				print ">".$other."</option>\n";
			}
			?>
			</select></td>
		</tr>
		<tr><td align=right>Country *</td>
					<td>
						<input type=text name=country size=28 value="<?php print($country); ?>" />
					</td>
				</tr>
				<tr><td align=right>Post code (PIN code)</td>
					<td>
						<input type=text name=pin size=28 value="<?php print($pincode); ?>"/>
					</td>
		</tr>
		<tr>
			<td colspan=2 style="text-align:center">
				
				<input type=submit value= "Save your profile"  class=buttonstyle onclick="javascript:return validate();">
				<input type=hidden name="cmd" value="editprofile" />
			
			</td>
		</tr>
	</table>
<? // } ?>
<script language="javascript" type="text/javascript">
   $(document).ready(function() {
        // validate signup form on keyup and submit
	$("#changepass").validate({
		rules: {
		        current_pass: {
			    required:true
			},
		        new_pass: {
                             required: true,
                             minlength: 6
                        },
                        new_pass2: {
                              required: true,
			      equalTo: "#new_pass"
			},

		},
		messages: {
			current_pass: {
		             required:"<br><div class='errorv'>Please enter your old password</div>"
			},
			new_pass: {
                             required: "<br><div class='errorv'>Please enter your new password</div>",
                             minlength: "<br><div class='errorv'>The password should be 6 characters long</div>"
                        },
                        new_pass2: {
                              required: "<br><div class='errorv'>Please repeat your new password</div>",
                              equalTo: "<br><div class='errorv'>The passwords don't match</div>"
                        },
                }
	});
        $("#editprofile").validate({
                rules: {
                       fullname: "required",
                       email: {
                              required: true,
                              email: true
                        },
                        pwd: {
                             required: true,
                             minlength: 6
                        },
                        pwd1: {
                              required: true,
                              minlength: 6,
                              equalTo: "#pwd"
			},

			city: {
                              required: true
                        },

                        state: {
                              required: true
                        },
                        country: {
                              required: true
                        },
                        mobile_no: {
                              digits: true
                        },

                },
		messages: {
                        fullname: "<br><div class='errorv'>enter your full name</div>",
                        email: {
                               required: "<br><div class='errorv'>please enter a valid email address</div>",
                               minlength: "<br><span>please enter a valid email address</span>",
                        },
                        pwd: {
                               required: "<br>please provide a password",
                               rangelength: jQuery.format("<br>enter at least {0} characters")
                        },
                        pwd1: {
                              required: "<br>please repeat your password",
                              minlength: jQuery.format("<br>enter at least {0} characters"),
                              equalTo: "<br>enter the same password as above"
                        },
			
                        city: "<br><div class='errorv'>please enter a city</div>",
                        
                        state: "<br><div class='errorv'>please enter a state</div>",
                        country: "<br><div class='errorv'>please enter a country</div>",
                        mobile_no: "<br><div class='errorv'>only digits allowed</div>",
              }
          });
    });
</script>

<style>
.editpro { width: 500px; margin-left:auto;margin-right:auto; }

.editpro td:first-child {
      font-weight:bold;
      text-align:right;
      width:40%;
}

.errorv { color: red; }
</style>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>

<? include("footer.php"); ?>
</body>
</html>
