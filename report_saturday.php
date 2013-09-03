<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

<? include("main_includes.php"); 
include("db.php"); 
include("functions.php"); 
include("page_includes_js.php");
include("google_maps_api.php");
?>

<style>
#top {
background-image:url('images/pagetopstrip.png');
background-repeat:no-repeat;
background-position:top left;
margin-left:10px;
}

.left {
background-image:url('images/lefttorightfullpage.png');
background-repeat:repeat-y;
margin-left:-1px;

}

#bottom {
background-image:url('images/pagebottomstrip.png');
background-repeat:no-repeat;
//background-position:top left;

}


</style>

<?php


	/******************************BEGIN CODE FOR CONSTRUCTING QUERY********************/
	
				$where_clause = "";
				$sql = "";


		    $sql = "SELECT s.common_name,l.location_name,l.city,st.state,u.user_name,l1.sighting_date,l1.frequency,l1.obs_start,l1.user_friend,";
		    $sql .= "l.latitude,l.longitude";
                    $sql .= " FROM migwatch_l1 l1 INNER JOIN migwatch_users u ON l1.user_id=u.user_id ";
				$sql .= " INNER JOIN migwatch_locations l ON l.location_id=l1.location_id ";
				$sql .= " INNER JOIN migwatch_species s ON s.species_id=l1.species_id ";
				$sql .= " INNER JOIN migwatch_states st ON st.state_id = l.state_id ";
                                
                                
   
				if( ( $_GET['species'] != "All" ) || $_GET['species'] != "" ) {
					$species = $_GET['species'];
				} else if (!empty($_SESSION['reports']['species'])) {
                                      $species = $_SESSION['reports']['species'];
		           
	                        }
				else {
			  	     $species = "All";
            			}
                              
                            
            			$_SESSION['reports']['species'] = $species;
                                
                                 
                                
				if( $species && is_numeric($species) ) {
                                  
               			       $where_clause = " WHERE l1.species_id=". $species;
            			       $tempsql = "select common_name from migwatch_species where species_id=".$species;
					$result = mysql_query($tempsql);
					if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$strSpecies = $row{'common_name'};
                                                }
					}
                                   

             			}
             
				if( ($_GET['location'] != 'All') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
		            	     	$location = $_GET['location'];
	     			} else if (!empty($_SESSION['reports']['location'])) {
                     		       $location = $_SESSION['reports']['location'];
	    			} else {
		          
					$location = 'All';
            			}

			
   if( $location && is_numeric($location) ) {
   
             $where_clause = " WHERE l1.location_id=". $location;
             $tempsql = "select location_name, city,district from migwatch_locations where location_id='$location'";
             $result = mysql_query($tempsql);
             if($result) {
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $strLocation = $row{'location_name'};
   
                    if( $row['city'] != "" ) { 
                        $strLocation .= ", ". $row['city'];
                    }
   
                    if( $row['district'] != "" ) { 
                       $strLocation .= ", " . $row['district'];
                    }
   
   
                    }						   
            }
   }

              			$_SESSION['reports']['location'] = $location;
                               
                                if($location){
                                 if($location != 'All' )
            			   if ($where_clause == ""){
                         	      $where_clause = " WHERE l1.location_id = ".$location;
	      			   } else {
		  		      $where_clause .= " AND l1.location_id=".$location;
            			   } 
                                }


            			if(isset($_GET['season'])) {
		           	    $season = $_GET['season'];
	         		} else if (!empty($_SESSION['reports']['season'])) {
                     		    $season = $_SESSION['reports']['season'];
                	        } else {
	       		            $season = getCurrentSeason();		
                                }

                                $_SESSION['reports']['season'] = $season;
            
         
				$seasonArr = explode('-',$season);
				$seasonStart = $seasonArr[0];
				$seasonEnd = $seasonArr[1];
				

				if ($where_clause == "")
					$where_clause = " WHERE l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";
				else
					$where_clause .= " AND l1.obs_start BETWEEN '$seasonStart-07-01' AND '$seasonEnd-06-30' AND l1.obs_start <> '1999-11-30' AND l1.obs_start <> '0000-00-00' ";

				if ($_GET['dtObs'] != ""){
					list($mm,$dd) = explode('-',$_GET['dtObs']);

					if ($mm >= 7 && $mm <= 12) {
						$year = $seasonStart;
					} else {
						$year = $seasonEnd;
					}

					$dtObs = "$year-$mm-$dd";
					$where_clause .= " AND l1.obs_start < '".$dtObs."' ";
				}


                               if( ($_GET['user'] != 'All') || ($_GET['user'] != '')) {
                                        $user = $_GET['user'];
                                } else if (!empty($_SESSION['reports']['user'])) {
                                       $user = $_SESSION['reports']['user'];
                                } else {

                                        $user = 'All';
                                }

				$_SESSION['reports']['user'] = $user;


				if ($user){
                                   if($user != 'All')
					if ($where_clause == "")
						$where_clause = " WHERE l1.user_id = ".$_GET['user'];
					else
						$where_clause .= " AND l1.user_id = ".$_GET['user'];

					$tempsql = "Select user_name from migwatch_users where user_id=".$_GET['user'];
					$result = mysql_query($tempsql);
					$strUserName = "";
					if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
							$strUserName = $row{'user_name'};
					}
			        }

				if ($_GET['obsfrq'] != "1"){
					if($where_clause == ""){
						switch($_GET['obsfrq']){
							case 2:
								$where_clause = " WHERE l1.frequency = 'Daily' ";
								$strFrequency = "Daily only";
								break;
							case 3:
								$where_clause = " WHERE l1.frequency in ('Weekly','Daily','Twice a week') ";
								$strFrequency = "Weekly or more frequent";
								break;
							case 4:
								$where_clause = " WHERE l1.frequency in ('Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Fortnightly or more frequent";
								break;
							case 5:
								$where_clause = " WHERE l1.frequency in ('Monthly','Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Monthly or more frequent";
								break;
							default:
								$where_clause = "";
								break;
						}
					} else {
						switch($_GET['obsfrq']){
							case 2:
								$where_clause .= " AND l1.frequency = 'Daily' ";
								$strFrequency = "Daily only";
								break;
							case 3:
								$where_clause .= " AND l1.frequency in ('Weekly','Daily','Twice a week') ";
								$strFrequency = "Weekly or more frequent";
								break;
							case 4:
								$where_clause .= " AND l1.frequency in ('Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Fortnightly or more frequent";
								break;
							case 5:
								$where_clause .= " AND l1.frequency in ('Monthly','Fortnightly','Weekly','Daily','Twice a week') ";
								$strFrequency = "Monthly or more frequent";
								break;
							default:
								$where_clause .= "";
								break;
						}
					}

				}
				else
					$strFrequency = "Any";

				

            
              if($_GET['type'] != 'All' || $_GET['type'] != '') {
                    $type =  $_GET['type'];    
	    			} else if (!empty($_SESSION['reports']['type'])) {
                     $type = $_SESSION['reports']['type'];       
	   		 } else {
		          
	       		$type = 'All';	
            }

            $_SESSION['reports']['type'] = $type;
            if($type) {
                if($type != 'All') {
				if ($where_clause == "")
					$where_clause = " WHERE l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
				else
					$where_clause .= " AND l1.valid = 1 AND deleted = '0' AND l1.obs_type = '$type'";
               } 
            } else {

					if ($where_clause == "")
					$where_clause = " WHERE l1.valid = 1 AND deleted = '0'";
				else
					$where_clause .= " AND l1.valid = 1 AND deleted = '0'";

           
            }


				if ($_SESSION['dev_entries'] == 1) {
					// Show all entries : including the ones from developer
				} else {
					$where_clause .= " AND u.user_name != 'Developer'";
				}

				$where_clause .= " AND s.Active = '1'";

				$sql .= $where_clause;

				/*switch($_GET['sorter']){
					case "spc":
						$order_by = " ORDER BY common_name";
						break;
					case "loc":
						$order_by = " ORDER BY location_name ASC";
						break;
					case "rby":
						$order_by = " ORDER BY user_name ASC";
						break;
					case "sdt":
						$order_by = " ORDER BY sighting_date ASC";
						break;
					case "obs":
						$order_by = " ORDER BY obs_start ASC";
						break;
					case "frq":
						$order_by = " ORDER BY frequency ASC";
						break;

					default:
						$order_by = " ORDER BY sighting_date ASC";
						break;
				}
				//$sql .= $order_by;
				*/
				
	/******************************END CODE FOR CONSTRUCTING QUERY********************/


	?>

