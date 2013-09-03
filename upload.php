<?php

$sighting_id = $_REQUEST['sighting_id'];

include("db.php");

include("config.php");

for( $i =1 ; $i <= count($_FILES); $i++ ) { 
     $uploadFile = $uploadDir . $_FILES['userfile' . $i]['name'];
     $uploadthumb = $uploadDir . "tn_" . $_FILES['userfile'. $i]['name'];

$sql = "SELECT l1.number, s.scientific_name, l.location_name, l.city, st.state, l1.sighting_date, l1.species_id, l1.location_id, ";
     $sql.= "l1.obs_type, l1.id,l1.species_id,l1.location_id, l1.deleted FROM migwatch_l1 l1 ";
     $sql.= "INNER JOIN migwatch_species s ON l1.species_id = s.species_id ";
     $sql.= "INNER JOIN migwatch_locations l ON l1.location_id = l.location_id ";
     $sql.= "INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
     $sql.= " where l1.deleted = '0' AND l1.id='$sighting_id'";

     $result = mysql_query($sql);
     while($row = mysql_fetch_array($result)) {
        $loc_name = $row{'location_name'}; 
        $loc_city = $row{'city'};
        $loc_state = $row{'state'};
        $si_date = $row{'sighting_date'};
        $sci_name = $row{'scientific_name'};

     }

if($loc_name) { $tags = $loc_name; }
if($loc_city) { $tags.= ', ' . $loc_city; }
if($loc_state) { $tags.= ', '. $loc_state; }
if($si_date) { $tags.= ', '. $si_date; }
if($sci_name) { $tags.= ', '. $sci_name; }

if ( move_uploaded_file($_FILES['userfile' . $i]['tmp_name'], $uploadFile))
{
	//print "File is valid, and was successfully uploaded. ";
	//print "Here's some more debugging info:";
	//print_r($_FILES); 

	createthumb($uploadFile,$uploadthumb,100,100);

}

}


?>
<!--<table><tr><td>
<?
	//print "<center><img src=\"images/" . $_FILES['userfile' . $i]['name'] . "\">\n\n";
	print "<img src=\"image_uploads/tn_" . $_FILES['userfile' . $i]['name'] . "\">\n\n";
?></td><?
	print "<form action='finalupload.php' method=POST>\n";
	print "<input type=hidden name='filename' value=\"" .  $_FILES['userfile' . $i]['name'] . "\">\n";
	print "<td>Add a caption<br><input type=text name=title size=20></td></tr>";
	print "<tr><td><input type=submit value='submit' name='final_upload'></td></tr>";
       ?> <script>$('#photo<? echo $i; ?>').hide(); </script><?
	

	
?>
</form></td></tr>



</table>-->
<!--<script>$('#uploadPic').hide(); $('#uploadbutton1').hide(); </script>-->
<?
/*} else 
{ 
	print "Possible file upload attack! Here's some debugging info: "; 
	//print_r($_FILES); 
} 

*/

print ""; 


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

