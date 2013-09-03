<? include("checklogin.php");
   $page_title="MigrantWatch: Frequently Asked Questions";
   include("main_includes.php");
?>
<body><? 
	include("header.php");	
?>
<style> .toc_box { border: solid 1px; padding: 10px; font-size:10px; width:300px } </style>
<script> $('.toc_box').corner();</script>
<div class="container first_image">
<? $page=mw_get_page(440); ?>
   <div id='tab-set'>   
     <ul class='tabs'>
        <li><a href='#x' class='selected'><? echo $page[0]; ?></a></li>
    </ul>
   </div>
   <div class='page_layout' style="font-size:13px;width:900px">
     <? echo nl2br($page[1]); ?>
   </div><br><br>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<?php 
   include("footer.php"); include("login_includes.php");
?>

</body>
</html>
