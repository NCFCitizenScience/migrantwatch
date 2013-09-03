<? 
   include("db.php");
   
   $sql="SELECT l1.id,s.common_name,l.location_name,l.city,st.state,u.user_name,l1.sighting_date,l1.number,l.latitude,l.longitude,l.location_id FROM migwatch_l1 l1 INNER JOIN migwatch_users u ON l1.user_id=u.user_id INNER JOIN migwatch_locations l ON l.location_id=l1.location_id INNER JOIN migwatch_species s ON s.species_id=l1.species_id INNER JOIN migwatch_states st ON st.state_id = l.state_id WHERE l1.sighting_date BETWEEN '2011-07-01' AND '2012-06-30' AND l1.sighting_date <> '1999-11-30' AND l1.sighting_date <> '0000-00-00' AND l1.valid = 1 AND deleted = '0' AND u.user_name != 'Developer' AND s.Active = '1' order by l.latitude,l.longitude DESC";
   
   $query=mysql_query($sql);
   
   while($data = mysql_fetch_assoc($query)) {
   	        $rows[] = array('data'=>$data);
   }     

   header('Content-type: application/json');
   echo json_encode(array('rows'=>$rows));

?>