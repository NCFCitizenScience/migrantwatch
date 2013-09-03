<html>
<head>
<title>jQuery Form Plugin</title>
<link rel="stylesheet" type="text/css" media="screen" href="../jq.css" />
<link rel="stylesheet" type="text/css" media="screen" href="form.css" />
<script type="text/javascript" src="http://malsup.com/jquery/jquery-1.2.6.js"></script>
<script type="text/javascript" src="http://malsup.com/jquery/jquery.tabs.pack.js"></script>
<script type="text/javascript" src="http://malsup.com/jquery/jquery.history.pack.js"></script>
<script type="text/javascript" src="http://malsup.com/jquery/form/jquery.form.js?2.28"></script>
<script type="text/javascript" src="http://malsup.com/jquery/chili-1.7.pack.js"></script>
<script>
   // prepare the form when the DOM is ready 
   $(document).ready(function() { 
       // bind form using ajaxForm 
       $('#htmlForm').ajaxForm({ 
	   // target identifies the element(s) to update with the server response 
	 target: '#htmlExampleTarget', 
 
	     // success identifies the function to invoke when the server response 
	     // has been received; here we apply a fade-in effect to the new content 
	     success: function() { 
	     $('#htmlExampleTarget').fadeIn('slow'); 
	   } 
	 }); 
     });
</script>
<div id="htmlExampleTarget"></div>
<form enctype="multipart/form-data" id="htmlForm" action="html-echo.php" method="post"> 
   
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
   Choose a file to upload: <input name="userfile" type="file" /><br>
   
    <input type="submit" value="Upload File" />
</form>
