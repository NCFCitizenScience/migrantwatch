<? include("checklogin.php");
   $page_title="MigrantWatch: Highlighted species";
   include("main_includes.php");
?>
<body><? 
	include("header.php");	
?>

<div class="container first_image">
   <div id='tab-set'>   
     <ul class='tabs'>
        <li><a href='#x' class='selected'>highlighted species</a></li>
    </ul>
   </div>
   <div class='page_layout'>
     <? include("hspecies_includes.php"); ?>
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
