<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$comment_time =  date('Y-m-d H:i:s');
if ($_SESSION['userid']) {
if(isset($_GET['comment']) && $_GET['user_id'] && $_GET['sighting_id'] && $_GET['owner'])
{
	$textcontent=addslashes($_GET['comment']);
	$user_id  = $_GET['user_id'];
	$sighting_id = $_GET['sighting_id'];
	include("db.php");
	$sighting_owner=$_GET['owner'];
	$sql ="insert into migwatch_sighting_comments(user_id,sighting_id,comment_text,comment_time) values('$user_id','$sighting_id','$textcontent','$comment_time')";
	mysql_query($sql);
	$comment_id = mysql_insert_id();
	
	$sql = "select comment_id from migwatch_sighting_comments where sighting_id='$sighting_id'";
	$result1 = mysql_query($sql);
	$total_comments = mysql_num_rows($result1);
}
$comment_out = $total_comments;
$comment_out .="&&&&<table class='all_comments'><tr><td id=row_". $comment_id . "><div class='sighting_comments'><b>You</b>: "; 
$comment_out .=$textcontent . "&nbsp;<br><small><a title='Click here to delete this comment' href='#x' class='del_comment_link' id='". $comment_id . "'>delete</a><small></div></td></tr></table>";

echo $comment_out;
 } ?>

<script>
$(".del_comment_link").click(function() {
     var new_com_pid = $(this).attr("id");
     var data = 'id=' + encodeURIComponent(new_com_pid);
     jConfirm('Are you sure you want to delete this comment?', '',function(r) {
         if(r==true) {           
             $('#row_' + new_com_pid).fadeOut("slow");
	      $.ajax({
                url: "delete_sighting_comment.php",
                     type: "GET",
                     data: data,
                     cache: false,
		     success: function (html) {
		
                          $('#ncom').html(html);
			  $('#row_' + new_com_pid).fadeOut("slow");
                     }
             });

	    }
});

});       
</script>
<style>
.sighting_comments { margin-top:2px; padding:5px; font-size:13px;border: solid 1px #d95c15; background-color:#fff; color:#000; min-width:600px; }
.all_comments { width: 700px; }