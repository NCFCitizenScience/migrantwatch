<? session_start();
if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){ 
  
      $_SESSION['userid'] = $_COOKIE['cookuid'];
      $_SESSION['username'] = $_COOKIE['cookname'];  
      $_SESSION['password'] = $_COOKIE['cookpass'];  
   }  
date_default_timezone_set('Asia/Calcutta');
?>