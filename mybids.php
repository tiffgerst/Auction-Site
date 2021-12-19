<?php include_once("header.php")?>
<?php require("utilities.php")?>

<link rel="stylesheet" href="../css/custom.css">
<div class="container">
<h2 class="my-3">My bids</h2>

<?php
$email = $_SESSION["username"];

$num_result = mysqli_num_rows(query("SELECT auctionID FROM bids WHERE buyerEmail = '$email' LIMIT 1"));

if ($num_result==0){
  # Result is empty
  echo("<p class='text-muted'>You don't have any bids silly :O</p></div>");
  exit;
}
?>

<p class="text-muted">Hey there! Here are your bids :)</p>
<form method="GET" class="form-inline">
  <select name="bidCategories" class="form-control mr-2">
    <option selected value="">All my bids</option>
    <option value="highest">Bids where I'm the highest bidder</option>
    <option value="!highest">Bids where I'm not the highest bidder</option>
  </select>
  <div class="form-check">
    <label class="form-check-label mx-2" for="expired">Show expired:</label>
    <input class="form-check-input" type="checkbox" id="expired" name="show_expired" value="TRUE">
  </div>
  <button type="submit" class="btn btn-primary ml-3">Search</button>
</form>
</div> <!-- end container for title and search specs -->

<div class="container mt-4">
<ul class="list-group">

<?php
  $query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.picture,a.current_price, a.num_bids,
  um.user_max
  FROM
  
  -- Auction details for each auction the user bid on
  (SELECT a.auctionID, a.title, a.description, a.endDate, a.picture,b.current_price, b.num_bids FROM
  -- All auctions: their highest bid and number of bids
  (SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b
  RIGHT JOIN
  -- All auctions the user has bid on
  (SELECT * FROM auctions WHERE auctionID IN (SELECT auctionID FROM bids WHERE buyerEmail = '$email')";
  
  // Don't show expired unless the GET is set
  if (!isset($_GET['show_expired'])) {
    $query .= " AND endDate > CURRENT_TIMESTAMP()";
  }
  
  $query .= ") a
  ON a.auctionID = b.auctionID) a,
  
  -- The highest bid the user placed for each auction they bid on
  (SELECT auctionID, MAX(bidValue) AS 'user_max' FROM bids WHERE buyerEmail = '$email' GROUP BY auctionID) um
  
  -- Join
  WHERE um.auctionID = a.auctionID";
  
  // Perform query
  $result = query($query);
  $num_results = mysqli_num_rows($result);

  $counter = 0;

  while ($row = $result->fetch_assoc()) {
    $auctionID = $row['auctionID'];
    $title = $row['title'];
    $description = $row['description'];
    $current_price = $row['current_price'];
    $num_bids = $row['num_bids'];
    $endDate = new DateTime($row['endDate']);
    $image = $row['picture'];
    
    $user_max = $row['user_max'];

    if(isset($_GET['bidCategories'])){
      $bid_cat = $_GET['bidCategories'];
    }else{
      $bid_cat = '';
    }

    if ($bid_cat == "highest"){
      if ($user_max != $current_price) {
        continue;
      }
    }

    else if ( $bid_cat == "!highest") {
      if ($user_max == $current_price) {
        continue;
      }
    }
    
    print_listing_li($auctionID, $title, $description, $current_price, $num_bids, $endDate, $image); 
    $counter+=1;
  }

  if ($counter == 0) {
    echo('No results found');
  }
?>

</ul>
</div>

<?php include_once("footer.php")?>