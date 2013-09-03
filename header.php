<?php
	include("db.php");	
	include("functions.php");
		
?>
<div class="container top">
     <div class="left">
     	  <div class="container logo-box">
    	      <div class='logo_div'>
      	       	    <a href='http://migrantwatch.in'><img style="margin-left:0" src="logo/mwlogo.png" alt="MigrantWatch" title="Click on the logo to go back to the main page"></a>
    	      </div>
	      <div class='lbox_div'>
      	      	   <form name="frm_login" action="userlogin.php" method="POST">
		   <table>
	  <? if(  $_SESSION['userid'] ) { ?>
             	   <tr>
			<td style='text-align:right;'><? echo $_SESSION['username']; ?>&nbsp;( <a href='updateprofile.php'>profile</a>&nbsp;/&nbsp;<a href='userlogin.php?cmd=logout'>sign out</a> )&nbsp;</td>
             	   </tr>
                  <? } else { ?>
	  	  <tr>
			<td class="small-link">
           		    <table class='ltable'>
			    <tr>
           		    	   <td style="text-align:right;width:150px"><a title="register"  href="register.php">signup!</a></td>
           			   <td style="padding-left:35px">
				       <a title="forgot password ?" href="forgot_password.php">forgot?</a>
				   </td>
				   <td style="text-align:right;padding-right:33px">
                  		       <a  title="remember me" class="rememberme"  href="#">remember me</a> 
				       <a style="display:none" class="rememberme"  href="#">remembered</a>
                  		       <input type="hidden" value="0" id="remember" name="remember">
          			   </td>
	   		    </tr>
           		    </table>

         		</td>
		  </tr>
             	  <tr>
			<td style="padding:0;margin:0;border-left:solid 1px">
		  	    <input class="default-value login-box" type="text" name="email" value="email id">
		  	    <input id="password-clear" class="login-box" type="text"  value="password">
		  	    <input class="login-box" id="password-password" type="password" name="pwd" value="">
                  	    <input type="hidden" name="cmd" value="login">
		  	    <input style="width:30px;height:26px;border:solid 1px #666" type="submit" value="go" onclick="javascript:return validate();">
			</td>
	    	  </tr>
	     <? } ?>
      	     	  </table>
      		  <table class="main-links"> 
         	  <tr>
			<td style=""><a href="addsightings.php">report&nbsp;sightings</a></td>
          		<td style=''>|</td>
          		<td style=""><a href="editsightings.php">edit&nbsp;sightings</a></td>
          		<td style=''>|</td>
          		<td style=""><a href="watchlist.php">my&nbsp;watchlist</a></td>
         	  </tr>
		  </table>
      		</form>
    	       </div>
  	  </div>
	  <div class="container main_banner">
    
	  </div>
	  <div class="container menu-links">
 	   <ul id="jsddm">
    	   <li><a href="#">join</a>
        <ul>
            <li><a href="join.php">why join</a></li>
        </ul>
    </li><li>|</li>
    <li><a href="#">what to do</a>
        <ul>
            <li><a href="watch_migrants.php">watch migrants</a></li>
            <li><a href="identify_migrants.php">identify migrants</a></li>
            <li><a href="submit_data.php">submit data</a></li>
        </ul>
    </li><li>|</li>
	<li><a href="#">participants</a>
        <ul>
            <li><a href="participants.php">individuals</a></li>
            <li><a href="groups.php">groups</a></li>
            <li><a href="participant_map.php">participant map</a></li>
        </ul>
    </li><li>|</li>
 <li><a href="#">species</a>
        <ul>
            <li><a href="migrants_we_watch.php">migrants we watch</a></li>
            <li><a href="guide.php">species guide</a></li>
            <li><a href="highlighted_species.php">highlighted species</a></li>
            <li><a href="all_species.php">all species</a></li>
        </ul>
    </li><li>|</li>
	<li><a href="#">campaigns</a>
        <ul>
            <li><a href="piedcuckoo.php">pied cuckoo</a></li>
            
        </ul>
    </li><li>|</li>
	<li><a href="#">data</a>
        <ul>
            <li><a href="data.php">view data/maps</a></li>
            <li><a href="terms_of_use.php">terms of use</a></li>
        </ul>
    </li><li>|</li>
	<li><a href="#">resources</a>
        <ul>
            <li><a href="getting_started.php">getting started with indian birds</a></li>
            <li><a href="bird_migration.php">bird migration and climate change</a></li>
            <li><a href="citizen_science_projects.php">citizen science projects</a></li>
	    <li><a href="publications.php">migrantwatch publications</a></li>
        </ul>
    </li><li>|</li>
	<li><a href="#">news</a>
        <ul>
            <li><a href="media.php">media</a></li>       
        </ul>
    </li><li>|</li>
	<li><a href='http://migrantwatch.in/blog'>blog</a></li><li>|</li>
	<li><a href='faq.php'>faq</a></li><li>|</li>
	<li><a href='#'>about</a>
	  <ul>
	    <li><a href="about.php">about us</a></li>
            <li><a href="acknowledgements.php">acknowledgements</a></li>
         </ul>
	</li>
</ul>

 <div style="float:right">
      <!--<form id="search_form" action="search/index.php" method="get">-->
      <input type="text" name="query" id="query" style="width:200px;border:solid 1px #666;" value="type a species name" delay="1500">
      <input type="hidden" name="search" value="1">&nbsp;
      <!--<input type="submit" class="submit" value="go">
      </form>-->
 </div>
</div>

