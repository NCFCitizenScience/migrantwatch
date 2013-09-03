<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
 <? 

include("db.php");      include("functions.php"); include("google_maps_api.php");


?>
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

<!-- Framework CSS -->
        <link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href=".blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
        
        <!-- Import fancy-type plugin for the sample page. -->

        <link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
          <script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">


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

				if ( $_GET['species'] != "All" || $_GET['species'] != "" ){
					$species = $_GET['species'];
				} else if (!empty($_SESSION['reports']['species'])) {
                                      $species = $_SESSION['reports']['species'];
		           
	                        }
				else {
			  	     $species = "All";
            			}
                              
                                echo "Species".$species;
            			$_SESSION['reports']['species'] = $species;
                        
				if($species) {
                                   if($species != 'All' )
               			       $where_clause = " WHERE l1.species_id=". $species;
            			       $tempsql = "Select common_name from migwatch_species where species_id=".$species;
					$result = mysql_query($tempsql);
					if($result){
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
							$strSpecies = $row{'common_name'};
					}

             			}
             
				if( ($_GET['location'] != 'All') || ($_GET['location'] != '') || ($_GET['location'] != $locationHintText )) {
		            	     	$location = $_GET['location'];
	     			} else if (!empty($_SESSION['reports']['location'])) {
                     		       $location = $_SESSION['reports']['location'];
	    			} else {
		          
					$location = 'All';
            			}

              			$_SESSION['reports']['location'] = $state;
                               
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

//echo $sql;
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
	map.setMapType(G_SATELLITE_MAP);
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


<link href="../CalendarControl.css"  rel="stylesheet" type="text/css">

<style>
    TD{font-size:12px;text-align:left;}

    .title{font-weight:bold;font-size:x-small;}

    SELECT{font-size:x-small;}
</style>



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
    <script type="text/javascript">

    </script>

</head> <body onload="load()" onunload="GUnload()">



<div class="container">
    <FORM name=reports action=index.php method=GET>
    <table style="width:100%" cellpadding=2 cellspacing=0>
    <tr>
            <td width=100% style="color:red;text-align:right;font-weight:bold;">
                <?php
                 /*   if (!isset($_SESSION['userid'])) {
                        die("Session not available. Fatal error.");
                    } */
                ?>
            </td>
        </tr>
     </table>
     <div class="span-4 box" style="width:950px;">
	  <table>
           
         
		<tr>
         <td>SEASON</td>
			<td>SPECIES</td>
         <td>PARTICIPANT</td>
         <td>SIGHTING TYPE</td>
       </tr>
       <tr>
                    <td><select name='season'  OnChange="this.form.submit();">
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
           
                    </select></td>

						 <!-- <? if(isset($_REQUEST['season'])) { ?>
                        <td><? echo $_REQUEST['season']; ?>&nbsp;<a href='#'>remove</a></td>
		              <? }  ?>-->
	  
                <td>
                    
                  
                    <input type='text' id='species' size='25' style="border:solid 1px #666">
		               <input type='hidden' id='species_hidden' name='species' value="<? echo $species; ?>">
		</td>
<?
/*

if($species_rem = $_GET['species']) {

    if($_GET['type']) {  $remove = '&type='  . $_GET['obs_type']; }
    if($_GET['state']) { $remove .= '&state=' . $_GET['state']; }
    if($_GET['season']) { $remove .= '&season=' . $_GET['season']; }

	$sql = "select common_name,scientific_name,alternative_english_names from migwatch_species where species_id='$species_rem'";
   $result = mysql_query($sql);
   $data = mysql_fetch_assoc($result);
   echo $data["common_name"] . " (<i> " . $data["scientific_name"] . " </i>) <a href='index.php?$remove'>Remove</a>";
}

*/
?>





		<td>
           
                    <select name=user OnChange="this.form.submit();">
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
                </div>
		</td>
		<td>
                    

                    <select name="type" style="width:100px;" onchange="this.form.submit();">
                            <option value="All">All</option>
                            <option value="first"<?php if($type == 'first') print("selected"); ?>>First Sighting</option>
                            <option value="general"<?php if($type == 'general') print("selected"); ?>>General Sighting</option>
                            <option value="last"<?php if($type == 'last') print("selected"); ?>>Last Sighting</option>
                     </select>
                </div>
		</td></tr>

        <tr>
			<td colspan="4">
                <div>LOCATION<br>
               <input type='text' id='location'  size='25' style="width:800px;border:solid 1px #666">
               <input type='hidden' id='location_hidden' name='location' value="<? echo $location; ?>">
                 </div>	
         </td></tr>
	</table>
	  
    </form>
     </div>
     <div class="span-8" style="width:950px" id="tab-set">
        <ul class='tabs'>
   
           <li><a href='#text1' class='selected'>Map View</a></li>
           <li><a href='#text2'>Tabular View</a></li>
 
   </ul>
   
   <div id='text1'>
	         <div id="map" style="width:950px;height:500px"></div>
    </div>
    <div id='text2'>
		 <table id="table1" style="width:900px;" cellpadding=3 cellspacing=0>
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
                        print "</tr>\n";
                        $i++;


			}
		?>
      </tbody>
		</table>
       <div id="pager" class="column span-7" style="" >
                        <form name="" action="" method="post">
                                <table >
                                <tr>
                                        <td><img src='../pager/icons/first.png' class='first'/></td>
                                        <td><img src='../pager/icons/prev.png' class='prev'/></td>
                                        <td><input type='text' size='8' class='pagedisplay'/></td>
                                        <td><img src='../pager/icons/next.png' class='next'/></td>
                                        <td><img src='../pager/icons/last.png' class='last'/></td>
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
<script type="text/javascript">
$("ul.tabs li.label").hide(); 
$("#tab-set > div").hide(); 
$("#tab-set > div").eq(0).show(); 
  $("ul.tabs a").click( 
  function() { 
  $("ul.tabs a.selected").removeClass('selected'); 
  $("#tab-set > div").hide();
  $(""+$(this).attr("href")).fadeIn('slow'); 
  $(this).addClass('selected'); 
  return false; 
  }
  );
  
  </script>
<script type="text/javascript">
$().ready(function() {
            $('#species').autocomplete("../autocomplete.php", {
                width: 260,
                selectFirst: false,
                matchSubset :0,
                mustMatch: true,
		  //formatItem:formatItem
		 
		  });

	    $("#species").result(function(event , data, formatted) {
                if (data)
                  document.getElementById('species_hidden').value = data[1];
		            this.form.submit();
		            //document.location.href = 'index.php?<? echo $url; ?>&species='+ str;
                 
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
		            //document.location.href = 'index.php?<? echo $url; ?>&species='+ str;
                 
	      });

			

	
 });
</script>
     <!---------Level1 Report - FORM END----------------------->
<!-----------------------------------------------------
Written by: Suresh V [verditer at gmail.com]
For: National Center for Biological Sciences, Bangalore
http://ncbs.res.in

In Love of Birds.

------------------------------------------------------->

</div>
