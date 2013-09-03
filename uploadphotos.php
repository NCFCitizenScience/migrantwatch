<?   
include("auth.php");
include("db.php");

   $sighting_id = $_REQUEST['id']; 

   if(!is_numeric($sighting_id)) { exit(); }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<? include("main_includes_thickbox.php"); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="css/sighting.css" type="text/css">
        <script src="js/jquery/alerts/jquery.ui.draggable.js" type="text/javascript"></script>
        <script src="js/jquery/alerts/jquery.alerts.js" type="text/javascript"></script>
        <link href="js/jquery/alerts/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<?
include("config.php");
if($_GET['update']) {
  $sid = trim($_GET['id']);
   for($j=0; $j<count($_GET['pid']); $j++) {
     $photo_caption = trim($_GET['photo_caption'][$j]);
     $pid = trim($_GET['pid'][$j]);
     $sql = "update migwatch_photos set photo_caption='$photo_caption' where photo_id='$pid' and sighting_id='$sid'";
     mysql_query($sql); 
   }  
?>
  <script>
	parent.updatePhotoCount("<? echo $sid; ?>","<? echo count($_GET['pid']); ?>");
	parent.editsightingupdate("<div class='success'>Changes successfully updated</div>");
	parent.tb_remove();
  </script> <?
}

if( $_GET['deletephoto'] == 'true') {
    if(isset($_GET['photo_id'])) {
        $id = (int)$_GET['photo_id'];
        $query= "select user_id from migwatch_photos where photo_id='$id'";
        $result = mysql_query($query);
        while ( $data = mysql_fetch_assoc($result)) {
              $user_id = $data['user_id'];
        }
        if( $user_id == $_SESSION['userid']) {
            $sql = "DELETE FROM migwatch_photos WHERE photo_id = '$id'";
            mysql_query($sql);

        }

	$sql="select * from migwatch_photos where sighting_id='$sighting_id'";
	$result=mysql_query($sql);
	$pcount = mysql_num_rows($result);
	?><script type="text/javascript">
		 parent.updatePhotoCount("<? echo $sighting_id; ?>","<? echo $pcount; ?>");
        	 parent.editsightingupdate("<div class='success'>The photograph has been successfully deleted</div>");
		parent.tb_remove();

		</script>
<?

} 
}
if($_REQUEST['uploadphotos'] ){
    $user_id = $_SESSION['userid'];
   for( $i =1 ; $i <= count($_FILES); $i++ ) { 
      if($_FILES['userfile'. $i]['name']) {	       
        $ext = pathinfo($_FILES['userfile'. $i]['name'], PATHINFO_EXTENSION);
	
	if(strtolower($ext) != 'jpg' &&  strtolower($ext) != 'jpeg' && strtolower($ext) != 'png') {
	     $error[] = "Photo # " . $i . " not a valid image file.<br>";
	     
	} else {

      	$uploadFile = $uploadDir . $_FILES['userfile' . $i]['name'];
     	$photo_caption = $_REQUEST['caption' . $i];     
     	$orgfile = $_FILES['userfile'. $i]['name'];
	//$orgfile_2 =  str_replace("'", "", $orgfile_1);
	//$orgfile =  str_replace(" ", "_", $orgfile_2);
     	$uploadFilem = $uploadDir . "f_" . $_FILES['userfile'. $i]['name'];
     	$uploadthumb = $uploadDir . "tn_" . $_FILES['userfile'. $i]['name'];
     	$uploadthumb_medium = $uploadDir . "metn_" . $_FILES['userfile'. $i]['name'];


	if ( move_uploaded_file($_FILES['userfile' . $i]['tmp_name'], $uploadFile))
	{
                if(list($width, $height) = getimagesize($uploadFile)) {           
                if($width > 600 ) {
                   createthumb($uploadFile,$uploadFilem,600,600);                            
                 } else {
		   createthumb($uploadFile,$uploadFilem,$width,$width);

                 }

		createthumb($uploadFile,$uploadthumb,80,80);
                createthumb($uploadFile,$uploadthumb_medium,160,160);

	}
	
	$sql = "insert into migwatch_photos(sighting_id,user_id,photo_caption) values ('$sighting_id','$user_id','$photo_caption')";
	mysql_query($sql);
	$photo_id_new =  mysql_insert_id();
        list($name,$ext) = explode('.',$orgfile);
	$final_filename = hash('md5',$name) . "_" . $photo_id_new . "." . $ext;

        $sql = "update migwatch_photos set photo_filename = '$final_filename' where photo_id='$photo_id_new'";
	mysql_query($sql);

	$fromfile = $uploadDir . "f_" .  $orgfile;
        $tofile = $uploadDir .  "f_" . $final_filename; 

       rename("$fromfile" ,"$tofile");
       $t1 = $uploadDir . "tn_".$orgfile ;
       $t2 = $uploadDir .  "tn_".$final_filename ;
       rename("$t1" ,"$t2");
       $t1 = $uploadDir . "metn_".$orgfile ;
       $t2 = $uploadDir .  "metn_".$final_filename ;
       rename("$t1" ,"$t2");
       
       } else  {
       
       $error[] = "Photo #" . $i;

       //echo "<div class=error1>Photo " .  $i . "upload error</div>";

       }}
   }
       
}
}

