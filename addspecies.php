<?
session_start(); /*
        if(!isset($_SESSION['userid'])) {
                header("Location: ../login.php");
                die();
        }*/	
?>
<html>
<head>
<? 
   include("db.php");
   include("main_includes.php"); 
   include("page_includes_js.php");
   $uploadDir = '/home/wildindia/public_html/mwtest/migwatch_species_uploads/'
?>
</head>

<?


if( $_REQUEST['add_species_submit']) {
  
  $species_name = trim($_REQUEST['species_name']);
   $species_id = trim($_REQUEST['species_id']);
   $size = mysql_escape_string(stripslashes($_REQUEST['size']));
   $descr = mysql_escape_string(stripslashes($_REQUEST['descr']));
   $behaviour = stripslashes($_REQUEST['behaviour']);
   $habitat = stripslashes($_REQUEST['habitat']);
   $dist_char = mysql_escape_string(stripslashes($_REQUEST["dist_char"]));
   $winter_dist = stripslashes($_REQUEST['winter_dist']);
   $breed_dist = stripslashes($_REQUEST['breed_dist']);
   $iucn_status = stripslashes($_REQUEST['iucn_status']);
   $call_text = stripslashes($_REQUEST['call_text']);
   $other_notes = stripslashes($_REQUEST['other_notes']);
   $video = stripslashes($_REQUEST['video']);

  if ( $species_name == '' ) { exit(); }

  if( $_REQUEST['id'] ) {
     $entry_id = $_REQUEST['id'];
     $sql = "UPDATE migwatch_species_set SET species_id='$species_id',species_name='$species_name' , size='$size',descr='$descr',behaviour='$behaviour',dist_char='$dist_char',winter_dist='$winter_dist',breed_dist='$breed_dist',iucn_status='$iucn_status',call_text='$call_text',other_notes='$other_notes',video='$video',habitat = '$habitat' where id='$entry_id'";
     mysql_query($sql);

   } else {
     $sql = "insert into migwatch_species_set(species_id,species_name,size,descr,behaviour,dist_char,winter_dist,breed_dist,iucn_status,call_text,other_notes,video,habitat) values ('$species_id','$species_name','$size','$descr','$behaviour','$dist_char','$winter_dist','$breed_dist','$iucn_status','$call_text','$other_notes','$video','$habitat')";
     mysql_query($sql);
     $entry_id =  mysql_insert_id();   
   }
   //handle image and audio files
   if($_FILES['call_audio']['name']) {
      $uploadFile = $uploadDir . $_FILES['call_audio']['name'];
   if ( move_uploaded_file($_FILES['call_audio']['tmp_name'], $uploadFile)) {
      $orgfile = $_FILES['call_audio']['name'];
      list($name,$ext) = split('\.',$orgfile);
       $final_filename = $name . "_" . $entry_id . "." . $ext;
       $fromfile = $uploadDir  .  $orgfile;
       $tofile = $uploadDir  . $final_filename; 
       rename("$fromfile" ,"$tofile");
       $sql = "update migwatch_species_set set call_audio = '$final_filename' where id='$entry_id'";
       mysql_query($sql);
       
    }
   }


    if($_FILES['img_male']['name']) {
      $uploadFile = $uploadDir . $_FILES['img_male']['name'];
      $uploadthumb1 = $uploadDir . "tn_" .  $_FILES['img_male']['name'];
      $uploadthumb = $uploadDir . "metn_" .  $_FILES['img_male']['name'];
      if ( move_uploaded_file($_FILES['img_male']['tmp_name'], $uploadFile)) {
        $orgfile = $_FILES['img_male']['name'];
        if(list($width, $height) = getimagesize($uploadFile)) {
           createthumb($uploadFile,$uploadthumb1,100,100);
           createthumb($uploadFile,$uploadthumb,160,160);
        }

      list($name,$ext) = split('\.',$orgfile);
       echo $final_filename = $name . "_" . $entry_id . "_male." . $ext;
       $fromfile = $uploadDir  .  $orgfile;
       $tofile = $uploadDir . $final_filename;
       rename("$fromfile" ,"$tofile");
       
       $t1 = $uploadDir . "tn_".$orgfile ;
       $t2 = $uploadDir .  "tn_".$final_filename ;
       rename("$t1" ,"$t2");

       $t3 = $uploadDir . "metn_".$orgfile ;
       $t4 = $uploadDir .  "metn_".$final_filename ;
       rename("$t3" ,"$t4");

       $sql = "update migwatch_species_set set img_male = '$final_filename' where id='$entry_id'";
       mysql_query($sql);

    }
   
   }

  if($_FILES['img_female']['name']) {
      $uploadFile = $uploadDir . $_FILES['img_female']['name'];
      $uploadthumb = $uploadDir . "metn_" .  $_FILES['img_female']['name'];
      $uploadthumb2 = $uploadDir . "tn_" .  $_FILES['img_female']['name'];
   if ( move_uploaded_file($_FILES['img_female']['tmp_name'], $uploadFile)) {
      $orgfile = $_FILES['img_female']['name'];
      if(list($width, $height) = getimagesize($uploadFile)) {
 /*     if($width > 600 ) {
       createthumb($uploadFile,$uploadFile,600,600);
         } else {
              createthumb($uploadFile,$uploadFile,$width,$width);
         }*/
         createthumb($uploadFile,$uploadthumb,160,160);
	 createthumb($uploadFile,$uploadthumb2,100,100);
      }

      list($name,$ext) = split('\.',$orgfile);
       $final_filename = $name . "_" . $entry_id . "_female." . $ext;
       $fromfile = $uploadDir  .  $orgfile;
       $tofile = $uploadDir . $final_filename;
       rename("$fromfile" ,"$tofile");

       $t1 = $uploadDir . "tn_".$orgfile ;
       $t2 = $uploadDir .  "tn_".$final_filename ;
       rename("$t1" ,"$t2");

       $t3 = $uploadDir . "metn_".$orgfile ;
       $t4 = $uploadDir .  "metn_".$final_filename ;
       rename("$t3" ,"$t4");

       $sql = "update migwatch_species_set set img_female = '$final_filename' where id='$entry_id'";
       mysql_query($sql);

    }

   }


    if($_FILES['img_juv']['name']) {
      $uploadFile = $uploadDir . $_FILES['img_juv']['name'];
      $uploadthumb = $uploadDir . "metn_" .  $_FILES['img_juv']['name'];
      $uploadthumb2 = $uploadDir . "tn_" .  $_FILES['img_juv']['name'];
   if ( move_uploaded_file($_FILES['img_juv']['tmp_name'], $uploadFile)) {
      $orgfile = $_FILES['img_juv']['name'];
      if(list($width, $height) = getimagesize($uploadFile)) {
      /*if($width > 600 ) {
       createthumb($uploadFile,$uploadFile,600,600);
         } else {
              createthumb($uploadFile,$uploadFile,$width,$width);
         }*/
         createthumb($uploadFile,$uploadthumb,160,160);
	 createthumb($uploadFile,$uploadthumb2,100,100);
      }

      list($name,$ext) = split('\.',$orgfile);
       $final_filename = $name . "_" . $entry_id . "_juv." . $ext;
       $fromfile = $uploadDir  .  $orgfile;
       $tofile = $uploadDir . $final_filename;
       rename("$fromfile" ,"$tofile");

       $t1 = $uploadDir . "metn_".$orgfile ;
       $t2 = $uploadDir .  "metn_".$final_filename ;
       rename("$t1" ,"$t2");

       $t3 = $uploadDir . "tn_".$orgfile ;
       $t4 = $uploadDir .  "tn_".$final_filename ;
       rename("$t3" ,"$t4");

       $sql = "update migwatch_species_set set img_juv = '$final_filename' where id='$entry_id'";
       mysql_query($sql);

    }

   }


if($_FILES['img_hab']['name']) {
      $uploadFile = $uploadDir . $_FILES['img_hab']['name'];
      $uploadthumb = $uploadDir . "metn_" .  $_FILES['img_hab']['name'];
      $uploadthumb2 = $uploadDir . "tn_" .  $_FILES['img_hab']['name'];
   if ( move_uploaded_file($_FILES['img_hab']['tmp_name'], $uploadFile)) {
      $orgfile = $_FILES['img_hab']['name'];
      if(list($width, $height) = getimagesize($uploadFile)) {
      /*if($width > 600 ) {
       createthumb($uploadFile,$uploadFile,600,600);
         } else {
              createthumb($uploadFile,$uploadFile,$width,$width);
         }*/
         createthumb($uploadFile,$uploadthumb,160,160);
	 createthumb($uploadFile,$uploadthumb2,100,100);
      }

      list($name,$ext) = split('\.',$orgfile);
       $final_filename = $name . "_" . $entry_id . "_hab." . $ext;
       $fromfile = $uploadDir  .  $orgfile;
       $tofile = $uploadDir . $final_filename;
       rename("$fromfile" ,"$tofile");

       $t1 = $uploadDir . "tn_".$orgfile ;
       $t2 = $uploadDir .  "tn_".$final_filename ;
       rename("$t1" ,"$t2");

       $t3 = $uploadDir . "metn_".$orgfile ;
       $t4 = $uploadDir .  "metn_".$final_filename ;
       rename("$t3" ,"$t4");

       $sql = "update migwatch_species_set set img_hab = '$final_filename' where id='$entry_id'";
       mysql_query($sql);

    }

   }


   if($_FILES['img_behav']['name']) {
      $uploadFile = $uploadDir . $_FILES['img_behav']['name'];
      $uploadthumb = $uploadDir . "metn_" .  $_FILES['img_behav']['name'];
      $uploadthumb2 = $uploadDir . "tn_" .  $_FILES['img_behav']['name'];
   if ( move_uploaded_file($_FILES['img_behav']['tmp_name'], $uploadFile)) {
      $orgfile = $_FILES['img_behav']['name'];
      if(list($width, $height) = getimagesize($uploadFile)) {
      
              createthumb($uploadFile,$uploadthumb2,100,100);

         createthumb($uploadFile,$uploadthumb,160,160);
      }

      list($name,$ext) = split('\.',$orgfile);
       $final_filename = $name . "_" . $entry_id . "_behav." . $ext;
       $fromfile = $uploadDir  .  $orgfile;
       $tofile = $uploadDir . $final_filename;
       rename("$fromfile" ,"$tofile");

       $t1 = $uploadDir . "metn_".$orgfile ;
       $t2 = $uploadDir .  "metn_".$final_filename ;
       rename("$t1" ,"$t2");

       $t3 = $uploadDir . "tn_".$orgfile ;
       $t4 = $uploadDir .  "tn_".$final_filename ;
       rename("$t3" ,"$t4");

       $sql = "update migwatch_species_set set img_behav = '$final_filename' where id='$entry_id'";
       mysql_query($sql);

    }

   }


   
   
}


