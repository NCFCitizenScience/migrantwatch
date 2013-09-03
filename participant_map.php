<? include("checklogin.php");
   $page_title="MigrantWatch: Participant map";
   include("main_includes.php");
?>
<body><? 
	include("header.php");	
?>
<style> .cms { font-size:13px; line-height:1.8em; padding:20px;} </style>
<div class="container first_image">

   <div id='tab-set'>   
     <ul class='tabs'>
        <li><a href='#x' class='selected'>participant&nbsp;map</a></li>
    </ul>
   </div>
   <div class='page_layout cms'>
     <img align=center src="http://www.migrantwatch.in/MW_participants_5Sep08.jpg">
   </div>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<?php 
   include('login_includes.php'); 
   include("footer.php");
?>
</body>
</html>
