<?php
	 include("auth.php");
	 include("db.php");
	 $page_title="MigrantWatch: Watchlist";
	 function getSpeciesInfo($id) {
           $sql = "select common_name from migwatch_species where species_id='$id'";
           $result=mysql_query($sql);
           $data = mysql_fetch_assoc($result);
           return $data['common_name'];
         }

	  function getLocationInfo($id) {
           $sql = "SELECT l.location_id, l.location_name, l.city, l.district,s.state ";
	   $sql.= "FROM migwatch_locations as  l, ";
	   $sql.= "migwatch_states as s ";
           $sql.= "where l.state_id = s.state_id and location_id='$id'";
           $result=mysql_query($sql);
           $row = mysql_fetch_assoc($result);
           return $row['location_name'].", ".$row['city'].", ".$row['district'].", ".$row['state'];
         }

	function getSpeciesWatchlist($user) {
	   $sql="select id,species_id,url, location, state, user, season, stype from migwatch_species_watchlist where user_id=" . $_SESSION['userid'];
	   $result=mysql_query($sql);
	   while($data = mysql_fetch_array($result)) {
	   	$sw['id'][] = $data['id'];
	   	$sw['species_id'][] = $data['species_id'];
		$sw['url'][] = $data['url'];
		$sw['location'][] = $data['location'];
		$sw['user'][] = $data['user'];
		$sw['season'][] = $data['season'];
		$sw['state'][] = $data['state'];
		$sw['type'][] = $data['stype'];
	   }
	   return $sw;
	 }

	function getLocationWatchlist($user) {
           $sql="select id,location_id,url,  species, user, season, stype from migwatch_location_watchlist where user_id=" . $_SESSION['userid'];
           $result=mysql_query($sql);
           while($data = mysql_fetch_array($result)) {
           	       $lw['location_id'][] = $data['location_id'];
		       $lw['id'][] = $data['id'];
		       $lw['url'][] = $data['url'];
		       $lw['season'][] = $data['season'];
		       $lw['type'][] = $data['stype'];
		       $lw['species'][] = $data['species'];
		       $lw['user'][] = $data['user'];
		      
           }
           return $lw;
        }

	if(isset($_SESSION['userid'])){
                $sql = "SELECT user_name, user_email, address, address1, address2, city, district, state_id, country, pincode FROM migwatch_users WHERE user_id=".$_SESSION['userid'];
                $result = mysql_query($sql);
                                if($result){
                                        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){                                               
                                                $user_name = $row{'user_name'};
                                                $user_email = $row{'user_email'};
                                                $address = $row{'address'};
                                                $address1 = $row{'address1'};
                                                $address2 = $row{'address2'};
                                                $city = $row{'city'};
                                                $district = $row{'district'};
                                                $state_id = $row{'state_id'};
                                                $country = $row{'country'};
                                                $pincode = $row{'pincode'};
                                        }
                                }

                                $sql = "select state from migwatch_states where state_id='$state_id'";
                                $result = mysql_query($sql);
                                $state=mysql_fetch_assoc($result);
        }
	include("main_includes.php");
