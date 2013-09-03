<? include("checklogin.php");
   $page_title="MigrantWatch: All species";
   include("main_includes.php");
?>
<body><? 
	include("header.php");	
?>

<div class="container first_image">
   <div id='tab-set'>   
     <ul class='tabs'>
        <li><a href='#x' class='selected'>all species</a></li>
    </ul>
   </div>
   <div class='page_layout'>
     <? include("allspecies_includes.php"); ?>
   </div>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<?php 
   include("footer.php");
   include("login_includes.php");
?>

</body>
</html>
