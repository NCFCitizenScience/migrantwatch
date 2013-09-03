<? include('db.php'); 
   $user_id=$_GET['user'];   
   $sql="select user_email, city from migwatch_users where user_id='$user_id'";
   $result=mysql_query($sql);
   $data=mysql_fetch_assoc($result);
   $output = "Email: ".  $data['user_email'] . "<br><b>City</b>: " . $data['city'] . "<br><b>Links</b>: <a href='data.php?user=" . $user_id . "'>sightings</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='gallery.php?user=" . $user_id . "'>photos</a>";
   echo $output;
?>