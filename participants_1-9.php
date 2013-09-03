<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>MigrantWatch -- Participants</title>
  <base href="http://www.ncbs.res.in/citsci/migrantwatch/"/>
</head>
<?php include("banner.html"); ?>
  <style> <!-- A{text-decoration:none} --> </style>
<body style="width: 770px;">
<br />
<?php
/**
 * Database Connection Creation
 */
include('db.php');

/**
 * Use joins to get all matching records from the states table
 */
$sql = "SELECT u.user_name, u.user_id, u.city, st.state, st.state_id,u.country from migwatch_users u " .
	   "INNER JOIN migwatch_states st ON st.state_id=u.state_id WHERE u.active = '1' " .
	   "AND u.user_name != 'admin'" .
	   "AND u.user_name != 'Developer'" .
	   "AND u.user_name != 'MigrantWatch Admin' " .
	   "AND u.user_name != 'Guest'" .
	   "ORDER BY st.state,u.user_name";
$result = mysql_query($sql);
$error = mysql_error();
if (!empty($error)) {
	print("<br /><font color='red'><b>Ok, so we so not have the correct database details yet.</b><br>" .
		  "MySql Says : <br /> $error </font>");
	exit;
}
// The total found participants
$total_participants = mysql_num_rows($result);

  if ($total_participants > 0) {
  	while($details = mysql_fetch_assoc($result)) {
		// If there is no city make it Undefined

		if ($details['state_id'] != '36') {

			/**
			 * If we do not have city but have district then usem that
			 */
			if (empty($details['city']) && !empty($details['district'])) {
				$details['city'] = ucfirst($details['district']);
			}

			$users[$total_participants][$details['state']][] =
			array(
				'user_name' => $details['user_name'],
				'city'      => ucfirst($details['city']),
			);
		} else {
			$outsiders[$details['state']][] =
			array(
				'user_name' => $details['user_name'],
				'city'      => ucfirst($details['city']),
				'district'  => ucfirst($details['district']),
				'country'   => ucfirst($details['country'])
			);
		}
  	}

	if (!empty($outsiders) && !empty($users)) {
		$users[$total_participants][key($outsiders)] = $outsiders[key($outsiders)];
	}

  } else {
	echo 'Error Querying DataBase. Please try Later.';
  }
