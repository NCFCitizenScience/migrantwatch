<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <? include("main_includes.php"); ?>


<script type="text/javascript" src="jquery-gradient/jquery.dimensions.js" ></script>
<script type="text/javascript" src="jquery-gradient/jquery.gradient.js"></script>
<script type="text/javascript">
$(function () {


$('.gradient1').gradient({
from:      '003366',
to:        '333333',
//direction: 'horizontal'
});



});

</script>


</head>
<body>

<div class='container gradient1' style="height:500px;width:950px;margin-left:auto;margin-right:auto" id='tab-set'>
   <ul class='tabs'>
   
           <li><a href='#text1' class='selected'>Tab1</a></li>
           <li><a href='#text2'>Tab 2</a></li>
 
   </ul>
   <div id='text1' style='color:#fff'>
              Content of Tab #1
  </div>
  <div id='text2' style='color:#fff'>
          Content of tab #2
   </div>
</div>





</div>



</body>
<? include("tab_include.php"); ?>
</html>
