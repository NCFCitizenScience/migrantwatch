<html>
<head>
<script type="text/javascript" src="jquery.js"></script>
</head>
<? if($_POST) { echo "hello"; } ?>
<body>
<script type="text/javascript">
        $(document).ready(function() {
        var toggle_like = false;        
             
                $('#l_like').show();
                $('#l_unlike').hide();
              $('a#l_like').click(function() {
                $('#l_like').toggle();
                $('#l_unlike').toggle();
                
        });
                 $('a#l_unlike').click(function() {
                $('#l_like').toggle();
                $('#l_unlike').toggle();
                $.post('like.php');

			
        });

});
</script>


<a href="#" id="l_like">like</a>
<a href="#" id="l_unlike">unlike</a>
</body>
</html>
