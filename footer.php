<script type="text/javascript">
var timeout    = 500;
var closetimer = 0;
var ddmenuitem = 0;

function jsddm_open()
{  jsddm_canceltimer();
   jsddm_close();
   ddmenuitem = $(this).find('ul').css('visibility', 'visible');}

function jsddm_close()
{  if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

function jsddm_timer()
{  closetimer = window.setTimeout(jsddm_close, timeout);}

function jsddm_canceltimer()
{  if(closetimer)
   {  window.clearTimeout(closetimer);
      closetimer = null;}}

$(document).ready(function()
{  $('#jsddm > li').bind('mouseover', jsddm_open)
   $('#jsddm > li').bind('mouseout',  jsddm_timer)});

document.onclick = jsddm_close;
</script>

<script type="text/javascript">
$('.default-value').each(function() {
    var default_value = this.value;
    $(this).css('color', '#666'); // this could be in the style sheet instead
    $(this).focus(function() {
        if(this.value == default_value) {
            this.value = '';
            $(this).css('color', '#333');
        }
    });
    $(this).blur(function() {
        if(this.value == '') {
            $(this).css('color', '#666');
            this.value = default_value;
        }
    });
});


$('#password-clear').show();
$('#password-password').hide();

$('#password-clear').focus(function() {
    $('#password-clear').hide();
    $('#password-password').show();
    $('#password-password').focus();
});
$('#password-password').blur(function() {
    if($('#password-password').val() == '') {
        $('#password-clear').show();
        $('#password-password').hide();
    }
});

$('.help_text').tipsy({gravity: 's',title: 'title', html: true});

 var toggle = false;
  $(".rememberme").click(function () {
     if( toggle  == false) { 
         $('#remember').val('1'); 
         toggle = true;
     } else { 
         $('#remember').val('0'); toggle = false; 
     }
     $(".rememberme").toggle();           
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
   $(".first_image").corner('bottom');
  $(".realtime").corner("keep borders: keep");
   $('.ticker2').cycle({fx: 'fade', speed:  'fast', 
    timeout: 4000, 
    next:   '#next2', 
    prev:   '#prev2'  

});
 $(".timeago").timeago();

$('#query').emptyonclick();
   $(".sidebar").corner("keep borders: keep");
   $(".filter").corner();
   $(".first_image").corner('bottom');
   $('#query').autocomplete("autocomplete.php", {
  //    width: 388,
       selectFirst: false,
       mustMatch: true,
      matchSubset :true,
      matchContains: false,
      formatItem: formatItem

          });

$("#query").result(function(event, data, formatted) {

				   document.location.href= "guide.php?id="+ data[1];

});
});
function formatItem(row) {
     var completeRow;
         completeRow = new String(row);
                var scinamepos = completeRow.lastIndexOf("(");
                    var rest = completeRow.substr(0,scinamepos)
                        var sciname = completeRow.substr(scinamepos);
                            var commapos = sciname.lastIndexOf(",");
                sciname = sciname.substr(0,commapos);
                var newrow = rest + '<br><i>' + sciname + '</i>';
        	    return newrow;

  }

  </script>
<script type="text/javascript" src="js/jquery/jquery.cycle.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5355447-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>