<script>
function formatItem(row) {
	 		 return row[0] + " (<strong>id: " + row[1] + "</strong>)";
			 }
			 function formatResult(row) {
			 	  return row[0].replace(/(<.+?>)/gi, '');
				  }

$("#user_box").autocomplete('search_username.php', {
   width: 300,
   multiple: true,
   matchContains: true,
   formatItem: formatItem,
   formatResult: formatResult
});
</script>


<input id="user_box" name="user_box">