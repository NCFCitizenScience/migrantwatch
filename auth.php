<?
	session_start();
        $current_url = $_SERVER['REQUEST_URI'];
	if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){  
      	     $_SESSION['userid'] = $_COOKIE['cookuid'];
             $_SESSION['username'] = $_COOKIE['cookname'];  
             $_SESSION['password'] = $_COOKIE['cookpass'];  
        }  

  	if(!isset($_SESSION['userid'])){
		header("Location: login.php?done=$current_url");
		die();
   	}

date_default_timezone_set('Asia/Calcutta');
?>