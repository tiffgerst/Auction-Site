<?php include_once("header.php")?>

<?php
# If user is not logged in or not a seller, they should not be able to use this page
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
      <form method="post" id='createNewAuction' action="create_auction_result.php">
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
              <option value="fill">Fill me in</option>
              <option value="with">with options</option>
              <option value="populated">populated from a database?</option>
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
        <button type="submit" class="btn btn-primary form-control">Create Auction</button>
      </form>
    </div>
  <script src="js/form_verification.js"></script>
  </div>
</div>
</div>
<?php include_once("footer.php")?>