?>
<style>

textarea {
   width:400px;
   height : 60px;
}

input {
  border:solid 1px #333;
  width:400px;
}

.species_table tr td:first-child  {
  text-align:right;
  font-weight:bold;
  font-size:13px;
}

</style>
<body>
	<div  class="container">
<?
if( $id = $_GET['id'])  { 
   $sql = "select species_id, species_name,size,descr,behaviour,dist_char,winter_dist,breed_dist,iucn_status,call_text,other_notes,call_audio,img_male,img_female,img_juv,img_hab,img_behav,habitat,video from migwatch_species_set where id='$id'";
   $result = mysql_query($sql);

   while($data = mysql_fetch_array($result)) {

      $species_id_v = $data['species_id'];
      $species_name_v = $data['species_name'];
      $size_v = $data['size'];
      $descr_v = $data['descr'];
      $behaviour_v = $data['behaviour'];
      $dist_char_v = $data['dist_char'];
      $winter_dist_v = $data['winter_dist'];
      $breed_dist_v = $data['breed_dist'];
      $iucn_status_v = $data['iucn_status'];
      $call_text_v = $data['winter_dist'];
      $other_notes_v = $data['other_notes'];
      $call_audio_v = $data['call_audio'];
      $img_male_v = $data['img_male'];
      $img_female_v = $data['img_female'];
      $img_juv_v = $data['img_juv'];
      $img_hab_v = $data['img_hab'];
      $img_behav_v = $data['img_behav']; 
      $habitat_v = $data['habitat'];
      $video_v = $data['video'];
   }


   echo "<table><tr><td><a href='addspecies.php'>see all species</a></td></tr></table>";

} else {

   echo "<table class='box'>";
  
   $i = 0;
   $sql = 'select id,species_name from migwatch_species_set';
   $result = mysql_query($sql);
   while ( $data = mysql_fetch_assoc($result)) {
   if ( $i != 0  && $i % 4 == 0 ) { echo "</tr><tr>"; }
     $n = $i + 1;
     echo "<td>" . $n . " <a href='addspecies.php?id=" . $data['id'] . "'>" . $data['species_name'] . "</a>&nbsp;<a style='background-color:orange;color:#000' href='deletespecies.php?id=" . $data['id'] . "'>delete</a></td>";
    $i++; 
   }
    
   echo "</table>";

}

