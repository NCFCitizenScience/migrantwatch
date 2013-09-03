<?php include("checklogin.php"); 
      $species_id = $_GET['id'];
      $page_title="MigrantWatch: Species guide";
      include("main_includes.php");
?>
<body><? 
	include("header.php");	
?>
<div class="container first_image" style='min-height:200px'>
  <div id='tab-set'>   
     <ul class='tabs'>
        <li><a href='#x' class='selected'>species&nbsp;guide</a></li>
    </ul>
   </div>

    <?
   if( $species_id ) {
        $species_info = mw_get_species($species_id);

?>

<table><tr><td style="width:700px;">
<?

$sql = "select s.common_name,s.species_id, s.alternative_english_names,s.scientific_name, s.population, s.habitat_type,s.OBC_number, s2.size, s2.descr,s2.behaviour,s2.dist_char,s2.winter_dist,s2.breed_dist,s2.iucn_status,s2.call_text,s2.other_notes,s2.img_male,s2.img_female,s2.img_juv,s2.img_hab,s2.call_audio,s2.img_behav,s2.habitat,s2.video from migwatch_species as s,migwatch_species_set as s2 where s.species_id =  s2.species_id";

$result = mysql_query($sql);
$num_results = mysql_num_results;


$n=0;
$pos=0;
$photos;
while ($line = mysql_fetch_array($result)) {
        $guide[$n] = $line["species_id"];        
        if ($guide[$n] == $species_id) { $pos=$n;}
        if ($n == 0 ) {
          $guide_first = $guide[$n];
        }
        $guide_last = $guide[$n];
        $n++;
}

$prev = $guide[$pos-1];
$next = $guide[$pos+1];

?>

		<table style='width:600px; margin-left:auto;margin-right:auto'>
		<tr><td><a href='guide.php?id=<? echo $prev; ?>'>Prev</a>&nbsp;|&nbsp;<a href='guide.php'>Main</a>&nbsp;|&nbsp;<a href='guide.php?id=<? echo $next; ?>'>Next</a></td></tr>
		<tr><td><h3 style='margin-bottom:0px'><? echo $species_info['common_name']; ?></h3></td></tr>
		<tr><td><h4>Scientific name</h4><i><? 	echo $species_info['scientific_name']; ?></i></td></tr>
		<? if ( $species_info['population'] ) {?>
		<tr><td><h4>Population</h4><? echo ucfirst($species_info['population']); ?></td></tr>
		<? } ?>
		<? if ( $species_info['habitat_type'] ) {?>
		<tr><td><h4>Habitat</h4><? echo ucfirst($species_info['habitat_type']); ?></td></tr>
		<? } ?>

		<? if (	$species_info['size'] ) {?>
		<tr><td><h4>Size</h4>
		<? echo ucfirst($species_info['size']); ?></td></tr>
                <? } ?>

		<? if ( $species_info['descr'] ) {?>
		   <tr><td><h4>Description</h4>
			<? echo ucfirst($species_info['descr']); ?></td></tr>
                <? } ?>

		<? if ( $species_info['behaviour'] ) {?>
                   <tr><td><h4>Behaviour</h4>
                        <? echo $species_info['behaviour']; ?></td></tr>
                <? } ?>

		<? if ( $species_info['dist_char'] ) {?>
                   <tr><td><h4>Distinguishing characteristics</h4>
                        <? echo $species_info['dist_char']; ?></td></tr>
                <? } ?>

		<? if ( $species_info['winter_dist'] ) {?>
                   <tr><td><h4>Winter distribution</h4>
                        <? echo $species_info['winter_dist']; ?></td></tr>
	        <? } ?>

		<? if ( $species_info['breed_dist'] ) {?>
                   <tr><td><h4>Breeding distribution</h4>
                        <? echo $species_info['breed_dist']; ?></td></tr>
	       <? } ?>

	       <? if ( $species_info['iucn_status'] ) {?>
                   <tr><td><h4>IUCN Status</h4><?
		       $status = $species_info['iucn_status'];
		       if ( $status == "en" ) { 
		       	  $iucn_name = "Endangered"; 
		       } else if ( $status == "vu" ) { 
		       	   $iucn_name="Vulnerable"; 
		        } else if ( $status == "th" ) {
			   $iucn_name = "Threatened";
			} else if ( $status == "cs" ) {
			   $iucn_name = "Conservation dependent";
         	        } else if ( $status == "nt" ) {
                           $iucn_name = "Near threatened";
                        } else if ( $status == "lc" ) {
                           $iucn_name = "Least concern";
                        }
                         
                        echo $iucn_name ?></td></tr>
	        <? } ?>
		
                 

		<? if (	$species_info['call_audio'] ) {?>
		<tr><td><h4>Call audio</h4><a class="media" href="migwatch_species_uploads/<? echo $species_info['call_audio']; ?>">click to  download/play the call</a> </td></tr>
		<? } ?>
		<? if (	$species_info['video'] ) {?>
		<tr><td><? echo $species_info['video']; ?></td></tr>
		<? } ?>
		<tr><td>		
		</table>

<? } else { ?>
     	  
 	  <table class="filter">
                 <form id='editform' method="GET" name="frm_sortfield">
                 <tr>
                        <td style="text-align:center">
		          <select name="archive-dropdown" onChange='document.location.href=this.options[this.selectedIndex].value;'> 
                             <option value="">Select a common name</option>
			     <?
				$all = mw_get_all_species();
				 for( $j=0; $j < count( $all['species_id'] );  $j++ ) { ?>
    				  <option value='?id=<? echo $all["species_id"][$j]; ?>'><? echo $all["common_name"][$j]; ?></option>
                                <? } ?>

			     ?>			     
			   </select>
			</td>
                        <td style="text-align:center">
                          <select name="archive-dropdown" onChange='document.location.href=this.options[this.selectedIndex].value;'>
                             <option value="">Select a scientific name</option>
                             <?
				$all = mw_get_all_species();
					 for( $j=0; $j < count( $all['species_id'] );  $j++ ) { ?>
                                  <option value='?id=<? echo $all["species_id"][$j]; ?>'><? echo $all["scientific_name"][$j]; ?></option>
                                <? } ?>

                             ?>
                           </select>
		        </td>
                        
                </tr>
          </table>
	 <div style="width:700px;margin-left:auto;margin-right:auto;text-align:right;margin-top:10px" id="Pagination" class="pagination"></div>
		<br style="clear:both;" />

		<dl id="Searchresult">
			<dt>Search Results will be inserted here ...</dt>
		</dl>

		<form name="paginationoptions">
			
			<input type="hidden" value="prev" name="prev_text" id="prev_text"/>
			<input type="hidden" value="next" name="next_text" id="next_text"/>
			
		</form>
<? } ?>
</td>

