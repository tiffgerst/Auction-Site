<?php
 
    // Include the Mail package
    require_once "Mail.php";
 
    function send_email($recipient,$subject,$body){
    // Identify the sender, recipient, mail subject, and body
    $sender    = "Silly Goose <sillygoosecoursework@gmail.com>";
    $recipient = $recipient;
    $subject = $subject;
    $body = $body;
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
    $mail = $smtp->send($recipient, $headers, $body,);
 
    if (PEAR::isError($mail)) {
        echo("<p>" . $mail->getMessage() . "</p>");
    } 
    //else {
    //     pass;
    //     #echo("<p>Message successfully sent!</p>");
    // }
    }
     
?>