?>
<body>
	<? include("header.php"); ?>
	<style>
	.sidebar { margin-top:20px; }
	.sidebar_table { padding:5px; }
	.sidebar_table input[type=text]{ padding:2px;width:170px; }
	.sidebar_table select{ padding:2px;width:175px; }
	.watchlist a{color:#d95c15; }
	.errorv { color:red; font-size:10px; }
	.edit_profile select { width:170px; padding:2px}
	.edit_profile td{ vertical-align:top; }
        .ralign td { text-align:right }
	</style>
	<div class="container first_image">
       <div id='tab-set'>     
   	     <ul class='tabs'> 
   	     	 <li><a href='#first' class='selected'>my&nbsp;watchlist</a></li> 
   	     </ul> 
	</div>
	<table class="page_layout">
	<tr><td>
	<table style='vertical-align:top' class='watchlist'>
			   <tr><td><h4>Species Watchlist</h4></td></tr>
			  
			   <? $sw= getSpeciesWatchlist($_SESSION['userid']);
			      if ( count($sw) > 0 ) {
			      	 
				 for ($i=0; $i < count($sw['species_id']); $i++) {
				        $sw_i = $sw['id'][$i];
					$url = $sw['url'][$i];
					$sw_s = $sw['species_id'][$i];

					$location_id = $sw['location'][$i];
					$user_watch = $sw['user'][$i];
					$season_watch = $sw['season'][$i];
					$s_type_watch = $sw['type'][$i];
					$state_watch = $sw['state'][$i];

					if($user_watch){
						$user_name_watch = getUserNameById($user_watch,$connect);
					} else {
					       $user_name_watch ='';
					}

					$location_name='';

					if($location_id > 0) {
							
						$sql="select l.location_name,s.state, l.city,l.district from migwatch_locations as l, migwatch_states as s where s.state_id=l.state_id and  l.location_id='$location_id'";
						$result=mysql_query($sql);
						while($data = mysql_fetch_assoc($result)){
						     $location_name = '  from ' . $data['location_name'];
						     if($data['city']) { 
						        $location_name.=', ' . $data['city'];
						     }
						     if($data['district']) { 
                                                        $location_name.=', ' . $data['district'];
                                                     }
						     
						      if($data['state']) {
                                                        $location_name.=', ' . $data['state'];
                                                     }

					
						}


					} else if($state_watch) {
					    $sql="select state from migwatch_states where state_id='$state_watch'";
					    $result=mysql_query($sql);
					    $data=mysql_fetch_assoc($result);
					    $location_name = " from " . $data['state'];

					} else {
					  $location_name = " from all locations";

					}
					

				 	echo "<tr id='srow_" . $sw_i ."'><td><a style='float:left;vertical-align:middle' id='surl_" . $sw_i . "' title='Click here to see latest results'";
					echo " href='data.php?";
					if ($url) { echo $url; }
					echo "'><b>" . getSpeciesInfo($sw_s) . "</b>";
					if($s_type_watch) { echo " (" . ucfirst($s_type_watch) ." sighting) "; }
					if($location_name) { echo $location_name; }
					if($user_name_watch) { echo " by " . $user_name_watch; }
					if($season_watch){
                                          echo " (" . $season_watch . ") ";
                                        } 

					echo "</a>&nbsp&nbsp;";
					echo "( <a title='delete this watchlist' href='#' class='delete_species' id='sdel-" . $sw_i . "'>Delete</a>&nbsp;|";
					
		echo "&nbsp;<a class='thickbox edit' id='sedit_" . $sw_i . "' title='edit this watchlist' href='editspecieswatchlist.php?id=" . $sw_i;
		if ($url) { echo "&" . $url; }
        	echo "&TB_iframe=true&width=600&height=400'>Edit</a> )</td></tr>";
				  }
		
                            } else { 

			         echo "<tr><td id='dt'>No species added</td></tr>";

		             } ?>

			    
			    <tr><td><b>Please type a species name in the box below to add a species to your watchlist</b><br>
			    <form action='insertspecieswatchlist.php' method='GET'>
			    <input type='text' id='species' size="25" style="border:solid 1px #666;padding:5px;width:400px">
			    <input type='hidden' id='species_hidden' name='species' value=''>
			    <input type='submit' style='height:26px' value="Add Species">
			    </form>
			    </td></tr>


			    <tr><td><h4>Location Watchlist</h4></td></tr>
			  
			   <? $lw= getLocationWatchlist($_SESSION['userid']);
			      if ( count($lw) > 0 ) {
			      	 
				 for ($i=0; $i < count($lw['location_id']); $i++) {
				        $lw_i = $lw['id'][$i];
					$url = $lw['url'][$i];
					$lw_s = $lw['location_id'][$i];
					$lw_user_watch = $lw['user'][$i];
                                        $lw_season_watch = $lw['season'][$i];
                                        $lw_type_watch = $lw['type'][$i];
                                        $lw_species_watch = $lw['species'][$i];

					if($lw_species_watch) {
					   $lw_species_name = getSpeciesInfo($lw_species_watch);

					}


				 	echo "<tr><td id='lrow_" . $lw_i . "'><a id='lurl_" . $lw_i . "' style='float:left;vertical-align:middle' title='Click here to see latest results'";
					echo " href='data.php?";
					if ($url) { echo $url; }
					echo "'>";

					if($lw_species_watch) {
                                             echo getSpeciesInfo($lw_species_watch);
                                        }


					if($lw_type_watch) {
                                             echo " (" . ucFirst($lw_type_watch) . " sighting)";
                                        }

					 if($lw_user_watch) {
                                             echo " by " . getUserNameById($lw_user_watch,$connect);
                                        }

					echo " from <b>" . getLocationInfo($lw_s) . "</b> ";
				
					if($lw_season_watch) { echo " (" . $lw_season_watch . ")"; }
				
					echo "</a>&nbsp;&nbsp";
					echo "( <a title='delete this watchlist' class='delete_loc' href='#' id='ldel-" . $lw_i . "'>Delete</a>&nbsp;|";
	echo "&nbsp;<a id='ledit_" . $lw_i . "' class='thickbox edit' title='edit this watchlist' href='editlocationwatchlist.php?id=" . $lw_i . "&" . $url ."&TB_iframe=true&width=600&height=400'>Edit</a> )</td></tr>";
				  }
		
                            } else { 

			         echo "<tr><td id='dt'>No location added</td></tr>";

		             } ?>

			     <tr><td><b>Please type a location name in the box below to add a location to your watchlist</b><br>
			    	<form action='insertspecieswatchlist.php' method='GET'>
			    	<input type='text' id='location' size="25" style="border:solid 1px #666;padding:5px;width:400px">
			    	<input type='hidden' id='location_hidden' name='location' value=''>
			    	<input type='submit' value="Add Location" style='height:26px'>
			    	</form>
			    </td></tr>
			    
			</table>
		</td>
		<td style="width:200px;" class='sidebar'>
		   <table class='ralign'>
			<tr><td><a href='updateprofile.php#updatepass' id='change_password' title='Click here to change password'>Change password</a></td></tr> 
		   	<tr><td class='ralign'><a href='updateprofile.php#updateprofile' title="Click here to edit personal information" id='edit_personal'>Edit personal information</a></td></tr>
			<tr><td><table id='edit_personal_td' class='ralign'>
			
			<tr><td><? echo $user_name; ?></td></tr>
			<tr><td><b>Address:</b><br> <? echo $address . ",<br>" . $address1 . ",<br>" . $address2;?></td></tr> 
			<tr><td><b>City:</b> <? echo $city; ?></td></tr>
			<tr><td><b>District:</b> <? echo $district; ?></td></tr>
			<tr><td><b>State:</b> <? echo $state['state']; ?></td></tr>
			<tr><td><b>Pincode:</b> <? echo $pincode; ?></td></tr>
			</table>
			</td></tr>
			
		   </table>
		
		</td>
	</tr>		
	</table>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<? include("footer.php"); ?>
<script>

function update_lurl(url,id) {
   $('#lurl_' + id).val(url);
   $('#ledit_' + id).attr({'href': 'editlocationwatchlist.php?id='+ id + '&' + url + '&TB_iframe=true&width=600&height=400'});
}

function update_surl(url,id) {
   $('#surl_' + id).attr({'href': 'data.php?' + url});
   $('#sedit_' + id).attr({'href': 'editspecieswatchlist.php?id='+ id + '&' + url + '&TB_iframe=true&width=600&height=400'});
}

function page_refresh() {

window.location.reload();

}


$().ready(function() {

$("#species").autocomplete("auto_species_watchlist.php", {
  width: 400,
  selectFirst: false,
  matchSubset :0,
  mustMatch: true,                          
});

$("#species").result(function(event , data, formatted) {
  if (data) {
     	 
         document.getElementById('species_hidden').value = data[1];
   }
});

$(".delete_species").click(function(){
	var id = $(this).attr("id");
	id = id.replace(/sdel-/, "");
	var data = 'id=' + encodeURIComponent(id);
	jConfirm('Are you sure you want ot delete this entry from the species watchlist?', '',function(r) {
         if(r==true) {                   
            $.ajax({
                url: "deletespecieswatchlist.php",
                     type: "GET",
                     data: data,
                     cache: false,
                     success: function (html) {
                                 $('#srow_' + id).fadeOut("slow");
                     }
            });
           
          }
                                        
       });
	
});


$(".delete_loc").click(function(){
	var id = $(this).attr("id");
        id = id.replace(/ldel-/, "");
	var data = 'id=' + encodeURIComponent(id);
        jConfirm('Are you sure you want ot delete this entry from the location watchlist?', '',function(r) {
         if(r==true) {
            $.ajax({
                url: "deletelocwatchlist.php",
                     type: "GET",
                     data: data,
                     cache: false,
                     success: function (html) {
                                 $('#lrow_' + id).fadeOut("slow");
                     }
            });

          }

       });
});


$("#location").autocomplete("auto_location_watchlist.php", {
  width: 400,
  selectFirst: false,
  matchSubset :0,
  mustMatch: true,                          
});

$("#location").result(function(event , data, formatted) {
  if (data) {
     	 
         document.getElementById('location_hidden').value = data[1];
   }
});


});
</script>

</body>
</html>