<script type="text/javascript">

    //<![CDATA[
	// SQ - all this is preliminary stuff needed by Google Maps
	function createMarker(point, user, friend, location, city, state, species, sDate, obsFreq, start, icon) {
  		var marker = new GMarker(point,icon);
  		GEvent.addListener(marker, "click", function() {
    			marker.openInfoWindowHtml("<font face='Arial' size='1'><b>" + species + "</b><br>Reported by <b>" + user + "</b><br>On behalf of <b>" + friend + "</b><br>At " + location + ", " + city + ", " + state + "<br>On " + sDate + "<br>Observation frequency: " + obsFreq + "<br>Start date: " + start + "</font>");
  		});
  		return marker;
	}


    function load() {
      if (GBrowserIsCompatible()) {

	var icon1 = new GIcon();
	icon1.shadow = "" ;
	icon1.image = "mark1.png";
	icon1.iconSize = new GSize(8, 8);
	icon1.shadowSize = new GSize(0, 0);
	icon1.iconAnchor = new GPoint(6, 1);
	icon1.infoWindowAnchor = new GPoint(5, 1);

	var icon2 = new GIcon();
	icon2.shadow = "" ;
	icon2.image = "mark2.png";
	icon2.iconSize = new GSize(8, 8);
	icon2.shadowSize = new GSize(0, 0);
	icon2.iconAnchor = new GPoint(6, 1);
	icon2.infoWindowAnchor = new GPoint(5, 1);

	var icon3 = new GIcon();
	icon3.shadow = "" ;
	icon3.image = "mark3.png";
	icon3.iconSize = new GSize(8, 8);
	icon3.shadowSize = new GSize(0, 0);
	icon3.iconAnchor = new GPoint(6, 1);
	icon3.infoWindowAnchor = new GPoint(5, 1);

	var icon4 = new GIcon();
	icon4.shadow = "" ;
	icon4.image = "mark4.png";
	icon4.iconSize = new GSize(8, 8);
	icon4.shadowSize = new GSize(0, 0);
	icon4.iconAnchor = new GPoint(6, 1);
	icon4.infoWindowAnchor = new GPoint(5, 1);

	var icon5 = new GIcon();
	icon5.shadow = "" ;
	icon5.image = "mark5.png";
	icon5.iconSize = new GSize(8, 8);
	icon5.shadowSize = new GSize(0, 0);
	icon5.iconAnchor = new GPoint(6, 1);
	icon5.infoWindowAnchor = new GPoint(5, 1);

	var icon6 = new GIcon();
	icon6.shadow = "" ;
	icon6.image = "mark6.png";
	icon6.iconSize = new GSize(8, 8);
	icon6.shadowSize = new GSize(0, 0);
	icon6.iconAnchor = new GPoint(6, 1);
	icon6.infoWindowAnchor = new GPoint(5, 1);

	var icon7 = new GIcon();
	icon7.shadow = "" ;
	icon7.image = "mark7.png";
	icon7.iconSize = new GSize(8, 8);
	icon7.shadowSize = new GSize(0, 0);
	icon7.iconAnchor = new GPoint(6, 1);
	icon7.infoWindowAnchor = new GPoint(5, 1);

	var icon8 = new GIcon();
	icon8.shadow = "" ;
	icon8.image = "mark8.png";
	icon8.iconSize = new GSize(8, 8);
	icon8.shadowSize = new GSize(0, 0);
	icon8.iconAnchor = new GPoint(6, 1);
	icon8.infoWindowAnchor = new GPoint(5, 1);

	var icon9 = new GIcon();
	icon9.shadow = "" ;
	icon9.image = "mark9.png";
	icon9.iconSize = new GSize(8, 8);
	icon9.shadowSize = new GSize(0, 0);
	icon9.iconAnchor = new GPoint(6, 1);
	icon9.infoWindowAnchor = new GPoint(5, 1);

	var icon10 = new GIcon();
	icon10.shadow = "" ;
	icon10.image = "mark10.png";
	icon10.iconSize = new GSize(8, 8);
	icon10.shadowSize = new GSize(0, 0);
	icon10.iconAnchor = new GPoint(6, 1);
	icon10.infoWindowAnchor = new GPoint(5, 1);

        var map = new GMap2(document.getElementById("map"));
	map.addControl(new GLargeMapControl());
  	map.addControl(new GMapTypeControl());
        map.setCenter(new GLatLng(20.21,77.86), 4);
	//map.setMapType(G_SATELLITE_MAP);
<?	
	
        $table_sql = $sql;
	$result = mysql_query($sql);
	$i = 1;
	list($startSeason,$endSeason) =  explode('-',$_GET['season']);
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$line['count'] = $i;
		print "var point = new GLatLng(" . $line['latitude'] . "+(Math.random()*0.2)-0.1," . $line['longitude'] . "+(Math.random()*0.2)-0.1);\n";
		if (strtotime($line['sighting_date']) >= strtotime("$startSeason-07-01") && strtotime($line['sighting_date']) <= strtotime("$startSeason-07-31"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon10));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-08-01") && strtotime($line['sighting_date']) <= strtotime("$startSeason-08-15"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon9));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-08-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-08-31"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon8));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-08-31") && strtotime($line['sighting_date']) <= strtotime("$startSeason-09-15"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon7));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-09-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-09-30"))			//
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon6));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-09-30") && strtotime($line['sighting_date']) <= strtotime("$startSeason-10-15"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon5));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-10-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-10-31"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon4));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-10-31") && strtotime($line['sighting_date']) <= strtotime("$startSeason-11-15"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon3));\n\n";
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-11-15") && strtotime($line['sighting_date']) <= strtotime("$startSeason-11-30"))
		print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon2));\n\n"; 
		elseif (strtotime($line['sighting_date']) > strtotime("$startSeason-11-30"))
			print "map.addOverlay(createMarker(point,\"" . addslashes($line['user_name']) . "\", \"" . addslashes($line['user_friend']) . "\", \"" . addslashes($line['location_name']) . "\", \"" . addslashes($line['city']) . "\", \"" . addslashes($line['state']) . "\", \"" . addslashes($line['common_name']) . "\", \"" . addslashes($line['sighting_date']) . "\", \"" . addslashes($line['frequency']) . "\", \"" . addslashes($line['obs_start']) . "\",icon1));\n\n";
			} 
	?>

	}
   }

    //]]>
    </script>






    <script language="javascript">

        function formatItem(row) {
            var completeRow;
            completeRow = new String(row);
            var scinamepos = completeRow.lastIndexOf("(");
            var rest = substr(completeRow,0,scinamepos);
            var sciname = substr(completeRow,scinamepos);
            var commapos = sciname.lastIndexOf(",");
            sciname = substr(sciname,0,commapos);
            var newrow = rest + ' <i>' + sciname + '</i>';
            return newrow;
        }

        function isEmpty(s)
        {   return ((s == null) || (s.length == 0))
        }

        // BOI, followed by one or more whitespace characters, followed by EOI.
        var reWhitespace = /^\s+$/
        // BOI, followed by one or more characters, followed by @,
        // followed by one or more characters, followed by .,
        // followed by one or more characters, followed by EOI.
        var reEmail = /^.+\@.+\..+$/
        var defaultEmptyOK = false
        // Returns true if string s is empty or
        // whitespace characters only.

        function isWhitespace (s)

        {   // Is s empty?
            return (isEmpty(s) || reWhitespace.test(s));
        }
        function formReset(){
            document.rpt_l1.target = "";
            document.rpt_l1.action = "rpt_level1.php";
        }

        function showReport(){
            document.rpt_l1.target = "blank";
            document.rpt_l1.action = "rpt_level1_excel.php";
            document.rpt_l1.submit();
            document.rpt_l1.target = "";
        }


        function validate(){
            if(isWhitespace(document.frm_chpass.opwd.value))
            {
                alert('Please enter old password');
                return false;
            }


            if(document.frm_chpass.pwd.value != document.frm_chpass.pwd1.value){
                alert("The new passwords dont match. Please re-enter.");
                return false;
            }

            if(document.frm_chpass.pwd.value.length < 6){
                alert("The new password should be atleast 6 characters long.");
                return false;
            }

            return true;

        }

        function substr( f_string, f_start, f_length ) {
            // http://kevin.vanzonneveld.net
            // +     original by: Martijn Wieringa
            // *         example 1: substr("abcdef", 0, -1);
            // *         returns 1: "abcde"

            if(f_start < 0) {
                f_start += f_string.length;
            }

            if(f_length == undefined) {
                f_length = f_string.length;
            } else if(f_length < 0){
                f_length += f_string.length;
            } else {
                f_length += f_start;
            }

            if(f_length < f_start) {
                f_length = f_start;
            }

            return f_string.slice(f_start,f_length);
        }

        

        
	    
    </script>

