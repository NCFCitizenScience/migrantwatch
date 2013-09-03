<? 
   include("auth.php");
   $page_title="MigrantWatch: Report sightings";
   
   include("main_includes.php");
?>

<body>
<?
   include("header.php");
?>
<div class="container first_image">
     <div class='addsighting'>
   	<div id='tab-set'>   
   	     <ul class='tabs'>
   	     	 <li><a href='#first' class='selected'>add&nbsp;sightings</a></li>
   	     </ul>
	 </div>   	 
	 
			<?  include("add_sightings_form.php"); ?>
   </div>
   
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<? include("footer.php"); ?>
</body>
</html>
