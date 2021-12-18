<?php
<<<<<<< HEAD
    $random  = '1';
    $absolute_path = realpath("bemailer.py");
    $command = escapeshellcmd("$absolute_path $random");
    $output = shell_exec($command);
    
=======
    $command = escapeshellcmd("\wamp64\www\Auction\bestemail.py $random ");
    $output = shell_exec($command);
>>>>>>> cffff5f (email test)
?>