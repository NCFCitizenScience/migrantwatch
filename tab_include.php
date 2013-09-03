<link rel="stylesheet" href="<? echo $src_base; ?>/tabs/screen.css" type="text/css" media="screen">
<script type="text/javascript">
	$("ul.tabs li.label").hide(); 
	$("#tab-set > div").hide(); 
	$("#tab-set > div").eq(0).show(); 
       //$("#tab-set > div").eq(1).show();
     
  $("ul.tabs a").click(
 
  	     function() { 
                        
  	     		$("ul.tabs a.selected").removeClass('selected'); 
  			$("#tab-set > div").hide();
                       
  			$(""+$(this).attr("href")).fadeIn('slow'); 
                       
  			$(this).addClass('selected');
                                                
                        $('#table1').trigger("appendCache");
  			return false; 
     }
  );
 
  
</script>
