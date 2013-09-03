<? 
    $content = $_GET['content']; 
    if(!$content) { echo "Invalid"; exit(); }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
  <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>Simple News Ticker</title> 
    <link rel="stylesheet" type="text/css" href="simpleTicker.css"> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js">></script> 
<script type="text/javascript" src="http://malsup.github.com/chili-1.7.pack.js"></script> 
<script type="text/javascript" src="http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.2.72.js"></script> 
<script type="text/javascript" src="http://malsup.github.com/jquery.easing.1.1.1.js"></script>

    <style>
      .ticker { width:250px; height:100px; border:1px solid #aaaaaa; overflow:auto; }
.ticker dt { font:normal 14px Georgia; padding:0 10px 5px 10px; background-color:#e5e5e5; padding-top:10px; border:1px solid #ffffff; border-bottom:none; border-right:none; position:relative; }
.ticker dd { margin-left:0; font:normal 11px Verdana; padding:0 10px 10px 10px; border-bottom:1px solid #aaaaaa; background-color:#e5e5e5; border-left:1px solid #ffffff; position:relative; }
.ticker dd.last { border-bottom:1px solid #ffffff; }
.ticker div { margin-top:0; }
</style>
  </head> 
  <body> 
    <div class="tickerContainer" style='height:200px'> 
        <? echo stripslashes(unserialize(gzuncompress(stripslashes(base64_decode(strtr($content, '-_,', '+/=')))))); ?>
	</ul><div class="nav"><a id="prev2" href="#x">Prev</a> <a id="next2" href="#x">Next</a></div>
    </div> 
<script>
  $('.ticker').each(function() {     
      var ticker = $(this);
      $(this).cycle({ 
      fx:     'fade', 
      speed:  'fast', 
      timeout: 0, 
      next:   '#next2', 
      prev:   '#prev2' 
     });
});
</script> 
    

   

       
  </body> 
</html>
