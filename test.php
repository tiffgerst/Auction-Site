<?php
    $command = escapeshellcmd("\wamp64\www\Auction\bestemail.py $random ");
    $output = shell_exec($command);
?>