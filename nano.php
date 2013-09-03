<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>FriendFeed - Create Account</title>
<link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="FriendFeed Search"/>



<link rel="stylesheet" href="http://friendfeed.com/static/css/nano.css?v=3927" type="text/css"/>
<style type="text/css">


.wizard td {
vertical-align: middle;
font-size: 12pt;
font-weight: bold;
}
.wizard .dot {
height: 27px;
width: 25px;
padding-right: 2px;
text-align: center;
color: white;
background: url("/static/images/dot-grey.png?v=b3ca") no-repeat;
}
.wizard .text a:link,
.wizard .text a:visited {
color: silver;
text-decoration: none;
}
.wizard .text.selected a:link,
.wizard .text.selected a:visited {
color: #598dce;
}
.wizard .dot.selected {
background: url("/static/images/dot-blue.png?v=435c") no-repeat;
}
.wizard .text {
padding-left: 5px;
padding-right: 25px;
background: 0;
}

</style>





<style type="text/css">
.buttons.big input {
font-size: 12pt;
font-weight: bold;
}
</style>

<style type="text/css">
.meta {
margin-top: 2px;
color: #737373;
font-size: 13px;
}
table.form td {
padding-bottom: 12px;
}
.usernameurl {
color: #006600;
font-weight: bold;
}
.legal {
margin-top: 2em;
color: #737373;
font-size: 13px;
}
.legal a {
color: #7777cc;
}
.error {
color: #c00;
}
h3 {
font-size: 18px;
}
</style>


</head>
<body onload="analytics()">

<div id="bodydiv">


<div id="container" class="nosidebar">


<div id="header">
<table>
<tr>
<td class="logo"><a href="/"><img src="/static/images/nano-logo.png?v=5ff0" alt="FriendFeed" style="width:227px;height:50px;"/></a></td>
<td></td>
</tr>
</table>
</div>

<div id="body">
<div class="box white" id="page">

<div class="box-top default-bar">
<div class="box-corner"></div>
<div class="box-spacer"></div>
</div>

<div class="box-body">



<div style="margin-bottom: 25px; padding-top: 5px">

<table class="wizard">
<tr>
<td class="selected dot">1</td>
<td class="selected text"><a href="/account/create">Create your account</a></td>
<td class="dot">2</td>
<td class="text"><a href="/friends/newimport">Find your friends</a></td>
<td class="dot">3</td>
<td class="text"><a href="/?login=1">FriendFeed!</a></td>
</tr>
</table>
</div>


<div style="margin:20px; margin-top:0">


<div class="form createform">
<form action="/account/create" method="post" onsubmit="return checkRequired(this, ['name', 'nickname', 'password', 'email']) &amp;&amp; checkPasswords(this) &amp;&amp; checkName(this);">
<input type="hidden" name="at" value="17085707470677891501_1250826258"/>

<input type="hidden" name="subscribe" value=""/>
<table class="form">
<tr>
<td class="label">Full name</td>
<td class="value"><input name="name" type="text" size="25" id="name" value="" maxlength="25"/> <span class="meta"><span id="nameerror" class="error"></span></span></td>
</tr>
<tr>
<td class="label" style="vertical-align:top; padding-top:3px">Username</td>
<td class="value">
<div><input name="nickname" type="text" size="25" maxlength="25" id="nickname" value="" autocomplete="off"/></div>
<div class="meta"><span id="nicknamepreview">Your URL: http://friendfeed.com/<span class="usernameurl">USERNAME</span></span></div>
<div style="padding-top:2px; color:#cc0000" class="error usernameerror"></div>
</td>
</tr>
<tr>
<td class="label">Password</td>
<td class="value"><input name="password" type="password" size="25" value="" autocomplete="off"/> <span class="meta"><span id="passworderror" class="error"></span></span></td>
</tr>
<tr>
<td class="label">Email address</td>
<td class="value"><input name="email" type="text" size="25" id="email" value=""/></td>
</tr>
<tr>
<td>&nbsp;</td>
<td class="value">
<input type="checkbox" id="privatecheckbox" name="private" value="1"/> <label for="privatecheckbox">Private feed (only people I approve see my feed)</label>
</td>
</tr>
<tr>
<td></td>
<td class="buttons big">
<input type="submit" value="Create my account &raquo;"/>


</td>
</tr>
</table>
</form>
</div>


</div>

<div style="clear:both; height:12px"></div>
</div>
<div class="box-bottom">
<div class="box-corner"></div>
<div class="box-spacer"></div>
</div>
</div>
</div>

</div>
</div><div id="extradiv"></div><div id="extradivtoo"></div>


<script src="http://friendfeed.com/static/javascript/jquery-1.3.js?v=bb38" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[

var gCurrentUserId = null;

var gTwitLogin = '';
var gHttpsPrefix = 'https://friend.com';
var gRTL = false;
//]]>
</script>

<script src="http://friendfeed.com/static/javascript/nano.en.js?v=f7f1" type="text/javascript"></script>







<script type="text/javascript">
//<![CDATA[

function checkName(form) {
$("#nameerror").empty();
if (!form.name.value) {
return true;
}
if (form.name.value.length < 3 || form.name.value.length > 25) {
$("#nameerror").text('Your name must be between 3 and 25 characters long');
form.name.focus();
return false;
}
return true;
}
function checkPasswords(form) {
$("#passworderror").empty();
if (!form.password.value) {
return true;
}
if (form.password.value.length < 6) {
$("#passworderror").text('Your password must be at least six characters long');
form.password.focus();
return false;
}
return true;
}
$(function() {
setUpCreateForm($(".createform"));
});
//]]>
</script>





</body>
</html>
