<? include("main_includes.php"); include("page_includes_js.php"); ?>


<script>

function new_info(from_form) {
  $('#lat').text(from_form);
}

</script>

	<a href="testmaps.php?height=500&width=800&TB_iframe=true" class="thickbox" title="">Update ThickBox content</a>
<form>
<input id='lat' type='text' name='location'>
</form>