?>
<div class='container'>
	      <form action="addspecies.php" enctype="multipart/form-data" method="post" id="addmigwatchspecies">
	      	    <table class='species_table' style="width:600px;text-align:right">
		    <tr>
			<td colspan="2"><h2>Add a new MigrantWatch species</h2></td>
		    </tr>
		    <tr>
			<td>Name of the species</td>
          <td><input type="hidden" name="id" value="<? echo $_GET['id']; ?>">
		   <input type="text" name="species_name" id="species" value="<? echo $species_name_v; ?>"></td>
			<input type="hidden" name="species_id" id="species_id" value="<? echo $species_id_v; ?>">
		    </tr>
		    <tr>
			<td>Size</td><td><input type="text" name="size" id="size" value="<? echo $size_v; ?>"></td>
		    </tr>
		    <tr>
			<td>Description</td><td><textarea name="descr" id="descr"><? echo $descr_v; ?></textarea>
                    </tr>
		    <tr>
                        <td>Identification guide</td><td><textarea  name="dist_char" id="dist_char"><? echo $dist_char_v; ?></textarea>
                    </tr>
		    <tr>
                        <td>Behaviour</td><td><textarea  name="behaviour" id="behaviour"><? echo $behaviour_v; ?></textarea>
                    </tr>

                    <tr>
                      <td>Habitat</td><td><textarea name="habitat"><? echo $habitat_v; ?></textarea> </td>
                    </tr>
		    
		      
		    <tr>
			<td>Winter Distribution</td><td><textarea name="winter_dist"><? echo $winter_dist_v; ?></textarea></td>
		    </tr>	
		    <tr>
		      <td>Breeding Distribution</td><td><textarea name="breed_dist"><? echo $breed_dist_v; ?></textarea> </td>
                    </tr>
	
		    <tr>
			<td>IUCN status</td>
			<td>
				<select name="iucn_status" id="iucn_status">
					<option value="">select a status</option>
					<option value="en" <?php if($iucn_status_v == 'en') print("selected"); ?>>Endangered</option>
					<option	value="vu" <?php if($iucn_status_v == 'vu') print("selected"); ?>>Vulnerable</option>
					<option	value="th" <?php if($iucn_status_v == 'th') print("selected"); ?>>Threatened</option>
					<option value="cd" <?php if($iucn_status_v == 'cd') print("selected"); ?>>Conservation Dependent</option>
					<option value="nt" <?php if($iucn_status_v == 'nt') print("selected"); ?>>Near Threatened</option>
					<option value="lc" <?php if($iucn_status_v == 'lc') print("selected"); ?>>Least Concern</option>
				</select>
			</td>
		    </tr>
		    <tr>
			<td>Call ( Audio )</td><td><input type="hidden" name="MAX_FILE_SIZE" value="100000000"><input type="file" name="call_audio" id="call_audio"><br><? echo $call_audio_v; ?></td>
		    </tr>
		    <tr>
