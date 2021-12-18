<?php include_once("header.php")?>
<?php include_once("utilities.php")?>
<?php
if (isset($_SESSION['username'])){
$my_username = $_SESSION['username'];
}
$query = "SELECT * FROM users WHERE email = '{$my_username}'";
$result = query($query)->fetch_assoc();
$country = $result['country'];
$city = $result['city'];
$postcode = $result['postcode'];
$address = $result['addressLine'];

?>

  <div class="container mt-4">
    <form method="POST" id="editUser" action="edit_credentials_confirmation.php">
    <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <!-- Password -->
      <input type="password" class="form-control" name="password" id="password" placeholder="New Password"  minlength = "7">
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">Leave Blank to Remain Unchanged</span></small>
    </div>
  </div>
  <div class="form-group row">
  <label for="address_first_line" class="col-sm-2 col-form-label text-right">Address</label>
  <div class="col-sm-10">
      <!-- address Confirmation -->
      <input type="text" class="form-control" name="address_first_line"  value='<?php echo($address) ?>' placeholder = 'First Line of address' require>
      <small class="form-text text-muted"><span class="text-danger"></span></small>
      <input type="text" class="form-control" name="address_city"  value='<?php echo($city) ?>' placeholder = 'City' require>
      <small class="form-text text-muted"><span class="text-danger"></span></small>
      <input type="text" class="form-control" name="address_country" value='<?php echo($country) ?>' placeholder = 'Country' require>
      <small class="form-text text-muted"><span class="text-danger"></span></small>
      <input type= "text" class="form-control"name="address_post_code" value = '<?php echo($postcode) ?>' placeholder = 'Postcode' require>
      <small class="form-text text-muted"><span class="text-danger"></span></small>
    </div>
</div>
  <div class="form-group row">
    <button type="submit" class="btn btn-primary form-control">Confirm Change</button>
  </div>
<script type="module" src="js/user_verification.js"></script>
</form>
</div>