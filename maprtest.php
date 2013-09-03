<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch</title>

<!-- Framework CSS -->
        <link rel="stylesheet" href="blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
        
        <!-- Import fancy-type plugin for the sample page. -->
        <link rel="stylesheet" href="blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
          <script type="text/javascript" src="jquery-1.3.2.min.js"></script>
 <style>
        //table.tablesorter tr.even td { background:#E5ECF9;}


</style>
<script src="alerts/jquery.ui.draggable.js" type="text/javascript"></script>
<script src="alerts/jquery.alerts.js" type="text/javascript"></script>
<link href="alerts/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" /> 
<link href="jqmodal_include.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="dialog.css" type="text/css" />
<script type="text/javascript" src="jquery-gradient/jquery.dimensions.js" ></script>
<script type="text/javascript" src="gradientz.js"></script>
<script type="text/javascript" src="jquery.jdialog.js"></script>
<script type="text/javascript" src="pager/jquery.tablesorter.js"></script>
<script type="text/javascript" src="pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="pager/chili-1.8b.js"></script>
<script type="text/javascript" src="pager/docs.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<link href="pager/style.css"  rel="stylesheet" type="text/css" />

<script type="text/javascript">
        $(function() {
    

                  
                $("#table1")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager"), positionFixed: false});

                  $("#table2")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager2"), positionFixed: false});
                     
        });

        
</script>
<link href="thickbox.css"  rel="stylesheet" type="text/css" />
<script src="thickbox.js" language="javascript"></script>

<script src="jquery.autocomplete.js" language="javascript"></script>
<script src="jquery.bgiframe.min.js" language="javascript"></script>
<script src="jquery.autocomplete.js" language="javascript"></script>
<link href="jquery.autocomplete.css"  rel="stylesheet" type="text/css" />
<script src="jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>

<link href="jqModal.css"  rel="stylesheet" type="text/css" />
<script src="jqModal.js" type="text/javascript"></script>
<script src="jquery.autogrow.js" type="text/javascript"></script>
<script src="jquery.corner.js" type="text/javascript"></script>
<link href="CalendarControl.css"  rel="stylesheet" type="text/css">
<script src="CalendarControl.js" language="javascript"></script>
<script src="jquery.form.js" language="javascript"></script>
<script src="date.js" language="javascript"></script>
<script src="alerts/jquery.ui.draggable.js" type="text/javascript"></script>
<script src="alerts/jquery.alerts.js" type="text/javascript"></script>
<link href="alerts/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAz2GdKUVPA5WYpd4RF7AQxTWp0bGQXViaMnWtK231_SaDUi5iRQAAX3W7fYMprLUwEDnlzJcSr5Lcw" type="text/javascript"></script>


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
var point = new GLatLng(13.370832+(Math.random()*0.2)-0.1,77.68188+(Math.random()*0.2)-0.1);
var point = new GLatLng(13.370832+(Math.random()*0.2)-0.1,77.68188+(Math.random()*0.2)-0.1);
var point = new GLatLng(13.370832+(Math.random()*0.2)-0.1,77.68188+(Math.random()*0.2)-0.1);
var point = new GLatLng(13.370832+(Math.random()*0.2)-0.1,77.68188+(Math.random()*0.2)-0.1);
var point = new GLatLng(13.370832+(Math.random()*0.2)-0.1,77.68188+(Math.random()*0.2)-0.1);
var point = new GLatLng(13.370832+(Math.random()*0.2)-0.1,77.68188+(Math.random()*0.2)-0.1);

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
background-position: bottom left;

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

.filter {
border: solid 1px #777777;
padding:10px;
margin-top:10px;
}

a {

color:#d95c15;
text-decoration:none;
}

a:hover {

color:#d95c15;
text-decoration:underline;
}