<td class='sidebar'>
     <table style='text-align:center;margin-left:auto;margin-right:auto'>
     <? if ( $species_info['img_male'] ) { ?>
     	   <tr><td style='text-align:center;'><a href='migwatch_species_uploads/<? echo $species_info['img_male']; ?>' class='lightbox'><img src="migwatch_species_uploads/metn_<? echo $species_info['img_male']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?> (Male)</td></tr>
     <? } ?>
     <? if ( $species_info['img_female'] ) { ?>
        <tr><td><a href='migwatch_species_uploads/<? echo $species_info['img_female']; ?>' class='lightbox'><img src="migwatch_species_uploads/metn_<? echo $species_info['img_female']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?> (Female)</td></tr>
     <? }  ?>
    <? if ( $species_info['img_juv'] ) { ?>
        <tr><td><a href='migwatch_species_uploads/<? echo $species_info['img_juv']; ?>' class='lightbox'>
         <img src="migwatch_species_uploads/metn_<? echo $species_info['img_juv']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?> (Juvenile)</td></tr>
     <? } ?>
    <? if ( $species_info['img_general'] ) { ?>
        <tr><td><a href='migwatch_species_uploads/<? echo $species_info['img_general']; ?>' class='lightbox'>
<img src="migwatch_species_uploads/metn_<? echo $species_info['img_general']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?></td></tr>
    <? } ?>
    <? if ( $species_info['img_flight'] ) { ?>
        <tr><td><a href='migwatch_species_uploads/<? echo $species_info['img_flight']; ?>' class='lightbox'>
<img src="migwatch_species_uploads/metn_<? echo $species_info['img_flight']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?> (flight)</td></tr>
<? } ?>
     </table>
</td>
</tr></table>
<? include("credits.php"); ?>
</div>
</div>
</div>
<div class="container bottom">

</div>
<? include("footer.php"); ?>

<script type="text/javascript" src="js/jquery/lightbox/js/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery/lightbox/css/jquery.lightbox-0.5.css" media="screen" />
<script type="text/javascript">
    $('a.lightbox').lightBox();
</script>
<? include("species_guide_includes.php"); ?>
</body>
</html>
