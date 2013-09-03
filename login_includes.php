<script type="text/javascript">
     function isEmpty(s){
        	 return ((s == null) || (s.length == 0))
        }

	// BOI, followed by one or more whitespace characters, followed by EOI.
        var reWhitespace = /^\s+$/
        // BOI, followed by one or more characters, followed by @,
        // followed by one or more characters, followed by .,
        // followed by one or more characters, followed by EOI.
        var reEmail = /^.+\@.+\..+$/
        var defaultEmptyOK = false
        // Returns true if string s is empty or
        // whitespace characters only.

       function isWhitespace (s){   // Is s empty?
       		return (isEmpty(s) || reWhitespace.test(s));
       }

       function validate(){
       		if (isWhitespace(document.frm_login.email.value) || document.frm_login.email.value == 'email id'){
                   alert('Please enter your email address.');
                   document.frm_login.email.focus();
                   return false;
                }

       if(isWhitespace(document.frm_login.pwd.value)) {
		alert("Please enter password.");
                document.frm_login.pwd.focus();
                return false;
        }
        return true;

     }

</script>