</head> 
<body onload="load()" onunload="GUnload()">
<div class="container" id="top" style="width:990px;padding-top:30px; background-color:#fff; margin-left:auto;margin-right:auto;">
   
<div class="left">
  <div class="container" style="margin-left:auto;margin-right:auto;height:110px;margin-top:0px;padding-bottom:10px">
   

    <div style='float:left'>
      
      <img style="margin-left:0" src="logo/mwlogo.png" alt="MigrantWatch">
    </div>
    <div style='float:right;margin-top:26px'>
      <form name="frm_login" action="process_details.php" method="POST">
		<table>
   
	  <tr>
        <td class="small-link" style="margin:0;padding:0;font-size:10px;">
           <table style="margin:0;padding:0;width:390px;"><tr>
           <td style="text-align:right;width:150px"><a  href="#">signup!</a></td>
           <td style="padding-left:35px"><a href="#">forgot?</a></td>
           <td style="text-align:right;padding-right:33px">
                  <a id="rememberme"  href="#">remember me</a>
                  
           </td>
           </table>
         </td></tr>
             <tr>
            
	    <td style="padding:0;margin:0;"><input class="default-value login-box" type="text" name="email" value="email id" />
	   <input id="password-clear" class="login-box" type="text"  value="password" autocomplete="off"/>
	      <input class="login-box" id="password-password" type="password" name="password" value="" autocomplete="off" />
	    <input style="width:30px;border:solid 1px #666" type="submit" value="go"></td>
	  </tr>
      </table>
      <table style="margin-top:-15px;margin-left:-8px; width:390px; text-align:left" class="main-links"> 
          <td style=""><a href="#">report sightings</a></td>
          <td style=''>|</td>
          <td style=""><a href="#">edit sightings</a></td>
          <td style=''>|</td>
          <td style=""><a href="#">profile</a></td>
	  
             
          </tr>
		</table>
      </form>
    </div>
  </div>

