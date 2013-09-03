!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
                    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script type="text/javascript" src="http://dev.jquery.com/view/trunk/plugins/validate/jquery.validate.js"></script>
<style type="text/css">
* { font-family: Verdana; font-size: 96%; }
label { width: 10em; float: left; }
label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
p { clear: both; }
.submit { margin-left: 12em; }
em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
  <script>
  $(document).ready(function(){
    $("#commentForm").validate({
    rules: {
       ccomment : {
        required: true,
      }
     },
      messages: {
        ccomment :{
         required: "Required input",
        }
      }
     });


   });

  
  </script>
<script type="text/javascript" >
$(function() {
$(".comment_button").click(function() {

var test = $("#content").val();
var dataString = 'content='+ test;

if(test=='')
{
alert("Please Enter Some Text");
}
else
{
$("#flash").show();
$("#flash").fadeIn(400).html('<img src="ajax-loader.gif" align="absmiddle"> <span class="loading">Loading Comment...</span>');

$.ajax({
type: "POST",
url: "demo_insert.php",
data: dataString,
cache: false,
success: function(html){
$("#display").after(html);
document.getElementById('content').value='';
document.getElementById('content').focus();
$("#flash").hide();
}
});
} return false;
});
});
</script>
// HTML code
<div>
<form method="post" name="form" action="">
<h3>What are you doing?</h3>
<textarea cols="30" rows="2" name="content" id="content" maxlength="145" >
</textarea><br />
<input type="submit" value="Update" name="submit" class="comment_button"/>
</form>
</div>
<div id="flash"></div>
<div id="display"></div
  <style type="text/css">
#commentForm { width: 500px; }
#commentForm label { width: 250px; }
#commentForm label.error, #commentForm input.submit { margin-left: 253px; }
#signupForm { width: 670px; }
#signupForm label.error {
	    margin-left: 10px;
	    width: auto;
	    display: inline;
}
#newsletter_topics label.error {
		   display: none;
		   margin-left: 103px;
}
</style>

</head>
<body>
  

 <form class="cmxform" id="commentForm" method="get" action="">
 <fieldset>
      <p>
     
     <textarea id="ccomment" name="comment" cols="22"></textarea>
   </p>
   <p>
     <input class="submit" type="submit" value="Submit"/>
   </p>
 </fieldset>
 </form>
</body>
</html>
