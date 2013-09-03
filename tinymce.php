
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
<meta name="description" content="TinyMCE is a platform independent web based Javascript HTML WYSIWYG editor control released as Open Source under LGPL by Moxiecode Systems AB. It has the ability to convert HTML TEXTAREA fields or other HTML elements to editor instances. TinyMCE is very easy to integrate into other CMS systems." />
<meta name="keywords" content="tinymce,wysiwyg,javascript,open source,LGPL,dhtml,editor,control,cms,online,html,clientside,xhtml,mozilla,firefox,safari,explorer,midas,execcommand,contenteditable,filemanager,imagemanager,plugins" />
<meta name="copyright" content="Moxiecode Systems AB" />
<meta name="robots" content="index,follow" />
<link rel="stylesheet" type="text/css" href="http://tinymce.moxiecode.com//css/compress.php" media="screen" />
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="/css/ie_fixes.css" media="screen" /><![endif]-->
<script type="text/javascript" src="http://tinymce.moxiecode.com//js/general.js"></script>
<link rel="alternate" type="application/rss+xml" title="TinyMCE News RSS" href="http://tinymce.moxiecode.com/forum_news_rss.php" />
<title>TinyMCE - jQuery plugin</title>
</head>
<body>

<div id="header">

	<div class="wrapper">

		<div class="content">

			<div class="left" id="logo">
				<a href="/index.php"></a>
			</div>

			<div class="right tright">

				<div class="tleft">

					<div id="tsearch">
						<div class="form">
							<form id="smallsearch" method="get" action="/search.php">
								<div><input type="text" name="searchquery" id="s" value="Search" size="18" onfocus="this.value='';" /></div>
								<div><a href="javascript:document.forms['smallsearch'].submit();"><!-- IE --></a></div>
							</form>
						</div>
					</div>

					<!-- document write this so it doesn't get indexed -->
<script type="text/javascript">// <![CDATA[
document.write('<div id=\"tquote\">' +
'<div class=\"text\">' +
'\"I am absolutely stunned by this innovation. Such power is only matched by its simplicity in implementation. Fantastic work guys!\"' +
'<em>- Stephen Scott, <a href=\"http://www.sassquad.com\" target=\"_blank\">Sassquad.com<'+'/a><'+'/em>' +
'<'+'/div>' +
'<'+'/div>');
// ]]>
</script>
				</div>
			
			</div>

			<div class="clearer">&nbsp;</div>

		</div>

	</div>

</div>


<div id="navigation">
	<div class="wrapper">

		<div class="links">
		
			<ul class="tabs">
<li><span><a href="/index.php">Home</a></span></li><li><span><a href="/using.php">About</a></span></li><li><span><a href="/download.php">Download</a></span></li><li class="selected_tab"><span><a href="/examples/full.php">Examples</a></span></li><li><span><a href="/documentation.php">Documentation</a></span></li><li><span><a href="/plugins.php">Plugins</a></span></li><li><span><a href="/support.php">Support</a></span></li><li><span><a href="/punbb">Forum</a></span></li><li><span><a href="/guestbook.php">Guestbook</a></span></li>			</ul>

			<div class="clearer">&nbsp;</div>

		</div>

	</div>
</div>

<div id="subnavigation">
	<div class="wrapper">
		<div class="content">

			<div class="links">
<a href="/examples/full.php">Full featured</a><a href="/examples/skins.php">Skins</a><a href="/examples/office.php">Office</a><a href="/examples/simple.php">Simple</a><a href="/examples/compressor.php">Compressor</a><a href="/examples_im/example_01.php">MCImageManager</a><a href="/examples_fm/example_01.php">MCFileManager</a>				<div class="clearer">&nbsp;</div>
			</div>

		</div>
	</div>
</div>


<div id="main">
	<div class="wrapper">

		<div id="main_content">
			

