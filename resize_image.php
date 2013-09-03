$tmpimg = tempnam("/tmp" "MKPH");
$newfile = "$uploaddir/scaled.jpg";

/*== CONVERT IMAGE TO PNM ==*/
if ($ext == "jpg") { system("djpeg $imgfile >$tmpimg"); }
else { echo("Extension Unknown. Please only upload a JPEG image."); exit(); }

/*== scale image using pnmscale and output using cjpeg ==*/
system("pnmscale -xy 250 200 $tmpimg | cjpeg -smoo 10 -qual 50 >$newfile");