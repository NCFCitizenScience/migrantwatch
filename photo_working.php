<?php
include("db.php");

?>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Anush Shetty</title>
<script src="http://code.jquery.com/jquery-latest.js"></script>
  <!-- Framework CSS -->
	<link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="../../blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
	
	<!-- Import fancy-type plugin for the sample page. -->
	<link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">
        
  </script>

</head>
  <body>

<!-- #F0F0F0 -->
<style>
  
#navlinks a{font-size:13px; font-weight:bold; color:#333;text-transform: uppercase;letter-spacing: 1px; color:green}

</style>





<?
$photo_id = $_GET['id'];
$tag_name = $_GET['tag'];

$query = "select photo_caption, photo_filename, sighting_id  from migwatch_photos where photo_id = '$photo_id'";
$result = mysql_query($query);
$data = mysql_fetch_assoc($result);
$photo_caption =  stripslashes($data['photo_caption']);

$photo_filename =  $data['photo_filename'];

list($width, $height, $type, $attr) = getimagesize("image_uploads/$photo_filename");


/*
$query = "select photo_id from  migwatch_photos  ORDER BY photo_id DESC ";
$result = mysql_query($query);

$n =0;
$pos;
$photos;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$photos[$n] = $line["photo_id"];	
	if ($photos[$n] == $photo_id) { $pos=$n;}
	$n++;
}

$prev_photo_id = $photos[$pos-1];
$next_photo_id = $photos[$pos+1];
$previous = $prev_photo_id;
$next = $next_photo_id ;
*/
?> 

<table id="loadbox">


<tr><td style="width:<? echo $width; ?>px"><div style="width:<? echo $width; ?>">
<div style="float:left;font-size:15px;font-family: Georgia,'Palatino'; color:#333;">

<? //print "<a href=\"?id=$previous&tag=" . urlencode($tag_name) . "\">&lt;&lt;&lt;</a>"; ?>
<? print "<a id='llinkd' href='#id=$prev' class='loadleft'>prev</a>"; ?>
</div>
<div  style="float:right; font-size:15px;font-family: Georgia,'Palatino'; color:#333;">

<b><? print "<a id='rlinkd' href='#id=$next' >next</a>"; ?></b>



</div></div>
</td></tr>
<tr><td>

</tr></td>

<tr><td>
<style>
a:link, a:visited {text-decoration: none; }
a:hover {text-decoration: underline;}
</style>

</td></tr>
<style>

#imgthingi {

 background: url(spinner.gif) no-repeat center center;


}

</style>
<tr><td>

 <? print "<div id='imgthing'><img class='imagethingi'  style=\"float:left;\" src=\"image_uploads/f_$photo_filename\"></div>"; ?>





	</div>
 
</td>



</tr> 

</table>

<? //include("footer.php"); ?>
</div>

	


</div>

<script>

//$(document).ready(function() {

 $('#rlinkd').click(function() {
 
  var hash = this.href;
  hash = hash.replace(/^.*#/, '');
  hash = hash.split('=');

  var hashid = hash[1];
  
  var data = 'id=' + encodeURIComponent(hashid);
  
        $.ajax({
                url: "loader.php",      
                type: "GET",            
                data: data,             
                cache: false,
                success: function (html) {      
                        var thtml = html.split("|");

                        //add the content retrieved from ajax and put it in the #content div
                        $('#imgthing').html(thtml[1]);
                        var rlink = thtml[0];
                        
                           
                        $('#rlinkd').attr('href', '#id=' + rlink); 
                        //display the body with fadeIn transition
                        $('#imgthing').fadeIn('slow');       
                     
                }               
        });

   
//});



});
</script>


</html>