?>
<table style="width: 720px; text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
  <tbody>
	<tr>
    <td style="text-align: justify; vertical-align: top;">
      <div style="text-align: center;">
	      <div style="text-align: right; font-family: Helvetica,Arial,sans-serif;">
	      	<small>List of participants <span style="font-weight: bold;">&nbsp;| &nbsp;</span>
	      	<a style="font-weight: bold;" href="participantmap.php">Participant map</a>
	      	<br>
	     	</small>
	      </div>
	      <font face="Arial" size="2"><br>
			<?php echo $total_participants ?> registrants from 29 States/UTs on <?php echo date('d M Y') ?><br>
	      </font>
	      <hr style="width: 100%; height: 2px;"><font face="Arial" size="2"><br>
	      </font>
      </div>
      <small style="font-family: Helvetica,Arial,sans-serif;"><span style="font-weight: bold;"></span></small>
      <div style="text-align: justify;">
      <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
        <tbody>
          <tr>
            <td style="width: 50%; vertical-align: top; font-family: Helvetica,Arial,sans-serif; font-weight: bold;"><small>Individuals</small></td>
           <td style="width: 50%; vertical-align: top; font-family: Helvetica,Arial,sans-serif; font-weight: bold; text-align: center;"><small>Groups</small></td>
          </tr>
         <tr>
            <td style="vertical-align: top; font-family: Helvetica,Arial,sans-serif;">
		      <?php
		      $i = 1;
		      if ($total_participants > 0) {
		      	echo "<small>";
		      	foreach($users[$total_participants] as $state => $data) {
		      		if ($i != 1) {
		      			echo "<br />";
		      		}
					echo "<span style='text-decoration: underline;'>$state</span><br>";
		      		foreach ($data as $details) {

						echo "$details[user_name]";
						$details['city'] = trim($details['city']);

						if (!empty($details['city'])) {
							echo ", $details[city]";
						}

						if (isset($details['country']) &&
							!empty($details['country']) &&
							strtolower($details['country']) != 'india') {
							echo ", $details[country]";
						}
						echo "<br />";
		      		}
		      		$i++;
		      	}
		      	echo "</small>";
		      } else {
		      	echo '--';
		      }
	          ?>
            </td>
            <td style="vertical-align: top; font-family: Helvetica,Arial,sans-serif; text-align: center;">
            	<small>
            		<a href="http://groups.yahoo.com/group/keralabirder/"> </a><a href="http://delhibird.net/"> <img style="border: 0px solid ; width: 200px; height: 44px;" alt="Delhibird" src="particilogos/delhibird_white_sm.jpg"></a>
            		<br><br><br>
					<a href="http://groups.yahoo.com/group/keralabirder/"><img style="border: 0px solid ; width: 200px; height: 119px;" alt="Keralabirder" src="particilogos/Keralabirder_logo_sm.jpg"></a><br>
					&nbsp;
            	</small>
				<p>
					<small>&nbsp;</small>
				</p>
				<small>
            	</small>
				<p>
					<small>
						<br>
						<a href="http://www.ncf-india.org/"> <img style="border: 0px solid ; width: 200px; height: 74px;" alt="NCF" src="particilogos/NCF_logo_sm.gif"></a><br>
						&nbsp;
					</small>
				</p>
	            <small>
    	        </small>
				<p>
				<small>&nbsp;</small></p>
				<small>
            	</small>
	            <p>
	            	<small>
		            	<br>
			            <a href="http://groups.yahoo.com/group/bngbirds/">
	        		    <img style="border: 0px solid ; width: 200px; height: 127px;" alt="bngbirds" src="particilogos/bngbirds_logo_sm.jpg"></a><br>
						&nbsp;</small></p>
		            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://groups.yahoo.com/group/birdsofbombay/">
            <img style="border: 0px solid ; width: 123px; height: 25px;" alt="birdsofbombay" src="particilogos/birdsofbombay_yahoo.png"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://in.groups.yahoo.com/group/Tamilbirds/">
            <img style="border: 0px solid ; width: 123px; height: 100px;" alt="tamilbirds" src="particilogos/Tamilbirds_logo_new.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://groups.yahoo.com/group/birdsofNEIndia/">
            <img style="border: 0px solid ; width: 123px; height: 23px;" alt="birdsofNEIndia" src="particilogos/birdsofNEIndia_yahoo.png"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://groups.google.com/group/bengalbird">
            <img style="border: 0px solid ; width: 72px; height: 72px;" alt="bengalbird" src="particilogos/bengalbirdlogo.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://groups.yahoo.com/group/birdsofpune/">
            <img style="border: 0px solid ; width: 200px; height: 44px;" alt="birdsofpune" src="particilogos/birdsofpune_yahoo.png"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://nagpurbirds.org/"> <img style="border: 0px solid ; width: 125px; height: 21px;" alt="Nagpur birds" src="particilogos/NagpurBirds_web.png"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://birdsofgujarat.net/main/index.php">
            <img style="border: 0px solid ; width: 200px; height: 44px;" alt="Birds of Gujarat" src="particilogos/Birds_of_Gujarat_logo_sm.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="mailto:mail.wrcs@gmail.com"> <img style="border: 0px solid ; width: 200px; height: 125px;" alt="WRCS" src="particilogos/WRCS_logo.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.rishivalley.org/"> <img style="border: 0px solid ; width: 153px; height: 130px;" alt="Rishi Valley" src="particilogos/RVLogo_sm.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <img style="border: 0px solid ; width: 119px; height: 150px;" alt="NCS Nashik" src="particilogos/NCSNashik_logo_sm.jpg"><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.kalpavriksh.org/"> <img style="border: 0px solid ; width: 200px; height: 46px;" alt="Kalpavriksh" src="particilogos/Kalpavriksh_logo_sm.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.asiaticlion.org/"> <img style="border: 0px solid ; width: 137px; height: 130px;" alt="WCT" src="particilogos/WCT_logo_sm.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <img alt="MBC" src="particilogos/MBC_sm.jpg" style="border: 1px solid ; padding: 1px 4px; width: 120px; height: 30px;"><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="mailto:bsap_online@yahoogroups.com"> <img style="border: 0px solid ; width: 162px; height: 72px;" alt="BSAP" src="particilogos/BSAP_logo_sm1.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.gnape.org/"> <img style="border: 0px solid ; width: 165px; height: 90px;" alt="GNAPE" src="particilogos/GNAPE_logo_sm.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.bnhs.org/"> <img style="border: 0px solid ; width: 200px; height: 93px;" alt="BNHS" src="particilogos/BNHS_logo.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.samrakshan.org/"> <img style="border: 0px solid ; width: 108px; height: 52px;" alt="Samrakshan" src="particilogos/samrakshan_logo.gif"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.kashmirnetwork.com/birds/">
            <img style="border: 0px solid ; width: 150px; height: 150px;" alt="Birds of Kashmir" src="particilogos/BirdsofKashmir.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.srishtinature.org/"> <img style="border: 0px solid ; width: 88px; height: 198px;" alt="Shrishti" src="particilogos/Shrishti_NHS_logo.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.ceeindia.org/"> <img style="border: 0px solid ; width: 200px; height: 67px;" alt="CEE" src="particilogos/CEE_logo.GIF"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://www.npcil.nic.in/"> <img style="border: 0px solid ; width: 100px; height: 145px;" alt="NPCIL" src="particilogos/NPCIL_logo.GIF"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <img style="border: 0px solid ; width: 120px; height: 124px;" alt="RSNH" src="particilogos/RSNH-Logo_sm.jpg"><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="http://groups.yahoo.com/group/hopethane/">
            <img style="border: 0px solid ; width: 120px; height: 93px;" alt="HOPE" src="particilogos/HOPE.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <a href="mailto:banabikshan@yahoo.co.in"> <img style="border: 0px solid ; width: 120px; height: 125px;" alt="Banabikshan" src="particilogos/Banabikshan_sm.jpg"></a><br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small><br>






            <img style="border: 0px solid ; width: 130px; height: 130px;" alt="Rays of Hope" src="particilogos/RaysOfHope_sm.jpg"><br>






            <br>






