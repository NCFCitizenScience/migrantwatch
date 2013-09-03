<?

  include("db.php");



?>
<!DOCTYPE html> 
<html> 
<head> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<title>Status of the golden jackal in India</title> 
<link href="styles_res_new.css" rel="stylesheet" type="text/css" charset="utf-8" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src ="http://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("jquery","1.4.2");
</script>

<style>
             </style>
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script>
	 var india = new google.maps.LatLng(22.71,82.15);
	 var markers = [];
         var m_big = [];
         var iterator = 0;
         var mc = 0;
         var t1=  null;
         var t2= null;
         var t3= null;

function HomeControl(controlDiv, map) {
 
  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';
 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '2px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to set the map to India';
  controlDiv.appendChild(controlUI);
 
  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = '<b>Show entire India</b>';
  controlUI.appendChild(controlText);
 
  
  google.maps.event.addDomListener(controlUI, 'click', function() {
    map.setCenter(india);
    map.setZoom(5);
  });
 
}

        function clearOverlays() {
              if (markers) {
                 for (i in markers) {
                     markers[i].setMap(null);
                     
                 }
                 markers.length = 0;
              }
              markers=[];
              $('#do_nos').html('0');
              $('#oo_nos').html('0');
        }

        function incr_my() {
             var my_nos = $('#my_nos').html();
             var my_nos = parseInt(my_nos) + 1;
             $('#my_nos').html(my_nos);             
        }

        function incr_do() {
             var do_nos = $('#do_nos').html();
             var do_nos = parseInt(do_nos) + 1;
             $('#do_nos').html(do_nos);

        }

        function incr_oo() {
             var oo_nos = $('#oo_nos').html();
             var oo_nos = parseInt(oo_nos) + 1;
             $('#oo_nos').html(oo_nos);

         }





	$(document).ready(function(){
	
                var zoomin;
                var win_width = $(window).width();
                
                if(win_width < 400) {
                     zoomin = 4;
                } else {
                     zoomin = 5;
                }

                var d = new Date();
		
               
               $('.mainlinks li').removeClass("here");
		$('.mainlinks li:nth-child(2)').addClass("here");

                var latlng = new google.maps.LatLng(22.71,82.15);
                var myOptions = {
                    zoom: 5,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                var map = new google.maps.Map(document.getElementById("map"), myOptions);
		var homeControlDiv = document.createElement('DIV');
                var homeControl = new HomeControl(homeControlDiv, map);
 
                homeControlDiv.index = 1;
                map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);

                 var iconBlue = "https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.3|0|020ce5|11";
                 var iconRed = "https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.6|0|e50202|11";

                 var iterator= 0;

                 var i2 =0;
                 var count = $('#actual_count').val();
                 var t_1;
                 var timer_is_on=0;

                 var tick_fast = 1;
                 var tick_slow = 1;

                 function start_it(tick) {
                  $.getJSON("getallentries.php",
                   function(data){
                      var no_markers = data.rows.length;
                      addmarkermaster(data,tick);
                   }
                  );
                  }

                 function addmarkermaster(data,tick){
                   var total_data = data.rows.length;
                   if(count < total_data) {
                     t_1 =setTimeout(function(){
                                addMarker(data,tick);
                            }, tick);
                    } else {
                      $('#play_pause').hide();
                      $('#play_again').show();
                    }
                    
                  }

                  
            
                   function pauseAnimation(){                           
                            clearTimeout(t_1);
                            timer_is_on = 0;
                   }

                 
                 markers = [];
                 iterator = 0;
                 function addMarker(datata,tick) {
                         
                         var data_lat = datata.rows[count].data.latitude;
                         var data_lng = datata.rows[count].data.longitude;
			 var data_info_type = '1';                     
                     
                            var icon_put = iconBlue;
                            var mcat = '2'; 
                            var customdata = {
                               category: mcat
                            }
                     

                         if(data_lat != 'undefined') {
                   	 
                         var marker_pos =  new google.maps.LatLng(data_lat,data_lng);
                         var marker_i = new google.maps.Marker({
                             position: marker_pos,
                             map: map,
                             draggable: false,
                             icon: icon_put,
                             data: customdata
                         });
                      
                          markers.push(marker_i);
                          
                          }

                          if(data_info_type == '1') {
                              incr_oo();
                          } else {
                              incr_do();
                          }
                          
                          iterator++;
                          
                          count = parseInt(count) + 1;
                          $('#actual_count').val(count);
                          addmarkermaster(datata,tick);
                                
                 }

                 $('#toggle_others').click(function() {
                     toggleMarkers('category',2);
                 });

                 function toggleMarkers(attr,val) {
                 if (markers){
                    for (i in markers) {
                        if(markers[i].data[attr] == val){
                            var visibility = (markers[i].getVisible() == true) ? false : true;
                              markers[i].setVisible(visibility);
                        }
                    }
                 }
                 }

                 $('#start_again').click(function() {
                     pauseAnimation();
                     if (markers) {
                        for (i in markers) {
                            markers[i].setMap(null);
                        }
                         
                        markers.length = 0;
                    }
                    markers=[];
                     

                    $('#do_nos').html('0');
                    $('#oo_nos').html('0');
                    $('#my_nos').html('0');
                    $('#actual_count').val('0');
                    count = 0;
                    $('#play_again').hide();
                    $('#play_pause').show();
                    $('#play_pause').val("Pause");
                    start_it(tick_slow);
                 });


                 $('#fast_fwd').click(function() {
                    pauseAnimation();
                    start_it(tick_fast);
                 });

                  $('#play_pause').click(function() {
                     if ($(this).val() == "Play") {
                       $(this).val("Pause");
                        start_it(tick_slow);
                     }  else {
                        $(this).val("Play");
                        pauseAnimation();
                     }
                 });

                 $('#play_again').click(function() {
                        $('#start_again').trigger('click');

                 });

                 $('#play_again').hide();
                 start_it(tick_slow);

                $('.showhide').click(function(){
                        $('.help>li:not(:first-child)').toggle("slow");
                        $('.hshow').toggle();
                       $('.hhide').toggle();
                });

                $("#webtitle").fitText(1.5);


		$('.register').hide();
	    $('#reglink').show();
	    $('#loginlink').hide();
	    $('#reset_cancel').hide();


	    $('#reglink').click(function() { 
		$('#loginlink').show();
		$('#reglink').hide();
		$('.login').show();
	        $('.register').show();
		$('#login_btn').val("Register");
                $('#logintitle').html('REGISTER TO PARTICIPATE');
                $('#login_type').val('2');
                $('#response').html('All fields mandatory');
		$('#forgotpass').hide();
                $('#rem').hide();
                reset_login_fields();
	    });

            $('.loginsec').show();

	    $('#loginlink').click(function() { 
		$('#reglink').show();
		$('#loginlink').hide();
		$('.login').show();
	        $('.register').hide();
		$('#login_btn').val("Login");
                $('#logintitle').html('LOGIN');
                $('#login_type').val('1');
                $('#response').html('');
                $('#forgotpass').show();
                $('#rem').show();
                reset_login_fields();
	    });

	    $('#forgotpass').click(function() { 
		$('.register').hide();
		$('.login').hide();
		$('#reglink').hide();
		$('#loginlink').hide();
		$('#forgotpass').hide();
		$('#login_btn').val("Reset password");
		$('#reset_cancel').show();
                $('#logintitle').html('FORGOT PASSWORD?');
                $('#login_type').val('3');
                $('#response').html('');
                $('#rem').hide();
                reset_login_fields();
	    });

	    $('#reset_cancel').click(function() {
		$('#loginlink').trigger('click');
		$('#forgotpass').show();
	    	$('#reset_cancel').hide();
	    });
                
                <? if($_GET['confirm']) { ?>
                   $('#response').html("Thank you. Your email id has now been confirmed. Please login below to participate in the survey.");
                <? } ?>


                <? if(!$check_smart)  { ?> 
                   $("#inline").fancybox(); 
		   $("#inline2").fancybox(); 
                <? } ?>
        
                <? if($email_get) { ?>
                   $("#user_settings").fancybox();
                 <? } ?>
                 


                get_session();

                $('#session_update').click(function() {
                      $.ajax({
                         url: "logout.php",
                         type: "POST",
                         cache: false,
                         success: function (html) {
                             $('#session_update').html("Not logged in");
                             get_session();
			     get_prev_updates();
                             reset_login_fields();
                         }
                      });
                     
                });

		$('#chemail').click(function() {
                   var new_email = $('#user_email1').val();
                   var pwd  = $('#current_pwd').val();
                   var data ="email=" + new_email + "&pwd=" + pwd;
                   $.ajax({
                         url: "chemail.php",
                         type: "POST",
                         data: data,
                         cache: false,
                         success: function (html) {
                             $('#s_response').html(html);    
                             reset_login_fields();
                         }
                  });
                });

                $('#chpass').click(function() {
                     var old_pwd = $('#old_pwd').val();
                     var new_pwd1 = $('#new_pwd1').val();
                     var new_pwd2 = $('#new_pwd2').val();
                     var data ="old_pwd=" + old_pwd + "&new_pwd1=" + new_pwd1 + "&new_pwd2=" + new_pwd2;
                     $.ajax({
                         url: "chpass.php",
                         type: "POST",
                         data: data,
                         cache: false,
                         success: function (html) {
                             $('#s_response').html(html);
                             reset_login_fields();
                         }
                     });
                });


	        
            });

            function set_username(user) {
                     var html_user = user + " (<a href='#' onclick='logout(); return false;'>Log out</a>)" ; 
                     $('.username').html(user);

            }

	    function get_session(){
                      $.get('gs.php', function(data) {
                            if(data) {
                                  $('#session_update').html(data + " (Log out)");                                  
                                   $('#session_update').show();
                                  $('#inline').hide();
                                  $('#c1').html("Logged in as " + data);
                                  $('.loginsec').hide();
                                  $('#user_settings').show();
                            } else {
                                  $('#user_settings').hide();
                                  $('#session_update').hide();
                                  $('#inline').show();
                                  $('#c1').html("");
                                  $('.lmain input[type=text], .lmain input[type=password]').val('');
                                  $('.loginsec').show();
                            }
                      });
             }
	 

            function enterLogin() {
                  var user_email = $('#user_email').val();
                  var user_pwd1 = $('#user_pwd1').val();
                  var user_pwd2 = $('#user_pwd2').val();
                  var user_type = $('input:radio[name=user_type]:checked').val();
                  
                  var login_type = $('#login_type').val();
                  var remember = $('#remember_me').attr("checked");
                  if(remember) { remember = '1'; } else { remember = '0'; }
                  
                  if(!user_type) { user_type = '4'; }

                  if(login_type == '2') {
                         var data = "email=" + user_email + "&user_type=" + user_type + "&user_pwd1=" + user_pwd1 + "&user_pwd2=" + user_pwd2 + "&ltype=2";
                  } else if(login_type == '1')  {
                         var data = "email=" + user_email + "&user_pwd1=" + user_pwd1 + "&ltype=1" + "&remember=" + remember;
                  } else if(login_type == '3') {
                         var data = "email=" + user_email + "&ltype=3";
                  }
        
                  $.ajax({
                         url: "submit_reg.php",
                         type: "POST",
                         data: data,
                         cache: false,
                         success: function (html) {
                               $('#response').html(html);
                               reset_login_fields();
                         }
                  });                     

        }

