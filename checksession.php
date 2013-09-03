<?

session_start();

if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){  
      $_SESSION['username'] = $_COOKIE['cookname'];  
      $_SESSION['password'] = $_COOKIE['cookpass'];  
}  