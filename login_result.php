<?php
# Get $_POST variables and check if they both have a value
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ((empty($email)) or (empty($password))) {
    # One of them doesn't have a value
    exit("Email or password empty: please enter an email and a password");
}
# Get database credentials
require_once(db_credentials.php);

# Create a database connection
$connection = mysqli_connect($dbhost,$dbuser,$dbpass,'users');

# Perform a database query
$query = "SELECT * FROM users ";
$query .= "WHERE user = ".$email." ";
$query .= "AND password = ".$password;
$users_set = mysqli_query($connection,$query);

# Use returned data

# Release returned data

# Close database connection

mysqli_close($connection);
// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.

session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = "test";
$_SESSION['account_type'] = "buyer";

echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to index after 5 seconds
header("refresh:5;url=index.php");

?>