<?php
  $dest = "tiffany.gerstmeyr@web.de";
  $subjetc = "Test Email";
  $body = "Hi this is a test email sent by a php script";
  $headers = "From: YourGmailId@gmail.com";
  if (mail($dest, $subjetc, $body, $headers)) {
    echo "Email successfully sent to $dest ...";
  } else {
    echo "Failed to send email...";
  }
?>