<div class="container" style="width:950px;height:166px; margin-top:-20px; background-color:#fff;">
 
   
</div>
<style>

.small-link , .small-link a {
color: #d95c15;


}
.main-links td{

//padding-left:3px;

padding-bottom:0px;
color:#c0c0c0;
text-decoration:none;
font-size:14px;


}

.main-links a{
color:#d95c15;
text-decoration:none;
font-weight:bold;
}

.menu-links {

color:#c0c0c0;
width:950px;
height:40px;
padding-top:4px; 
background-image: url('images/mainmenugray-yello.png');
background-repeat:repeat-x;
font-size:15px;


}
.menu-links a{ 
color: #fff;
text-decoration:none;
font-weight:bold;
}

.menu-links a:hover{ 
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
 </div>
<div style="float:right"><input type="search" style="border:solid 1px #666;" value="search"><input type="submit" style="margin-right:8px;border:solid 1px #666;width:30px" value="go"></div>
</div>

<style type="text/css" media="screen">
.first_image {
background-color:#fffff9;
background-image: url("images/gradientbg.png");
background-repeat: repeat-x;
background-position: 0 100px;

}

hr {

background:#d95c15;
//border: solid 0.2px;
//margin-top:100px;
margin-bottom:0px;

}

.map-show-link{
background-color: #ffcb1a;
padding:5px;
margin-left:8px;
width:170px;
margin-top:0px;
text-align:center;
margin-bottom:10px;

}

.map-show-link a{
color: #333;
text-decoration:none;
font-size:14px;

}



.filter td {


padding-bottom:0px;
margin-bottom:0px;
}

</style>


<div class="container"  style="width:950px;margin-left:auto;margin-right:auto">

<div class="container" style="width:950px;padding-bottom:20px">

<style>
.filter td { margin-top:0; margin-bottom:0; padding-top:0;padding-bottom:0 }

</style>

<FORM name="reports" action="report_new.php" method="GET">
<table class="filter" style="width:950px;margin-left:auto;margin-right:auto;">
           
         
		<tr>
         <td style=''>SEASON</td>
         <td>SIGHTING TYPE</td>
	 <td>SPECIES</td>
         <td>PARTICIPANT</td>
         <td></td>
       </tr>
       <tr>
         <td style="width:140px;">
                    <select style="width:85%;font-size:12px;" name='season'  OnChange="this.form.submit();">
                    <?php
                    /**
                     * Use the current month and year to find out the last season to be
                     * displayed in the drop down. (Season : 1st July - 31st Aug)
                     */
                    $currentMonth = date('m');
                    $currentYear  = date('Y');

                    /**
                     * If the current month is greater than June i.e July and onwords only then display
                     * the current year in the season
                     */
                    if ($currentMonth > 6) {
                        $endSeason = $currentYear;
                    } else {
                        $endSeason = $currentYear - 1;
                    }

                    // The sighting started in 2007-2008 so start from this season
                    for ($i = 2007;$i <= $endSeason; $i++) {
                        $fromTo = "$i-".($i+1);
                        echo '<option value="' . $fromTo  . '"';
                        if (empty($_GET)) {
                            echo ' selected>';
                        } else {
                            echo ($_GET['season'] == $fromTo) ? ' selected>' : '>';
                        }
                        echo $fromTo;
                        echo '</option>';
                    }
                    ?>
           
                    </select>&nbsp;&nbsp;
		    <? $current_season = getCurrentSeason();
                   
                       if( $_GET['season'] != '' && $_GET['season']  != $current_season  ) {		       
		    ?>
                     <a href="#" onClick="get_remove('season');">X</a>
                    
                       <? } ?>

                   </td> <td  style="width:140px;">
                    

                    <select name="type" style="font-size:12px;width:85%" onchange="this.form.submit();">
                            <option value="All">All</option>
                            <option value="first"<?php if($type == 'first') print("selected"); ?>>First Sighting</option>
                            <option value="general"<?php if($type == 'general') print("selected"); ?>>General Sighting</option>
                            <option value="last"<?php if($type == 'last') print("selected"); ?>>Last Sighting</option>
                     </select>&nbsp;&nbsp;
                      <? if( $_GET['type'] != "All" ) {  ?>
                           <a href="#" onclick="get_remove('type');">X</a>
                      <? } ?>
		</td>
	  
                <td style="width:330px;">
                    
                  
                    <input type='text' id='species' size="25" style="width:90%;border:solid 1px #666" value="<? echo $strSpecies; ?>">
		               <input type='hidden' id='species_hidden' name='species' value="<? echo $species; ?>">
                              &nbsp;
                             <? if( is_numeric($_GET['species'] )) {  ?>
                                  <a href="#" onclick="get_remove('species');">X</a>
                             <? } ?>
		</td>



		<td style="width:300px;">
           
                    <select name=user style="width:91%" OnChange="this.form.submit();">
                    <option value='All'>-- Select --</option>
                    <?php

                    //  The current season..
                    $today = getdate();
                    $currentSeason = ($today['mon'] > 6) ? $today['year'] : $today['year'] - 1;
                    $season = (isset($_POST['season'])) ? substr($_POST['season'], 0, 4) : $currentSeason;
                    $seasonEnd = (int)$season + 1;

                    $sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
                    $sql .= "l1.user_id=u.user_id WHERE l1.valid=1 ";
                    //$sql .= "AND l1.obs_start BETWEEN '$season-07-01' AND '$seasonEnd-06-30'  ";
                    $sql .= "ORDER BY u.user_name";
                    $result = mysql_query($sql);
                    if(mysql_num_rows($result) <= 0) {
                        $sql = "SELECT DISTINCT u.user_name, u.user_id from migwatch_users u INNER JOIN migwatch_l1 l1 ON ";
                        $sql .= "l1.user_id=u.user_id WHERE l1.valid=1 ORDER BY u.user_name";
                        $result = mysql_query($sql);
                    }
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                        print "<option value=".$row{'user_id'};
                        if (($_GET['user'] != "") && ($_GET['user'] == $row{'user_id'}))
                            print " selected ";
                        print ">".$row{'user_name'}."</option>";
                    }
                    ?>
                    </select>
                </div>&nbsp;
                <? if( is_numeric( $_GET['user'] )) {  ?>
                     <a href="#" onclick="get_remove('user');">X</a>
                <? } ?>
		</td><td></td>
		</tr>
		<tr>
			<td colspan="2">STATE</td>
         <td colspan="2">LOCATION</td>
       </tr>
        <tr>
          
          <td colspan="2" style="">
             <select style="width:93%;"  id="state" name=state OnChange="this.form.submit();" >

                        <option value="all">All the States</option>
                    <?php

                            $result = mysql_query("SELECT state_id, state FROM migwatch_states order by state");
                            if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                                    if($row['state'] != 'Not In India') {
                                        print "<option value=".$row{'state_id'};
                                        if (($_GET['state'] != "") && ($_GET['state'] == $row{'state_id'}))
                                            print " selected ";
                                        print ">".$row{'state'}."</option>";
					                          } else {
                                        $other_id = $row['state_id'];
                                        $other = $row['state'];
                                    }
                                }
                                print("<option value=".$other_id);
							if($other_id == $state_id)
                                    print " selected ";
				    	                  print ">".$other."</option>\n";
                            }

                    ?></select>&nbsp;&nbsp;
                   <? if( is_numeric($_GET['state'] )) {  ?>
                     <a href="#" onclick="get_remove('state');">X</a>
                   <? } ?>

          </td>

			<td colspan="2" style="width:600px;">
               
               <input type='text' id='location'  size='25' value="<? echo $strLocation; ?>" style="width:95%;border:solid 1px #666">
               <input type='hidden' id='location_hidden' name='location' value="<? echo $location; ?>">
               <? if( is_numeric($_GET['location'] )) {  ?>
                  &nbsp;<a href="#" onclick="get_remove('location');">X</a>
               <? } ?>
              
         </td>
         <td><a href="/report_new.php">Remove&nbsp;All</a></td>
        </tr>
	</table>