.filter input { font-size:11px;border: solid 1px #777777;padding: 3px }

.filter select { border: solid 1px #777777; font-size: 11px; padding: 3px }
.filter td {

font-weight:bold;
padding-bottom:0px;
margin-bottom:0px; 
}

</style>
<script>
$(document).ready(function() {

    $(".filter").corner();
    $(".first_image").corner();
});

</script>


<div class="container first_image"  style="padding-bottom:10px;width:950px;margin-left:auto;margin-right:auto">

<div class="container" style="width:950px;padding-bottom:20px">

<style>
//.filter td { margin-bottom:0; padding-top:0;padding-bottom:0 }

</style>

<FORM name="reports" action="report_new.php" method="GET">
<table class="filter" style="width:950px;margin-left:auto;margin-right:auto;">
           
         
		<tr>
         <td style=''>season</td>
         <td>sighting type</td>
	 <td>species</td>
         <td>participant</td>
         <td></td>
       </tr>
       <tr>
         <td style="width:140px;">
                    <select style="width:85%;font-size:12px;" name='season'>
                    <option value="2007-2008" selected>2007-2008</option><option value="2008-2009" selected>2008-2009</option><option value="2009-2010" selected>2009-2010</option>           
                    </select>&nbsp;&nbsp;
		    
                   </td> <td  style="width:140px;">
                    

                    <select name="type" style="width:85%">
                            <option value="All">All</option>
                            <option value="first">First Sighting</option>
                            <option value="general">General Sighting</option>
                            <option value="last">Last Sighting</option>
                     </select>&nbsp;&nbsp;
                                                 <a href="#" onclick="get_remove('type');">X</a>
                      		</td>
	  
                <td style="width:330px;">
                    
                  
                    <input type='text' id='species' size="25" style="width:90%;border:solid 1px #666" value="">
		               <input type='hidden' id='species_hidden' name='species' value="">
                              &nbsp;
                             		</td>



		<td style="width:300px;">
           
                    <select name=user style="width:91%">
                    <option value='All'>-- Select --</option>
                    <option value=551>A Shivaprakash</option><option value=373>Abheek Ghosh</option><option value=845>Abhijit Menon-Sen</option><option value=772>Abhijit Narvekar</option><option value=553>Adesh Shivkar</option><option value=640>Aditya C Panda</option><option value=0>admin</option><option value=556>AI Siddiqui</option><option value=314>Akarsha BM</option><option value=809>Akshay</option><option value=922>Alakananda. B</option><option value=359>Alok Dixit</option><option value=631>Alwan Sadagopan</option><option value=519>Amit Ray</option><option value=794>Amlan Dutta</option><option value=932>Amol Patwardhan</option><option value=939>Anand Kalinadhabhatla</option><option value=425>Anand Krishnan</option><option value=837>Anand Kumar Bhatt</option><option value=316>Anandraj KC</option><option value=815>Aniket Bhatt</option><option value=675>Anush Shetty</option><option value=351>Aparajita Datta</option><option value=572>Arjun Surendra</option><option value=762>Arpit Deomurari</option><option value=632>Arpit Jain</option><option value=431>Arun Kumar</option><option value=432>Arun Kumar</option><option value=658>Arunava Das</option><option value=773>Ashish G B</option><option value=834>Ashish Mantri</option><option value=489>Ashish Parmar</option><option value=713>Ashutosh Barua</option><option value=818>Ashwin Pomal</option><option value=592>Ashwin Viswanathan</option><option value=583>Ashwini Vaidya</option><option value=401>Atul Joshi</option><option value=711>Avin Ramaiya</option><option value=937>Babu A</option><option value=840>BalaSundaraRaman L</option><option value=402>Bhargav Joshi</option><option value=869>Biswapriya Rahut</option><option value=534>C Sashikumar</option><option value=384>C Tom Hash</option><option value=749>C. Lalchhandama</option><option value=345>Capt Praveen Chopra</option><option value=850>Cdr. Kanwar B Singh</option><option value=337>Centre for Learning</option><option value=376>Chandanjyoti Gohain</option><option value=539>Chitra Shanker</option><option value=589>CK Vishnudas</option><option value=1016>Col Ashwin Baindur</option><option value=339>Dayani Chakravarthy</option><option value=460>Debesh Mitra</option><option value=906>Deepak Soni</option><option value=399>Denny John</option><option value=756>Developer</option><option value=858>Dhaivat </option><option value=894>Dharmaraj N Patil</option><option value=491>Dharmaraj N Patil</option><option value=372>Dipankar Ghose</option><option value=981>Dipu Karuthedathu</option><option value=413>Divya Karnad</option><option value=466>Divya Mudappa</option><option value=940>Dr SV Narasimhan</option><option value=326>Dr. P. Balakrishnan</option><option value=754>Dr. T Badri Narayanan</option><option value=1009>Dr.DauLal Bohara</option><option value=801>E.S.Praveen</option><option value=393>EK Jayadevan</option><option value=938>Faruk A Mhetar</option><option value=504>Fasalu Rahiman</option><option value=891>Fauzia Arief</option><option value=500>Fionna Prins</option><option value=335>Garima Bhatia</option><option value=970>Garima Singhal</option><option value=1022>Gaurav Sharma</option><option value=908>Geetha Jaikumar</option><option value=647>Ginu George</option><option value=405>Girish Jathar</option><option value=910>Gnanaskandan KesavaBharathi</option><option value=785>Golap Gogoi</option><option value=924>Gurunath Desai</option><option value=566>Hari Sridhar</option><option value=334>Harish Bhat</option><option value=486>Harsh Pandit S</option><option value=383>Harsha J</option><option value=561>Hiren Soni</option><option value=998>Issac Sam</option><option value=827>Jaidev Dhadhal</option><option value=594>James Williams</option><option value=709>Janaki Turaga</option><option value=913>Jayesh Patel</option><option value=795>Jency</option><option value=369>JM Garg</option><option value=472>K Muthunarayanan</option><option value=912>Kailash Prasad</option><option value=821>Kalpana Malani</option><option value=600>Kamala Mukunda</option><option value=887>Kamalakar Krishnaji Jaripatke</option><option value=606>Kanwar B Singh (MBC)</option><option value=412>Karidas KV</option><option value=728>Karthick B</option><option value=408>Kashmira Kakati</option><option value=697>Kaustubh Pandharipande</option><option value=481>KD Nimavat</option><option value=947>Kedar Champhekar</option><option value=575>Ketan Tatu</option><option value=450>Ketki Marthak</option><option value=420>Killivalavan R</option><option value=941>Kirti Kumar Mandalaywala</option><option value=788>Kottayam Nature Society</option><option value=547>KS Sheshadri</option><option value=378>Kulbhushan Goyal</option><option value=573>Kulbhushansingh Suryawanshi</option><option value=371>Kumar Ghorpade</option><option value=492>Kunal Manohar Patil</option><option value=463>Kushal Mookherjee</option><option value=507>KV Rajeev</option><option value=555>L Shyamal</option><option value=426>Lalitha Krishnan</option><option value=611>Latchoumanan Muthu Andavan</option><option value=439>Luxmi SJ</option><option value=531>M Sanjeevareddy</option><option value=343>Madhu Chandra</option><option value=735>Mahiban Ross</option><option value=844>Mandar Khadilkar</option><option value=445>Mangesh Deherkar</option><option value=374>Manoj Ghosh</option><option value=666>Manoj N Kulkarni.Solapur</option><option value=419>Mayuresh Khatavkar</option><option value=943>Mayuresh Satish Gangal</option><option value=424>MB Krishna</option><option value=423>Meghna Krishnadas</option><option value=670>MigrantWatch Admin</option><option value=499>Mike Prince</option><option value=663>Miriam Paul Sreeram</option><option value=315>MO Anand </option><option value=533>Mohana Sarker</option><option value=1024>Mohina Macker</option><option value=362>Mousumi Dutta</option><option value=429>MS Kulkarni</option><option value=490>Mushtak Pasha</option><option value=568>Mythili Sriram</option><option value=418>Nachiket Kelkar</option><option value=483>Nameer Ommer</option><option value=798>Nanda Ramesh</option><option value=760>Narbir Kahlon</option><option value=846>Naren Rajani</option><option value=476>Naresh Nanda</option><option value=610>Navjit Singh</option><option value=909>Neeraj Deepak Gade</option><option value=1019>Neeraj Mahar</option><option value=951>Nikhil Patwardhan</option><option value=467>Nilarun Mukherjee</option><option value=331>Nithila Baskaran</option><option value=482>Nitin S</option><option value=677>Oinam Sunanda Devi</option><option value=379>P Gracious</option><option value=448>P Manjunath</option><option value=639>P. Jeganathan</option><option value=851>P. Kartik Kumar</option><option value=377>Paresh Gosavi</option><option value=784>Parth Parikh</option><option value=357>Pawan Dhall</option><option value=741>Pooshan Gajanan Mahajani</option><option value=823>Pradnya Shenoy</option><option value=996>Prasanna Parab</option><option value=780>Prashant Bhagat</option><option value=325>Prashanth M Badarinath</option><option value=497>Prashanth NS</option><option value=930>Pratap Sevak</option><option value=929>Pratik</option><option value=389>Pratik P Jain</option><option value=498>Praveen J</option><option value=461>Praveen P Mohandas</option><option value=854>Pravin S</option><option value=630>Preetham</option><option value=612>Pritesh Patel</option><option value=576>PS Thakker</option><option value=691>R Dhanapal</option><option value=395>R Jayapal</option><option value=541>R Sharada</option><option value=416>Ragupathy Kannan</option><option value=692>Rajarajan</option><option value=770>Rajashree Khalap</option><option value=703>Rajasree Vasudevan</option><option value=1026>Rajeev G.C</option><option value=452>Rajeev Mathew</option><option value=526>Rajesh Sachdev</option><option value=414>Raju Kasambe</option><option value=720>Raju Sankaran</option><option value=920>Ramit Singal</option><option value=515>Ranjini J</option><option value=860>Ranjith Valsalan</option><option value=516>Ratnakar J</option><option value=342>Ravi Chand</option><option value=607>Ravi Vaidyanathan</option><option value=505>Renju K Raj</option><option value=522>Retheesh KR</option><option value=332>Richa Bhake</option><option value=893>Rima Dhillon</option><option value=340>Rohan Chakravarty</option><option value=855>Rohit Gangwal</option><option value=441>Roy Machia</option><option value=656>Runa Khamaru</option><option value=355>RV Devkar</option><option value=478>S Prasanth Narayanan</option><option value=569>S Subramanya</option><option value=923>S. Revathi</option><option value=480>Sachin Nayak</option><option value=942>Sachin Shurpali</option><option value=863>SachinandanDutta</option><option value=634>Sagar B Shah</option><option value=330>Sahas Barve</option><option value=597>Saji Yohannan</option><option value=956>Samad Kottur</option><option value=456>Samir Mehta</option><option value=398>Samiran Jha</option><option value=350>Sandeep Das</option><option value=945>Sandilya Theuerkauf</option><option value=577>Sandilya Theuerkauf</option><option value=838>Sandip H Ramanuj</option><option value=825>Sangeeta Dhanuka</option><option value=407>Sangeetha Kadur</option><option value=380>Sanjay Gubbi</option><option value=475>Sanjeev Nalavade</option><option value=353>Sarav A Desai</option><option value=453>Sarita Mehra</option><option value=508>Sarita Rana</option><option value=712>Satish Kumar Jain</option><option value=454>Satya Prakash Mehra</option><option value=471>Senthil Murugan</option><option value=982>Shailaja</option><option value=317>Sharada Annamaraju</option><option value=593>Sheetal Vyas</option><option value=464>Shibi Moses</option><option value=409>Shiran Kalappurakkal</option><option value=1033>Shiva</option><option value=552>Shivashankar</option><option value=364>Siva Ganapathy</option><option value=903>Sivaji Anguru</option><option value=1017>SK Srinivas</option><option value=421>Smitha K Komath</option><option value=588>Sneha Vijayakumar</option><option value=926>Somashekhar Hiremath</option><option value=564>Sree Kumar</option><option value=563>Sreekar R</option><option value=565>Sreekumar B</option><option value=901>Subhash Gogi</option><option value=443>Subhayan Mandal</option><option value=338>Subir Chakraborty</option><option value=511>Sudheendra Rao NR</option><option value=462>Sudhir Moharir</option><option value=484>Sudhir Oswal</option><option value=502>Suhel Quader</option><option value=743>Sukhendu Rudra</option><option value=571>Sumesh PT</option><option value=360>Sumit Dookia</option><option value=538>Sumit Sen</option><option value=914>Sumit Sinha</option><option value=1013>Sumita Chaudhry</option><option value=832>Sunil Kulkarni</option><option value=352>Suniti Bhushan Datta</option><option value=965>Supriya Nair</option><option value=370>Supriyo Ghatak</option><option value=400>Suresh Jones</option><option value=495>Surya Prakash</option><option value=679>Sushant Narayan Bhusari</option><option value=1010>Swadeepsinh Jadeja</option><option value=366>Tara Gandhi</option><option value=1028>Tarin Mithel</option><option value=530>Tarique Sani</option><option value=590>Tejal Dhulla Vishweshwar</option><option value=518>TG Ravikumar</option><option value=601>Thangavel Rajagopal</option><option value=811>The Nature Trust</option><option value=550>Thejaswi Shivanand</option><option value=367>TS Ganesh</option><option value=392>Tuhin Jana</option><option value=358>Udayan Dharmadhikari</option><option value=447>Umesh Mani</option><option value=567>Umesh Srinivasan</option><option value=368>Urmila Ganguli</option><option value=437>Usha Lachungpa</option><option value=732>Utpal Singha Roy</option><option value=710>Uttara Mendiratta</option><option value=532>V Santharam</option><option value=344>Vaibhav Chaturvedi</option><option value=748>Vaidehi Gunjal</option><option value=636>Vardharajan Gokula</option><option value=980>Varun.T</option><option value=819>Venkatakrishnan Srinivasan</option><option value=496>Venu Prasad</option><option value=917>Vidyadhar Atkore</option><option value=806>Vijay Mohan Raj</option><option value=506>Vijay Mohan Raj</option><option value=559>Vikram Jit Singh</option><option value=799>Vikramjit Singh Bal</option><option value=705>Vinaya Kumar Thimmappa</option><option value=672>Vinayak Krishna Patil</option><option value=683>Vishal Bhave</option><option value=775>Vishal Jadhav</option><option value=388>Vishal V Jadhav</option><option value=919>Vishnupriya Hathwar</option><option value=605>Vivek Chandran</option><option value=927>Vivek Ramachandran</option><option value=581>VL Upadya</option><option value=989>Yanamadala Pratap Rao</option><option value=997>Yashodhan Gharat</option><option value=777>Yazdy Palia</option><option value=596>Yesoda Bai R</option>                    </select>
                </div>&nbsp;
                		</td><td></td>
		</tr>
                <tr><td colspan='4' style='height:15px'></tr>
		<tr>
			<td colspan="2">state</td>
         <td colspan="2">location</td>
       </tr>
        <tr>
          
          <td colspan="2" style="">
             <select style="width:93%;"  id="state" name=state >

                        <option value="all">All the States</option>
                    <option value=1>Andaman and Nicobar Islands</option><option value=2>Andhra Pradesh</option><option value=3>Arunachal Pradesh</option><option value=4>Assam</option><option value=5>Bihar</option><option value=6>Chandigarh</option><option value=7>Chhattisgarh</option><option value=8>Dadra and Nagar Haveli</option><option value=9>Daman and Diu</option><option value=10>Delhi</option><option value=11>Goa</option><option value=12>Gujarat</option><option value=13>Haryana</option><option value=14>Himachal Pradesh</option><option value=15>Jammu and Kashmir</option><option value=16>Jharkhand</option><option value=17>Karnataka</option><option value=18>Kerala</option><option value=19>Lakshadweep</option><option value=20>Madhya Pradesh</option><option value=21>Maharashtra</option><option value=22>Manipur</option><option value=23>Meghalaya</option><option value=24>Mizoram</option><option value=25>Nagaland</option><option value=26>Orissa</option><option value=27>Puducherry</option><option value=28>Punjab</option><option value=29>Rajasthan</option><option value=30>Sikkim</option><option value=31>Tamil Nadu</option><option value=32>Tripura</option><option value=33>Uttar Pradesh</option><option value=34>Uttarakhand</option><option value=35>West Bengal</option><option value=36>Not In India</option>
</select>&nbsp;&nbsp;
                   
          </td>

			<td colspan="2" style="width:500px;">
               
               <input type='text' id='location'  size='25' value="" style="width:95%;border:solid 1px #666">
               <input type='hidden' id='location_hidden' name='location' value="">
                             
         </td>
        
         <td style='width:110px'><input type='submit' value='go'>&nbsp;<a href="/report_new.php">Remove&nbsp;All</a></td>
        </form>
        </tr>
	</table>

</div>

<div class='container' style="width:950px;margin-left:auto;margin-right:auto;border-top:solid 1px #d95c15" id='tab-set'>
   <ul class='tabs'>
   
           <li><a href='#text1' class='selected'>map</a></li>
           <li><a href='#text2'>tabular</a></li>
 
   </ul>
   <div id='text1'>
              <div id="map" style="width:950px;height:500px"></div>
  </div>

  <div id='text2'>
     <table id="table1" class="tablesorter" style="width:950px;margin-left:auto;margin-right:auto" cellpadding="3">
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

     <tr><td>1</td><td>Indian Blue Robin</td><td>Nandi Hills, Bangalore, Karnataka</td><td>29-11-2008</td><td> -- </td><td>Sachin Shurpali</td><td style='border-right:0.5px solid #ffcb1a'></td></tr><tr><td>2</td><td>Tickell's Leaf-wabler</td><td>Nandi Hills, Bangalore, Karnataka</td><td>29-11-2008</td><td> -- </td><td>Sachin Shurpali</td><td style='border-right:0.5px solid #ffcb1a'></td></tr><tr><td>3</td><td>Ashy Drongo</td><td>Nandi Hills, Bangalore, Karnataka</td><td>29-11-2008</td><td> -- </td><td>Sachin Shurpali</td><td style='border-right:0.5px solid #ffcb1a'></td></tr><tr><td>4</td><td>Red-throated Flycatcher</td><td>Nandi Hills, Bangalore, Karnataka</td><td>29-11-2008</td><td> -- </td><td>Sachin Shurpali</td><td style='border-right:0.5px solid #ffcb1a'></td></tr><tr><td>5</td><td>Blue-headed Rock-thrush</td><td>Nandi Hills, Bangalore, Karnataka</td><td>29-11-2008</td><td> -- </td><td>Sachin Shurpali</td><td style='border-right:0.5px solid #ffcb1a'></td></tr><tr><td>6</td><td>Greenish Warbler</td><td>Nandi Hills, Bangalore, Karnataka</td><td>29-11-2008</td><td> -- </td><td>Sachin Shurpali</td><td style='border-right:0.5px solid #ffcb1a'></td></tr>      </tbody>
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
<link rel="stylesheet" href="tabs/screen.css" type="text/css" media="screen">

<script type="text/javascript">
	$("ul.tabs li.label").hide(); 
	$("#tab-set > div").hide(); 
	$("#tab-set > div").eq(0).show(); 
       //$("#tab-set > div").eq(1).show();
     
  $("ul.tabs a").click(
 
  	     function() { 
                        
  	     		$("ul.tabs a.selected").removeClass('selected'); 
  			$("#tab-set > div").hide();
                       
  			$(""+$(this).attr("href")).fadeIn('slow'); 
                       
  			$(this).addClass('selected');
                                                
                        $('#table1').trigger("appendCache");

  			return false; 
     }
  );
  $("#toggle-label").click( function() {
  			    $(".tabs li.label").toggle(); 
  			   return false; 
  }); 

 
  
</script>

<script type="text/javascript">
$(function () {
  

$('.gradient1').gradient({
from:      '003366',
to:        '333333',
direction: 'horizontal'
});



});

</script>



</div>
</body>
</html>

<script type="text/javascript">
function get_remove(parameter) {

var remove_season = '';
var remove_type = '';
var remove_species = '';
var remove_user = '';
var remove_state = '';
var remove_location = '';

  if ( parameter == 'season') {
    
     remove_season = '2009-2010';
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