&nbsp;</small></p>





            <small>
            </small>





            <p><small><img style="border: 0px solid ; width: 110px; height: 136px;" alt="SOS" src="particilogos/SOS_logo_sm.jpg"></small></p>





            <small>
            </small>





            <p><small>&nbsp;</small></p>





            <small>
            </small>





            <p><small> &nbsp;<img style="width: 220px; height: 66px;" alt="Wild Orissa" src="particilogos/WildOrissaLogo_sm.jpg"></small></p>





            <small>
            </small></td>






          </tr>











        </tbody>





      </table>






      <br>






      </div>






      </td>






    </tr>











  </tbody>
</table>






<small style="font-family: Helvetica,Arial,sans-serif;"><br>






</small>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>MigrantWatch</title>


  <style>
<!-- A{text-decoration:none}
-->
  </style>
</head>


<body style="width: 720px;">

<map name="MapBottom">
<area shape="rect" coords="69, 27, 208, 85" href="http://www.ncbs.res.in/">
<area shape="rect" coords="606, 5, 705, 106" href="http://www.indianbirds.in">
</map>

<img banner="" src="bottombanner.gif" style="border-style: none;" usemap="#MapBottom"><br>

</body>
</html>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-5355447-1");
pageTracker._trackPageview();
</script>  </body>

</html>