?>
<table style="width:700px;margin-left:auto;margin-right:auto;">
<tr><td colspan="2">
<?
	if($error) {
		   echo "<div class=error1 style='text-align:center;margin-left:auto:margin-right:auto'>Error in uploading ";
		   foreach ( $error as $err) {
		   	   echo $err;
			   echo ", ";
		   }
		   echo '<br>Please check your file size. It could have been larger than the allowed size limit of 2MB</div>';

	}

?>
</td>
</tr>
<tr>
<?  $sql1 = "select photo_id,photo_filename, photo_caption from migwatch_photos where sighting_id='$sighting_id'";
    $result1 = mysql_query($sql1);
 
   $num_r = mysql_num_rows($result1);
    if ( $num_r > 0 ) {

?>
<td style="width:300px">
	
<?
     echo "<table><tr>";
     $i=0;
     echo "<form id='updatecap' action='uploadphotos.php' method='GET'>";
     while($row1 = mysql_fetch_assoc($result1)) {    
        $photo_id = $row1['photo_id'];
	$filename = trim($row1['photo_filename']);
	$caption = $row1['photo_caption'];        
	echo "<tr><td style='width:300px'>";
	     echo "<table class='single_img' style='width:300px'>";
	     echo "<tr>";
	     	  echo "<td><img style='border:solid 1px' src='image_uploads/tn_". $filename."'></td>";
		  echo "<td><b>Caption</b><br><textarea style='height:60px;width:150px' name='photo_caption[]'>" . $caption . "</textarea>";
		  echo "<input type='hidden' name='pid[]' value='". $photo_id . "'>";
		  echo "<br><a class='deletephoto' title='delete photo' id=" . $photo_id . " href='#x'>delete photo</a></td>";
                  echo "</tr><tr><td colspan=2><hr></td>";
	          echo "</tr></table></td>";
       $i++;
      }
      echo "<input type='hidden' name='id' value='". $sighting_id ."'>";
      echo "</tr><td><input type='submit' name='update' value='Save all changes'></td>";
      echo "</form>";
		echo "</tr>";
      echo "</table>";
     
      $count_uploaded_pics = mysql_num_rows($result1);
     $can_upload_count = 4 - $count_uploaded_pics;
?>


</td>
<? } 

 $can_upload_count = 4 - $count_uploaded_pics;
?>

