<?php
include("../sightings/db.php");

?>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Anush Shetty</title>
<script src="http://code.jquery.com/jquery-latest.js"></script>
  <!-- Framework CSS -->
	<link rel="stylesheet" href="../blueprint/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="../blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="../../blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
	
	<!-- Import fancy-type plugin for the sample page. -->
	<link rel="stylesheet" href="../blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="../tabs/screen.css" type="text/css" media="screen">
        
  </script>
<? include("../sightings/google_api.php"); ?>
</head>
  <body onunload="GUnload()" style="valign:top; background-color:#fbfbfb; ">

<!-- #F0F0F0 -->
<style>
  
#navlinks a{font-size:13px; font-weight:bold; color:#333;text-transform: uppercase;letter-spacing: 1px; color:green}

</style>

<div class='container' style='width:900px'><? //include("header.php"); ?></div>



<?
$photo_id = $_GET['id'];
$tag_name = $_GET['tag'];

if(!$tag_name)
{
	$tag_name = "All";
}

if(!$photo_id) {
     print "<script>";
     print "<!--\n";
     print "window.location=\"viewtags.php?tag=$tag_name\"\n";
     print "// -->\n";
     print "</script>";
     break;
}




$query = "select title, info, filename, equipment  from photos where photo_id = '$photo_id'";
$result = mysql_query($query);
$data = mysql_fetch_assoc($result);
$title =  stripslashes($data['title']);
$desc = stripslashes($data['info']);
$photo =  $data['filename'];
//$location = $data['location'];
$equipment = $data['equipment'];

list($width, $height, $type, $attr) = getimagesize("../sightings/images/$photo");


if ($tag_name) {
    $query = "select tag_id from tags where tag_name = '$tag_name'";
    $result = mysql_query($query);
    $data = mysql_fetch_assoc($result);
    $tag_id = $data['tag_id'];
}


$query = "select photo_id from  photo_tags where tag_id = '$tag_id' ORDER BY photo_id DESC ";
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

$query = "select common_name from species as s, photo_species as ps where s.species_id = ps.species_id and photo_id = '$photo_id'";
$result = mysql_query($query);
$data = mysql_fetch_assoc($result);
$common_name =  stripslashes($data['common_name']);



$query = "select latitude,longitude,location,location_exact from geocodes as g, photo_geo as pg where g.loc_id = pg.loc_id and photo_id = '$photo_id'";
$result = mysql_query($query);
$data = mysql_fetch_assoc($result);
$lat =  stripslashes($data['latitude']);
$lng = stripslashes($data['longitude']);
$loc =  $data['location'];
$loce = $data['location_exact'];
?> 

<div class="container box" style="width:900px;background-color:eef0f3; color:#877c7c; line-height:18px;">

 

																																							       <div style="text-align:center;/* background-color:#F0F0F0; */ background-color:#; padding-top:1%; margin:0">

<table>


<tr><td style="width:<? echo $width; ?>px"><div style="width:<? echo $width; ?>">
<div style="float:left;font-size:15px;font-family: Georgia,'Palatino'; color:#333;">

<b><? print "<a href=\"?id=$previous&tag=" . urlencode($tag_name) . "\">&lt;&lt;&lt;</a>"; ?></b>

</div>
<div style="float:right; font-size:15px;font-family: Georgia,'Palatino'; color:#333;">

<b><? print "<a href=\"?id=$next&tag=" . urlencode($tag_name) . "\">&gt;&gt;&gt;</a>"; ?></b>


</div></div>
</td></tr>
<tr><td>
<h2 style="color:877c7c"><? echo $title; ?></h2>
</tr></td>

<tr><td>
<style>
a:link, a:visited {text-decoration: none; }
a:hover {text-decoration: underline;}
</style>

<?
$query = "select t.tag_name from tags as t, photo_tags as pt where (pt.photo_id = '$photo_id' and pt.tag_id = t.tag_id)";
$result = mysql_query($query);

?>
<div style="float:left; font-size:12px; font: 'Helvetica Neue', helvetica, sans-serif;">
<div style="color:#000;"><b>Tags</b> :</div>
</div>
<div style="font-size:11px;text-transform: uppercase;  font: 'Helvetica Neue', helvetica, sans-serif;">
<?
$n = 0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if ($n == 0) {} else { print "&nbsp;";}; 
	print "<a style=\"color: #fff; padding:2px;margin-left:2px;background-color:#3e75b8  \"  href=\"viewtags.php?tag=" . urlencode($line["tag_name"]) . "\">".  $line["tag_name"] . "</a>" ;
	$n++;
}

	
?>
</div>
</td></tr>
<tr><td>

 <? print "<img style=\"float:left;\" src=\"../sightings/images/$photo\">"; ?>
<? if($width <= 400)
   {
      $box_width=450;
   }
   else
   {
      $box_width = 350;
   }
 ?>

<script type="text/javascript">

  var map;
var marker;
var html;
// onload
window.onload=function(){loadMap();}
// delay map display until iframe renders
// display map
 function loadMap() {
      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map"));
         map.setCenter(new GLatLng(<? echo $lat; ?>,<? echo $lng; ?>),5, G_PHYSICAL_MAP);
        map.setUIToDefault();
  
        function createMarker(point, number)
	{
  		marker = new GMarker(point);
  		// Show this markers index in the info window when it is clicked^M
  		html =  number;
  		GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
  		return marker;
	};	
	<?
        echo "var point = new GLatLng(" . $lat . "," . $lng . ");\n";
        echo "marker = createMarker(point, '" . addslashes($loce) . "');\n";
        echo "map.addOverlay(marker);\n";
        ?>
      }
       
    }


    </script>
<div class="box" 
   style="margin-left:1%; text-align:justify;background-color:#eef0f3; color:877c7c; padding-top:1%; float:right; width:<? echo $box_width; ?>">



      <table>
	<tr>
      <? if($common_name){ ?>
   <td><b>Species</b>:</td><td><? echo $common_name; ?></td> <? }      ?>
	</tr>
	<tr>
   <td><b>Location</b>:</td><td><? echo $loc; if($loce) { ?>&nbsp;(&nbsp;<? echo $loce; ?>&nbsp;)<? } ?></td>
	</tr>
	<tr>
		<td colspan=2><? echo $desc; ?></td>
	</tr>
      </table><hr><table>
        																																								 <tr><td><div id="map" style="width: <? echo $box_width; ?>px; height:300px;"></div></td></tr>
                
								     </table>
</div>


	</div>
 
</td>



</tr> 

</table>

<? include("../footer.php"); ?>
</div>

	


</div>


  
<script type="text/javascript">
	
	$("ul.tabs li.label").hide();
	$("#tab-set > div").hide();
	$("#tab-set > div").eq(0).show();
        
  $("ul.tabs a").click(
  function() {
                $("ul.tabs a.selected").removeClass('selected');
                $("#tab-set > div").hide();
                $(""+$(this).attr("href")).fadeIn('slow');
                $(this).addClass('selected');
                 map.checkResize();
                return false;
        }
	 
   
  );


</script>


</html>

