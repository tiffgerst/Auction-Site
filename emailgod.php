<?php
 
    // Include the Mail package
    require_once "Mail.php";
 
    // Identify the sender, recipient, mail subject, and body
    $sender    = "Tiff <sillygoosecoursework@gmail.com>";
    $recipient = "Leo <leonardpaturel@gmail.com>";
    $subject = "I AM AN EMAIL GOD";
    $body = "PEAR Mail successfully sent this email.";
 
    // Identify the mail server, username, password, and port
    $server   = "smtp.gmail.com";
    $username = "sillygoosecoursework@gmail.com";
    $password = "&C6QLPbNS&6iBdRQ";
    $port     = "587";
 
    // Set up the mail headers
    $headers = array(
        "From"    => $sender,
        "To"      => $recipient,
        "Subject" => $subject
    );
 
    // Configure the mailer mechanism
    $smtp = Mail::factory("smtp",
        array(
            "host"     => $server,
            "username" => $username,
            "password" => $password,
            "auth"     => true,
            "port"     => $port
        )
    );
 
    // Send the message
    $mail = $smtp->send($recipient, $headers, $body);
 
    if (PEAR::isError($mail)) {
        echo("<p>" . $mail->getMessage() . "</p>");
    } else {
        echo("<p>Message successfully sent!</p>");
    }
     
?>