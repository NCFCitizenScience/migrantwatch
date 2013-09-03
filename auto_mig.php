<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<script type="text/javascript" src="jquery.js"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<script src="jquery.bgiframe.min.js" language="javascript"></script>
<script type='text/javascript' src='jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>
<link href="jquery.autocomplete.css"  rel="stylesheet" type="text/css" />

<script type="text/javascript">

$().ready(function() {
    $("#singleBird").autocomplete("auto_miglocations.php", {
            width: 260,
                selectFirst: false,
                mustMatch:true
        });
});
</script></head><body><form>
<input type="text" style="width:300px" id="singleBird"></form></body>
</html>