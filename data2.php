<?php
include("checklogin.php");
include("db.php");
$strSpecies='Type a species name';
$strLocation='Type a location name';
$page_title="MigrantWatch: Data";
include("main_includes.php");

$kml_text="<span class='help_text_content'><h4>KML</h4>KML is a file format used to display geographic data in an earth browser, such as Google Earth, Google Maps</span>";

$csv_text="<span class='help_text_content'><h4>CSV</h4>CSV is Comma-Separated Value file for use in spreadsheet and database tools</span>";

include("header.php");
include("query_reports_update.php");

$where_clause2 = " AND s.Active = '1' group by l.latitude,l.longitude DESC";
$where_clause3 = " AND s.Active = '1' order by l1.id DESC";



function get_sighting_type_count($sql,$type) {
	 $fs_sql = $sql . " AND l1.obs_type='$type' " . $where_clause3;
	 $result=mysql_query($fs_sql);
	 $num_rows = mysql_num_rows($result);
	 return $num_rows;  
}

function get_species_count($sql) {
         $fs_sql = $sql . " group by l1.species_id";
         $result=mysql_query($fs_sql);
         $num_rows = mysql_num_rows($result);
         return $num_rows;
}

function get_loc_count($sql) {
         $fs_sql = $sql . " group by l1.location_id";
         $result=mysql_query($fs_sql);
         $num_rows = mysql_num_rows($result);
         return $num_rows;
}

function get_state_count($sql) {
         $fs_sql = $sql . " group by st.state_id";
         $result=mysql_query($fs_sql);
         $num_rows = mysql_num_rows($result);
         return $num_rows;
}

function get_user_count($sql) {
         $fs_sql = $sql . " group by l1.user_id";
         $result=mysql_query($fs_sql);
         $num_rows = mysql_num_rows($result);
         return $num_rows;
}





$basic_sql =  $sql . " AND s.Active = '1'";		


$map_sql = $sql . " "  . $where_clause2;
$table_sql = $sql . " "  . $where_clause3;

?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">


	 var map=null;

         var markers = [];




         function load() {
               
	       var myLatlng = new google.maps.LatLng(20.21,77.86);
    	       var myOptions = {
     	              zoom: 5,
      		      center: myLatlng,
      		      mapTypeId: google.maps.MapTypeId.ROADMAP
    	       }

    	       var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

<?	
	
                $result = mysql_query($map_sql);
	        $i = 1;
	        list($startSeason,$endSeason) =  explode('-',$season);
	        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $lat= $line['latitude'];
                $lng = $line['longitude'];
		$loc_id = $line['location_id'];


                if(is_numeric($line['latitude'])) {

?>

			 var myLatlng = new google.maps.LatLng(<? echo $line['latitude']; ?>, <? echo $line['longitude']; ?>);
			 var image = 'http://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_red.png';

			 var contentString = "<? echo $line{'location_name'} . ", ".$line{'city'}.", ".$line{'state'}; ?>";

			      
    			 var infowindow = new google.maps.InfoWindow();

		         var marker = new google.maps.Marker({
        		     position: myLatlng, 
        		     map: map,
        		     title: contentString,
			     icon: image,
			     id: <? echo $loc_id; ?>
    			 });

<?
	                 $url_to_pass = '';
			 if($_GET) {
                             foreach( $_GET as $key => $value ) {
                                      if($key!='location') {
                                                         $url_to_pass .="&" . $key . "=" . $value;
			               }                             
                             }

			 }
?>

			 google.maps.event.addListener(marker, 'click', function() {		    
			    load_content(map,this,infowindow,"<? echo $url_to_pass; ?>");
    			 });


<?

                       
                        $location_id_get = $line['location_id'];
                        $url_add = '';
                 	if($_GET) { 
                             foreach( $_GET as $key => $value ) {
		             	      if($key!='location') {
				      			 $url_add .="&" . $key . "=" . $value;     
                             }
                        }
			    
		        
                            $url_add .="&location=" . $location_id_get;
                       } else { 

                            $url_add = "location=" . $location_id_get;
                 }
                 print "var final_content = '" . $url_add . "';";

                 }
              	 
	    }
       
?>
	}


function load_content(map,marker,infowindow,url){
  var data = "location=" + marker.id + url;
  $.ajax({
    url: 'get_sighting_list.php',
    type: "GET",
    data: data,
    success: function(dataone){
      infowindow.setContent(dataone);
      infowindow.open(map, marker);
    }
  });
  
}

  
</script>
<body onload="load()">

<style>
.tickerContainer { padding: 10px; }

</style>

