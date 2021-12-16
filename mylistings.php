<?php include_once("header.php")?>
<?php require_once("utilities.php")?>

<div class="container"> <!-- start container for title and search specs -->
<h2 class="my-3">My listings</h2>

<?php
$email = $_SESSION["username"];

$num_result = mysqli_num_rows(query("SELECT auctionID FROM auctions WHERE sellerEmail = '$email'"));

if ($num_result==0){
  # Result is empty
  echo("<p class='text-muted'>You don't have any auctions silly :O</p></div>");
  exit;
}
?>

<p class="text-muted">Hey there! Here are your auctions :)</p>
<form method="GET" class="form-inline">
  <select name="auctionCategories" class="form-control mr-2">
    <option selected value=""> All my auctions</option>
    <option value="live">My live auctions</option>
    <option value="success">My successful auctions</option>
    <option value="nobids">My unsuccessful auctions (no bids)</option>
    <option value="noreserve">My unsuccessful auctions (reserve price not met)</option>
  </select>
  <label class="mx-2" for="orderBy">Sort by:</label>
  <select class="form-control" name="orderBy" id="orderBy">
    <option selected value="endDate">End Date (old to new)</option>
    <option value="priceLow">Price (low to high)</option>
    <option value="priceHigh">Price (high to low)</option>
  </select>
  <button type="submit" class="btn btn-primary ml-3">Search</button>
</form>
</div> <!-- end container for title and search specs -->

<div class="container mt-4"> <!-- start container for listings -->
<ul class="list-group">
<?php
// Build initial query
$query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture, a.reservePrice,
COALESCE(b.current_price,startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids'
FROM
-- Use the bids table to determine what the highest bid is (if it exists)
(SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b
-- If the auction isn't in the bids table (0 bids) then 'current_price' will be null
RIGHT JOIN (SELECT * FROM auctions WHERE sellerEmail = '$email'";

// End date filters
if (isset($_GET['auctionCategories'])) {
  // Don't need to validate it because it doesn't go into any of our queries
  $option = $_GET['auctionCategories'];

  if ($option == "live") {
    $query .= " WHERE endDate > CURRENT_TIME()";
  }
  else {
    $query .= " WHERE endDate < CURRENT_TIME()";
  }
}

$query .= ") a
ON a.auctionID = b.auctionID";

// Additional filters for auction category
if (isset($_GET['auctionCategories'])) {
  if ($option == "success") {
    $query .= " WHERE COALESCE(current_price,startPrice) > reservePrice";
  }

  else if ($option == "nobids") {
    $query .= " WHERE COALESCE(num_bids,0) = 0";
  }

  else if ($option == "noreserve") {
    $query .= " WHERE reservePrice > COALESCE(b.current_price,startPrice) AND COALESCE(num_bids,0) > 0";
  }
}

// Ordering
if (isset($_GET['orderBy'])) {
  $ordering = $_GET['orderBy'];
  if ($ordering == 'endDate') {
    $ordering = "endDate ASC";
  }
  else if ($ordering == 'priceLow'){
    $ordering = "current_price ASC";
  }
  else if ($ordering == 'priceHigh'){
    $ordering = "current_price DESC";
  }
  else {
    // Malicious - they didn't use front end
    $ordering = "endDate ASC";
    exit;
  }
}

else {
  $ordering = "endDate ASC";
}

$query .= " ORDER BY ".$ordering;

// Get results
$result = query($query);
$num_rows = mysqli_num_rows($result);

if ($num_rows==0) {
  echo('No results found');
  exit;
}

while ($row = $result->fetch_assoc()) {
  $item_id = $row['auctionID'];
  $title = $row['title'];
  $description = $row['description'];
  $end_date = new DateTime($row['endDate']); # Convert from string to DT object
  $num_bids = $row['num_bids'];
  $picture = $row['picture'];
  $current_price = $row['current_price'];
  
  print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date,$picture);
}  
?>
</ul>
</div> <!-- end container for listings -->
<?php include_once("footer.php")?>