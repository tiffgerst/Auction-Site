<?php
include_once("header.php");
include_once("utilities.php");
?>
<div class="container">

<h2 class="my-3">Hey There Hot Stuff!</h2>
<p class="text-muted"> Here's a selection of auctions that you haven't bidded on that have high bidding activity. </p> 
<?php 
$email = $_SESSION["username"];

// Check if there are auctions (with bids) that the user has not bidded on
$sql = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture, n.popularity
FROM 
-- Table with all auctions that the buyer has not bidded on (a)
(SELECT * FROM auctions WHERE auctionID IN (SELECT auctionID FROM bids WHERE buyerEmail <> '$email') AND endDate > CURRENT_TIME()) a
LEFT JOIN 
-- Table with the number of bids for each auction (n)
(SELECT auctionID, COUNT(auctionID) AS 'popularity' FROM bids GROUP BY auctionID) n
ON a.auctionID = n.auctionID
ORDER BY popularity DESC LIMIT 3";
$hot = query($sql);

$num_results = mysqli_num_rows($hot);

if ($num_results==0){
  echo('Nothings hot right now :( except for you, silly goose!');
  exit;
}

// For each auction that the user has not bidded on
while ($row = $hot->fetch_assoc()) {
  // Extract attributes from auctions table
  $item_id = $row['auctionID'];
  $title = $row['title'];
  $desc = $row['description'];
  $startPrice = $row['startPrice'];
  $end_time = new DateTime($row['endDate']);
  $image = $row['picture'];
  
  // Get information from the bids table
  $_ = query("SELECT COALESCE(COUNT(auctionID),0) as 'num_bids',
  COALESCE(MAX(bidValue),$startPrice) as 'current_price'
  FROM bids WHERE auctionID = $item_id GROUP BY auctionID");
  $row = $_->fetch_assoc();
  $num_bids = $row['num_bids'];
  $current_price = $row['current_price'];
  
  print_listing_li($item_id, $title, $desc, $current_price, $num_bids, $end_time, $image);
  }
?>