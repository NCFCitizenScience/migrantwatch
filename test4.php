<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

  <!-- Framework CSS -->
        <link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
        
        <!-- Import fancy-type plugin for the sample page. -->
        <link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
          <script type="text/javascript" src="jquery-1.3.2.min.js"></script>        
	 <link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">
        <link href="thickbox.css"  rel="stylesheet" type="text/css" />
<script src="thickbox.js" language="javascript"></script>
         

  </head>

  <body>
  

   <div class="container">
<h2>Migrant Watch</h2>

		<div class='span-9' style='width:700px' id='tab-set'>
           
   			<ul class='tabs'>
   
   				<li><a href='#text1' class='selected'>General Sighting</a></li>
   				<li><a href='#text2'>First Sighting</a></li>
   				<li><a href='#text3'>Last Sighting</a></li>
   
   			</ul>
   			<div id='text1'>
   			<p>   
   				<? include("gensightings2.php"); ?>

			</p>
			</div>

			<div id='text2'>
				<p><? include("firstsighting.php"); ?></p>
			</div>

			<div id='text3'>
				<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
			</div>

		</div>
	</div>
	

</div>


<script type="text/javascript">
$("ul.tabs li.label").hide(); 
$("#tab-set > div").hide(); 
$("#tab-set > div").eq(0).show(); 
  $("ul.tabs a").click( 
  function() { 
  $("ul.tabs a.selected").removeClass('selected'); 
  $('#gen_form :input').attr('disabled', true);
  $("#tab-set > div").hide();
  $(""+$(this).attr("href")).fadeIn('slow'); 
  $(this).addClass('selected'); 
  return false; 
  }
  );


	
  
  </script>


</body>
</html>
