
<link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
        
        <!-- Import fancy-type plugin for the sample page. -->
        <link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
          <script type="text/javascript" src="jquery-autocomplete/lib/jquery.js"></script>        
	 <link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">
        <link href="thickbox.css"  rel="stylesheet" type="text/css" />
<script src="thickbox.js" language="javascript"></script>
         <link href="CalendarControl.css"  rel="stylesheet" type="text/css">
<script src="CalendarControl.js" language="javascript"></script>


<script src="jquery-autocomplete/jquery.autocomplete.js" language="javascript"></script>
<link href="jquery-autocomplete/jquery.autocomplete.css"  rel="stylesheet" type="text/css" />

<script type='text/javascript' src='jquery-autocomplete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='jquery-autocomplete/lib/jquery.ajaxQueue.js'></script>


<script src="jquery.autogrow.js" language="javascript"></script>
<script src="date.js" language="javascript"></script>
<link href="jqmodal.css"  rel="stylesheet" type="text/css" />
<script src="jqmodal.js" language="javascript"></script>
<link href="thickbox.css"  rel="stylesheet" type="text/css" />
<script src="thickbox.js" language="javascript"></script>
<script src="jquery.emptyonclick.js" language="javascript"></script>

<? $type='general'; ?>

<script>
 $().ready(function() {
    $('#addCurrentLocation').autocomplete("autocomplete.php", {
      width: 300,
	  
                mustMatch: true,
                selectFirst: false,
                
	  });
  });
</script>
<td><input style="width:388px" type="text" id="addCurrentLocation" name="addCurrentLocation" value=""></td>