<script type="text/javascript" src="../js/compress.php?pack=syntax"></script>

			<div id="main_single" class="p15">
				<h1>jQuery plugin</h1>

				<div class="example_tabs">
					<a id="example_demo_tab" href="#" class="example_toggler toggled" onclick="return Examples.toggleView('demo','source');"><span class="application"><small>View Example</small></span></a>
					<a id="example_source_tab" href="#" class="example_toggler nolborder" onclick="return Examples.toggleView('source','demo');"><span class="code"><small>View Source</small></span></a>
					<div class="clearer">&nbsp;</div>
				</div>

				<div id="example_content">
					<div id="example_demo_view">

<p>This example shows how to use the special jQuery plugin from the TinyMCE jQuery package.</p>

<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1.3");
</script>

<!-- Load jQuery build -->
<script type="text/javascript" src="http://tinymce.moxiecode.com/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : 'http://tinymce.moxiecode.com/js/tinymce/jscripts/tiny_mce/tiny_mce_jquery.js',

			// General options
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/example.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "http://tinymce.moxiecode.com/lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>

						<form method="post" action="example_21.php">
							<div>
								<textarea id="content" name="content" style="width:100%" class="tinymce" rows="5" cols="20">
																		&lt;p&gt;
									&lt;img src=&quot;img/logo.jpg&quot; alt=&quot; &quot; height=&quot;48&quot; width=&quot;250&quot; style=&quot;float:right; margin:5px&quot;&gt;	TinyMCE is a platform independent web based Javascript HTML &lt;b&gt;WYSIWYG&lt;/b&gt; editor control released as Open Source under LGPL by Moxiecode Systems AB. It has the ability to convert HTML TEXTAREA fields or other HTML elements to editor instances. TinyMCE is very easy to integrate into other Content Management Systems.
									&lt;/p&gt;
									&lt;p&gt;
									We recommend &lt;a href=&quot;http://www.getfirefox.com&quot; target=&quot;_blank&quot;&gt;Firefox&lt;/a&gt; and &lt;a href=&quot;http://www.google.com&quot; target=&quot;_blank&quot;&gt;Google&lt;/a&gt; &lt;br&gt;
									&lt;/p&gt;
																	</textarea>

								<!-- Some integration calls -->
								<a href="javascript:;" onmousedown="$('#content').tinymce().show();">[Show]</a>
								<a href="javascript:;" onmousedown="$('#content').tinymce().hide();">[Hide]</a>
								<a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('Bold');">[Bold]</a>
								<a href="javascript:;" onmousedown="alert($('#content').html());">[Get contents]</a>
								<a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getContent());">[Get selected HTML]</a>
								<a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getContent({format : 'text'}));">[Get selected text]</a>
								<a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getNode().nodeName);">[Get selected element]</a>
								<a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
								<a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a>
							</div>
						</form>
					</div>

					<div id="example_source_view">
						<h2>HTML source</h2>
						<p>Below is all you need to setup the example.</p>
<pre class="brush:html">
&lt;!-- Load jQuery --&gt;
&lt;script type="text/javascript" src="http://www.google.com/jsapi"&gt;&lt;/script&gt;
&lt;script type="text/javascript"&gt;
	google.load("jquery", "1.3");
&lt;/script&gt;

&lt;!-- Load jQuery build --&gt;
&lt;script type="text/javascript" src="../js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript"&gt;
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
&lt;/script&gt;

&lt;form method="post" action="somepage"&gt;
	&lt;textarea id="content" name="content" class="tinymce" style="width:100%"&gt;
	&lt;/textarea&gt;

	&lt;!-- Some integration calls --&gt;
	&lt;a href="javascript:;" onmousedown="$('#content').tinymce().show();"&gt;[Show]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="$('#content').tinymce().hide();"&gt;[Hide]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('Bold');"&gt;[Bold]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="alert($('#content').html());"&gt;[Get contents]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getContent());"&gt;[Get selected HTML]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getContent({format : 'text'}));"&gt;[Get selected text]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getNode().nodeName);"&gt;[Get selected element]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('mceInsertContent',false,'&lt;b&gt;Hello world!!&lt;/b&gt;');"&gt;[Insert HTML]&lt;/a&gt;
	&lt;a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('mceReplaceContent',false,'&lt;b&gt;{$selection}&lt;/b&gt;');"&gt;[Replace selection]&lt;/a&gt;
