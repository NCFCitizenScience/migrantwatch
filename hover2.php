<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Coda Bubble Example</title>
    <style type="text/css" media="screen">
    <!--
        * {
            margin: 0;
            padding: 0;
        }
        
        body {
            padding: 10px;
        }
        
        h1 {
            margin: 14px 0;
            font-family: 'Trebuchet MS', Helvetica;
        }
        
        p {
            margin: 14px 0;
            font-family: 'Trebuchet MS', Helvetica;
        }
        
        .bubbleInfo {
            position: relative;
            top: 150px;
            left: 100px;
            width: 500px;
        }
        .trigger {
            position: absolute;
        }
     
        /* Bubble pop-up */

        .popup {
                position: absolute;
                display: none;
                z-index: 50;
                //border-collapse: collapse;
		border: solid 1px;
        }

        .popup td.corner {
                height: 15px;
                width: 19px;
        }
/*
        .popup td#topleft { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-1.png); }
        .popup td.top { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-2.png); }
        .popup td#topright { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-3.png); }
        .popup td.left { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-4.png); }
        .popup td.right { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-5.png); }
        .popup td#bottomleft { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-6.png); }
        .popup td.bottom { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-7.png); text-align: center;}
        .popup td.bottom img { display: block; margin: 0 auto; }
        .popup td#bottomright { background-image: url(http://static.jqueryfordesigners.com/demo/images/coda/bubble-8.png); }
*/
        .popup table.popup-contents {
                font-size: 12px;
                line-height: 1.2em;
                background-color: #fff;
                color: #666;
                font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", sans-serif;
                }

        table.popup-contents th {
                text-align: right;
                text-transform: lowercase;
                }

        table.popup-contents td {
                text-align: left;
                }

        tr#release-notes th {
                text-align: left;
                text-indent: -9999px;
                background: url(http://jqueryfordesigners.com/demo/images/coda/starburst.gif) no-repeat top right;
                height: 17px;
                }

        tr#release-notes td a {
                color: #333;
        }
        
    -->
    </style>

    <script src="jquery.js" type="text/javascript"></script>

    <script type="text/javascript">
    <!--

    $(function () {
        $('.bubbleInfo').each(function () {
            var distance = 10;
            var time = 250;
            var hideDelay = 500;

            var hideDelayTimer = null;

            var beingShown = false;
            var shown = false;
            //var trigger = $('.trigger');
            var info = $('.popup', this).css('opacity', 0);
	    

	    //alert(trigger);
            $('.trigger').mouseover(function () {
		var userid = $(this).attr('id');
		$.ajax({  
          	type: 'GET',  
          	url: 'getuserinfo.php',  
          	data: 'user='+ userid, 
          	success: function(html)  {  
			
			$('.popup-contents').html(html);
		}
		
	        });
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                if (beingShown || shown) {
                    // don't trigger the animation again
                    return;
                } else {
                    // reset position of info box
                    beingShown = true;
		     var pos = $(this).offset();
		     //alert(pos);
                    info.css({
                        top: -20,
                        left: -50,
                        display: 'block'
                    }).animate({
                        top: '-=' + distance + 'px',
                        opacity: 1
                    }, time, 'swing', function() {
                        beingShown = false;
                        shown = true;
                    });
                }

                return false;
            }).mouseout(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                hideDelayTimer = setTimeout(function () {
                    hideDelayTimer = null;
                    info.animate({
                        top: '-=' + distance + 'px',
                        opacity: 0
                    }, time, 'swing', function () {
                        shown = false;
                        info.css('display', 'none');
                    });

                }, hideDelay);

                return false;
            });
        });
    });
    
    //-->
    </script>
</head>
<body id="page">
    <h1>Coda Bubble Example</h1>
    <p>This shows a demonstration of the 'puff' popup bubble effect as seen over the download link on the <a href="http://www.panic.com/coda/">Coda web site</a>.</p>
    <p>Roll the mouse over and out from the download image to see the popup fade in and out of view, while gently gliding upwards.</p>
    <p><a href="http://jqueryfordesigners.com/coda-popup-bubbles">Read the article, and see the screencast this demonstration relates to</a></p>
    <p><small>Note that the transparency doesn't work in IE - this is the same on the Coda web site where the images were sourced.</small></p>

    
    <div class="bubbleInfo">
         <div>
            <a class="trigger" href="http://jqueryfordesigners.com/demo/images/coda/nav-download.png" id="675" />hello</a>
        	<br><br>
            <a class="trigger" href="http://jqueryfordesigners.com/demo/images/coda/nav-download.png" id="676" />hello1323</a>

<br><br> <a class="trigger" href="http://jqueryfordesigners.com/demo/images/coda/nav-download.png" id="676" />hello1323</a>
        </div>
	
        <table id="dpop" class="popup">
                <tbody><tr>
                        <td id="topleft" class="corner"></td>
                        <td class="top"></td>
                        <td id="topright" class="corner"></td>
                </tr>

                <tr>
                        <td class="left"></td>
                        <td><table class="popup-contents">

                        </table>

                        </td>
                        <td class="right"></td>    
                </tr>

                <tr>
                        <td class="corner" id="bottomleft"></td>
                        <td class="bottom"><img width="30" height="29" alt="popup tail" src="http://static.jqueryfordesigners.com/demo/images/coda/bubble-tail2.png"/></td>
                        <td id="bottomright" class="corner"></td>
                </tr>
        </tbody></table>
</div>
</body>
</html>
