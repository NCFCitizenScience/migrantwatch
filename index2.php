<? session_start(); 
   include("db.php");
   //include("wordpress_includes.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>
</head>
<body>
<style> #map-show-link { text-decoration:none;margin-top:-10px;font-size:13px; } </style>
<? 
      
	include("header.php");
  	include("map-box.php"); 
?>
<!-- END OF HEADER -->
<div class="container first_image">
<table>
	<tr><td><?php echo $blog_content; ?></td></tr> 
</table>

<div><hr></div>
<div class="map-show-link" style="margin-top:-10px;">
<a href="#" id="map-show-hide">latest&nbsp;sightings&nbsp;<span style='color:#d95c15'>(+)</span></a></div>
      <div id="map" style="margin-left:8px;"></div>
		<ul id="list" style=""></ul>
		<div id="message"></div>



<table style="width:930px;margin-left:auto;margin-right:auto;">
       <tr><td colspan="2"><hr></td>
        <tr><td class="cms" style="border-right:solid 1px #d95c15;width:45%"><? echo mw_get_page('2'); ?></td><td class="cms" style="width:45%;padding-left:15px"><? echo mw_get_page('2'); ?></td></tr>
        <tr><td colspan="2"><hr></td></tr>
	<tr><td class="cms" colspan="2"><? echo mw_get_page('4'); ?></td></tr>
</table>


<script type="text/javascript">

$(document).ready(function() {
 $('#map').hide();
 $('#list').hide();
 var toggled = false; 
 $('#map-show-hide').click(function() { 
   $('#map').toggle();
   $('#list').toggle();
   
   if( toggled==false ) { $('#map-show-hide').html("latest&nbsp;sightings&nbsp;<span style='color:#d95c15'>(-)</span>"); toggled = true; } else {
        $('#map-show-hide').html("latest&nbsp;sightings&nbsp;<span style='color:#d95c15'>(+)</span>");
	toggled = false;
   }


 });
 $('.error_top').corner();
  
 $('.first_image').corner('bottom');
  //$('#rememberme').toggle();
 
});
</script>


</div>

</div>
</div>
<div class="container bottom">
<?php
$endtime = microtime();
$endarray = explode(" ", $endtime);
$endtime = $endarray[1] + $endarray[0];
$totaltime = $endtime - $starttime; 
$totaltime = round($totaltime,5);
echo "This page loaded in $totaltime seconds.";
?>
</div>


<?php 
   include("page_includes_js.php"); 
   include("login_includes.php");
?>
</body>
</html>