<div class="container first_image">
<FORM name="reports" action="data.php" method="GET">
<table class="filter">    
<tr>
         <td>season</td>
         <td>sighting&nbsp;type</td>
	 <td>species</td>
         <td>participant</td>
         
       </tr>      
         <td style="width:190px;">
                    <select style="width:85%;font-size:12px;" name='season'>
                    <option value='All'>All seasons</option>
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
                            echo ($season == $fromTo) ? ' selected>' : '>';
                       
                        echo $fromTo;
                        echo '</option>';
                    }
                    ?>
           
                    </select>
		    <? $current_season = getCurrentSeason();
                   
                       if( $_GET['season'] != '' && strtolower($_GET['season']) !='all' ) {		       
		    ?>
                     <a title="remove season" href="#" onClick="get_remove('season');">X</a>
                    
                       <? } ?>

                   </td> <td  style="width:190px;">
                    
		    
                    <select name="type" style="width:85%">
                            <option value="All">All</option>
                            <option value="first"<?php if($type == 'first') print("selected"); ?>>First</option>
                            <option value="general"<?php if($type == 'general') print("selected"); ?>>General</option>
                            <option value="last"<?php if($type == 'last') print("selected"); ?>>Last</option>
                     </select>
                      <? if( $_GET['type'] != "All" && $_GET['type'] != '') {  ?>
                           <a title="remove sighting type" href="#" onclick="get_remove('type');">X</a>
                      <? } ?>
		</td>
	  
                <td style="width:300px;">
                    
                    
                    <input type='text' id='species' size="25" style="width:85%;border:solid 1px #666;padding:2px" value="<? echo $strSpecies; ?>">
		               <input type='hidden' id='species_hidden' name='species' value="<? echo $species; ?>">
                             
                             <? if( is_numeric($_GET['species'] )) {  ?>
                                  <a title="remove species" href="#" onclick="get_remove('species');">X</a>
                             <? } ?>
		</td>



		<td style="width:240px;">
           	   
                    <select name=user style="width:85%">
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
                <? if( is_numeric( $_GET['user'] )) {  ?>
                     <a href="#" onclick="get_remove('user');">X</a>
                <? } ?>
		</td>
		</tr>
                
		<tr>
			<td colspan="2">state</td>
         <td colspan="2">location</td>
	 	 
       </tr>
        <tr>
          
          <td colspan="2" style="">
             <select style="width:93%;"  id="state" name=state >

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

                    ?></select>
                   <? if( is_numeric($_GET['state'] )) {  ?>
                     <a title="remove state" href="#" onclick="get_remove('state');">X</a>
                   <? } ?>

          </td>

			<td style="width:400px;">
               
               <input type='text' id='location' value="<? echo $strLocation; ?>" style="padding:2px;width:85%;border:solid 1px #666">
               <input type='hidden' id='location_hidden' name='location' value="<? echo $location; ?>">
               <? if( is_numeric($_GET['location'] )) {  ?>
                  &nbsp;<a title="remove location" href="#" onclick="get_remove('location');">X</a>
               <? } ?>
              
         </td>
        
         <td style='width:200px;text-align:right;'><a href='data.php' title='unselect all the filters' class='submit unselect'>unselect&nbsp;all</a>&nbsp;<input type='submit' class='submit' value='go'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </form>
        </tr>
	</table>
<? 
   $url_add2 = '';
   foreach( $_GET as $key => $value ) {
   	    if( strtolower($value) != 'all' && $value !='' ) {
   	    $url_add2 .="&" . $key . "=" . $value;
	    }
   }



