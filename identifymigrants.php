<? session_start(); 
   include("db.php");
   //include("wordpress_includes.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>
</head>
<body><? 
	include("header.php");	
?>

<div class="container first_image">
<? $page=mw_get_page(429); ?>
   <div id='tab-set'>   
     <ul class='tabs'>
        <li><a href='#x' class='selected'><? echo strtolower($page[0]); ?></a></li>
    </ul>
   </div>
   <div class='page_layout'>
     <? echo nl2br($page[1]); ?>
   </div>
</div>
</div>
</div>
<div class="container bottom">

</div>
<?php 
   include("footer.php");
?>
<script src="combine_js.php?version=<?php require('combine_js.php'); ?>" type="text/javascript"></script>
</body>
</html>
