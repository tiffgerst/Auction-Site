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

// Read POST variables, check if they are set
$accountType = check($_POST['accountType']);
$email = check($_POST['email']);
$password = check($_POST['password']);
$passwordConfirmation = check($_POST['passwordConfirmation']);
$country = check($_POST['address_country']);
$address = check($_POST['address_first_line'].', '.$_POST['address_city'].', '.$_POST['address_post_code']);

// Escape strings to do first query
$accountType=escape_string($accountType);
$email=escape_string($email);

// Check if account already exists
$query = "SELECT * FROM users WHERE accountType = '$accountType' AND email = '$email'";
$result = query($query);

if (mysqli_num_rows($result)>0) {
    echo("Account with that email already exists");
    header("refresh:3;url=register.php");
    exit;
}

// Escape the rest of the strings for the INSERT query
$$password=escape_string($password);
$country=escape_string($country);
$address=escape_string($address);

// INSERT
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
header("refresh:3;url=browse.php");
?>