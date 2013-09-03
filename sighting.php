<? include("checklogin.php");
   include("db.php");
   $user_id = $_SESSION['userid'];
   $sighting_id = $_GET['id'];
   $sql1= "SELECT l1.id, l1.sighting_date,l1.obs_type,l.location_id,l.latitude,l.longitude, u.user_id,l1.entered_on, l.location_name,l.city,l.district, u.user_name, s.state, c.scientific_name,c.common_name FROM migwatch_l1 l1 INNER JOIN migwatch_species c ON l1.species_id = c.species_id INNER JOIN migwatch_locations l ON l1.location_id = l.location_id INNER JOIN migwatch_states s ON s.state_id = l.state_id INNER JOIN migwatch_users u ON u.user_id = l1.user_id";
   $sql2 = " where l1.id = '$sighting_id'";
   $query = $sql1 . " " . $sql2; 

  $result = mysql_query($query);
  if(mysql_num_rows($result) > 0) {
   $data = mysql_fetch_assoc($result);
   $location_id = $data['location_id'];
   $location_name = $data['location_name'] . ", " .  $data['state'];
   $location_geocodes = $data['latitude'] . "," . $data['longitude'];
   $species_name = $data['common_name'] . " (<i>" . $data['scientific_name'] . "</i>)";
   $entered_on = date('g:i a F j, Y ', strtotime($data['entered_on']));
   $owner_id = $data['user_id'];
   $obs_type = $data['obs_type'];
   $sighting_date = date('F j, Y ', strtotime($data['sighting_date']));
  }

$location_detail = $data['location_name'];
if($data['city']) { $location_detail .= ", " . $data['city']; }
if($data['district']) { $location_detail .= ", " . $data['district']; }
$location_detail .= ", " . $data['state'];

$result2 = mysql_query($sql1);
$n=0;
$sight;
$page_title="MigrantWatch Sightings";
include("main_includes.php");
?>
<body>
<? 
   include("header.php");
