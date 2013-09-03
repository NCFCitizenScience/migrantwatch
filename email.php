<?php
$to      = 'anushshetty@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: mw@migrantwatch.in' . "\r\n" .
    'Reply-To: mail@anushshetty.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>