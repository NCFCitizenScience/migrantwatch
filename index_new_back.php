<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

<? include("main_includes.php"); ?>

</head>
<body> 
<? include("map-box.php"); ?>
<div class="container" style="width:100%; margin-top:-20px; padding-top:30px; background-color:#fff; margin-left:auto;margin-right:auto">
  <div class="container" style="width:980px;margin-left:auto;margin-right:auto;height:120px;">
    <div style='float:left'>
      <!--<h2 style='margin-left:20px'>MigrantWatch</h2>-->
      <img style="margin-left:20px" src="logo/mwlogo.png" alt="MigrantWatch">
    </div>
    <div style='float:right;'>
      <form name="frm_login" action="process_details.php" method="POST">
	<table>
          <tr><td style="text-align:right" class='small-link'><a  href="#">Register</a>&nbsp;|&nbsp;<a href="#">Forgot Password</a></td></tr>
	  <tr>
	    <td><input class="default-value" type="text" name="email" value="email id" />
	   <input id="password-clear" type="text"  value="password" autocomplete="off"/>
	      <input id="password-password" type="password" name="password" value="" autocomplete="off" />
	    <input style="width:60px" type="submit" value="go"></td>
	  </tr>
          
          <tr>
             <td class="main-links">
               <table><tr><td><a href="#">report sightings</a></td><td>|</td>
               <td><a href="#">edit sightings</a></td><td>|</td>
               <td><a href="#">profile</a></td></tr></table>
	       <!--<table>
		<tr>
	         <td class="main_links"><a href="#">report sightings</a>&nbsp;|&nbsp;</td><td><a href="#">edit sightings</a></td><td><a href="#">your profile</a></td>
                </tr>
                </table>-->
              </td>
          </tr>
	</table>
      </form>
    </div>
  </div>
</div>
<div class="container" style="width:100%;height:200px; margin-top:-20px; background-color:#fff;">
    <div class="infiniteCarousel" style="width:980px;margin-left:auto;margin-right:auto;">
      <div class="wrapper">
        <ul>
	<li><a href="http://www.flickr.com/photos/remysharp/3047035327/" title="Tall Glow"><img src="http://farm4.static.flickr.com/3011/3047035327_ca12fb2397_s.jpg" height="200" width="200" alt="Tall Glow" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047872076/" title="Wet Cab"><img src="http://farm4.static.flickr.com/3184/3047872076_61a511a04b_s.jpg" height="200" width="200" alt="Wet Cab" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047871878/" title="Rockefella"><img src="http://farm4.static.flickr.com/3048/3047871878_84bfacbd35_s.jpg" height="200" width="200" alt="Rockefella" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047034929/" title="Chrysler Reflect"><img src="http://farm4.static.flickr.com/3220/3047034929_97eaf50ea3_s.jpg" height="200" width="200" alt="Chrysler Reflect" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047871624/" title="Chrysler Up"><img src="http://farm4.static.flickr.com/3164/3047871624_2cacca4684_s.jpg" height="200" width="200" alt="Chrysler Up" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047034661/" title="Time Square Awe"><img src="http://farm4.static.flickr.com/3212/3047034661_f96548965e_s.jpg" height="200" width="200" alt="Time Square Awe" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047034531/" title="Wonky Buildings"><img src="http://farm4.static.flickr.com/3022/3047034531_9c74359401_s.jpg" height="200" width="200" alt="Wonky Buildings" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047034451/" title="Leaves of Fall"><img src="http://farm4.static.flickr.com/3199/3047034451_121c93386f_s.jpg" height="200" width="200" alt="Leaves of Fall" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047034531/" title="Wonky Buildings"><img src="http://farm4.static.flickr.com/3022/3047034531_9c74359401_s.jpg" height="200" width="200" alt="Wonky Buildings" /></a></li>
          <li><a href="http://www.flickr.com/photos/remysharp/3047034451/" title="Leaves of Fall"><img src="http://farm4.static.flickr.com/3199/3047034451_121c93386f_s.jpg" height="200" width="200" alt="Leaves of Fall" /></a></li>
          </ul>        
      </div>
    </div>
</div>
<style>
.body {
 font-family:"Lucida sans",Arial, verdana;

}
.small-link , .small-link a {
color: #d95c15;

}
.main-links td{

text-align:right;
padding-left:3px;
padding-bottom:10px;
color:#d95c15;
text-decoration:none;
font-size:15px;
font-weight:bold;

}

.main-links a{
color:#d95c15;
text-decoration:none;
}

.menu-links {

color:#fff;
width:980px;
height:40px;
padding-top:4px; 
background-image: url('images/mainmenugray-yello.png');
background-repeat:repeat-x ;
font-size:15px;
font-weight:bold;

}
.menu-links a{ 
color: #fff;
text-decoration:none;
}

.menu-links a:hover {
color: #ffcb1a;

}



</style>
<div class="container menu-links">
 <div style="float:left; padding-left:10px"><a href="#">join</a>&nbsp;|&nbsp;
 <a href="#">why join</a>&nbsp;|&nbsp;
 <a href="#">species</a>&nbsp;|&nbsp;
 <a href="#">data</a>&nbsp;|&nbsp;
 <a href="#">participants</a>&nbsp;|&nbsp;
 <a href="#">publications</a>&nbsp;|&nbsp;
 <a href="#">resources</a>&nbsp;|&nbsp;
 <a href="#">faq</a>
 </div><div style="float:right"><input type="search" value="Search"><input type="submit" style="width:40px" value="go"></div>
