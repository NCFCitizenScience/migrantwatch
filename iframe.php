<? include("main_includes.php"); ?>
<? include("page_includes_js.php"); ?>

<iframe id="uploadIFrame" scrolling="no" frameborder="0" hidefocus="true" style="height:800px;width:800px;text-align: center;vertical-align: top; border-style: none; margin: 0px;" src="testmaps.php"></iframe>

<script>

var $currentIFrame = $('#uploadIFrame');
$currentIFrame.contents().find("body #hiddenExample").val();

</script>