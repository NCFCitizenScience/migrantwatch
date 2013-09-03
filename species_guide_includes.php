<? include("pagination/members.php"); ?>
<script type="text/javascript" src="pagination/jquery.pagination.js"></script>
<link rel="stylesheet" href="pagination/pagination.css" />        
<script type="text/javascript">function pageselectCallback(page_index, jq){
var items_per_page = 16;
var max_elem = Math.min((page_index+1) * items_per_page, members.length);
var newcontent = '';
newcontent += "<table style='border: solid 2px #c0c0c0;padding:10px'><tr>";
for(var j= (page_index * items_per_page) + 1 ;j<=max_elem;j++) {
	var i = j - 1;
	newcontent += '<td class="state"><a href="guide.php?id=' + members[i][4] + '"><img src="migwatch_species_uploads/tn_' + members[i][2] + '"><br>' + members[i][0] + '</a></td>';
	if ( ( j % 4 ) == '0' ) { newcontent +='</tr><tr>'; }						
}
newcontent += '</tr></table>';
$('#Searchresult').html(newcontent);
return false;
}
            
function getOptionsFromForm(){
                var opt = {callback: pageselectCallback};
                // Collect options from the text fields - the fields are named like their option counterparts
                $("input:hidden").each(function(){
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
	var optInit = getOptionsFromForm();
        $("#Pagination").pagination(members.length, optInit);
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

.sidebar td {
	text-align:center;
}

.sidebar img{
	 border: solid 5px #c0c0c0;
}
</style>
