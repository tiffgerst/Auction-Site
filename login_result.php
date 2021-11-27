<?php require("utilities.php");?>

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
    animation-duration: 5s;
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
    <img src="images/SillyGoose.png" class="loading-goose" alt="Sample Image">
    <img src="images/sunglasses.png" class="loading-glasses" alt="Sample Image">
    <h1 class = "loading-text">You will be redirected shortly.</h1>
</div>
');

// Redirect to header after 5 seconds
header("refresh:5;url=browse.php");

?>