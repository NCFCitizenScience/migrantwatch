<link rel="stylesheet" href="http://blueprintcss.org/blueprint-plugins/tabs/screen.css" type="text/css" media="screen">

<script type="text/javascript">
  
    

	$("ul.tabs li.label").hide(); 
	$(".tab-set > div").hide(); 
	$(".tab-set > div").eq(0).show(); 
        //$(".container").removeClass('gradient1');
 
  $("ul.tabs a").click( 
             
  	     function() { 
             
                               
  	     $("ul.tabs a.selected").removeClass('selected'); 
                                  
  	     $(".tab-set > div").hide();
  	     $(""+$(this).attr("href")).fadeIn('slow'); 
  	     $(this).addClass('selected'); 
  	     return false; 
 
  }
  );
  
  
  
</script>

<script type="text/javascript">


$(document).ready(function(){


  

$('.gradient1').gradientz({

start: "#fffff9",     // start color: default is the background color.
          end: "#ece0ca"
});



});

</script>-->
