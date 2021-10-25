<?php require("utilities.php")?>

<?php
# Get $_POST variables and check if they both have a value
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ((empty($email)) or (empty($password))) {
    # One of them doesn't have a value
    echo('<div class="text-center">Email or password empty: please enter an email and a password.</div>');
    header("refresh:5;url=browse.php");
    exit();
}

# Perform a database query
$query = "SELECT * FROM users ";
$query .= "WHERE user = '".$email."' ";
$query .= "AND password = '".$password."'";
$result = query($query);

if (!($result)) {
    echo('<div class="text-center">Email or password incorrect: please enter a valid email and password.</div>');
    header("refresh:5;url=browse.php");
    exit();
}

session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $email;
$_SESSION['account_type'] = "buyer";

echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to header after 5 seconds
header("refresh:5;url=browse.php");

?>