&lt;/form&gt;
</pre>
					</div>
				</div>

				
				<div id="more_examples">
					<h2>More Examples</h2>
					<p>Here are more examples showing off different features of TinyMCE.</p>

					<div class="col3">
						<ul class="example_list">
														<li class="alt ex"><a href="example_01.php">Example 01 - Toggle editor with JavaScript</a></li>
														<li class="odd ex"><a href="example_02.php">Example 02 - Valid elements</a></li>
														<li class="alt ex"><a href="example_03.php">Example 03 - Class selectors/deselectors</a></li>
														<li class="odd ex"><a href="example_04.php">Example 04 - Multiple configs/inits</a></li>
														<li class="alt ex"><a href="example_05.php">Example 05 - Custom cleanup</a></li>
														<li class="odd ex"><a href="example_06.php">Example 06 - Ajax load/save</a></li>
														<li class="alt ex"><a href="example_07.php">Example 07 - Read only mode</a></li>
								
						</ul>
					</div>

					<div class="col3">
						<ul class="example_list">
														<li class="alt ex"><a href="example_08.php">Example 08 - URL conversion</a></li>
														<li class="odd ex"><a href="example_09.php">Example 09 - BBCode</a></li>
														<li class="alt ex"><a href="example_10.php">Example 10 - Noneditable content</a></li>
														<li class="odd ex"><a href="example_11.php">Example 11 - Fullpage example</a></li>
														<li class="alt ex"><a href="example_12.php">Example 12 - Load on demand</a></li>
														<li class="odd ex"><a href="example_13.php">Example 13 - Load on demand using compressor</a></li>
														<li class="alt ex"><a href="example_14.php">Example 14 - Setup editor events</a></li>
								
						</ul>
					</div>

					<div class="col3last">
						<ul class="example_list">
														<li class="alt ex"><a href="example_15.php">Example 15 - External toolbar</a></li>
														<li class="odd ex"><a href="example_16.php">Example 16 - Custom listbox/splitbutton</a></li>
														<li class="alt ex"><a href="example_17.php">Example 17 - Accessibility</a></li>
														<li class="odd ex"><a href="example_18.php">Example 18 - Menu button</a></li>
														<li class="alt ex"><a href="example_19.php">Example 19 - Tab focus</a></li>
														<li class="odd ex"><a href="example_20.php">Example 20 - Custom toolbar button</a></li>
														<li class="alt ex"><a href="example_21.php">Example 21 - Post to self</a></li>
														<li class="odd ex"><a href="example_22.php">Example 22 - jQuery version</a></li>
														<li class="alt selected"><a href="example_23.php">Example 23 - jQuery plugin</a></li>
								
						</ul>
					</div>
				</div>
				<div class="clearer">&nbsp;</div>
			</div>


			<div class="clearer">&nbsp;</div>
		</div>
	</div>
</div>

<div id="footer">
	<div class="wrapper">
		<div class="content">

			<p class="right">
<a href="/index.php">Home</a> <span class="separator">|</span> <a href="/using.php">About</a> <span class="separator">|</span> <a href="/download.php">Download</a> <span class="separator">|</span> <a href="/examples/full.php">Examples</a> <span class="separator">|</span> <a href="/documentation.php">Documentation</a> <span class="separator">|</span> <a href="/plugins.php">Plugins</a> <span class="separator">|</span> <a href="/support.php">Support</a> <span class="separator">|</span> <a href="/punbb">Forum</a> <span class="separator">|</span> <a href="/guestbook.php">Guestbook</a>			</p>

			<p class="left">
				<a href="/index.php">TinyMCE</a> - A Free Javascript WYSIWYG Editor
			</p>

			<div class="clearer">&nbsp;</div>

			<p class="small">
				<img src="/img/logo_moxiecode.gif" alt="" class="left mr15" />
				&#169; 2003-2009 <a href="http://www.moxiecode.com">Moxiecode Systems</a> AB. All rights Reserved.<br/>
				Provided for developers anywhere, everywhere, anytime and with any content.
			</p>

			<div class="clearer">&nbsp;</div>

		</div>
	</div>
</div>

</body>
</html>
