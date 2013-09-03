<?php 
//echo '<div style="background-color:#ffa; padding:20px">hello</div>'; 

$uploadDir = "images2/";

$uploadFile = "images2/" . $_FILES['userfile']['name'];
$uploadthumb = "images2/" . "tn_" . $_FILES['userfile']['name'];

if ( move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile))
  {
    //print "File is valid, and was successfully uploaded. ";
    //print "Here's some more debugging info:";
    //print_r($_FILES); 
    //echo $uploadFile."<br>";
    //echo $uploadthumb;

    createthumb($uploadFile,$uploadthumb,160,160);
    //print "<center><img src=\"images2/" . $_FILES['userfile']['name'] . "\">\n\n";
    print "<center><img src=\"images2/tn_" . $_FILES['userfile']['name'] . "\"></center>";

  }

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