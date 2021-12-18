<?php
    $random  = '1';
    $absolute_path = realpath("bemailer.py");
    $command = escapeshellcmd("$absolute_path $random");
    $output = shell_exec($command);
    
?>