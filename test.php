<?php
    $random  = '1';
    $command = escapeshellcmd("\wamp64\www\Auction\bemailer.py $random");
    $output = shell_exec($command);
    
?>