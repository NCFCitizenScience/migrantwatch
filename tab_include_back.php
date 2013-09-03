<link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">
<script type="text/javascript">
$(function () {
$("ul.tabs li.label").hide(); 
$("#tab-set > div").hide(); 
$("#tab-set > div").eq(0).show(); 
  $("ul.tabs a").click( 
  function() { 
  $("ul.tabs a.selected").removeClass('selected'); 
  $("#tab-set > div").hide();
  $(""+$(this).attr("href")).fadeIn('slow'); 
  $(this).addClass('selected'); 
  return false; 
  }
  );
  });
</script>