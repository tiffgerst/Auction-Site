<?php include_once("header.php")?>

<?php
// If user is not logged in or not a seller, they should not be able to use this page
// -> redirect
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
  }
?>

<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <form method="post" id='createNewAuction' action="create_auction_result.php" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <!-- Title -->
            <input type="text" class="form-control" name="auctionTitle" id="auctionTitle" placeholder="e.g. Black mountain bike" required>
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">Required.</span> A short description of the item you're selling, which will display in listings.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
          <div class="col-sm-10">
            <!-- Details -->  
            <textarea class="form-control" name="auctionDetails" id="auctionDetails" rows="4"></textarea>
            <small id="detailsHelp" class="form-text text-muted">Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionCategory" class="col-sm-2 col-form-label text-right">Category</label>
          <div class="col-sm-10">
            <!-- Category -->  
            <select class="form-control" name="auctionCategory" id="auctionCategory" required>
              <option disabled selected value>Please select an option</option>
              <option value="shoes">Shoes</option>
              <option value="pants">Pants</option>
              <option value="tops">Tops</option>
              <option value="dress">Dresses</option>
              <option value="skirts">Skirts</option>
              <option value="suits">Suits</option>
              <option value="accessories">Acessories</option>
            </select>
            <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">Required.</span> Select a category for this item.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
          <div class="col-sm-10">
	        <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <!-- Start Price -->  
              <input type="number" class="form-control" name="auctionStartPrice" id="auctionStartPrice" required>
            </div>
            <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">Required.</span> Initial bid amount.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <!-- Reserve Price --> 
              <input type="number" class="form-control" name="auctionReservePrice" id="auctionReservePrice">
            </div>
            <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <!-- End Date --> 
            <input type="datetime-local" class="form-control" name="auctionEndDate" id="auctionEndDate" required>
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">Required.</span> Day for the auction to end.</small>
          </div>       
        </div>
        <div class="form-group row">
        <label for="auction_image" class= "col-sm-2 col-form-label text-right" >Photo</label>
        <div class="col-sm-10">
          <!-- Image --> 
          <input type="file" name ="auction_image" required>
          <small id="imageHelp" class="form-text text-muted">Upload an image of the product</small>
        </div>
      </div>
      <button type="submit" class="btn btn-primary form-control" name= "form_submit">Create Auction</button>

      <script type="module" src="js/auction_verification.js"></script>
      </form>
    </div>
  </div>
</div>
</div>
<?php include_once("footer.php")?>