</div>

<div class='container' style=";width:950px;margin-left:auto;margin-right:auto" id='tab-set'>
   <ul class='tabs'>
   
           <li><a href='#text1' class='selected'>map view</a></li>
           <li><a href='#text2'>tabular view</a></li>
 
   </ul>
   <div id='text1'>
              <div id="map" style="width:950px;height:500px"></div>
  </div>

  <div id='text2'>
     <table id="table1" class="tablesorter" style="width:900px;" cellpadding="3">
                <thead>
                        <tr>
                                <th>&nbsp;No</th>
                                <th>Common Name</th>           
                                <th>Location</th>
                                <th>Date</th>                        
                                <th>Count</th>
                                <th>Reported by</th>
                                <th>On behalf of</th>
                        </tr>
                </thead>
                <tbody>

     <?
	$i=1;
         
         $result = mysql_query($table_sql);

	list($startSeason,$endSeason) =  explode('-',$_GET['season']);
	   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	print "<tr>";
                        print "<td>$i</td>";
	            print "<td>".$row{'common_name'}."</td>";
                        print "<td>".$row{'location_name'}.", ".$row{'city'}.", ".$row{'state'}."</td>";
                        print "<td>".date("d-m-Y",strtotime($row{'sighting_date'}))."</td>";
                        ($row['number'] > 0 ) ? print "<td>".$row{'number'}."</td>" : print "<td> -- </td>";
                        print "<td>".$row{'user_name'}."</td>";
                        print "<td>".$row{'user_friend'}."</td>";
                        print "</tr>";
                        $i++;


}
?>
      </tbody>
     </table>
       <div id="pager" class="column span-7" style="" >
                        <form name="" action="" method="post">
                                <table >
                                <tr>
                                        <td><img src='pager/icons/first.png' class='first'/></td>
                                        <td><img src='pager/icons/prev.png' class='prev'/></td>
                                        <td><input type='text' size='8' class='pagedisplay'/></td>
                                        <td><img src='pager/icons/next.png' class='next'/></td>
                                        <td><img src='pager/icons/last.png' class='last'/></td>
                                        <td>
                                                <select class='pagesize'>
                                                        <option selected='selected'  value='10'>10</option>
                                                        <option value='20'>20</option>
                                                        <option value='30'>30</option>
                                                        <option  value='40'>40</option>
                                                </select>
                                        </td>
                                </tr>
                                </table>
                        </form>
                </div>

       </div>

