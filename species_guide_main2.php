        
        <!-- Load data to paginate -->
        
        <script type="text/javascript" src="http://d-scribe.de/webtools/jquery-pagination/lib/jquery_pagination/jquery.pagination.js"></script>
	<link rel="stylesheet" href="http://d-scribe.de/webtools/jquery-pagination/lib/jquery_pagination/pagination.css" />
	        <? include("pagination/members.php"); ?>
 <script type="text/javascript">
            
            // This file demonstrates the different options of the pagination plugin
            // It also demonstrates how to use a JavaScript data structure to 
            // generate the paginated content and how to display more than one 
            // item per page with items_per_page.
                    
            /**
             * Callback function that displays the content.
             *
             * Gets called every time the user clicks on a pagination link.
             *
             * @param {int}page_index New Page index
             * @param {jQuery} jq the container with the pagination links as a jQuery object
             */
			function pageselectCallback(page_index, jq){
                // Get number of elements per pagionation page from form
                var items_per_page = $('#items_per_page').val();
                var max_elem = Math.min((page_index+1) * items_per_page, members.length);
                var newcontent = '';
                
                // Iterate through a selection of the content and build an HTML string
                for(var i=page_index*items_per_page;i<max_elem;i++)
                {
                    newcontent += '<dt>' + members[i][0] + '</dt>';
                    newcontent += '<dd class="state">' + members[i][2] + '</dd>';
                    newcontent += '<dd class="party">' + members[i][3] + '</dd>';
                }
                
                // Replace old content with new content
                $('#Searchresult').html(newcontent);
                
                // Prevent click eventpropagation
                return false;
            }
            
            // The form contains fields for many pagiantion optiosn so you can 
            // quickly see the resuluts of the different options.
            // This function creates an option object for the pagination function.
            // This will be be unnecessary in your application where you just set
            // the options once.
            function getOptionsFromForm(){
                var opt = {callback: pageselectCallback};
                // Collect options from the text fields - the fields are named like their option counterparts
                $("input:text").each(function(){
                    opt[this.name] = this.className.match(/numeric/) ? parseInt(this.value) : this.value;
                });
                // Avoid html injections in this demo
                var htmlspecialchars ={ "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;"}
                $.each(htmlspecialchars, function(k,v){
                    opt.prev_text = opt.prev_text.replace(k,v);
                    opt.next_text = opt.next_text.replace(k,v);
                })
                return opt;
            }
			
            // When document has loaded, initialize pagination and form 
            $(document).ready(function(){
				// Create pagination element with options from form
                var optInit = getOptionsFromForm();
                $("#Pagination").pagination(members.length, optInit);
                
				// Event Handler for for button
				$("#setoptions").click(function(){
                    var opt = getOptionsFromForm();
                    // Re-create pagination content with new parameters
                    $("#Pagination").pagination(members.length, opt);
                }); 

            });
            
        </script>

        <style type="text/css">
         .state {
	 	
		text-align:center;
	 }

	 .state	img{
	  	border:	solid 5px #c0c0c0;
		
         }

        
		#Searchresult {
			margin-top:15px;
			margin-bottom:15px;
			width:700px;
			margin-left:auto;
			margin-right:auto;
			padding:5px;
			
            
		}
        
        #Searchresult dt {
            font-weight:bold;
        }
        
        #Searchresult dd {
            margin-left:25px;
        }
		
        </style>
		

        <div style="width:700px;margin-left:auto;margin-right:auto;text-align:right;margin-top:10px" id="Pagination" class="pagination"></div>
		<br style="clear:both;" />

		<dl id="Searchresult">
			<dt>Search Results will be inserted here ...</dt>
		</dl>

		<form name="paginationoptions">
			
			<input type="text" value="prev" name="prev_text" id="prev_text"/>
			<input type="text" value="next" name="next_text" id="next_text"/>
			
		</form>
		
    </body>
</html>
