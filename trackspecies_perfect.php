<? session_start(); 
  if(!isset($_SESSION['userid'])){
                header("Location: login.php");
                die();
        }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

<? 
   include("main_includes.php"); 
   include("db.php"); 
   include("functions.php"); 
?>


 
<script type="text/javascript" src="jquery-gradient/jquery.dimensions.js" ></script>
<script type="text/javascript" src="http://www.parkerfox.co.uk/labs/gradientz.js"></script>

<style>
#top {
background-image:url('images/pagetopstrip.png');
background-repeat:no-repeat;
background-position:top left;
margin-left:10px;
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

</head>
<body style="">
<script type="text/javascript" src="http://www.parkerfox.co.uk/labs/gradientz.js"></script>
<div class="container" id="top" style="width:990px;padding-top:30px; margin-left:auto;margin-right:auto;">
   
<div class="left">
  <div class="container" style="margin-left:auto;margin-right:auto;height:110px;margin-top:0px;padding-bottom:10px">
   

    <div style='float:left'>
      <!--<h2 style='margin-left:20px'>MigrantWatch</h2>-->
      <img style="margin-left:0" src="logo/mwlogo.png" alt="MigrantWatch">
    </div>
    <div style='float:right;margin-top:26px'>
      <form name="frm_login" action="process_details.php" method="POST">
		<table>
   
	  <tr>
        <td class="small-link" style="margin:0;padding:0;font-size:10px;">
           <table style="margin:0;padding:0;width:390px;"><tr>
           <td style="text-align:right;width:150px"><a  href="#">signup!</a></td>
           <td style="padding-left:35px"><a href="#">forgot?</a></td>
           <td style="text-align:right;padding-right:33px">
                  <a id="rememberme"  href="#">remember me</a>
                  
           </td>
           </table>
         </td></tr>
             <tr>
            
	    <td style="padding:0;margin:0;"><input class="default-value login-box" type="text" name="email" value="email id" />
	   <input id="password-clear" class="login-box" type="text"  value="password" autocomplete="off"/>
	      <input class="login-box" id="password-password" type="password" name="password" value="" autocomplete="off" />
	    <input style="width:30px;border:solid 1px #666" type="submit" value="go"></td>
	  </tr>
      </table>
      <table style="margin-top:-15px;margin-left:-8px; width:390px; text-align:left" class="main-links"> 
          <td style=""><a href="#">report sightings</a></td>
          <td style=''>|</td>
          <td style=""><a href="#">edit sightings</a></td>
          <td style=''>|</td>
          <td style=""><a href="#">profile</a></td>
	  
             
          </tr>
		</table>
      </form>
    </div>
  </div>

<div class="gradient1 container" style="width:950px;height:166px; margin-top:-20px;">
 
   
</div>
<style>

.small-link , .small-link a {
color: #d95c15;


}
.main-links td{

//padding-left:3px;

padding-bottom:0px;
color:#c0c0c0;
text-decoration:none;
font-size:14px;


}

.main-links a{
color:#d95c15;
text-decoration:none;
font-weight:bold;
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
<div style="float:right"><input type="search" style="border:solid 1px #666;" value="search"><input type="submit" style="margin-right:8px;border:solid 1px #666;width:30px" value="go"></div>
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
padding:5px;
margin-left:8px;
width:170px;
margin-top:0px;
text-align:center;
margin-bottom:10px;

}

.map-show-link a{
color: #333;
text-decoration:none;
font-size:14px;

}



.filter td {


padding-bottom:0px;
margin-bottom:0px;
}

</style>
  



  <div class="container gradient1" style="width:950px;">

   
   <div  style="width:950px; margin-left:auto; margin-right:auto;padding-bottom:40px;">
               
		<div style='width:950px;' id='tab-set'>
           
   			<ul class='tabs' style=''>
   
   				<li style='margin-left:20px;'><a href='#text1' class='selected'>first</a></li>
   				<li><a href='#text2'>last</a></li>
   				<li><a href='#text3'>general</a></li>
   
   			</ul>
   			<div id='text1' style='width:920px;margin-top:0'>
   			<p>   
                           
   				<? include("first_sighting_new_kachch.php"); ?>

			</p>
			</div>

			<div id='text2'>
				<p><? //include("first_sighting.php"); ?></p>
			</div>

			<div id='text3'>
				<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
			</div>

		</div>
	</div>
	</div>

</div>

<div class="container" id="bottom" style="width:990px;padding-top:30px; background-color:#fff; margin-left:auto;margin-right:auto;">

</div>


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
width:180px;

}

</style>

 
<script type="text/javascript">
$('.default-value').each(function() {
    var default_value = this.value;
    $(this).css('color', '#666'); // this could be in the style sheet instead
    $(this).focus(function() {
        if(this.value == default_value) {
            this.value = '';
            $(this).css('color', '#333');
        }
    });
    $(this).blur(function() {
        if(this.value == '') {
            $(this).css('color', '#666');
            this.value = default_value;
        }
    });
});

$('#password-clear').show();
$('#password-password').hide();

$('#password-clear').focus(function() {
    $('#password-clear').hide();
    $('#password-password').show();
    $('#password-password').focus();
});
$('#password-password').blur(function() {
    if($('#password-password').val() == '') {
        $('#password-clear').show();
        $('#password-password').hide();
    }
});
</script>



<script>
$(document).ready(function() {

	$('#map').show();
   $('#list').show();
   
    $('#map-show-hide').click(function() {   
      $('#map').toggle();
      $('#list').toggle();
    });

   
    //$('#rememberme').toggle();

     $("#rememberme").click(function () {
       $("#rememberme").html('remembered');
    });

});

</script>
<!--
<? include("tab_include.php"); ?>

<script type="text/javascript" src="jquery-gradient/jquery.dimensions.js" ></script>


<script type='text/javascript'>

$(document).ready(function(){
$('.gradient1').gradientz({
        start: "#fffff9",     // start color: default is the background color.
        end: "#ece0ca"
});


});
</script>
<div class='container gradient1'>
<h2>hello</h2>
<div style="width:950px;margin-left:auto;margin-right:auto" class='tab-set'>
   <ul class='tabs'>
   
           <li><a href='#text1' class='selected'>Tab1</a></li>
           <li><a href='#text2'>Tab 2</a></li>
 
   </ul>
   <div id='text1'>
              <? include("first_sighting_new_kachch.php"); ?>
              test123
  </div>
  <div id='text2'>
          Content of tab #2
   </div>
</div>


</div>-->


<? include("tab_include2.php"); ?>

</body>
</html>
