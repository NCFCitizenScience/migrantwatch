<? include("checklogin.php");
   $user_id_s = $_SESSION['userid'];
   $page_title="MigrantWatch: Photos";
   include("main_includes.php");
?>

<body>
<style>.sidebar table td { text-align:right; } </style>
<? 
include("header.php");
?>
<div class="container first_image">
     <div id='tab-set'>   
             <ul class='tabs'> 
                 <li><a href='#' class='selected'>photos</a></li> 
             </ul> 
     </div>
     <div class='page_layout'>
<?
$photo_id = $_GET['id'];
$query= "SELECT distinct p.photo_filename,p.photo_caption,l1.id,l1.sighting_date,l.location_id, l.latitude, l.longitude, l.location_name,l.city,l.district,u.user_id, u.user_name, s.state,c.species_id, c.scientific_name,c.common_name FROM migwatch_l1 l1 INNER JOIN migwatch_species c ON l1.species_id = c.species_id INNER JOIN migwatch_locations l ON l1.location_id = l.location_id INNER JOIN migwatch_states s ON s.state_id = l.state_id INNER JOIN migwatch_users u ON u.user_id = l1.user_id INNER JOIN migwatch_photos p ON p.sighting_id = l1.id where p.photo_id = '$photo_id'";

if(isset($_GET['season']) && $_GET['season'] != 'All') {
        $season = $_GET['season'];
} 

if($season) {
  $seasonArr = explode('-',$season);
  $seasonStart = $seasonArr[0];
  $seasonEnd = $seasonArr[1];
   
   $where_clause = " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";

}

if( ( $_GET['species'] != "All" ) || $_GET['species'] != "" ) {
    $species = $_GET['species'];
} else {
    $species = "All";
}
                                                              
if( $species && is_numeric($species) ) {                                 
	$where_clause .= " AND l1.species_id=". $species;
}
                                        
if( (strtolower($_GET['location']) != 'all') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
	$location = $_GET['location'];
} else {	          
	$location = 'All';
}

if( is_numeric($location) ) {   
	$where_clause .= " AND  l1.location_id=". $location;
}

if( is_numeric($_GET['user'])) {
	$user = $_GET['user'];
} else {
   $user = 'All';
}

if ( is_numeric($user)) {
	$where_clause .= " AND l1.user_id = ".$_GET['user'];
}
				
if($_GET['type'] != 'All' || $_GET['type'] != '') {
   $type =  $_GET['type'];    
} else {
	$type = 'All';	
}

if($type) {
     if( strtolower($type) != 'all') {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
 		} else {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0'";
      }
}

if ($_SESSION['dev_entries'] == 1) {
	// Show all entries : including the ones from developer
} else {
	$where_clause .= " AND u.user_name != 'Developer'";
}