<td>Call ( Text )</td><td><textarea  name="call_text" id="call_text" value="<? echo $call_text_v; ?>"></textarea></td>
                    </tr>
		    <tr>
       <td>Other Notes</td><td><textarea  name="other_notes" id="other_notes" value="<? echo $other_notes_v; ?>"></textarea></td>
                    </tr>
		    <tr>
			<td>Image ( Male )</td><td><input type="file" name="img_male"	id="img_male"><br><? echo $img_male_v; ?></td>
                    </tr>
		    <tr>
                        <td>Image ( Female )</td><td><input type="file" name="img_female"   id="img_female"><br><? echo $img_female_v; ?></td>
                    </tr>
		    <tr>
                        <td>Image ( Juvenile )</td><td><input type="file" name="img_juv"   id="img_juv"><br><? echo $img_juv_v; ?></td>
                    </tr>
		    <tr>
                        <td>Image ( Habitat )</td><td><input type="file" name="img_hab"   id="img_hab"><br><? echo $img_hab_v; ?></td>
                    </tr>
		    <tr>
                        <td>Image ( Behaviour )</td><td><input type="file" name="img_behav"   id="img_behav"><br><? echo $img_behav_v; ?></td>
                    </tr>
		    <tr>
                        <td>Video ( Embed )</td><td><textarea  name="video" id="video" value="<? echo $video_v; ?>"></textarea></td>
                    </tr>
		    <tr><td colpsan="2"><input name="add_species_submit" style="width:80px" type="submit" value="submit"></td></tr>
		    </table>

	      </form>
<?
function createthumb($name,$filename,$new_w,$new_h)
{
        $system=explode(".",$name);
        if (preg_match("/jpg|jpeg/",$system[1])){$src_img=imagecreatefromjpeg($name);}
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

	</div>

</body>
<script type="text/javascript">

$('#species').autocomplete("autocomplete.php", {
                        width: 400,
                        selectFirst: false,
                        matchSubset :0,
                        extraParams : {all : 1},
                        mustMatch: true,                       
});

$("#species").result(function(event, data, formatted) {
  $('#species_id').val(data[1]);
});
</script>

</html>
