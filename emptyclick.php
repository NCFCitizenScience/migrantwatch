<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
    <head>

        <style type="text/css" media="screen">
            * { 
                margin: 0;
                padding: 0;
            }

            body {
                margin: 0 auto;
                color: #333;
                background-color: #E6E6E6;
                font: 12px  "lucida grande", verdana, sans-serif;;
            }
            
            #content {
                background-color: #FFF;
                position: absolute;
                width: 600px;
                margin: 10px;
                margin-left: -300px;
                left: 50%;
            }
            h1 {
                padding: 10px;
                background-color: #00F;
                color: #FFF;
            }
            h2 {
                padding: 10px;
                background-color:   #DCF3FE;
            }
            h2 a {
                float: right;
                color: #333;
                margin-top: 4px;
                font-size: 10px;
                text-decoration: none;
                padding: 2px;
            }
            h2 a:hover {
                background-color:   #CCC;
            }
            ul {
                list-style-position: inside;
                padding: 10px;
            }
            p, h3 {
                padding: 10px;
            }
            
            form p {
                padding: 5px 10px;
            }
            
            .plugins h3 {
                padding-top: 0px;
                padding-bottom: 5px;
            }
            
            .plugins ul {
                padding-top: 0px;
                margin-left: 10px;
             }
             
             #credits {
                 background-color: #E6E6E6;
                 text-align: center;
                 font-size: 11px;
                 padding-top: 10px;
             }
                 
        </style>
        <title>madewithlove | jQuery Empty On Click Plugin</title>
        <script src="jquery.js" type="text/javascript" charset="utf-8"></script>
        <script src="jquery.emptyonclick.js" type="text/javascript" charset="utf-8"></script>

    </head>
    <body>
        <div id="content">
            <h1>jQuery Empty On Click Plugin</h1>
            <p>
                <a href="http://www.madewithlove.be/blog/the-jquery-emptyonclick-plugin/">This blog post</a> is about the release of this plugin and how to use it.
            </p>
            <h2 id="features">Features</h2>

            <ul>
                <li>Empty field with default value on click/focus</li>
                <li>Put default value back after blur when the field is still empty</li>
                <li>Empty field with default value on submit, so it is really empty</li>
                <li>Reset field, so put the default value back, on form reset</li>
            </ul>
            <h2 id="changelog">Changelog</h2>

            <h3>Version 1.2 (17 Jun 2008)</h3>
            <p>
                Empty the fields onsubmit when they are not changed
            </p>
            
            <h3>Version 1.1 (11 Jun 2008)</h3>
            <p>
                Fixed a bug when working with an empty field (no default value)
            </p>
            
            <h3>Version 1.0 (06 Jun 2008)</h3>

            <p>
                Original version
            </p>
            <h2 id="download">Use it</h2>
            <p>
                Download the latest version here: <a href="/javascripts/jquery/jquery.emptyonclick.js">jquery.emptyonclick.js</a>.
            </p>
            <h2 id="demo">Demo time!</h2>

            <form action="" method="post" accept-charset="utf-8" onsubmit="return false;">
                <p>
                   
                </p>
                <p>
                    <textarea name="text_1" rows="8" cols="40">Textarea 1</textarea>
                </p>
					 <p><input type="text" name="input_1" value="Input 1" id="input_1" /></p>
                <p><input type="submit" value="Submit"> or <input type="reset" value="Reset"></p>

            </form>
            <script type="text/javascript" charset="utf-8">
                $(document).ready(function(){ 
                    $('input, textarea').emptyonclick();  
                });
            </script>
            <h2 id="using_it">Who&#x27;s using it</h2>
            <ul>
                <li><a href="http://www.jonnotie.nl">Jonnotie</a> (in the contact form)</li>
                <li><a href="http://www.hellomynameise.com">Hello My Name Is E</a> (in the login form)</li>

                <li><a href="http://www.weble.be">Weble</a> (in the client login form)</li>
            </ul>

            <div id="credits">
                Copyright &copy; 2008 <a href="http://www.madewithlove.be"><strong>made</strong>with<strong>love</strong></a>

            </div>
        </div>
        <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>
        <script type="text/javascript">
        try {
        var pageTracker = _gat._getTracker("UA-1914884-7");
        pageTracker._trackPageview();
        } catch(err) {}</script>
    </body>
</html>

