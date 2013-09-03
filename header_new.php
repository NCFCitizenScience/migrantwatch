<style>
#top {
background-image:url('images/pagetopstrip.png');
background-repeat:no-repeat;
background-position:top left;
//margin-left:10px;
}

.left {
background-image:url('images/lefttorightfullpage.png');
background-repeat:repeat-y;
margin-left:-1px;

}

#bottom {
background-image:url('images/pagebottomstrip.png');
background-repeat:no-repeat;
//background-position:top left;

}


</style>
<style type="text/css">
#password-clear {
    display: none;
    color:#666;
}

.login-box {

border: solid 1px #666;
margin-right:0px;
margin-left:-4px;
padding-left:0;
padding-right:0;

}

</style>

<div class="container" id="top" style="width:990px;padding-top:30px; background-color:#fff; margin-left:auto;margin-right:auto;">
   
<div class="left">
  <div class="container" style="margin-left:auto;margin-right:auto;height:110px;padding-top:8px;padding-bottom:10px">
   

    <div style='float:left'>
      <!--<h2 style='margin-left:20px'>MigrantWatch</h2>-->
      <img style="margin-left:0" src="logo/mwlogo.png" alt="MigrantWatch">
    </div>
    <div style='float:right;'>
      <form name="frm_login" action="process_details.php" method="POST">
		<table>
      
	  <tr>
        <td class="small-link" style="margin:0;">
           <table style="margin:0;padding:0;width:300px;"><tr>
           <!--<td style="text-align:right;width:120px"><a  href="#">signup!</a></td>
           <td style="padding-left:20px"><a href="#">forgot?</a></td>
           <td style="text-align:right">
                  <a id="rememberme"  href="#">remember me</a>-->
                  
           </td>
           </table></tr>
         </td></tr>
          
	    <? if(  $user_id ) { ?>
             <tr>
                 <td style='text-align:right;'>
		     <? $user_name = getUserNameById($user_id,$connect); echo $user_name; ?>
		     &nbsp;( <a href='login.php?cmd=logout'>Log Out</a> )&nbsp;
		 </td>
             </tr>
            <? } else { ?><tr>
	    <td style="padding:0;margin:0"><input class="default-value login-box" type="text" name="email" value="email id" />
	   <input id="password-clear" class="login-box" type="text"  value="password" autocomplete="off"/>
	      <input class="login-box" id="password-password" type="password" name="password" value="" autocomplete="off" />
	    <input style="width:30px;border:solid 1px #666" type="submit" value="go"></td>
	  </tr>
          <? } ?>
          <tr>
             <td class="main-links">
               <table><tr><td><a href="#">report sightings</a></td><td>|</td>
               <td><a href="#">edit sightings</a></td><td>|</td>
               <td><a href="#">profile</a></td></tr></table>
	       <!--<table>
		<tr>
	         <td class="main_links"><a href="#">report sightings</a>&nbsp;|&nbsp;</td><td><a href="#">edit sightings</a></td><td><a href="#">your profile</a></td>
                </tr>
                </table>-->
              </td>
          </tr>
		</table>
      </form>
    </div>
  </div>

<div class="container" style="width:950px;height:166px; margin-top:-20px; background-color:#fff;border:solid 1px">
    <h2>Banner<h2>
</div>
<style>

.small-link , .small-link a {
color: #d95c15;

}
.main-links td{

text-align:right;
padding-left:3px;
padding-bottom:10px;
color:#d95c15;
text-decoration:none;
font-size:14px;
font-weight:bold;

}

.main-links a{
color:#d95c15;
text-decoration:none;
}

.menu-links {

color:#c0c0c0;
width:950px;
height:40px;
padding-top:4px; 
background-image: url('images/mainmenugray-yello.png');
background-repeat:repeat-x;
font-size:15px;


}
.menu-links a{ 
color: #fff;
text-decoration:none;
font-weight:bold;
}

.menu-links a:hover{ 
color: #ffcb1a;

}


</style>
<div class="container menu-links">
 <div style="float:left; padding-left:10px"><a href="#">join</a>&nbsp;|&nbsp;
 <a href="#">why join</a>&nbsp;|&nbsp;
 <a href="#">species</a>&nbsp;|&nbsp;
 <a href="#">data</a>&nbsp;|&nbsp;
 <a href="#">participants</a>&nbsp;|&nbsp;
 <a href="#">publications</a>&nbsp;|&nbsp;
 <a href="#">resources</a>&nbsp;|&nbsp;
 <a href="#">faq</a>
 </div>
<div style="float:right"><input type="search" style="border:solid 1px #666;text-align:right" value="search"><input type="submit" style="margin-right:8px;border:solid 1px #666;width:30px" value="go"></div>
</div>
<!--<div class="container" style="width:100px; height:100px;background-color:">-->
<style type="text/css" media="screen">

.first_image {
background-color:#fffff9;
background-image: url("images/gradientbg.png");
background-repeat: repeat-x;
background-position: 0 100px;

}

hr {

background:#d95c15;
//border: solid 0.2px;
//margin-top:100px;
margin-bottom:0px;

}

.map-show-link{
background-color: #ffcb1a;
padding:10px;
margin-left:8px;
width:200px;
margin-top:0px;
text-align:center;
font-weight:bold;
margin-bottom:10px;

}

.map-show-link a{
color: #333;
text-decoration:none;
font-size:14px;

}


</style>
