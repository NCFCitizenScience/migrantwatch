<script src="<? echo $src_base; ?>/alerts/jquery.ui.draggable.js" type="text/javascript"></script>
<script src="<? echo $src_base; ?>/alerts/jquery.alerts.js" type="text/javascript"></script>
<link href="<? echo $src_base; ?>alerts/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" /> 
<link href="<? echo $src_base; ?>jqmodal_include.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="<? echo $src_base; ?>dialog.css" type="text/css" />
<script type="text/javascript" src="<? echo $src_base; ?>/jquery.jdialog.js"></script>
<script type="text/javascript" src="<? echo $src_base; ?>/pager/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<? echo $src_base; ?>/pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="<? echo $src_base; ?>/pager/chili-1.8b.js"></script>
<script type="text/javascript" src="<? echo $src_base; ?>/pager/docs.js"></script>
<script type="text/javascript" src="<? echo $src_base; ?>/jquery.validate.js"></script>
<link href="<? echo $src_base; ?>pager/style.css"  rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<? echo $src_base; ?>/jquery-ui/ui/ui.core.js"></script>
<script type="text/javascript" src="<? echo $src_base; ?>/jquery-ui/ui/ui.datepicker.js"></script>



<script type="text/javascript">
        $(function() {
    

                  
                $("#table1")
                .tablesorter({  headers: { 
            	  5: { sorter: false }, 6: { sorter: false }, 7 : { sorter: false }, 8: { sorter: false } },widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager"), positionFixed: false});

                  $("#table2")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager2"), positionFixed: false});
                     
        });

        
</script>
<link href="<? echo $src_base; ?>thickbox/thickbox.css"  rel="stylesheet" type="text/css" />
<script src="<? echo $src_base; ?>/thickbox/thickbox.js" language="javascript"></script>

<script src="<? echo $src_base; ?>/jquery.autocomplete.js" language="javascript"></script>
<script src="<? echo $src_base; ?>/jquery.bgiframe.min.js" language="javascript"></script>
<script src="<? echo $src_base; ?>/jquery.autocomplete.js" language="javascript"></script>
<link href="<? echo $src_base; ?>jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="<? echo $src_base; ?>/jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>

<link href="<? echo $src_base; ?>jqModal.css"  rel="stylesheet" type="text/css" />
<script src="<? echo $src_base; ?>/jqModal.js" type="text/javascript"></script>
<script src="<? echo $src_base; ?>/jquery.autogrow.js" type="text/javascript"></script>
<script src="<? echo $src_base; ?>/jquery.corner.js" type="text/javascript"></script>
<link href="<? echo $src_base; ?>CalendarControl.css"  rel="stylesheet" type="text/css">
<script src="<? echo $src_base; ?>/CalendarControl.js" language="javascript"></script>
<script src="<? echo $src_base; ?>/jquery.form.js" language="javascript"></script>
<script src="<? echo $src_base; ?>/date.js" language="javascript"></script>
<script src="<? echo $src_base; ?>/alerts/jquery.ui.draggable.js" type="text/javascript"></script>
<script src="<? echo $src_base; ?>/alerts/jquery.alerts.js" type="text/javascript"></script>
<link href="<? echo $src_base; ?>alerts/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
<link type="text/css" href="<? echo $src_base; ?>/jquery-ui/demos//demos.css" rel="stylesheet" />
<link type="text/css" href="<? echo $src_base; ?>/jquery-ui/themes/base/ui.all.css" rel="stylesheet">