</div>
</div>
</div>
<div class="container" id="bottom" style="width:990px;padding-top:30px; background-color:#fff; margin-left:auto;margin-right:auto;">

</div>
<script type='text/javascript'>

			$().ready(function() {

            $("#species").autocomplete("autocomplete_reports.php", {
                width: 260,
                selectFirst: false,
                matchSubset :0,
                mustMatch: true,
                            
           });

         $("#species").result(function(event , data, formatted) {
                if (data)
						
                  document.getElementById('species_hidden').value = data[1];
                            this.form.submit();
						
          });


         $('#location').autocomplete("auto_miglocations.php", {
                width: 800,
                selectFirst: false,
                matchSubset :0,
                mustMatch: true,
                  //formatItem:formatItem
                 
          });

         $("#location").result(function(event , data, formatted) {
                if (data)
                  document.getElementById('location_hidden').value = data[1];
                            this.form.submit();
                          
         });
});

</script>

<style type="text/css">
#password-clear {
    display: none;
    color:#666;
}

.login-box {

border: solid 1px #666;
margin-right:0px;
margin-left:-4px;
padding-left:0;
padding-right:0;
width:180px;

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



<script>
$(document).ready(function() {

	$('#map').show();
   $('#list').show();
   
    $('#map-show-hide').click(function() {   
      $('#map').toggle();
      $('#list').toggle();
    });

   
    //$('#rememberme').toggle();

     $("#rememberme").click(function () {
       $("#rememberme").html('remembered');
    });

});

</script>
<? include("tab_include.php"); ?>



</div>
</body>
</html>

<script type="text/javascript">
function get_remove(parameter) {

<? if($_GET['type']){
   $remove_type = $_GET['type'];
   }
  
   if($_GET['species']){
      $remove_species = $_GET['species'];
   }

   if($_GET['user']){
   $remove_user = $_GET['user'];
   }
   
   if($_GET['state']){
      $remove_state = $_GET['state'];
   }

   if($_GET['location']){
   $remove_location = $_GET['location'];
    }

    if($_GET['season']){
   $remove_season = $_GET['season'];
    }

?>
var remove_season = '<? echo $remove_season; ?>';
var remove_type = '<? echo $remove_type; ?>';
var remove_species = '<? echo $remove_species; ?>';
var remove_user = '<? echo $remove_user; ?>';
var remove_state = '<? echo $remove_state; ?>';
var remove_location = '<? echo $remove_location; ?>';

  if ( parameter == 'season') {
    
     remove_season = '<? echo getCurrentSeason(); ?>';
     alert(remove_season);
   }


   if ( parameter == 'type') {
      remove_type = 'All';
     }

    if (parameter == 'species') {
       remove_species = 'All'; 
     }

     if (parameter == 'user') {
     remove_user = 'All'; 
       
     
     }

 

    if (parameter == 'location') {
      remove_location = 'All';
       }

   if ( parameter == 'state') {
    remove_state = 'All'; 
  } 

  var url = "report_new.php?season=" + remove_season + "&type=" + remove_type + "&species=" + remove_species + "&user=" + remove_user + "&state=" + remove_state + "&location=" + remove_location;
 
  
  window.location = url;
}
</script>


