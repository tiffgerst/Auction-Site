<?php require("utilities.php")?>
<?php include_once("header.php")?>
<?php
function check($data) {
    if (!isset($data)) {
        exit;
    }
    else{
        return $data;
    }
  }

$my_username = $_SESSION['username'];

$address = escape_string(check($_POST['address_first_line']));
$city = escape_string(check($_POST['address_city']));
$postcode =escape_string(check($_POST['address_post_code']));
$country = escape_string(check($_POST['address_country']));

$query = "UPDATE users SET
country = '{$country}',
city = '{$city}',
postcode = '{$postcode}',
addressLine = '{$address}'";

if (isset($_POST['password'])){
    $password = escape_string($password);
    $password = password_hash($password,PASSWORD_BCRYPT);
    $query .= ",password = '{$password}'";
}

$query .= " WHERE email = '{$my_username}'";

query($query);

echo('<div class="text-center">Your change has been saved! You will be redirected shortly.</div>');
?>
<script type="text/javascript">
window.location.href = 'browse.php';
</script>