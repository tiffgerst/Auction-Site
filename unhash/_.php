<?php
echo('[');
foreach(array('buyer', 'buyer', 'braindead', '1234567',
'vvs', 'loulou', 'potato', 'JinsKitchen', 'sgoose1', 'psw1', '1234567') as $password) {
    echo("'");
    echo(password_hash($password,PASSWORD_BCRYPT));
    echo("'");
    echo(',');
}
echo(']');
?>