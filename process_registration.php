<?php require("utilities.php")?>

<?php
function check($data) {
    if (!isset($data)) {
        echo('Please fill out all required entries');
        exit;
    }
    else{
        return $data;
    }
  }

// Extract $_POST variables
$accountType = check($_POST['accountType']);
$email = check($_POST['email']);
$password = $_POST['password'];
$passwordConfirmation = $_POST['passwordConfirmation'];
$country = $_POST['address_country'];
$address = $_POST['address_first_line'].', '.$_POST['address_city'].', '.$_POST['address_post_code'];

// Check account already exists
$query = "SELECT * FROM users WHERE accountType = '$accountType' AND email = '$email'";
$result = query($query);

if (mysqli_num_rows($result)>0) {
    echo("Account with that email already exists");
    header("refresh:5;url=register.php");
    exit();
}

// Register users
query("INSERT INTO users VALUES
('$email','$password','$accountType', '$country', '$address')");

// Change session variables
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $email;
$_SESSION['account_type'] = $accountType;

// Notify user of success
echo('<div class="text-center">You are now registered! You will be redirected shortly.</div>');

// Redirect to browse after 5 seconds
header("refresh:5;url=browse.php");

?>