?>
<style> .photo_box { background-color:#fff; border: solid 1px;  } </style>
<script> $('.photo_box').corner(); </script>
<div class="container first_image">
     <div id='tab-set'>   
             <ul class='tabs'>
                 <li><a href='#x' class='selected'>sighting</a></li>
             </ul>
     </div>
     <table style='width:900px;margin-left:auto;margin-right:auto'>
	<tr><td colspan='2'><h4><? echo $species_name; ?>,&nbsp;<? echo $location_name; ?></h4></td></tr>
	<tr><td style='width:50%;vertical-align:top'>
	<table style=''>
	<tr><td><b>Type</b>&nbsp;<? echo ucfirst($obs_type); ?> sighting<b></td></tr>
	<tr><td><b>Added by</b><br><? echo getUserNameById($owner_id,$connect); ?></td></tr>
	<tr><td><b>Sighting date</b><br><? echo $sighting_date; ?></td></tr>
	<tr><td><b>Location</b><br><a href='location.php?id=<? echo $location_id; ?>' title='view location page'><? echo $location_detail; ?></a>&nbsp;<? if($location_geocodes != ',') { echo "<a class='thickbox' title='View location on map' href='maps.php?ll=" . $location_geocodes . "&width=700&height=350&TB_iframe=true'>(view on map)</a>"; 
	}
	?></td></tr><!--
	<tr><td><b>Upload date</b><br><? echo $entered_on; ?></td></tr>-->
<?
$sql3 = " where l1.location_id='$location_id' and l1.sighting_date='$sighting_date' and l1.id!='$sighting_id' and l1.user_id = '$owner_id'";
$query2 = $sql1 . " " . $sql3;
$result2=mysql_query($query2);
if(mysql_num_rows($result2) > 0 ) {
	echo "<tr><td><b>Other sightings from this outing</b><br>";
}

while ( $data2 = mysql_fetch_assoc($result2)) {
      $id= $data2['id'];
      $common_name = $data2['common_name'];
      echo "<a href='" . $src_base . "sighting.php?id=" . $id . "'>" . $common_name . "</a><br>";
}
?>
	</td></tr>

	<tr><td>
	<table class='comment_area'>
	<tr>
		<td><hr></td>
	</tr>
<?
$query_comments = "select comment_id from migwatch_sighting_comments where sighting_id='$sighting_id'";
$result = mysql_query($query_comments);
$comments_count  = mysql_num_rows($result);
?>
	<tr><td>
		<b><a href='#x' class='num_comments' id='<? echo $sighting_id; ?>'><span id='ncom'><? echo $comments_count; ?> comments</span></a></b>
	</td></tr>
	<tr><td><div class="comment_content"></div></td></tr>
<? if ( $_SESSION['userid'] ) { ?>
      	<tr>
		<td><span  id="loadplace" ></span></td></tr>
       <tr><td ><table style='margin-left:7px'>
        <tr>
		 <td style='padding:0;margin-bottom:0'>
		<textarea class='comment_textarea' id="<? echo $sighting_id; ?>">enter your comment here</textarea></td>
	</tr>
		<tr>
			<td style="text-align:left;margin-top:0px;padding:0">
			    <input style='margin-top:-5px' type="submit" value="add comment" class="comment_submit" id="<? echo $sighting_id; ?>">
			 </td>
		</tr>
		</table></td></tr>
<? } else {  ?>
   <tr><td>You need to be <a href='login.php?done=sighting.php?id=<? echo $sighting_id; ?>'>logged in</a> to comment</td></tr>

<? } ?>
</table>

</td></tr>

	</table>
</td>
<td class='sidebar'>
<?
$sql = "select photo_filename,photo_caption from migwatch_photos where sighting_id='$sighting_id'";
$result = mysql_query($sql);
echo "<table><tr>";
$photo_count=0;
while($data = mysql_fetch_array($result)) {
	    $photo_count++;
	    $photo_filename = $data['photo_filename'];
	
	  ?><td style='text-align:center'>
	  	<a href='image_uploads/f_<? echo $photo_filename; ?>' class='lightbox'>
		   <img src="image_uploads/tn_<? echo $photo_filename; ?>" title="<? echo $photo_filename; ?>">
		</a>
	   </td>
	   
<?
	if( $photo_count !='1' ) {
               if( ($photo_count % 2 ) == '0' ) { echo "</tr><tr>"; }
            }

}
	
if(mysql_num_rows($result) == '0' ) { 
echo "<td colspan=2 style='text-align:right'>No photos have been added for this sighting.</td>";
}
echo "</tr><tr><td colspan=2><hr></td></tr>"; ?>
<tr><td colspan=2 style='text-align:right'><a title='Other sightings by this user from the same location' href='data.php?location=<? echo $location_id; ?>&user=<? echo $owner_id; ?>'>Sightings by <? echo getUserNameById($owner_id,$connect); ?> from<br><? echo $location_name; ?></a></td></tr>

<tr><td colspan=2 style='text-align:right'><a title='Other photos by this user from the same location' href='gallery.php?location=<? echo $location_id; ?>&user=<? echo $owner_id; ?>'>Photos by <? echo getUserNameById($owner_id,$connect); ?> from<br><? echo $location_name; ?></a></td></tr>

</table></td></tr>
</table>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>

<script type="text/javascript">

$('.comment_content').hide();
$('.num_comments').click(function() {
     $('.comment_content').toggle();
     $('#loadplace').hide();
     var com_pid = $(".num_comments").attr("id");
     var data = 'id=' + encodeURIComponent(com_pid);
      $.ajax({
                url: "getsightingcomments.php",
		     type: "GET",
		     data: data,
                     cache: false,
		     success: function (html) {
		         		          
		     	 $('.comment_content').html(html);      
			 
		     }
      });	     	      
   	        
 });

$(document).ready(function()
{
	$(".comment_textarea").emptyonclick();
	$(".comment_submit").click(function(){
	var element = $(this);
	var id = $('.comment_textarea').attr("id");
	var comment_text = $('.comment_textarea').val();
	var dataString = 'user_id=<? echo $user_id; ?>&comment='+ comment_text + '&sighting_id=' + id + '&owner=<? echo $owner_id; ?>';
	if( comment_text == '' || comment_text == 'enter your comment here') {
            return false;
	} else {
	       $.ajax({
		type: "GET",
		url: "insertsightingcomment.php",
		data: dataString,
		cache: false,
		success: function(html){
			 var html_s = html.split("&&&&");
			 $('#ncom').html(html_s[0] + " comments");
			 $("#loadplace").html('');
			 $("#loadplace").show();
			 $("#loadplace").append(html_s[1]);
			 $('.comment_textarea').val('');
	         }
	       });
	}
	return false;
       });
});

</script>
<script type="text/javascript">
    $(function() {
        $('a.lightbox').lightBox();
	$('.comment_textarea').emptyonclick();
    });
</script>
<? include("footer.php"); include("login_includes.php"); ?>
<script type="text/javascript" src="js/jquery/lightbox/js/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery/lightbox/css/jquery.lightbox-0.5.css" media="screen" />
</body>
</html>