<td style="width:300px;vertical-align:top">
<? if(!$_REQUEST['uploadphotos']){ ?>
 <table>
        <tr><td colspan=2 class='notice' style='text-align:center;vertical-align:top'>You can upload upto 4 photographs for a particular sighting. </td></tr>
        <form id="uploadForm" enctype="multipart/form-data" action="uploadphotos.php?id=<? echo $sighting_id; ?>" method="post">
        
        <div id="picdiv">       
             <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
             <? for($i=1; $i <= $can_upload_count; $i++) { ?>
	     	
                <tr>
                        <td style='text-align:right;font-weight:bold' id='photo<? echo $i; ?>' >Photo <? echo $i; ?></td>
                        <td><input id="userfile<? echo $i; ?>"  name="userfile<? echo $i; ?>" type="file"/></td>
           
                </tr>
             <? } ?>
        </div>
        <? if ( $can_upload_count > 0 ) { ?>
	
        <tr><td></td><td style=''><input type="submit" value="Upload Photos" name="uploadphotos" id="uploadbutton1"/></td></tr>
	<? } ?>
 </form>
        
</table>
<? } ?>
</td>
</tr></table>
 
<?
function createthumb($name,$filename,$new_w,$new_h)
{
        $system=explode(".",$name);
        if (preg_match("/jpg|JPG|jpeg/",$system[1])){$src_img=imagecreatefromjpeg($name);}
        if (preg_match("/png/",$system[1])){$src_img=imagecreatefrompng($name);}
        $old_x=imageSX($src_img);
        $old_y=imageSY($src_img);
        if ($old_x > $old_y)
        {
                $thumb_w=$new_w;
                $thumb_h=$old_y*($new_h/$old_x);
        }
        if ($old_x < $old_y)
        {
                $thumb_w=$old_x*($new_w/$old_y);
                $thumb_h=$new_h;
        }
        if ($old_x == $old_y)
        {
                $thumb_w=$new_w;
                $thumb_h=$new_h;
        }
        $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
        if (preg_match("/png/",$system[1]))
        {
                imagepng($dst_img,$filename);
        } else {
                imagejpeg($dst_img,$filename);
        }
        imagedestroy($dst_img);
        imagedestroy($src_img);
}

?>

<script type="text/javascript">

$(document).ready(function() {
$('.deletephoto').click(function() {
				  
     var parent = $(this).parent().parent().parent(); 
     id = $(this).attr('id');
     
     jConfirm('Are you sure you want to delete this photo?', '',function(r) {
      if(r==true) {
           location.href="uploadphotos.php?id=<? echo $sighting_id; ?>&photo_id=" + id + "&deletephoto=true";
      }
   });
}); 

$("#uploadForm").validate({
          rules: {
    	  userfile1: {      	  	 
      		 accept: "png|jpg|jpeg|tiff",
           },
	   caption1 : {
	   	 required : function(element) {
		 	  return $('#userfile1').val() != '';
		  }
	   },
           userfile2: {
                 accept: "jpg|jpeg|tiff",
           },
           caption2 : {
                 required : function(element) {
                          return $('#userfile2').val() != '';
                  }
            },
	    userfile3: {
                 accept: "jpg|jpeg|tiff",
           },
           caption3 : {
                 required : function(element) {
                         return $('#userfile3').val() != '';
                  }
            },

	    userfile4: {

                 accept: "jpg|jpeg|tiff",

           },

           caption4 : {
                 required : function(element) {
                          return $('#userfile4').val() != '';
                  }
            },
  	  },
	  messages: {
	  	 userfile1: {
	  	 	    accept: "Only jpg and tiff formats allowed"
                 },
	   caption1 : {
	   	    required: "please enter a caption for the image"
	   },
	    userfile2: {
                 accept: "Only jpg and tiff formats allowed"
           },

           caption2 : {
                    required: "please enter a caption for the image"
           },
	 
	   userfile3: {
                 accept: "Only jpg and tiff formats allowed"
           },

           caption3 : {
                    required: "please enter a caption for the image"
           },
     
           userfile4: {
                 accept: "Only jpg and tiff formats allowed"
           },

           caption4 : {
                    required: "please enter a caption for the image"
           },
        
	  }
});

$("#userfile1").blur(function() {
  $("#caption1").valid();
});
		
});
</script>

</body>
</html>