</script>

</head> 
<body> 
        <div class='container'>

          <? include("main_bar.php"); ?>
  
           <ul class='map_layout'>  
              <li class='map_block'>
              
                 <input type="submit" id="toggle_others" class="btnsmall" value="Show only my records">
                <input type="submit" value="Pause" id="play_pause" class="btnsmall">
                <input type="submit" value="Play again" id="play_again" class="btnsmall">
                &nbsp;<input type="submit" value="<<" id="start_again" class="btnsmall">
                &nbsp;<input type="submit" value=">>" id="fast_fwd" class="btnsmall">
                <input type="hidden" id="actual_count" value="0">
                <ul class='legend above_map'>
                     <? if($user_id) { ?><li><img src='http://labs.google.com/ridefinder/images/mm_20_red.png'>&nbsp;<span class='ltext'>Your jackal locations</span></li>
		     <li><img src='http://labs.google.com/ridefinder/images/mm_20_blue.png'>&nbsp;<span class='ltext'>All other jackal locations</span></li>

		     <? } else { ?>
                     <li><img src='http://labs.google.com/ridefinder/images/mm_20_blue.png'>&nbsp;<span class='ltext'>All Jackal locations</span></li> 
		     <? } ?>
                </ul>
		<div id="map" style="width:100%;height:750px;"></div> 

               </li>
               <li class='sidebar'>
                   <ul class='stats'>
                        <li><div class='stitle'>People registered</div><? echo get_reg_nos(); ?></li>

                        <? if($user_id) { ?> 
                           <li class='myrecords'><div class='stitle'><a href='viewloc.php' title='View your records'>Your records</a></div>
                                    <a style='font-size:100px;font-weight:bold;' href='viewloc.php'><span id='my_nos'>0<span></a></li>
                        <? } ?>

                        <li><div class='stitle'>No of reports</div><span id='oo_nos'>0<span></li>

                        <li><div class='stitle'>No of locations</div><span id='do_nos'>0<span></li>

                        <li><div class='stitle'>No. of birds</div><? echo get_state_nos(); ?></li>

                        <!--<li><div class='stitle'>Most data from single contributor</div><? echo get_reg_nos(); ?></li>-->
                   </ul>
               </li>
           </ul>           
           <div class='footer'><? include("footer.php"); ?></div>
        </div>

     	

</body>
</html>

