<?php require("utilities.php")?>

<?php
# Get $_POST variables and check if they both have a value
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ((empty($email)) or (empty($password))) {
    # One of them doesn't have a value
    echo('<div class="text-center">Email or password empty: please enter an email and a password.</div>');
    header("refresh:5;url=browse.php");
    exit;
}

# Perform a database query
$query = "SELECT * FROM users ";
$query .= "WHERE email = '".$email."' ";
$query .= "AND password = '".$password."'";
$result = query($query);

# No results
if (mysqli_num_rows($result)==0)  {
    echo('<div class="text-center">Email or password incorrect: please enter a valid email and password.</div>');
    header("refresh:5;url=browse.php");
    exit;
}

# Set session variables
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $email;
$_SESSION['account_type'] = $result->fetch_assoc()['accountType'];

echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to header after 5 seconds
header("refresh:5;url=browse.php");

?>