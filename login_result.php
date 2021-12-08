<?php require("utilities.php");?>

<?php

function check($data) {
    if (!isset($data)) {
        exit;
    }
    else{
        return $data;
    }
  }

// Get POST variables and sanitize them
$email = escape_string(check($_POST['email']));
$password = escape_string(check($_POST['password']));

// Perform a database query
$query = "SELECT password FROM users
WHERE email = '$email'";
$result = query($query);

// Check if email doesn't exist
if (mysqli_num_rows($result)==0) {
    echo("<div class='text-center'>Email doesn't exist: please register.</div>");
    header("refresh:3;url=browse.php");
    exit;
}

// Check if password is valid
if (!password_verify($password,$result->fetch_assoc()['password'])) {
    echo('<div class="text-center">Password incorrect: please try again.</div>');
    header("refresh:5;url=browse.php");
    exit;
}

// Set session variables
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $email;
$_SESSION['account_type'] = $result->fetch_assoc()['accountType'];

echo('
<style>
.centered {
	position: fixed;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}

.loading-text {
    text-align: center;
}

.loading-glasses{
    width: 10rem;
    position: absolute;
    transform: translate(-17rem, 1rem);
    animation: drop ease-out;
    animation-duration: 2s;
}
@keyframes drop {
    0% {
        transform: translate(-17rem, -10rem);
    }
    100% {
      transform: translate(-17rem, 1rem);
    }
  }
</style>
<div class = "centered">
    <img src="assets/SillyGoose.png" class="loading-goose" alt="Sample Image">
    <img src="assets/sunglasses.png" class="loading-glasses" alt="Sample Image">
    <h1 class = "loading-text">You will be redirected shortly.</h1>
</div>
');

// Redirect to header after 5 seconds
header("refresh:5;url=browse.php");

?>