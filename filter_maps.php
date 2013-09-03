<? 
function get_remove( $parameter) {
		      if($_GET['type']){
                           $remove_type = $_GET['type'];
        	      }
		      
		      if($_GET['species']){
                           $remove_species = $_GET['species'];
                       }

		       if($_GET['user']){
                           $remove_user .= $_GET['user'];
                       }
		       if($_GET['state']){
                           $remove_state = $_GET['state'];
                       }
		       
                        if($_GET['location']){
                           $remove_location = $_GET['location'];
        		}

                        if($_GET['season']){
                           $remove_location = $_GET['season'];
                        }

			if ($parameter == 'type') {

			     $remove_type='All';
                        }
                       
		        if ($parameter == 'species') {
		       	  $remove_species='All';
		        }
 
                         if ($parameter == 'user') {
                          $remove_user='All';
                        }

			 if ($parameter == 'location') {
                          $remove_location ='All';
                        }
			
			 if ($parameter == 'state') {
                          $remove_state ='All';
                        }

}

?>