<?   include("db.php");
include("main_includes.php");
        include("page_includes_js.php"); 
     $sighting_id = $_GET['id']; 

include("config.php");

print_r($_FILES);

if($_REQUEST['uploadphotos'] ){
for( $i =1 ; $i <= count($_FILES); $i++ ) { 
     echo $uploadFile = $uploadDir . $_FILES['userfile' . $i]['name'];
     $uploadthumb = $uploadDir . "tn_" . $_FILES['userfile'. $i]['name'];

       echo $_FILES['userfile' . $i]['tmp_name'];

	if ( move_uploaded_file($_FILES['userfile' . $i]['tmp_name'], $uploadFile))
	{
                print_r($_FILES);
		createthumb($uploadFile,$uploadthumb,100,100);
                echo "hello";
	}
		
	//$sql = "insert into migwatch_photos(sighting_id,photo_captions,photo_filename";
	//mysql_query($sql);
	
}

}


  

     $sql1 = "select photo_id,photo_filename, photo_captions from migwatch_photos where sighting_id='$sighting_id'";
     $result1 = mysql_query($sql1);
     
     echo "<table style='width:400px'><tr>";
     $i=0;
     while($row1 = mysql_fetch_array($result1)) {    
        $photo_id = $row1['photo_id'];
	$filename = trim($row1['photo_filename']);
	$caption = $row1['photo_captions'];
	$j = $i % 2;
        if( $i != 0 && $j == 0 ) { echo "</tr><tr>"; }
	echo "<td>";
	     echo "<table class='single_img' style='width:300px'>";
	     echo "<tr>";
	     	  echo "<td><img style='border:solid 1px' src='images_tmp/tn_". $filename."'></td>";
		  echo "<td><textarea style='height:100px;width:150px' name='photo_caption'>" . $caption . "</textarea></td>";
	     echo "</tr>";
             echo "<tr>";          
       
       ?>
			<td colspan='2' style='text-align:right'>
			    <a class="deletephoto" id="delete-<? echo $photo_id; ?>" href="#x">delete</a>
			</td>
		  </tr>
		  </table>
	     </td>
      <?
       $i++;
      }
      
      echo "</table>";
      $count_uploaded_pics = mysql_num_rows($result1);
      $can_upload_count = 4 - $count_uploaded_pics;

?>

<script type="text/javascript">

$(document).ready(function() {

        $('a.deletephoto').click(function() {
         //e.preventDefault();
          var parent = $(this).parent().parent().parent(); 
          id = $(this).attr('id');
          id = id.replace(/delete-/, "");

                        jConfirm('Are you sure you want ot delete this photo?', '',function(r)
                                {
                                        if(r==true)
                                {
                                        $.post('deletephoto.php', { id: id, ajax: 'true' }, function() {
                                                parent.fadeOut(2000, function() {
                                                        parent.animate( { backgroundColor: '#cb5555' }, 500);
                                                });
                                        
                                });
                                }
        });

         });
});



</script>

 <div class="box" id="upload_box" style="width:600px">

<script> 

         

function appendNewPhotos()
{
	var tbl = $('#uploadPic');
      	var lastRow = $('#uploadPic tr').length; 
        var iteration = lastRow;
        if (iteration < <? echo $can_upload_count; ?>) {
        var newElement =  "<tr><td>Photo " + iteration + "</td><td>" + "<input name=userfile" + iteration + " type='file'/>" + "</td></tr>";
	$('#uploadPic').append(newElement); 

        } 
        //alert(newElement);
}
</script>
<form id="uploadForm" enctype="multipart/form-data" action="uploadtemp.php" method="post">
       <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
      
       
<? for($i=1; $i <= $can_upload_count; $i++) { ?>
      <!-- <tr><td id='photo<? echo $i; ?>' >Photo <? echo $i; ?></td><td><input id="userfile<? echo $i; ?>" name="userfile<? echo $i; ?>" type="file"/></td></tr>-->
<? } ?>
	Choose a file to upload: <input name="userfile1" type="file" /><br>
     <input name="userfile2" type="file" /><br>
      <input name="userfile3" type="file" />
     

      <input type="submit" value="Upload Photos" name="uploadphotos" id="uploadbutton1"/>
 </form>
                       
</div>

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
