<? 
   session_start(); 
   $page_title="MigrantWatch: Login";
   include("main_includes.php");
   
?>
<body>
<? include("header.php"); ?>
<div class="container first_image">
     <div id='tab-set'>   
             <ul class='tabs'>
                 <li><a href='#x' class='selected'>login</a></li>
             </ul>
     </div>
<?
if ($_SESSION['username']) {
    echo "<div class='notice' style='width:900px;margin-left:auto;margin-right:auto;'>You are logged in as " . $_SESSION['username'] . "</div>";	
} else  {

if($_GET['cmd'] == 'confirmed' ) {
   echo "<div class='notice'>Your email address has been confimed. </div>";

}

?>
<form id="migwatch_login" name="frm_login" action="userlogin.php" method="POST">
<table class="login_page">
<?
 if ( $_GET['cmd'] == 'error' ) { 
?>
        <tr><td colspan="2" style="text-align:center;font-size:14px;color:#d95c15;">Invalid username/password</td></tr>
<? } ?>
	<tr><td colspan="2" style="text-align:center;font-weight:normal;font-size:12px;">Did you <a title='forgot password?' href='forgot_password.php'>forget</a> your password?</td></tr>
        <tr><td	colspan="2" style="text-align:center;font-weight:normal;font-size:12px;">Are you trying to <a title='register' href='register.php'>register</a>?</td>
 
<tr><td>Email</td><td><input type="text" name="email" /></td></tr>
<tr>
	<td>Password</td>
        <td>
        <input id="password-password" type="password" name="pwd" value="" autocomplete="off" />
	</td>
</tr>
<tr>
	<td></td>
        <td><input type="hidden" name="cmd" value="login" />
	<input type="hidden" name="done" value="<? echo $_GET['done']; ?>">
        <input style="width:70px" type="submit" value="login" onclick="">
        </td>
</tr>
</table>
</form>

<? } ?>
</div>

</div>
</div>
<div class="container bottom">

</div>
<script language="javascript" type="text/javascript">
   $(document).ready(function() { 
	 $("#pwd_reset").validate({ 
                rules: { 
                  
                       email: { 
                              required: true, 
                              email: true
                        }
		},
		messages: {
			email: {
                              required: "<br><div class='errorv'>Please enter an email address</div>",
                              email: "<br><div class='errorv'>Please enter a valid email address</div>"
        		}
		}
	});
        $("#migwatch_login").validate({ 
                rules: { 
                  
                       email: { 
                              required: true, 
                              email: true
                        }, 
                        pwd: { 
                             required: true, 
                             minlength: 6 
                        },
		},
		messages: {
			email: {
                              required: "<br><div class='errorv'>Please enter an email address</div>",
                              email: "<br><div class='errorv'>Please enter a valid email address</div>"
        		},
                        pwd: {
                             required: "<br><div class='errorv'>Please enter a password</div>",
                             minlength: "<br><div class='errorv'>The password has to be 6 characters long</div>"
        		},

		}
	});
   });
</script>
<style>
.login_page { width:400px; border: solid 1px #000 ;margin-top:20px;margin-left:auto;margin-right:auto;background-color:#ffcb1a; }
.login_page input[type=text] { width:200px; border:solid 1px #666 }
.login_page input[type=password] { width:200px; border:solid 1px #666 }
.errorv{  color: red; }
</style>
<?php 
include("login_includes.php"); 
include("footer.php");
?>
</body>
</html>
