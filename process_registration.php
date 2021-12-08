<?php require("utilities.php")?>

<?php
function check($data) {
    if (!isset($data)) {
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
$city = check($_POST['address_city']);
$postcode = check($_POST['address_post_code']);
$addressLine = check($_POST['address_first_line']);

// Escape strings to do first query
$accountType=escape_string($accountType);
$email=escape_string($email);

// Check if account already exists
$query = "SELECT * FROM users WHERE email = '$email'";
$result = query($query);

if (mysqli_num_rows($result)>0) {
    echo("Account with that email already exists");
    header("refresh:3;url=register.php");
    exit;
}
echo('hello');

// Escape then salt + hash password
$password = escape_string($password);
$password = password_hash($password,PASSWORD_BCRYPT);

// Escape the rest of the strings for the INSERT query
$country = escape_string($country);
$city = escape_string($city);
$postcode = escape_string($postcode);
$addressLine = escape_string($addressLine);

// INSERT
query("INSERT INTO users
(email,password,accountType,
country,city,postcode,addressLine)
VALUES
('$email','$password','$accountType',
'$country','$city','$postcode','$addressLine')");

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