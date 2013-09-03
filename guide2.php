<?php session_start(); 
      $species_id = $_GET['id'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head> 
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>MigrantWatch : Species guide</title>
<?
       include("header.php");


?>
<script type="text/javascript" src="lightbox/js/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="lightbox/css/jquery.lightbox-0.5.css" media="screen" />

<script type="text/javascript" src="jquery.media.js"></script>
<script type="text/javascript">
    $(function() {
        $('a.media').media( { width: 300, height: 20 } );
	$('a.lightbox').lightBox(); 
    });
</script>

<div class='container first_image'>
<?
   if( $species_id ) {
        $species_info = mw_get_species($species_id);

?>

<div class='sidebar' style='margin-top:30px;margin-right:20px;border:solid 1px #333'>
     <table style='text-align:center;margin-left:auto;margin-right:auto'>
<? if ( $species_info['img_male'] ) { ?>
    <tr><td style='text-align:center;'><a href='migwatch_species_uploads/<? echo $species_info['img_male']; ?>' class='lightbox'><img src="migwatch_species_uploads/metn_<? echo $species_info['img_male']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?> ( Male )</td></tr>
<? } ?>
<? if ( $species_info['img_female'] ) { ?>
        <tr><td><a href='migwatch_species_uploads/<? echo $species_info['img_female']; ?>' class='lightbox'><img src="migwatch_species_uploads/metn_<? echo $species_info['img_female']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?> ( Female )</td></tr>
<? } ?>

<? if ( $species_info['img_juv'] ) { ?>
        <tr><td><a href='migwatch_species_uploads/<? echo $species_info['img_juv']; ?>' class='lightbox'>
<img src="migwatch_species_uploads/metn_<? echo $species_info['img_juv']; ?>" title="<? echo $species_info['common_name']; ?>"></a><br><? echo $species_info['common_name']; ?> ( Juvenile )</td></tr>
<? } ?>
</table>
</div>
<div style="width:700px;padding:20px;">

		<table style='width:600px; margin-left:auto;margin-right:auto'>
		<tr><td><h3 style='margin-bottom:0px'><? echo $species_info['common_name']; ?></h3></td></tr>
		<tr><td><i><? 	echo $species_info['scientific_name']; ?></i></td></tr>
		<? if ( $species_info['population'] ) {?>
		<tr><td><b>Population</b>:&nbsp;<? echo ucfirst($species_info['population']); ?></td></tr>
		<? } ?>
		<? if ( $species_info['habitat_type'] ) {?>
		<tr><td><b>Habitat</b>:&nbsp;<? echo ucfirst($species_info['habitat_type']); ?></td></tr>
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
		<tr><td><a class="media" href="migwatch_species_uploads/<? echo $species_info['call_audio']; ?>">call audio</a> </td></tr>
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
                        <td style="text-align:center">Common&nbsp;name<br>
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
                        <td style="text-align:center">Scientific name<br>
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
		
<?

	
include("species_guide_main2.php"); 	
?>
		
</tr>
	  

<? } ?>
	</div>
</div>
</div>
<style>

.sidebar td {

	 text-align:center;
}

.sidebar img{

	 border: solid 5px #c0c0c0;
}
</style>
<div class="container bottom">

</div>
</body>
</html>