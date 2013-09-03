<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <? include("main_includes.php"); ?>
</head>
<? include("page_includes.php"); ?>
<script type="text/javascript" src="jquery-gradient/jquery.dimensions.js" ></script>
<script type="text/javascript" src="http://www.parkerfox.co.uk/labs/gradientz.js"></script>


<body>
<div class='container gradient1'>
<h2>hello</h2>
<div style="width:950px;margin-left:auto;margin-right:auto" class='tab-set'>
   <ul class='tabs'>
   
           <li><a href='#text1' class='selected'>Tab1</a></li>
           <li><a href='#text2'>Tab 2</a></li>
 
   </ul>
   <div id='text1'>
              <? //include("first_sighting_new.php"); ?>
  </div>
  <div id='text2'>
          Content of tab #2
   </div>
</div>


</div>






<? include("tab_include2.php"); ?>

</body>
</html>
