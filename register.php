<?php include_once("header.php")?>

<div class="container">
<h2 class="my-3">Register new account</h2>

<!-- Create auction form -->
<form method="POST" id="createNewUser" action="process_registration.php">
  <div class="form-group row">
    <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <!-- Account Type (Buyer)-->
        <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <!-- Account Type (Seller) -->
        <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <!-- Email -->
      <input type="text" class="form-control" name="email" id="email" placeholder="Email" required>
      <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <!-- Password -->
      <input type="password" class="form-control" name="password" id="password" placeholder="Password" required minlength = "7">
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
  <div class="form-group row">
    <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
    <div class="col-sm-10">
      <!-- Password Confirmation -->
      <input type="password" class="form-control" name="passwordConfirmation" id="passwordConfirmation" placeholder="Enter password again" required>
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
  <div class="form-group row">
  <label for="address_first_line" class="col-sm-2 col-form-label text-right">address</label>
  <div class="col-sm-10">
      <!-- address Confirmation -->
      <input type="text" class="form-control" name="address_first_line"  placeholder="Fist Line of address" require>
      <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      <input type="text" class="form-control" name="address_city"  placeholder="City" require>
      <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      <input type="text" class="form-control" name="address_country" placeholder="Country" require>
      <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      <input type= "text" class="form-control"name="address_post_code" placeholder = "Postcode" require>
      <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
</div>
  <div class="form-group row">
    <button type="submit" class="btn btn-primary form-control">Register</button>
  </div>
<script type="module" src="js/user_verification.js"></script>
</form>

<div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>
</div>
<?php include_once("footer.php")?>