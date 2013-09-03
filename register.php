<?php session_start(); 
      $page_title="MigrantWatch: Sign up";
      include("main_includes.php");
?>
<body>
<?
   include("header.php");

?>
<div class="container first_image">
         <div id='tab-set'>   
             <ul class='tabs'>
                 <li><a href='#x' class='selected'>sign&nbsp;up</a></li>
             </ul>
         </div>
<?

if($_GET['error'] == 'captcha' ) {
   echo "<div class='notice'>You have entered an incorrect captcha. Please type the correct and captcha and register again</div>";

}

if( $_SESSION['userid'] ) {

   echo "<div class='notice'>You are logged in as " . $_SESSION['username'] . ". Please sign out and register</div>";
   
} else {

?>
<link rel="stylesheet" href="css/register.css" type="text/css">
<style>
.register td:first-child { vertical-align:top }
.error { color: red; font-weight:normal; font-size:10px; }
</style>
<script language="javascript" type="text/javascript">
   $(document).ready(function() { 
    	// validate signup form on keyup and submit 
    	$("#migwatch_register").validate({ 
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
                        }
			
	        },
		   
		messages: { 
            	   	fullname: "<br>enter your full name", 
			email: { 
                	       required: "<br>please enter a valid email address", 
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
			
			city: "<br>please enter a city",
			
			state: "<br>please enter a state",
			country: "<br>please enter a country",
			
                        mobile_no: "<br>only digits allowed",
	      }
	  });
    });		
</script>

<form id="migwatch_register" name=frm_register action=userlogin.php method=POST>
	<table class='register' cellpadding=2 cellspacing=2>
	<tr>
		<td colspan=2 align=right style="color:red;font-weight:bold;">
		<?php
			if ($_GET['cmd'] == "duplicateemail")
			   echo "<div class='notice'>This email is already registered.</div>";
		?>
		</td>
	</tr>		
      	<tr>
		<td>Your Full Name *</td>
		<td><input type=text name=fullname></td>
		</tr>
		<tr>
			<td>Email Address *</td>
			<td><input type=text name=email  autocomplete="off"></td>
		</tr>
		<tr>
			<td >Enter a Password *</td>
			<td><input type=password name=pwd id="pwd" autocomplete="off"></td>
		</tr>
		<tr>
			<td >Reenter the Password *</td>
			<td><input type=password name=pwd1 id="pwd1"></td>
		</tr>
                <tr>
                                <td>Mobile no. (+91)</td>
                                <td><input type=text name='mobile_no'></td>
                </tr>
		<tr>
                        <td align=right>Mailing Address </td>
                	<td><input type=text name=location></td>
                </tr>
                <tr>
                        <td align=right>Address (line 2) (optional)</td>
                	<td><input type=text name=location1></td>
                </tr>
                <tr>
                        <td align=right>Address (line 3) (optional)</td>
                	<td><input type=text name=location2></td>
                </tr>
                <tr>
                        <td align=right>City/Town/Village *</td>
                        <td><input type=text name=city></td>
                </tr>
                <tr>
                        <td align=right>District </td>
                	<td><input type=text name=dist></td>
                </tr>
		<tr>
		    <td >State or Union Territory *</td>
		    <td>
			<select name=state id="state" >
				<option value=''> -- SELECT -- </option>
			<?php
				include("db.php");
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
			</select>
		    </td>
	</tr>
	<tr>
		<td>Country *</td>
                <td>
			<input type=text name=country value = "India" />
                </td>
                </tr>
        <tr>
		<td align=right>Post code (PIN code)</td>
                <td>
                        <input type=text name=pin />
			<input type=hidden name="cmd" value="createnew" />
                </td>
         </tr>

	<tr><td colspan='2' style='text-align:center;'><input style='width:150px' type="submit" value="Register"></td></tr>
	<tr><td><b><small>Note: * indicates mandatory fields</small></b></td></tr>
       </table>
</form>
<? } ?>

<? include("credits.php"); ?>
</div>
</div>

<div class="container bottom">
</div>
<? include("footer.php"); include("login_includes.php"); ?>
</body>

</html>

