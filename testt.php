<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
                    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  
  <script>
  $(document).ready(function(){
    
    $("form").submit(function() {
     /* if ($("input:first").val() == "correct") {
        $("span").text("Validated...").show();
        return true;
      }
      $("span").text("Not valid!").show().fadeOut(1000); */

     alert('hello');
      return false;
    }); 

  });
  </script>
  <style>
  p { margin:0; color:blue; }
  div,p { margin-left:10px; }
  span { color:red; }
  </style>
</head>
<body>
  <p>Type 'correct' to validate.</p>
  <form action="javascript:alert('success!');">
    <div>
      <input type="text" />
      <input type="submit" />
    </div>
  </form>
  <span></span>
</body>
</html>
