<?

session_start();
    header("Cache-control: private"); // IE 6 Fix

    if(!isset($_POST['cmd']) || !isset($_SESSION['userid'])) {
        //$message = "This page is for internal operations only";

        exit;
    }
    foreach($_POST as $key=>$value){
        $_SESSION[$key]=$value;
    }

    //while(list($key,$val) = each ($_POST))
    //   $_SESSION[$key] = $val;

    include("./db.php");
    include("./functions.php");
    $referer = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'main.php';
    if(substr($referer, -4) == '.php') {
        $newreferer = $referer."?";
    } else {
        $newreferer = $referer."&";
    }

    echo $cmd = $_POST['cmd'];

?>