</div>
<!--<div class="container" style="width:100px; height:100px;background-color:">-->
<style type="text/css" media="screen">
.first_image {
background-color:#fffff9;
background-image: url("images/gradientbg.png");
background-repeat: repeat-x;
background-position: 0 100px;

}
</style>
<div class="container first_image" style="width:980px;height:600px">

      <div id="map" style="margin-top:100px;margin-left:8px;"></div>
		<ul id="list" style="margin-top:100px"></ul>
		<div id="message"></div>

</div>

<style type="text/css">
#password-clear {
    display: none;
    color:#666;
}


</style>

 
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
</script>

<style type="text/css" media="screen">

.infiniteCarousel {
  width: 980px;
  position: relative;
}

.infiniteCarousel .wrapper {
  width: 980px; /* .infiniteCarousel width - (.wrapper margin-left + .wrapper margin-right) */
  overflow: auto;
  height: 205px;
  margin: 0 0px;
  position: absolute;
  top: 0;
}

.infiniteCarousel ul a img {
  border: 0px solid #000;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
}

.infiniteCarousel .wrapper ul {
  width: 980px; /* single item * n */
  list-style-image:none;
  list-style-position:outside;
  list-style-type:none;
  margin:0;
  padding:0;
  position: absolute;
  top: 0;
}

.infiniteCarousel ul li {
  display:block;
  float:left;
  padding: 0px;
  height: 200px;
  width: 200px;
}

.infiniteCarousel ul li img {
    -webkit-transition: border-color 400ms;
}

.infiniteCarousel ul:hover li img {
  border-color: #000;
}

.infiniteCarousel ul:hover li:hover img {
  border-color: #333;
}

.infiniteCarousel ul li a img {
  display:block;
}

.infiniteCarousel .arrow { /*
display: block;
  height: 36px;
  width: 37px;
  background: url(images/arrow.png) no-repeat 0 0;
  text-indent: -999px;
  position: absolute;
  top: 37px;
  cursor: pointer;
  outline: 0; */
}

.infiniteCarousel .forward {
  background-position: 0 0;
  right: 0;
}

.infiniteCarousel .back {
  background-position: 0 -72px;
  left: 0;
}

.infiniteCarousel .forward:hover {
  background-position: 0 -36px;
}

.infiniteCarousel .back:hover {
  background-position: 0 -108px;
}




</style>

<script type="text/javascript" charset="utf-8">

(function () {
    $.fn.infiniteCarousel = function () {
        function repeat(str, n) {
            return new Array( n + 1 ).join(str);
        }
        
        return this.each(function () {
            // magic!
            var $wrapper = $('> div', this).css('overflow', 'hidden'),
                $slider = $wrapper.find('> ul').width(9999),
                $items = $slider.find('> li'),
                $single = $items.filter(':first')
                
                singleWidth = $single.outerWidth(),
                visible = Math.ceil($wrapper.innerWidth() / singleWidth),
                currentPage = 1,
                pages = Math.ceil($items.length / visible);
                
            /* TASKS */
            
            // 1. pad the pages with empty element if required
            if ($items.length % visible != 0) {
                // pad
                $slider.append(repeat('<li class="empty" />', visible - ($items.length % visible)));
                $items = $slider.find('> li');
            }
            
            // 2. create the carousel padding on left and right (cloned)
            $items.filter(':first').before($items.slice(-visible).clone().addClass('cloned'));
            $items.filter(':last').after($items.slice(0, visible).clone().addClass('cloned'));
            $items = $slider.find('> li');
            
            // 3. reset scroll
            $wrapper.scrollLeft(singleWidth * visible);
            
            // 4. paging function
            function gotoPage(page) {
                var dir = page < currentPage ? -1 : 1,
                    n = Math.abs(currentPage - page),
                    left = singleWidth * dir * visible * n;
                
                $wrapper.filter(':not(:animated)').animate({
                    scrollLeft : '+=' + left
                },9000, function () {
                    // if page == last page - then reset position
                    if (page > pages) {
                        $wrapper.scrollLeft(singleWidth * visible);
                        page = 1;
                    } else if (page == 0) {
                        page = pages;
                        $wrapper.scrollLeft(singleWidth * visible * pages);
                    }
                    
                    currentPage = page;
                });
      
                


               
            }
            
            // 5. insert the back and forward link
            //$wrapper.after('<a href="#" class="arrow back">&lt;</a><a href="#" class="arrow forward">&gt;</a>');
            
            // 6. bind the back and forward links
            $('a.back', this).click(function () {
                gotoPage(currentPage - 1);
                return false;
            });
            
            $('a.forward', this).click(function () {
                gotoPage(currentPage + 1);
                return false;
            });
            
            $(this).bind('goto', function (event, page) {
                gotoPage(page);
            });
            
            // THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL
            $(this).bind('next', function () {
                gotoPage(currentPage + 1);
            });
        });
    };
})(jQuery);

$(document).ready(function () {
    // THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL
    var autoscrolling = true;
    
    $('.infiniteCarousel').infiniteCarousel().mouseover(function () {
        autoscrolling = false;
    }).mouseout(function () {
        autoscrolling = true;
    });
    
    setInterval(function () {
        if (autoscrolling) {
            $('.infiniteCarousel').trigger('next');
        }
    },10);
});


</script>

</body>
</html>
