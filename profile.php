<? session_start(); 
   ob_start(); 
   include("db.php");
   $user = $_GET['id'];

   if (ctype_digit($user)) {
   
     $sql_where = " u.user_id='$user'";

   } else  {

     $sql_where	= " u.username='$user'";
   }

   function getLocationInfo($id,$connect) {

        $sql = "SELECT * FROM `migwatch_locations` m,`migwatch_states` s " .
                   "WHERE location_id='$id' AND m.state_id = s.state_id";
        $res = mysql_query($sql,$connect);
	
        if (mysql_num_rows($res) > 0) {
                $results = false;
                while($result = mysql_fetch_assoc($res)) {
                        $results = $result['location_name'] . ", " . $result['state'];
                }
                return $results;
     
   }
}

$page_title="MigrantWatch: Profile";
include("main_includes.php");
?>
<body onload="load()">
<? 
include("header.php");
?>
<div class="container first_image">
     	<div id='tab-set'>   
             <ul class='tabs'>
                 <li><a href='#x' class='selected'>profile</a></li>
             </ul>
        </div>
     <table class='page_layout'>
<?
   $sql= "select u.user_id, u.city,u.district,s.state from migwatch_users as u, migwatch_states as s where s.state_id = u.state_id and ";
   $sql .= $sql_where;
   $result_user = mysql_query($sql);
   $user_info = mysql_fetch_assoc($result_user);
   $user_id = $user_info['user_id'];
   $username =  getUserNameById($user_id,$connect);
   if($user_info['city']) { 
   	$user_location .=  $user_info['city'] .", ";
    } 

   if($user_info['district']) {
       $user_location .=  $user_info['district'] .", ";
    }
$user_location .=  $user_info['state'];

echo "<tr><td><span style='font-weight:bold;font-size:17px'>" . $username . "</span>&nbsp;&nbsp;"; 
echo "<br><span style='font-weight:bold'>" . $user_location . "</span></td></tr>";

?>
      <tr><td><h4>Recent activity</h4></td></tr>
      <?
	  $sql="select * from migwatch_lifestream where user_id='$user' order by id DESC LIMIT 5";
	  $result=mysql_query($sql);
	  while($data=mysql_fetch_assoc($result)) {
	  	echo "<tr><td class='comment_content'>" . $username  . " " . $data['activity_action'];
		if($data['target_user_id']) {
		 if($user == $data['user_id']) {
			 echo "&nbsp;his/her";
		 } else {
		         echo "&nbsp;" . getUserNameById($data['user_id'],$connect);
		 }
	        } else if($data['target_loc_id']) { 
			echo  "&nbsp;" . getLocationInfo($data['target_loc_id'],$connect);

		}
	        
		echo " <a href='" . $data['activity_name'] . ".php?id=" . $data['target_link_id'] . "'>" . $data['activity_name'] . "</a>";

		echo "</td></tr>";
	  }
      ?>
     </table>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
</script> 
<? include("footer.php"); include("login_includes.php"); ?>
</body>
</html>