//$where_clause .= " AND c.Active = '1'";
$query .= $where_clause;
$result = mysql_query($query);
if(mysql_num_rows($result) > 0) {
  $data = mysql_fetch_assoc($result);
  $photo_caption =  stripslashes($data['photo_caption']);
  $photo_filename =  $data['photo_filename'];
  $location_name = $data['location_name'] . ", " .  $data['state'];
  $location_geocodes = $data['latitude'] . "," . $data['longitude'];
  $species_name = $data['common_name'] . " (<i>" . $data['scientific_name'] . "</i>)";
  $photo_user_name = $data['user_name'];
  $photo_user_id = $data['user_id'];
  $species_id = $data['species_id'];
  $location_id = $data['location_id'];
  $sighting_id = $data['id'];
  $sighting_date =  $data['sighting_date'];
  list($width, $height, $type, $attr) = getimagesize("image_uploads/f_$photo_filename");
  	      
 $query = "SELECT distinct p.photo_id, p.photo_filename,p.photo_caption,l.location_id, l.location_name,l.city,l.district, u.user_name, s.state, c.common_name FROM migwatch_l1 l1 INNER JOIN migwatch_species c ON l1.species_id = c.species_id INNER JOIN migwatch_locations l ON l1.location_id = l.location_id INNER JOIN migwatch_states s ON s.state_id = l.state_id INNER JOIN migwatch_users u ON u.user_id = l1.user_id INNER JOIN migwatch_photos p ON p.sighting_id = l1.id";

if(isset($_GET['season']) && $_GET['season'] != 'All') {
	$season = $_GET['season'];                	        
}
 
if($season) {     
 $seasonArr = explode('-',$season);
 $seasonStart = $seasonArr[0];
 $seasonEnd = $seasonArr[1];
 $where_clause = '';
 $where_clause .= " WHERE l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";
}

if( ( $_GET['species'] != "All" ) || $_GET['species'] != "" ) {
    $species = $_GET['species'];
} else {
    $species = "All";
}
                                                              
if( $species && is_numeric($species) ) {                                 
	$where_clause .= " AND l1.species_id=". $species;
}
                                        
if( (strtolower($_GET['location']) != 'all') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
	$location = $_GET['location'];
} else {	          
	$location = 'All';
}

if( is_numeric($location) ) {   
	$where_clause .= " AND l1.location_id=". $location;
}
              			
if( is_numeric($_GET['user'])) {
	$user = $_GET['user'];
} else {
   $user = 'All';
}

if ( is_numeric($user)) {
	$where_clause .= " AND l1.user_id = ".$_GET['user'];
}
				
if($_GET['type'] != 'All' || $_GET['type'] != '') {
   $type =  $_GET['type'];    
} else {
	$type = 'All';	
}

if($type) {
     if( strtolower($type) != 'all') {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
 		} else {
			$where_clause .= " AND l1.valid = 1 AND deleted = '0'";
      }
}

if ($_SESSION['dev_entries'] == 1) {
	// Show all entries : including the ones from developer
} else {
	$where_clause .= " AND u.user_name != 'Developer'";
}

$query .= $where_clause;
$query  .=" ORDER BY p.photo_id DESC";
$result = mysql_query($query);
$n =0;
$pos;
$photos;
while ($line = mysql_fetch_array($result)) {
	$photos[$n] = $line["photo_id"];	
	if ($photos[$n] == $photo_id) { $pos=$n;}
        if ($n == 0 ) {
          $photo_first = $photos[$n];
        }
        $photo_last = $photos[$n];
	$n++;
}

$prev_photo_id = $photos[$pos-1];
$next_photo_id = $photos[$pos+1];
if( $prev_photo_id ) {
$prev = $prev_photo_id;
} else {

 $prev = $photo_last;
}

if( $next_photo_id ) {
      $next = $next_photo_id ;
} else {
      $next = $photo_first;
}

?> 
<div class='sidebar' style='float:right;margin-top:20px;'>
   <table>
	<tr><td><b>Uploaded by</b><br><span class="photo_user_name"><? echo $photo_user_name; ?></span></td></tr>
	<tr><td><b>Location</b><br>
		<a class='view_location' title='view location page' href='location.php?id=<? echo $location_id; ?>'><span class="location_name"><? echo $location_name; ?></span></a> 
		<? if ($location_geocodes) { ?>
		<a title='Location on map' class="thickbox" id="geo_link" href="maps.php?ll=<? echo $location_geocodes; ?>&TB_iframe=true&width=720&height=370">(view map)</a>
		<? } ?>
	</td></tr>
        <tr><td><b>Date</b><br><? echo date('d-M-Y',strtotime($sighting_date)); ?></td></tr>
	<tr><td><a class='see_userphoto' href='gallery.php?user=<? echo $photo_user_id; ?>'>See all photos by <span class="photo_user_name"><? echo $photo_user_name; ?></span></a></td><tr>
	<tr><td><a class='see_species' href='gallery.php?species=<? echo $species_id; ?>'>See all photos of <span class="species_name"><? echo $species_name; ?></span></a></td><tr>
	<tr><td><a class='see_location' href='gallery.php?location=<? echo $location_id; ?>'>See all photos from <span class="location_name"><? echo $location_name; ?></span></a></td></tr>
	
	<tr><td><a class='view_sighting' href='sighting.php?id=<? echo $sighting_id; ?>'>View sighting page</a></td></tr>
    </table>
</div>
<div style='width:650px'>
     <table id="loadbox">
     <tr>
	<td style="text-align:right">
<?
	$get_url = '';
	foreach	($_GET as $key=>$value) {
	  if( $key != 'id' ) {   
	      $get_url .= '&' . $key . '=' . $value;
	  }
	}
?>      
	<? print "<a href='gallery.php?$get_url' title='see all photos'>see all photos</a>"; ?>&nbsp;|&nbsp;
	<? print "<a class='rlinkd' id='llink' alt='go to previous' href='#id=$prev$get_url' class='loadleft'>prev</a>"; ?>
	&nbsp;|&nbsp;
	<? print "<a class='rlinkd' id='rlink' alt='go to next' href='#id=$next$get_url' >next</a>"; ?>
  	</td>
    </tr>
    <tr>
	<td><h3><div id="species_name"><? echo $species_name; ?></h3></div></td>
    </tr>
    <tr>
	<td>
		<? print "<div id='imgthing' style='text-align:center'><a href='#id=$next$get_url'><img class='imagethingi' src=\"image_uploads/f_$photo_filename\"></a></div>"; ?>
  	</td>
							       
    </tr> 
								    <tr><td id='photo_caption'><? echo $photo_caption; ?></td></tr>
    </table>
    <table style='width:600px;margin-left:auto;margin-right:auto;'>
    <tr>
	<td><hr></td>	
    </tr>
    
<?
$query_comments = "select comment_id from migwatch_photo_comments where photo_id='$photo_id'";
$result = mysql_query($query_comments);
$comments_count  = mysql_num_rows($result);
?>

    <tr>
	<td><b><a href='#x' class='num_comments' id='<? echo $photo_id; ?>'><span id='ncom'><? echo $comments_count; ?> comments</span></a></b></td>
    </tr>
    <tr><td><span class="comment_content"></span></td></tr>
    
<? if ( $_SESSION['userid'] ) { ?>
    <tr><td><span  id="loadplace" ></span></td></tr>

    <tr><td ><table style='margin-left:8px'>

		<tr>
			<td style='padding:0;margin-bottom:0'>
			<textarea class='comment_textarea' id="<? echo $photo_id; ?>" style='width:600px;height:50px;margin-bottom:0'>enter your comment here</textarea></td>
		</tr>
		<tr>
			<td style="text-align:left;margin-top:0px;padding:0">
			    <input style='margin-top:0' type="submit" value="add comment" class="comment_submit" id="<? echo $photo_id; ?>">
			 </td>
		</tr>
		
	</table>
	</td>
     </tr>
<? } else {  ?>
   <tr><td>You need to be <a href='login.php'>logged in</a> to comment</td></tr>

<? } ?>
</table>
</div>
<? } else { echo "<div class='error1'>No results</div>"; }?>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<script type="text/javascript">
 $('.comment_content').hide();
 $('.num_comments').click(function() {
     $('.new_comment').hide();
     $('.comment_content').toggle();
     var com_pid = $(".num_comments").attr("id");
     var data = 'id=' + encodeURIComponent(com_pid);
      $.ajax({
                url: "getcomments.php",
		     type: "GET",
		     data: data,
                     cache: false,
		     success: function (html) {
		     	  $('.comment_content').html(html);          
		     }
      });	     	      
   	        
 });

 $('.rlinkd').click(function() {
  
  $('.all_comments').hide();
  var hash = this.href;
  hash = hash.replace(/^.*#/, '');
  //var data = 'id=' + encodeURIComponent(hashid) + 'season=' + encodeURIComponent(seasonid);
  var data = hash;
        $.ajax({
                url: "loader.php",      
                type: "GET",            
                data: data,             
                cache: false,
                success: function (html) {      	
                        var thtml = html.split("|");
                            
                        //add the content retrieved from ajax and put it in the #content div
                        $('#imgthing').html(thtml[6]);
                        var rlink = thtml[1];
                        var llink = thtml[0];              
                        $('.photo_user_name').html(thtml[2]);
                        $('.location_name').html(thtml[3]);
			$('#geo_link').attr('href', 'maps.php?ll=' + thtml[4] + '&TB_iframe=true&width=800&height=400' );                        
			
                        $('#species_name').html(thtml[5]);
			$('.species_name').html(thtml[5]);
                       
			//var comment_href = "<a href='#x' onclick='getcomment()' class='num_comments' id=" + thtml[7] + ">" + thtml[8] + " Comments</a>";                       
			$('.num_comments').attr('id',thtml[7]);
			$('.comment_textarea').attr('id',thtml[7]);
			$('#ncom').html(thtml[8] + " comments");
			                      
                        $('#llink').attr('href', '#id=' + llink);   
                        $('#rlink').attr('href', '#id=' + rlink); 

			$('.see_species').attr('href', 'gallery.php?species=' + thtml[9]);
			$('.see_location').attr('href', 'gallery.php?location=' + thtml[10]);
			$('.see_userphoto').attr('href', 'gallery.php?user=' + thtml[11]);
                       
		        $('.view_location').attr('href', 'location.php?id=' + thtml[10]);
		        $('.view_sighting').attr('href', 'sighting.php?id=' + thtml[12]);

			$('#photo_caption').html(thtml[13]);                       

                        $('#imgthing').fadeIn('slow');       
                     
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

	var dataString = 'owner=<? echo $photo_user_id; ?>&user_id=<? echo $user_id_s; ?>&comment='+ comment_text + '&photo_id=' + id;
	
	if( comment_text == '' || comment_text == 'enter your comment here') {            
            return false;
	} else {
	       $("#flash").show();
	       $("#flash").fadeIn(400).html('loading.....');
	       $.ajax({
		type: "GET",
		url: "insertcomment.php",
		data: dataString,
		cache: false,
		success: function(html){
			 var html_s = html.split("&&&&");
			 $('#ncom').html(html_s[0] + " comments");
			 $("#loadplace").append(html_s[1]);
			 $('.comment_textarea').val('');
			 $("#flash").hide();
	         }
	       });
	}
	return false;
       });
});

</script>
<? 

   include("footer.php");
 ?>
</body>
</html>