$result = mysql_query($table_sql);
$total_num_rows = mysql_num_rows($result);
 if ( $total_num_rows > 0) {
?>
<div class='container page_layout' style='height:75px; font-size:14px;'>
   <b><? echo $total_num_rows; ?> reports found</b>
<? if($_SESSION['userid']) { ?> 
   &nbsp;&nbsp;|&nbsp;&nbsp;<b>Download data</b>&nbsp;<a title='Download data below as KML' href='download.php?output=kml<? echo $url_add2; ?>'>KML</a>&nbsp;
   (<a class='help_text' title="<? echo $kml_text; ?>" href='#'> ? </a>)&nbsp;&nbsp;|&nbsp;&nbsp;<a title='Download data below as CSV' href='download.php?output=csv<? echo $url_add2; ?>'>CSV</a>&nbsp;(<a class='help_text' title="<? echo $csv_text; ?>" href='#'> ? </a>)
<? } else { echo "&nbsp;&nbsp;|&nbsp;&nbsp;You must be logged in to download data"; }?>
</div>
<div class='container' style="width:930px;margin-left:auto;margin-right:auto;border-top:solid 1px #d95c15" id='tab-set'>
   <ul class='tabs'>
   
           <li style='margin-left:0'><a href='#text1' class='selected'>map</a></li>
           <li style='margin-left:0'><a href='#text2'>tabular</a></li>
 
   </ul>
   <div id='text1'>
              <div id="map_canvas" style="width:930px;height:700px;">Loading maps. This might take some time</div><br><br>
  </div>

  <div id='text2'>
     <table id="table1" class="tablesorter">
                <thead>
                        <tr>
                                <th style="width:50px">&nbsp;No</th>
                                <th style='width:200px;'>Common Name</th>           
                                <th style='width:200px'>Location</th>
                                <th style='width:100px'>Date</th>                        
				<th style='width:100px'>Sighting type</th>
                                <th style='width:50px'>Count</th>
                                <th style='width:200px'>Reported by</th>
                    
                        </tr>
                </thead>
                <tbody>
     <?
	$i=1;
	list($startSeason,$endSeason) =  explode('-',$_GET['season']);
	//if (mysql_num_rows($result) > 0) {
           $result = mysql_query($table_sql);
	   while ($row = mysql_fetch_array($result)) {
	             print "<tr>";
                        print "<td style='text-align:center'><a target='_blank' href='sighting.php?id=" . $row{'id'} ."'>" . $i . "</a></td>";
	                print "<td>".$row{'common_name'}."</td>";
                        print "<td>".$row{'location_name'}.", ".$row{'city'}.", ".$row{'state'}."</td>";
                        print "<td>".date("Y-m-d",strtotime($row{'sighting_date'}))."</td>";
		        print "<td>" . ucfirst($row{'obs_type'}) . "</td>";
                        if ($row['number'] > 0 ) { 
					print "<td>".$row['number']."</td>"; 
			} else {  print "<td> -- </td>"; } 
                        print "<td style='border-right:solid 1px #ffcb1a'>".$row{'user_name'}."</td>";
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
<div class='container'>
<style>

.fstats li, .fstats li ul { list-style:none; float:left; }
.fstats>li { width:33%; }
.fstats>li>ul>li { font-size:20px; text-transform:uppercase; width:100%; font-weight:bold; }

</style>
<ul class='fstats'>
   <li>
     <ul>
		<li><? echo get_sighting_type_count($basic_sql,'first'); ?> First sightings</li>

		<li><? echo get_sighting_type_count($basic_sql,'general'); ?> General sightings</li>

		<li><? echo get_sighting_type_count($basic_sql, 'last'); ?> Last sightings</li>


     </ul>
    </li>
    
    <li>
	<ul>
		<li><? echo get_species_count($basic_sql); ?> species</li>
	
		<li><? echo get_loc_count($basic_sql); ?> locations</li>
		
		<li><? echo get_state_count($basic_sql); ?> states</li>

	</ul>

    </li>

    <li>
	<ul>
		<li><? echo get_user_count($basic_sql); ?> contributors</li>

	</ul>

    </li>

</ul>
</div>
<? } else { ?>

<div class='container notice' style="width:900px;margin-left:auto;margin-right:auto;">No results</div>
<? } 
include("credits.php"); 
?>

</div>

</div>
</div>

<div class="container bottom">

</div>
<? include("tab_include.php"); ?>
<script type='text/javascript'>
$().ready(function() {

var state_val = $('#state').val();

$('#species').emptyonclick();
$('#location').emptyonclick();

$("#species").autocomplete("autocomplete_reports.php", {
  width: 260,
  selectFirst: false,
  matchSubset :0,
  mustMatch: true,                          
});

$("#species").result(function(event , data, formatted) {
  if (data) {
	 document.getElementById('species_hidden').value = data[1];
  }
});



$('#location').autocomplete("auto_location_watchlist.php", {
   width: 400,
   selectFirst: false,
   matchSubset :0,
   cache:false,
   mustMatch: true,
   extraParams: {state: function() { return $("#state").val(); } },
});

$("#location").result(function(event , data, formatted) {
   if (data) {
	document.getElementById('location_hidden').value = data[1];
   }
});
});


function get_remove(parameter) {
<? 
if($_GET['type']){
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
   remove_season = 'All';
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

var url = "data.php?season=" + remove_season + "&type=" + remove_type + "&species=" + remove_species + "&user=" + remove_user + "&state=" + remove_state + "&location=" + remove_location;

window.location = url;
}

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

function isEmpty(s){   
    return ((s == null) || (s.length == 0))
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
<? include("footer.php"); ?>
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

</body>
</html>


