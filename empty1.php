<!-- Framework CSS -->
        <link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
        
        <!-- Import fancy-type plugin for the sample page. -->
        <link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
          <script type="text/javascript" src="jquery-1.3.2.min.js"></script>
 <style>
        table.tablesorter tr.even td { background:#E5ECF9;}
</style> 


<script type="text/javascript" src="pager/jquery.tablesorter.js"></script>
<script type="text/javascript" src="pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="pager/chili-1.8b.js"></script>
<script type="text/javascript" src="pager/docs.js"></script>

<script type="text/javascript">
        $(function() {
                $("#table1")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager"), positionFixed: false});
                
        });

        
</script>
<link href="thickbox.css"  rel="stylesheet" type="text/css" />
<script src="thickbox.js" language="javascript"></script>

<script src="jquery.autocomplete.js" language="javascript"></script>

<script src="jquery.bgiframe.min.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<link href="jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>


  <script type="text/javascript" charset="utf-8">
                $(document).ready(function(){ 
                    $('#text1').emptyonclick();  
                });
            </script>

<textarea id="text1" name="text_1" rows="8" cols="40">Textarea 1</textarea>

<link href="jqModal.css"  rel="stylesheet" type="text/css" />
<script src="jqModal.js" type="text/javascript"></script>
<script src="jquery.autogrow.js" type="text/javascript">