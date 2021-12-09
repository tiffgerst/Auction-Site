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


$adress = check($_POST['address_first_line']);
$city = check($_POST['address_city']);
$postcode =check($_POST['address_post_code']);
$country = check($_POST['address_country']);


$adress = escape_string($adress);
$city = escape_string($city);
$postcode = escape_string($postcode);
$country = escape_string($country);

if (isset($_POST['password'])){
    $password = check($_POST['password']);
    $password = escape_string($password);
    $password = password_hash($password,PASSWORD_BCRYPT);
    $query = "UPDATE users
    SET
    password = '{$password}',
    country = '{$country}',
    city = '{$city}',
    postcode = '{$postcode}',
    addressLine = '{$adress}'
    WHERE email = '{$my_username}'";
}else{
    $query = "UPDATE users
    SET
    country = '{$country}',
    city = '{$city}',
    postcode = '{$postcode}',
    addressLine = '{$adress}'
    WHERE email = '{$my_username}'";
}
query($query);

echo('<div class="text-center">Your change has been saved! You will be redirected shortly.</div>');
?>
<script type="text/javascript">
window.location.href = 'browse.php';
</script>