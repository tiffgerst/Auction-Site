<?php require("utilities.php")?>

<?php
# Extract $_POST variables
$accountType = $_POST['accountType'];
$email = $_POST['email'];
$password = $_POST['password'];
$passwordConfirmation = $_POST['passwordConfirmation'];

/* 
Check if they're OK
*/

# Check account already exists
$query = "SELECT * FROM users WHERE accountType = '".$accountType."' AND email = '".$email."'";
$result = query($query);

if (mysqli_num_rows($result)>0) {
    echo("Account with that email already exists");
    header("refresh:5;url=register.php");
    exit();
}

/*
Everything is OK -> register user
*/
query(sprintf("INSERT INTO users VALUES ('%s','%s','%s')",$email,$password,$accountType));

# Change session variables
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $email;
$_SESSION['account_type'] = $accountType;

# Notify user of success
echo('<div class="text-center">You are now registered! You will be redirected shortly.</div>');

// Redirect to browse after 5 seconds
header("refresh:5;url=browse.php");

?>